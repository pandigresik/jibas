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

OpenDb();

$idpembayaran = $_REQUEST['idpembayaran'];

// -- Default Rekening Kas using value from previous input
$sql = "SELECT jd.koderek
		  FROM jbsfina.penerimaanjttcalon p, jbsfina.jurnal j, jbsfina.jurnaldetail jd, rekakun rk
	     WHERE p.replid = '$idpembayaran'
		   AND p.idjurnal = j.replid
		   AND j.replid = jd.idjurnal
		   AND jd.koderek = rk.kode
		   AND rk.kategori = 'HARTA'";
$defrekkas = FetchSingle($sql);

$sql = "SELECT c.nopendaftaran, b.besar, b.lunas, p.idbesarjttcalon, c.nama, p.idjurnal, p.jumlah, date_format(p.tanggal, '%d-%m-%Y') as tanggal, 
               p.keterangan, pn.nama as namapenerimaan, pn.rekkas, pn.rekpendapatan, pn.rekpiutang, pn.info1 AS rekdiskon,
			   p.info1 AS diskon, pn.replid AS idpenerimaan 
			 FROM penerimaanjttcalon p, besarjttcalon b, jbsakad.calonsiswa c, datapenerimaan pn 
 			WHERE p.replid = '$idpembayaran' AND p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.idpenerimaan = pn.replid";

$result = QueryDb($sql);
$row = mysqli_fetch_array($result);

$no = $row['nopendaftaran'];
$namasiswa = $row['nama'];
$idjurnal = $row['idjurnal'];
$tanggal = $row['tanggal'];
$keterangan = $row['keterangan'];
$idpenerimaan = $row['idpenerimaan'];
$namapenerimaan = $row['namapenerimaan'];
$besar = $row['jumlah'];
$besardiskon = $row['diskon'];
$idbesarjtt = $row['idbesarjttcalon'];
$besarjtt = $row['besar'];
$lunas = $row['lunas'];
$rekkas = $row['rekkas'];
$rekpiutang = $row['rekpiutang'];
$rekpendapatan = $row['rekpendapatan'];
$rekdiskon = $row['rekdiskon'];
$jdiskon = $row['diskon'];
$jbayar = $besar;
$jcicilan = $jbayar + $jdiskon;

//-- cek rekening diskon ------------------
$sql = "SELECT replid FROM datapenerimaan WHERE replid=$idpenerimaan AND info1 IS NOT NULL";
if (0 == (int)FetchSingle($sql))
{
	//-- rek diskon belum ada, warning user ---------------
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

if (1 == (int)$_REQUEST['issubmit']) 
{
	$jcicilan = UnformatRupiah($_REQUEST['jcicilan']);
	$tcicilan = MySqlDateFormat($_REQUEST['tcicilan']);
	$kcicilan = CQ($_REQUEST['kcicilan']);
	$jdiskon = (int)UnformatRupiah($_REQUEST['jdiskon']);
	$jbayar = $jcicilan - $jdiskon;
	$alasan = CQ($_REQUEST['alasan']);
	$petugas = getUserName();
	
	$selrekkas = $_REQUEST['rekkas']; // selected rekening kas
	
	if ($jbayar == $besar && $jdiskon == $besardiskon) 
	{
		//--------------------------------------------------------------
		// Hanya mengubah informasi pembayaran tanpa mengubah besarnya  
		// -------------------------------------------------------------
		
		BeginTrans();
		$success = true;
		
		$sql = "UPDATE penerimaanjttcalon
				   SET tanggal='$tcicilan', keterangan='$kcicilan', 
					   alasan='$alasan', petugas = '$petugas'
				 WHERE replid=$idpembayaran";
		$result = QueryDb($sql);
		
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
			echo  "alert('Gagal mengubah data!');";
			echo  "</script>";
			exit();
		}
	} 
	else 
	{
		//----------------------------
		// Mengubah besar pembayaran  
		// ---------------------------
		
		$sql = "SELECT SUM(jumlah), SUM(info1)
				FROM penerimaanjttcalon WHERE idbesarjttcalon = '$idbesarjtt' AND replid <> '$idpembayaran'";
		$row = FetchSingleRow($sql);
		$totalcicilan = $row[0];
		$totaldiskon = $row[1];
				
		$errmsg = "";
		if ($totalcicilan + $totaldiskon + $jbayar + $jdiskon > $besarjtt) 
		{
			$errmsg = "Maaf, pembayaran tidak dapat dilakukan! Jumlah pembayaran cicilan lebih besar daripada bayaran yang harus dilunasi";
			CloseDb();
		}
		else
		{
			$lunas = 0;
			$ketjurnal = "";
			if ($totalcicilan + $totaldiskon + $jbayar + $jdiskon == $besarjtt)
			{
				$ketjurnal = "Pelunasan $namapenerimaan calon siswa $namasiswa ($nis)";
				$lunas = 1;
			}
			else
			{
				$cicilan = 0;
				$sql = "SELECT replid FROM penerimaanjttcalon WHERE idbesarjttcalon='$idbesarjtt' ORDER BY tanggal, replid ASC";
				$res = QueryDb($sql);
				while($row = mysqli_fetch_row($res))
				{
					$cicilan++;
					if ($row[0] == $idpembayaran)
						break;
				}
				$ketjurnal = "Pembayaran ke-$cicilan $namapenerimaan calon siswa $namasiswa ($nis)";
				$lunas = 0;
			}
			
			// Ambil kode rekening dari jurnal bukan dari datapenerimaan
			$rekkas = AmbilKodeRekJurnal($idjurnal, "HARTA", $idpenerimaan);
			$rekpiutang = AmbilKodeRekJurnal($idjurnal, "PIUTANG", $idpenerimaan);
			$rekdiskon = AmbilKodeRekJurnal($idjurnal, "DISKON", $idpenerimaan);
				
			BeginTrans();
			$success = true;
			
			$sql = "UPDATE penerimaanjttcalon SET jumlah='$jbayar', keterangan='$kcicilan', tanggal='$tcicilan', 
			        alasan='$alasan', petugas='$petugas', info1='$jdiskon' WHERE replid = '".$idpembayaran."'";
			QueryDbTrans($sql, $success);
			
			$idjurnal = 0;
			if ($success)
			{
				$sql = "SELECT idjurnal FROM penerimaanjttcalon WHERE replid='$idpembayaran'";
				$idjurnal = FetchSingle($sql);
			}
			
			if ($success)
			{
				$sql = "UPDATE jurnal SET transaksi='$ketjurnal' WHERE replid = '".$idjurnal."'";
				QueryDbTrans($sql, $success);	
			}
			
			if ($success)
			{
				$sql = "UPDATE jurnaldetail SET debet='$jbayar' WHERE idjurnal='$idjurnal' AND koderek='$rekkas' AND kredit=0";
				QueryDbTrans($sql, $success);	
			}
			
			if ($success && $rekkas != $selrekkas)
			{
				$sql = "UPDATE jurnaldetail SET koderek='$selrekkas' WHERE idjurnal='$idjurnal' AND koderek='$rekkas' AND kredit=0";
				QueryDbTrans($sql, $success);	
			}
			
			if ($success)
			{
				$sql = "UPDATE jurnaldetail SET kredit='$jcicilan' WHERE idjurnal='$idjurnal' AND koderek='$rekpiutang' AND debet=0";
				QueryDbTrans($sql, $success);	
			}
			
			if ($success)
			{
				$sql = "SELECT COUNT(replid) FROM jurnaldetail WHERE idjurnal='$idjurnal' AND koderek='$rekdiskon'";
				$nJurnalDiskon = FetchSingle($sql);
				if ($nJurnalDiskon == 0 && $jdiskon > 0)
					$sql = "INSERT INTO jurnaldetail SET debet='$jdiskon', idjurnal='$idjurnal', koderek='$rekdiskon', kredit=0";
				else
					$sql = "UPDATE jurnaldetail SET debet='$jdiskon' WHERE idjurnal='$idjurnal' AND koderek='$rekdiskon' AND kredit=0";
				QueryDbTrans($sql, $success);	
			}
			
			if ($success)
			{
				$sql = "SET @DISABLE_TRIGGERS = 1;";
				QueryDb($sql);
				
				$sql = "UPDATE besarjttcalon SET lunas='$lunas' WHERE replid='$idbesarjtt'";
				QueryDbTrans($sql, $success);	
				
				$sql = "SET @DISABLE_TRIGGERS = NULL;";
				QueryDb($sql);
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
			} // if ($success)
		} // if ($totalcicilan + $totaldiskon + $jbayar + $jdiskon > $besarjtt)	
	} // if ($jbayar == $besar && $jdiskon == $besardiskon)
} // if (1 == (int)$_REQUEST['issubmit'])  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Ubah Pembayaran Cicilan]</title>
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
		    	validateEmptyText('alasan', 'Alasan Perubahan') &&
		    	validateMaxText('alasan', 500, 'Alasan Perubahan') &&
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
	var angka = document.getElementById("angkacicilan").value;
	if(isNaN(angka)) 
	{
		alert ('Besar cicilan harus berupa bilangan!');
		document.getElementById('jcicilan').value = "";
		document.getElementById('jcicilan').focus();
		return false;
	}
	else if(parseInt(angka) < 0)
	{
		alert ('Besar cicilan tidak boleh negatif!');
		document.getElementById('jcicilan').focus();
		return false;
	}
	
	var diskon = document.getElementById("angkadiskon").value;
	if(isNaN(diskon)) 
	{
		alert ('Besar diskon harus berupa bilangan!');
		document.getElementById('jdiskon').value = "";
		document.getElementById('jdiskon').focus();
		return false;
	}
	else if(parseInt(diskon) < 0)
	{
		alert ('Besar diskon tidak boleh negatif!');
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

function salindiskon()
{	
	var diskon = document.getElementById("jdiskon").value;
	document.getElementById("angkadiskon").value = diskon;
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
    .: Ubah Pembayaran Cicilan :.
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
        <td width="50%"><strong>Pembayaran</strong></td>
        <td colspan="2"><input type="text" size="30" value="<?=$namapenerimaan?>" readonly style="background-color:#CCCC99"/></td>
    </tr>
    <tr>
        <td><strong>Nama</strong></td>
        <td colspan="2"><input type="text" size="30" value="<?=$no . " - " . $namasiswa ?>" readonly style="background-color:#CCCC99"/></td>
    </tr>
    <tr>
        <td><strong>Cicilan</strong></td>
        <td colspan="2">
			<input type="text" name="jcicilan" id="jcicilan" value="<?=FormatRupiah($jcicilan) ?>" onblur="CalculatePay(); formatRupiah('jcicilan')" onfocus="unformatRupiah('jcicilan')" onKeyPress="return focusNext('jdiskon', event)" onkeyup="salinangka()"/>
			<input type="hidden" name="angkacicilan" id="angkacicilan" value="<?=$jbayar?>" />
		</td>
    </tr>
	<tr>
        <td><strong>Diskon</strong></td>
        <td colspan="2">
			<input type="text" name="jdiskon" id="jdiskon" value="<?=FormatRupiah($jdiskon) ?>" onblur="CalculatePay(); formatRupiah('jdiskon')" onfocus="unformatRupiah('jdiskon')" onKeyPress="return focusNext('alasan', event)" onkeyup="salindiskon()"/>
			<input type="hidden" name="angkadiskon" id="angkadiskon" value="<?=$jdiskon?>" />
        </td>
    </tr>
	<tr>
        <td>Bayar</td>
        <td colspan="2">
			<input type="text" name="jbayar" id="jbayar" readonly="readonly" value="<?=$jbayar?>" style="background-color: #CCCCCC"/>
        </td>
    </tr>
	<tr>
        <td><strong>Rek. Kas</strong></td>
        <td colspan="2">
			<select name="rekkas" id="rekkas" style="width: 200px">
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
        <input type="text" name="tcicilan" id="tcicilan" readonly size="15" value="<?=$tanggal ?>" onKeyPress="return focusNext('alasan', event)" style="background-color:#CCCC99"> </td>
        <td width="45%">
        &nbsp;
	     </td>        
    </tr>
    <tr>
        <td valign="top"><strong>Alasan Perubahan</strong></td>
        <td colspan="2"><textarea id="alasan" name="alasan" rows="3" cols="30" onKeyPress="return focusNext('kcicilan', event)"><?=$alasan ?></textarea>
        </td>
    </tr>
    <tr>
        <td valign="top">Keterangan</td>
        <td colspan="2"><textarea id="kcicilan" name="kcicilan" rows="3" cols="30" onKeyPress="return focusNext('Simpan', event)"><?=$keterangan ?></textarea>
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
var sprytextarea1 = new Spry.Widget.ValidationTextarea("alasan");
</script>