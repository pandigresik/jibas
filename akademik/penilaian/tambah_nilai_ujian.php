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
require_once('../cek.php');
require_once('HitungRata.php');

if(isset($_REQUEST["idujian"]))
	$idujian = $_REQUEST["idujian"];
	
if(isset($_REQUEST["nis"]))
	$nis = $_REQUEST["nis"];

$nilai = "";	
if(isset($_REQUEST["nilai"]))
	$nilai = $_REQUEST["nilai"];
$keterangan = "";
if(isset($_REQUEST["keterangan"]))
	$keterangan = $_REQUEST["keterangan"];

OpenDb();

$sql = "SELECT DISTINCT u.idaturan, u.idkelas, u.idsemester, s.nama 
		FROM jbsakad.ujian u, jbsakad.siswa s 
		WHERE u.replid = '$idujian' AND s.nis = '".$nis."'";
$res = QueryDb($sql);
$row = @mysqli_fetch_array($res);
$idkelas = $row['idkelas'];
$idsemester = $row['idsemester'];
$idaturan = $row['idaturan'];
$nama = $row['nama'];

if(isset($_REQUEST['ubah'])) 
{
	BeginTrans();
	$success = true;
	
	$sql = "INSERT INTO jbsakad.nilaiujian SET nis = '$nis', idujian = '$idujian', keterangan = '".CQ($keterangan)."', nilaiujian = '".$nilai."'";
	QueryDbTrans($sql, $success);
	
	if ($success)
		HitungRataSiswa($idkelas, $idsemester, $idaturan, $nis, $success);

	if ($success)
		HitungRataKelasUjian($idkelas, $idsemester, $idaturan, $idujian, $success);
	
	if ($success) 
	{	
		CommitTrans();
		CloseDb();	?>
		<script language = "javascript" type = "text/javascript">
			opener.refresh();
			window.close();
		</script>      	
<?php 		exit();
	} 	
	else
	{
		RollbackTrans();
	}
}

?>

<html>
<head>
<title>JIBAS SIMAKA [Tambah Data Nilai Ujian]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript">
function cek_form() {  
	return validateEmptyText('nilai', 'Nilai');	
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
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
    <form action="tambah_nilai_ujian.php" method="post" name="ubah_nilai_ujian" onSubmit="return cek_form()">
	<input type="hidden" name="nis" value="<?=$nis ?>">
	<input type="hidden" name="idujian" value="<?=$idujian ?>">	
	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
   	<!-- TABLE CONTENT -->
    <tr height="25">
        <td colspan="2" class="header" align="center">Tambah Nilai Ujian</td>
    </tr>
    <tr>
        <td><strong>NIS</strong></td>
        <td>            
        <input type="text" class="disabled" size="50" name="nis" value="<?=$nis?>" readonly></td>
    </tr>
    <tr>
        <td><strong>Nama</strong></td><td>       
        <input type="text" class="disabled" size="50" name="nama" value="<?=$nama;?>" readonly></td>
    </tr>
    <tr>
        <td><strong>Nilai</strong></td>
        <td><input type="text" name="nilai" id="nilai" size="5" value="<?=$nilai?>" maxlength="7" onKeyPress="return focusNext('keterangan',event)">
       </td>
    </tr>
    <tr>
        <td>Keterangan</td>
        <td><input type="text" id="keterangan" name="keterangan" size="50" value="<?=$keterangan?>" onKeyPress="return focusNext('ubah',event)"> </td>
    </tr>
	<tr>
		<td colspan="2" align="center"><strong><font color="red">Setelah menambah nilai ujian, disarankan untuk menghitung ulang nilai nilai akhir siswa.</font></strong></td>	
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
<?php
CloseDb();
?>