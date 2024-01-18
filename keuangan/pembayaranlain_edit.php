<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 29.0 (Sept 20, 2023)
 * @notes: JIBAS Education Community will be managed by Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 * 
 * Copyright (C) 2009 Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('library/jurnal.php');
require_once('include/db_functions.php');
require_once('include/theme.php');
require_once('include/sessioninfo.php');

$idpembayaran = $_REQUEST['idpembayaran'];

OpenDb();

// -- Default Rekening Kas using value from previous input
$sql = "SELECT jd.koderek
		  FROM jbsfina.penerimaanlain p, jbsfina.jurnal j, jbsfina.jurnaldetail jd, rekakun rk
	     WHERE p.replid = '$idpembayaran'
		   AND p.idjurnal = j.replid
		   AND j.replid = jd.idjurnal
		   AND jd.koderek = rk.kode
		   AND rk.kategori = 'HARTA'";
$defrekkas = FetchSingle($sql);

$sql = "SELECT p.idjurnal, p.sumber, p.jumlah, date_format(p.tanggal, '%d-%m-%Y') AS tanggal, 
			   p.keterangan, pn.nama as namapenerimaan, pn.rekkas, pn.rekpendapatan, pn.rekpiutang 
		  FROM penerimaanlain p, datapenerimaan pn 
		 WHERE p.replid = '$idpembayaran' AND p.idpenerimaan = pn.replid";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$sumber = $row['sumber'];
$idjurnal = $row['idjurnal'];
$tanggal = $row['tanggal'];
$keterangan = $row['keterangan'];
$namapenerimaan = $row['namapenerimaan'];
$jbayar = $row['jumlah'];
$rekkas = $row['rekkas'];
$rekpiutang = $row['rekpiutang'];
$rekpendapatan = $row['rekpendapatan'];

if (1 == (int)$_REQUEST['issubmit']) 
{
	$jcicilan = UnformatRupiah($_REQUEST['jcicilan']);
	$tcicilan = $_REQUEST['tcicilan'];
	$tcicilan = MySqlDateFormat($tcicilan);
	$kcicilan = CQ($_REQUEST['kcicilan']);
	$sumber = CQ($_REQUEST['sumber']);
	$alasan = CQ($_REQUEST['alasan']);
	
	$selrekkas = $_REQUEST['rekkas']; // selected rekening kas
	
	if ($jcicilan == $jbayar) 
	{
		//--------------------------------------------------------------
		// Hanya mengubah informasi pembayaran tanpa mengubah besarnya  
		// -------------------------------------------------------------
		
		BeginTrans();
		$success = true;
		
		$sql = "UPDATE penerimaanlain
				   SET sumber='$sumber', tanggal='$tcicilan', 
					   keterangan='$kcicilan', alasan = '$alasan' 
				 WHERE replid='$idpembayaran'";
		$result = QueryDbTrans($sql, $success);
		
		// Ambil kode rekening dari jurnal bukan dari datapenerimaan
		$rekkas = AmbilKodeRekJurnal($idjurnal, "HARTA", $idpenerimaan);
		if ($success && $rekkas != $selrekkas)
		{
			$sql = "UPDATE jurnaldetail
					   SET koderek='$selrekkas'
					 WHERE idjurnal='$idjurnal'
					   AND koderek='$rekkas'
					   AND kredit=0";
			QueryDbTrans($sql, $success);	
		}
		
		if ($success) 
		{
			CommitTrans();
			CloseDb();
			echo  "<script language='javascript'>";
			echo  "opener.refresh();";
			echo  "window.close();";
			echo  "</script>";
			exit();
		} 
		else 
		{
			RollbackTrans();
			CloseDb();
			echo  "<script language='javascript'>";
			echo  "alert('Gagal menyimpan data!);";
			echo  "</script>";
		}
	} 
	else 
	{
		//----------------------------
		// Mengubah besar pembayaran  
		// ---------------------------
		
		BeginTrans();
		$success = 0;

        $idpenerimaan = "";
		$sql = "SELECT idpenerimaan 
		          FROM jbsfina.penerimaanlain
		         WHERE replid = $idpembayaran";
		$res = QueryDb($sql);
		if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            $idpenerimaan = $row[0];
        }
		
		$sql = "UPDATE penerimaanlain SET sumber='$sumber', tanggal='$tcicilan', jumlah=$jcicilan, 
					   keterangan='$kcicilan', alasan = '$alasan' 
				 WHERE replid='$idpembayaran'";
		QueryDbTrans($sql, $success);

		// Ambil kode rekening dari jurnal bukan dari datapenerimaan
		$rekkas = AmbilKodeRekJurnal($idjurnal, "HARTA", $idpenerimaan);
		$rekpendapatan = AmbilKodeRekJurnal($idjurnal, "PENDAPATAN", $idpenerimaan);
		
		if ($success)
		{
			$sql = "UPDATE jurnaldetail
					   SET debet='$jcicilan'
					 WHERE idjurnal='$idjurnal' AND koderek='$rekkas' AND kredit=0";
			QueryDbTrans($sql, $success);
		}
		
		if ($success && $selrekkas != $rekkas)
		{
			$sql = "UPDATE jurnaldetail
					   SET koderek='$selrekkas'
					 WHERE idjurnal='$idjurnal' AND koderek='$rekkas' AND kredit=0";
			QueryDbTrans($sql, $success);
		}
		
		if ($success)
		{
			$sql = "UPDATE jurnaldetail
					   SET kredit='$jcicilan'
					 WHERE idjurnal='$idjurnal' AND koderek='$rekpendapatan' AND debet=0";
			echo $sql;
			QueryDbTrans($sql, $success);
		}
		
		if ($success) 
		{
			CommitTrans();
			CloseDb();
			echo  "<script language='javascript'>";
			echo  "opener.refresh();";
			echo  "window.close();";
			echo  "</script>";
			exit();
		} 
		else 
		{
			RollbackTrans();
			CloseDb();
			echo  '<br>sql '.$sql;
			echo  "<script language='javascript'>";
			echo  "alert('Gagal menyimpan data!);";
			echo  "</script>";
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Ubah Pembayaran Lainnya]</title>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/rupiah2.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script type="text/javascript" src="script/calendar.js"></script>
<script type="text/javascript" src="script/lang/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar-setup.js"></script>
<script language="javascript">
function ValidateSubmit() 
{
	var isok = 	validateEmptyText('sumber', 'Sumber Penerimaan') &&
		   		validateEmptyText('jcicilan', 'Jumlah Penerimaan') &&
			   	validasiAngka() &&
		   		validateEmptyText('tcicilan', 'Tanggal Penerimaan') &&
		   		validateEmptyText('alasan', 'Alasan Perubahan') &&
		   		validateMaxText('alasan', 500, 'Alasan Perubahan') &&
		   		validateMaxText('kcicilan', 255, 'Keterangan Penerimaan') &&
				confirm("Data sudah benar?");
	
	document.getElementById('issubmit').value = isok ? 1 : 0;
	
	if (isok)
		document.main.submit();
	else
		document.getElementById('Simpan').disabled = false;
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function panggil(elem){
	var lain = new Array('sumber','jcicilan','alasan','kcicilan');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#FFFF99';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

function validasiAngka() 
{
	var angka = document.getElementById("angkacicilan").value;
	if(isNaN(angka)) 
	{
		alert ('Besarnya pembayaran harus berupa bilangan!');
		document.getElementById('jcicilan').value = "";
		document.getElementById('jcicilan').focus();
		return false;
	}
	else if(parseInt(angka) < 0)
	{
		alert ('Besarnya pembayaran harus positif!');
		document.getElementById('jcicilan').focus();
		return false;
	}
	return true;
}

function angka(){	
	var angka = document.getElementById("jcicilan").value;
	document.getElementById("angkacicilan").value=angka;
}
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" style='background-color:#dfdec9' onLoad="document.getElementById('sumber').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Penerimaan :.
    </div>
	</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    
    <form name="main" method="post">
    <input type="hidden" name="issubmit" id="issubmit" value="0" />
    <input type="hidden" name="idpembayaran" id="idpembayaran" value="<?=$idpembayaran ?>" />
   	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
	<!-- TABLE CONTENT -->
    <tr>
        <td width="55%"><strong>Penerimaan</strong></td>
        <td colspan="2"><input type="text" size="30" value="<?=$namapenerimaan?>" readonly style="background-color:#CCCC99"/></td>
    </tr>
    <tr>
		<td><strong>Sumber</strong></td>
	    <td colspan="2"><input type="text" name="sumber" id="sumber" size="30" value="<?=$sumber ?>" maxlength="30" onKeyPress="return focusNext('jcicilan', event)" onfocus="panggil('sumber')"/></td>
	</tr>
    <tr>
        <td align="left"><strong>Jumlah</strong></td>
        <td colspan="2"><input type="text" name="jcicilan" id="jcicilan" value="<?=FormatRupiah($jbayar) ?>" onblur="formatRupiah('jcicilan')" onfocus="unformatRupiah('jcicilan');panggil('jcicilan')" onKeyPress="return focusNext('alasan', event)" onkeyup="angka()"/>
		<input type="hidden" name="angkacicilan" id="angkacicilan" value="<?=$jbayar?>" />
		</td>
    </tr>
	<tr>
        <td><strong>Rek. Kas</strong></td>
        <td colspan="2">
			<select name="rekkas" id="rekkas" style="width: 200px">
<?php 			OpenDb();
				$sql = "SELECT kode, nama
                          FROM jbsfina.rekakun
                         WHERE kategori = 'HARTA'
                         ORDER BY nama";        
                $res = QueryDb($sql);
                while($row = mysqli_fetch_row($res))
                {
                    $sel = $row[0] == $defrekkas ? "selected" : "";
                    echo "<option value='".$row[0]."' $sel>{$row[0]} {$row[1]}</option>";
                } ?>                
            </select>
		</td>
    </tr>
   <tr>
        <td align="left"><strong>Tanggal</strong></td>
        <td>
        <input type="text" name="tcicilan" id="tcicilan" readonly size="15" value="<?=$tanggal ?>" onKeyPress="return focusNext('alasan', event)" style="background-color:#CCCC99"> </td>
        <td width="60%">
        &nbsp;
	     </td> 
    </tr>
    <tr>
        <td valign="top"><strong>Alasan Perubahan</strong></td>
        <td colspan="2"><textarea id="alasan" name="alasan" rows="3" cols="30" onKeyPress="return focusNext('kcicilan', event)" onfocus="panggil('alasan')"><?=$alasan ?></textarea>
        </td>
    </tr>
    <tr>
        <td align="left" valign="top">Keterangan</td>
        <td colspan="2"><textarea id="kcicilan" name="kcicilan" rows="3" cols="30" onKeyPress="return focusNext('Simpan', event)" onfocus="panggil('kcicilan')"><?=$keterangan ?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center">
        <input type="button" name="Simpan" id="Simpan" class="but" value="Simpan" onclick="this.disabled = true; ValidateSubmit();" />
        <input type="button" name="tutup" id="tutup" class="but" value="Tutup" onclick="window.close()" />
        </td>
    </tr>
    </table>
    </form>
    
	<!-- END OF CONTENT //--->
    </td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
<?php if (strlen((string) $mysqli_ERROR_MSG) > 0) { ?>
	<center>
    <font color="red"><strong><?=$mysqli_ERROR_MSG?></strong></font>
    </center>
<?php } ?>
<?php if (strlen((string) $ERROR_MSG) > 0) { ?>
	<center>
    <font color="red"><strong><?=$ERROR_MSG?></strong></font>
    </center>
<?php } ?>

</body>
</html>