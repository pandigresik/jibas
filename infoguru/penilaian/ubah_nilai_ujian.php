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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('HitungRata.php');

if(isset($_REQUEST["id"]))
	$id = $_REQUEST["id"];
if(isset($_REQUEST["totnis"]))
	$totnis = $_REQUEST["totnis"];

OpenDb();
$query = "SELECT n.idujian, n.nilaiujian, n.keterangan, u.idaturan, u.idkelas, u.idsemester, s.nama, s.nis FROM jbsakad.ujian u, jbsakad.siswa s, jbsakad.nilaiujian n WHERE n.replid = '$id' AND n.idujian = u.replid AND s.nis = n.nis";
$result = QueryDb($query);

$row = @mysqli_fetch_array($result);
$idkelas = $row['idkelas'];
$idsemester = $row['idsemester'];
$idaturan = $row['idaturan'];
$idujian = $row['idujian'];
$nis = $row['nis'];
$nama = $row['nama'];
$nilai = $row['nilaiujian'];
$keterangan = $row["keterangan"];

if(isset($_REQUEST["nilai"]))
	$nilai = $_REQUEST["nilai"];
if(isset($_REQUEST["keterangan"]))
	$keterangan = $_REQUEST["keterangan"];
if(isset($_REQUEST["nasli"]))
	$nasli = $_REQUEST["nasli"];	
if(isset($_REQUEST["alasan"]))
	$alasan = $_REQUEST["alasan"];		

if(isset($_REQUEST["ubah"])) 
{
	BeginTrans();
	$success = 0;	
	$query_del_nau = "UPDATE jbsakad.nau SET nilaiAU = 0 WHERE idkelas='$idkelas' AND idsemester='$idsemester' AND idaturan='$idaturan' AND nis = '".$nis."'";
 	QueryDbTrans($query_del_nau, $success);

	if ($success)
	{
		$query = "SELECT replid FROM jbsakad.nilaiujian WHERE nis='$nis' AND idujian='$idujian'";
		$res = QueryDb($query);
		$row = mysqli_fetch_row($res);
		$idnilai = $row[0];
		$pengguna = SI_USER_ID() . " - ". SI_USER_NAME();
		
		$info = "";
		$query = "SELECT p.nama AS pelajaran, ju.jenisujian, u.deskripsi, DATE_FORMAT(u.tanggal, '%d-%m-%Y') AS tanggal, s.nis, s.nama
				 	   FROM nilaiujian nu, ujian u, pelajaran p, jenisujian ju, siswa s
					  WHERE nu.replid = '$idnilai' AND nu.idujian = u.replid AND u.idpelajaran = p.replid
						 AND u.idjenis = ju.replid AND nu.nis = s.nis;";
		$res = QueryDb($query);
		if (mysqli_num_rows($res) > 0)
		{
			$row = mysqli_fetch_array($res);
			$info = "Nilai Ujian ".$row['jenisujian']." ".$row['pelajaran']." tanggal ".$row['tanggal']." siswa ".$row['nis']." ".$row['nama'];
		}						 
		
		$query = "INSERT INTO jbsakad.auditnilai SET jenisnilai='nilaiujian', idnilai='$idnilai', nasli='$nasli', nubah='$nilai', alasan='$alasan', pengguna='$pengguna', informasi='$info'";
		QueryDbTrans($query,$success);
	}

	if ($success)
	{
		$query = "UPDATE jbsakad.nilaiujian SET keterangan='$keterangan', nilaiujian='$nilai' WHERE nis='$nis' AND idujian=$idujian";
		QueryDbTrans($query,$success);
	}
	
	if ($success)
		HitungRataSiswa($idkelas, $idsemester, $idaturan, $nis, $success);

	if ($success)
		HitungRataKelasUjian($idkelas, $idsemester, $idaturan, $idujian, $success);
	
	if ($success) 
	{
		CommitTrans();	
		CloseDb(); ?>
		<script language = "javascript" type = "text/javascript">	
			opener.refresh();
			window.close();
		</script>
<?php 	} 
	else  
	{
		RollbackTrans();
		CloseDb(); ?>
		<script language="javascript">
			alert ('Data gagal disimpan');
		</script>
<?php  }	
}
?>

<html>
<head>
<title>JIBAS SIMAKA [Ubah Data Nilai Ujian]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/validasi.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript">
function cek_form() {
  	var nilai = document.getElementById("nilai").value;
	if(nilai.length == 0) {
		alert("Nilai tidak boleh kosong!");		
		document.getElementById("nilai").focus();
		return false;
	} else {
		if (isNaN(nilai)){
			alert ('Nilai Akhir harus berupa bilangan!');			
			document.getElementById("nilai").focus();
			return false;
		}
		if (parseInt(nilai)>100){
			alert ('Rentang Nilai Akhir antara 0 - 100!');
			document.getElementById("nilai").focus();
			return false;
		}
	}

	return validateEmptyText('alasan', 'Alasan Perubahan Nilai');
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
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('nilai').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Nilai Ujian :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
    <form action="ubah_nilai_ujian.php" method="post" name="ubah_nilai_ujian" onSubmit="return cek_form()">	
    <input type="hidden" name="id" value="<?=$id ?>">
     <input type="hidden" name="totnis" value="<?=$totnis ?>">	
	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
   	<!-- TABLE CONTENT -->
    <tr>
        <td><strong>NIS</strong></td>
        <td><input class="disabled" type="text" size="15" name="nis" value="<?=$nis ?>" readonly></td>
    </tr>
    <tr>
        <td><strong>Nama</strong></td>
        <td><input class="disabled" type="text" size="50" name="nama" value="<?=$nama?>" readonly></td>
    </tr>
    <tr>
        <td><strong>Nilai</strong></td>
        <td>
        <input type="hidden" name="nasli" id="nasli" value="<?=$nilai?>">
        <input type="text" name="nilai" id="nilai" size="5" value="<?=$nilai?>" maxlength="7" onKeyPress="return focusNext('alasan',event)">
        </td>
    </tr>
    <tr>
        <td><strong>Alasan Perubahan Nilai</strong></td>
        <td><input type="text" name="alasan" id="alasan" size="50" value="<?=$alasan ?>" onKeyPress="return focusNext('ubah',event)"></td>
    </tr>
    <tr>
        <td>Keterangan</td>
        <td><input type="text" name="keterangan" id="keterangan" size="50" value="<?=$keterangan ?>" onKeyPress="return focusNext('ubah',event)"></td>
    </tr>
    <tr>
      	<td colspan="2" align="center"><font color="red"><strong>Setelah merubah nilai ujian, disarankan untuk menghitung
        ulang nilai nilai akhir siswa.</strong></font></td>
    </tr>
    <tr>
        <td align="center" colspan="2">
            <input type="submit" value="Simpan" name="ubah" id="ubah" class="but">
            <input type="button" value="Tutup" name="batal" class="but" onClick="window.close();">
         </td>
    </tr>
    </table>
    </form>

 <!-- END OF CONTENT //--->
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
</body>
</html>
<script type="text/javascript">
	var sprytextfield1 = new Spry.Widget.ValidationTextField("nilai");
	var sprytextfield2 = new Spry.Widget.ValidationTextField("keterangan");
</script>