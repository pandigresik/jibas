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
require_once('library/repairdatajttcalon.php');
require_once('library/smsmanager.func.php');

$replid = $_REQUEST['replid'];
$idkategori = $_REQUEST['idkategori'];
$idpenerimaan = $_REQUEST['idpenerimaan'];
$idtahunbuku = $_REQUEST['idtahunbuku'];

OpenDb();

// -- ambil nama penerimaan -------------------------------
$sql = "SELECT nama, rekkas, info2 FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$row = FetchSingleRow($sql);
$namapenerimaan = $row[0];
$defrekkas = $row[1];
$smsinfo = (int)$row[2];

// -- cek apakah ada rekening diskon -----------------
$sql = "SELECT COUNT(replid) FROM datapenerimaan WHERE replid=$idpenerimaan AND info1 IS NOT NULL";
if (0 == (int)FetchSingle($sql))
{
	//-- belum ada rekening diskon, warning user ---------
	CloseDb();
	
	echo "<br><br>";
	echo "<table border='1' style='font-family:Verdana; font-size:12px; border-width:1px; border-color:#8eb83e; background-color:#e8f4d0;' cellpadding='10' cellspacing='0'><tr height='200'><td align='center' valign='middle'>";
	echo "<strong>Mohon Maaf</strong>";
	echo "<br><br>";
	echo "Kode untuk rekening <strong><font color='red'>diskon</font></strong> untuk penerimaan \"<strong>$namapenerimaan</strong>\" belum ada. Silahkan tentukan dahulu kode rekening <strong><font color='red'>diskon</font></strong> di menu <strong>Penerimaan > Jenis Penerimaan</strong>";
	echo "<br><br>";
	echo "Rekening <strong><font color='red'>diskon</font></strong> adalah pasangan rekening <strong><font color='red'>pendapatan</font></strong>. ";
	echo "Contohnya, untuk rekening pendapatan <strong>412 Pendapatan DSP</strong> maka rekening diskonnya misalnya <strong>422 Diskon DSP</strong>.";
	echo "<br><br>";
	echo "<input type='button' value='Tutup' onclick='window.close()'>";
	echo "</td></tr></table>";
	
	exit();
}

// -- ambil nama calon siswa --------------------------
$sql = "SELECT nama, nopendaftaran FROM jbsakad.calonsiswa WHERE replid = '".$replid."'";
$row = FetchSingleRow($sql);
$namacalon = $row[0];
$nocalon = $row[1];


$sql = "SELECT b.cicilan 
          FROM jbsfina.besarjttcalon b, jbsakad.calonsiswa cs
         WHERE b.idcalon = cs.replid
           AND cs.nopendaftaran = '$nocalon'
           AND b.idpenerimaan = '$idpenerimaan'
           AND b.info2 = '".$idtahunbuku."'";
$row = FetchSingleRow($sql);
$jcicilan_default = $row[0];
	
if (1 == (int)$_REQUEST['issubmit']) 
{
	$idpetugas = getIdUser();
	$petugas = getUserName();
	$idbesarjtt = (int)$_REQUEST['idbesarjtt'];
	$tcicilan = MySqlDateFormat($_REQUEST['tcicilan']);
	$jcicilan = UnformatRupiah($_REQUEST['jcicilan']);
	$jdiskon = UnformatRupiah($_REQUEST['jdiskon']);
	$jbayar = $jcicilan - $jdiskon;
	$kcicilan = CQ($_REQUEST['kcicilan']);
	$rekkas = $_REQUEST['rekkas'];
	$smsinfo = isset($_REQUEST['smsinfo']) ? 1 : 0;
		
	//// Ambil nama penerimaan
	$sql = "SELECT nama, rekkas, rekpendapatan, rekpiutang, info1
			FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
	$row = FetchSingleRow($sql);
	$namapenerimaan = $row[0];
	//$rekkas = $row[1];
	$rekpendapatan = $row[2];
	$rekpiutang = $row[3];
	$rekdiskon = $row[4];
	
	//// Cari tahu besar pembayaran
	// FIXED: 26 Agustus 2010
	$idbesarjtt = 0;
	$besarjtt = 0;
	$sql = "SELECT b.replid AS id, b.besar, b.keterangan, b.lunas 
			FROM besarjttcalon b
			WHERE b.idcalon='$replid' AND b.idpenerimaan='$idpenerimaan' AND b.info2='$idtahunbuku'";
	$row = FetchSingleRow($sql);
	$idbesarjtt = $row[0];
	$besarjtt = $row[1];
		
	//// Cari tahu jumlah pembayaran cicilan yang sudah terjadi
	$sql = "SELECT SUM(jumlah), SUM(info1) FROM penerimaanjttcalon WHERE idbesarjttcalon='$idbesarjtt'";
	$row = FetchSingleRow($sql);
	$totalcicilan = $row[0];
	$totaldiskon = $row[1];

	//// Cek jumlah cicilan dengan besar pembayaran yang mesti dilunasi
	$ketjurnal = "";
	$lunas = 0;
	if ($totalcicilan + $totaldiskon + $jbayar + $jdiskon > $besarjtt) 
	{		
		$errmsg = "Maaf, pembayaran tidak dapat dilakukan! Jumlah bayaran cicilan lebih besar daripada pembayaran yang harus dilunasi!";
	}
	else
	{
		$lunas = 0;
		$ketjurnal = "";
		$ketsms = "";
		if ($totalcicilan + $totaldiskon + $jbayar + $jdiskon == $besarjtt)
		{
			$ketjurnal = "Pelunasan $namapenerimaan calon siswa $namacalon ($nocalon)";
			$ketsms = "pelunasan $namapenerimaan";
			$lunas = 1; //udah lunas
		}
		else
		{
			$sql = "SELECT COUNT(replid) + 1 FROM penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
			$cicilan = (int)FetchSingle($sql);
			
			$ketjurnal = "Pembayaran ke-$cicilan $namapenerimaan calon siswa $namacalon ($nocalon)";
			$ketsms = "pembayaran ke-$cicilan $namapenerimaan";
			$lunas = 0;
		}
		
		//Ambil awalan dan cacah tahunbuku untuk bikin nokas;
		$sql = "SELECT awalan, cacah FROM jbsfina.tahunbuku WHERE replid = '".$idtahunbuku."'";
		$row = FetchSingleRow($sql);
		$awalan = $row[0];
		$cacah = $row[1];
		$cacah += 1;  // increment counter
		$nokas = $awalan . rpad($cacah, "0", 6);  // form nokas
		
		//Begin Database Transaction
		BeginTrans();
		$success = true;
	
		//Simpan ke jurnal
		$idjurnal = 0;
		$success = SimpanJurnal($idtahunbuku, $tcicilan, $ketjurnal, $nokas, "", $idpetugas, $petugas, "penerimaanjttcalon", $idjurnal);
		
		//Simpan ke jurnaldetail
		if ($success) 
			$success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jbayar);
		if ($success) 
			$success = SimpanDetailJurnal($idjurnal, "K", $rekpiutang, $jcicilan);
		if ($jdiskon > 0 && $success)
			$success = SimpanDetailJurnal($idjurnal, "D", $rekdiskon, $jdiskon);
			
		//increment cacah di tahunbuku
		if ($success)
		{
			$sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid='$idtahunbuku'";
			QueryDbTrans($sql, $success);
		}
		
		//simpan data cicilan di penerimaanjtt
		if ($success)
		{
			$sql = "INSERT INTO penerimaanjttcalon
					SET idbesarjttcalon='$idbesarjtt', idjurnal='$idjurnal', tanggal='$tcicilan', 
						jumlah='$jbayar', keterangan='$kcicilan', petugas='$petugas', info1='$jdiskon'";
			QueryDbTrans($sql, $success);
		}
		
		//jika lunas ubah statusnya di besarjtt
		if ($lunas) 
		{
			if ($success) 
			{
				$sql = "SET @DISABLE_TRIGGERS = 1;";
				QueryDb($sql);
					
				$sql = "UPDATE besarjttcalon SET lunas=1 WHERE replid='$idbesarjtt'";
				QueryDbTrans($sql, $success);
				
				$sql = "SET @DISABLE_TRIGGERS = NULL;";
				QueryDb($sql);
			}
		}
		
		// -- Kirim SMS Informasi Pembayaran Calon Siswa
        if ($success && $smsinfo == 1)
        {
            $sql = "SELECT departemen
                      FROM jbsfina.tahunbuku
                     WHERE replid = '".$idtahunbuku."'";
            $departemen = FetchSingle($sql);
            
            CreateSMSPaymentInfo('CSISPAY',
                                 $departemen, $nocalon, $namacalon,
                                 RegularDateFormat($tcicilan),
                                 FormatRupiah($jbayar),
                                 $ketsms,
                                 $success);
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
	} // if ($jmlcicilan + $jmldiskon + $jbayar > $besarjtt)
} // if (1 == (int)$_REQUEST['issubmit']) 


// -- Get Previous User Input Values ------------------------------------
$tanggal = date('d-m-Y');
if (isset($_REQUEST['tcicilan']))
	$tanggal = $_REQUEST['tcicilan'];
	
$jcicilan = 0;	
if (isset($_REQUEST['jcicilan']))
	$jcicilan = UnformatRupiah($_REQUEST['jcicilan']);
else
    $jcicilan = $jcicilan_default;

$jdiskon = 0;
if (isset($_REQUEST['jdiskon']))
	$jdiskon = UnformatRupiah($_REQUEST['jdiskon']);
	
$kcicilan = "";	
if (isset($_REQUEST['kcicilan']))
	$kcicilan = $_REQUEST['kcicilan'];
	
$jbayar = $jcicilan - $jdiskon;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Tambah Pembayaran Cicilan]</title>
<script src="script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="script/tooltips.js" language="javascript"></script>
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
	var isok = 	validateEmptyText('jcicilan', 'Besarnya Cicilan') &&
		    	validasiAngka() &&
		    	validateEmptyText('tcicilan', 'Tanggal Cicilan') &&
		    	validateMaxText('kcicilan', 255, 'Keterangan Cicilan') &&
				confirm("Data sudah benar?");
	
	document.getElementById('issubmit').value = isok ? 1 : 0;
	
	if (isok)
		document.main.submit();
	else
		document.getElementById('Simpan').disabled = false;
}

function validasiAngka() 
{
	angka = document.getElementById('angkacicilan').value;
	if(isNaN(angka)) 
	{
		alert ('Besarnya cicilan harus berupa bilangan!');
		document.getElementById('jcicilan').focus();
		return false;
	}
	else if(parseInt(angka) <= 0)
	{
		alert ('Besarnya cicilan harus positif!');
		document.getElementById('jcicilan').focus();
		return false;
	}
	
	diskon = document.getElementById('angkadiskon').value;
	if(isNaN(diskon)) 
	{
		alert ('Besar diskon harus berupa bilangan!');
		document.getElementById('jdiskon').focus();
		return false;
	}
	else if(parseInt(diskon) < 0)
	{
		alert ('Besar diskon tidak boleh negatiff!');
		document.getElementById('jdiskon').focus();
		return false;
	}
	
	if (parseInt(diskon) > parseInt(angka))
	{
		alert ('Besar diskon tidak boleh lebih besar daripada besar cicilan!');
		document.getElementById('jdiskon').focus();
		return false;
	}
	
	return true;
}

function salinangka()
{	
	var angka = document.getElementById("jcicilan").value;
	document.getElementById("angkacicilan").value = angka;
}

function focusNext(elemName, evt) 
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) 
	{
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function salindiskon()
{	
	var angka = document.getElementById("jdiskon").value;
	document.getElementById("angkadiskon").value = angka;
}

function CalculatePay()
{
	var cicilan = document.getElementById("jcicilan").value;
	var diskon = document.getElementById("jdiskon").value;
	cicilan = rupiahToNumber(cicilan);
	diskon = rupiahToNumber(diskon);
	var bayar = cicilan - diskon;
	document.getElementById("jbayar").value = numberToRupiah(bayar);
}
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" style='background-color:#dfdec9' onLoad="document.getElementById('jcicilan').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Tambah Pembayaran Cicilan :.
    </div>
	</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    
    <form name="main" method="post">
    <input type="hidden" name="issubmit" id="issubmit" value="0" />
    <input type="hidden" name="idkategori" id="idkategori" value="<?=$idkategori ?>" />
	<input type="hidden" name="idpenerimaan" id="idpenerimaan" value="<?=$idpenerimaan ?>" />
	<input type="hidden" name="replid" id="replid" value="<?=$replid ?>" />
	<input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku ?>" />
       
   	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
	<!-- TABLE CONTENT -->
    <tr>
        <td width="50%"><strong>Pembayaran</strong></td>
        <td colspan="2"><input type="text" readonly="readonly" size="30" value="<?=$namapenerimaan?>" style="background-color:#CCCC99"/></td>
    </tr>
    <tr>
        <td><strong>Nama</strong></td>
        <td colspan="2"><input type="text" size="30" value="<?=$nocalon . " - " . $namacalon ?>" readonly style="background-color:#CCCC99"/></td>
    </tr>
    <tr>
        <td><strong>Cicilan</strong></td>
        <td colspan="2"><input type="text" name="jcicilan" id="jcicilan" value="<?=FormatRupiah($jbayar) ?>" onblur="CalculatePay(); formatRupiah('jcicilan')" onfocus="unformatRupiah('jcicilan')" onKeyPress="return focusNext('jdiskon', event);" onkeyup="salinangka()"/>
        <input type="hidden" name="angkacicilan" id="angkacicilan" value="<?=$jcicilan?>" /></td>
    </tr>
	<tr>
        <td>Diskon</td>
        <td colspan="2">
			<input type="text" name="jdiskon" id="jdiskon" value="<?=FormatRupiah($jdiskon) ?>" onblur="CalculatePay(); formatRupiah('jdiskon')" onfocus="unformatRupiah('jdiskon')" onKeyPress="return focusNext('kcicilan', event);" onkeyup="salindiskon()"/>
			<input type="hidden" name="angkadiskon" id="angkadiskon" value="<?=$jdiskon?>" />
        </td>
    </tr>
	<tr>
        <td>Bayar</td>
        <td colspan="2">
			<input type="text" name="jbayar" id="jbayar" readonly="readonly" style="background-color: #CCCCCC"/>
        </td>
    </tr>
	<tr>
        <td>Rek. Kas</td>
        <td colspan="2">
			<select name="rekkas" id="rekkas" style="width: 220px">
<?php              OpenDb();
                $sql = "SELECT kode, nama
                          FROM jbsfina.rekakun
                         WHERE kategori = 'HARTA'
                         ORDER BY nama";        
                $res = QueryDb($sql);
                while($row = mysqli_fetch_row($res))
                {
                    $sel = $row[0] == $defrekkas ? "selected" : "";
                    echo "<option value='".$row[0]."' $sel>{$row[0]} {$row[1]}</option>";
                }
                CloseDb();
                ?>                
            </select>
        </td>
    </tr>
    <tr>
        <td><strong>Tanggal</strong></td>
        <td>
        <input type="text" name="tcicilan" id="tcicilan" readonly size="15" value="<?=$tanggal ?>" onKeyPress="return focusNext('kcicilan', event)" style="background-color:#CCCC99"> </td>
        <td width="45%">
		  &nbsp;	
	     </td>        
    </tr>
    <tr>
        <td valign="top">Keterangan</td>
        <td colspan="2"><textarea id="kcicilan" name="kcicilan" rows="2" cols="30" onKeyPress="return focusNext('Simpan', event)"><?=$kcicilan?></textarea>
        </td>
    </tr>
	<tr>
        <td valign="top">&nbsp;</td>
        <td colspan="2">
            <input type='checkbox' id='smsinfo' name='smsinfo' <?php if ($smsinfo == 1) echo "checked"?> >&nbsp;Notifikasi SMS | Telegram
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
<?php if (strlen((string) $errmsg) > 0) {
	echo  "<script language='javascript'>";
	echo  "alert('$errmsg');";
	echo  "</script>";
} ?>
</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("tcicilan");
var sprytextfield1 = new Spry.Widget.ValidationTextField("jcicilan");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("kcicilan");
</script>
<?php
CloseDb();
?>