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
require_once('../inc/config.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/sessioninfo.php');
require_once('../inc/db_functions.php');
OpenDb();
if (isset($_REQUEST['Simpan'])){
	$sql = "SELECT * FROM konfigurasi";
	$result = QueryDb($sql);
	$num = @mysqli_num_rows($result);
	if ($num==0){
		$sql = "INSERT INTO konfigurasi SET siswa='".$_REQUEST['siswa']."',pegawai='".$_REQUEST['pegawai']."',other='".$_REQUEST['other']."',denda='".UnformatRupiah($_REQUEST['denda'])."'";
	} else {
		$sql = "UPDATE konfigurasi SET siswa='".$_REQUEST['siswa']."',pegawai='".$_REQUEST['pegawai']."',other='".$_REQUEST['other']."',denda='".UnformatRupiah($_REQUEST['denda'])."'";
	}
	$result = QueryDb($sql);
	if ($result){
		?>
        <script language="javascript">
			window.close();
        </script>
        <?php
	}
}
$sql = "SELECT * FROM konfigurasi";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$siswa = $row['siswa'];
if ($siswa=='')
	$siswa=0;
$pegawai = $row['pegawai'];
if ($pegawai=='')
	$pegawai=0;
$other = $row['other'];
if ($other=='')
	$other=0;
$denda = $row['denda'];
if ($denda=='')
	$denda=0;	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scr/tables.js"></script>
<script type="text/javascript" src="../scr/tools.js"></script>
<script type="text/javascript" src="../scr/rupiah.js"></script>
<script type="text/javascript">
function Blur(elementId){
	var value = document.getElementById(elementId).value;
	if (value==''){
		document.getElementById(elementId).value = 0 ;
		if (elementId=='denda')
			formatRupiah(elementId) ;
		else
			document.getElementById(elementId).value = 0 ;
	} else {
		if (isNaN(value)){
			document.getElementById(elementId).value = 0 ;
			if (elementId=='denda')
				formatRupiah(elementId) ;
			else
				document.getElementById(elementId).value = 0 ;
		} else {
			if (elementId=='denda')
				formatRupiah(elementId) ;
		}
	}	
}
function Fokus(elementId){
	unformatRupiah(elementId) ;
}
</script>
</head>

<body leftmargin="0" topmargin="0">
<div id="title" align="right">
    <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
  <font style="font-size:18px; color:#999999">Konfigurasi</font><br /><br />
</div>
<div id="content">
<fieldset><legend>Maksimum peminjaman yang dapat dilakukan</legend>
<form action="konfigurasi.php" method="post">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="17%" align="right">Siswa</td>
    <td width="83%">
      <input type="text" name="siswa" id="siswa" class="inptxt-small-text" value="<?=$siswa?>" style="width:40px" onBlur="Blur('siswa')" />    </td>
  </tr>
  <tr>
    <td align="right">Pegawai</td>
    <td><input type="text" name="pegawai" id="pegawai" class="inptxt-small-text" value="<?=$pegawai?>" style="width:40px" onBlur="Blur('pegawai')" /></td>
  </tr>
  <tr>
    <td align="right">Umum/Anggota&nbsp;Luar&nbsp;Sekolah</td>
    <td><input type="text" name="other" id="other" class="inptxt-small-text" value="<?=$other?>" style="width:40px" onBlur="Blur('other')" /></td>
  </tr>
  <tr>
    <td align="right">Denda per hari</td>
    <td><input type="text" name="denda" id="denda" class="inptxt-small-text" value="<?=FormatRupiah($denda)?>" style="width:150px" onBlur="Blur('denda')" onFocus="Fokus('denda')" /></td>
  </tr>
  <tr>
<?php
	$disabled = "";
	if (!IsAdmin())
		$disabled = "disabled='disabled'"; ?>
    <td colspan="2" align="center"><input type="submit" name="Simpan" value="Simpan" class="cmbfrm2" <?=$disabled?> />&nbsp;&nbsp;<input type="button" onClick="window.close()" value="Tutup"  class="cmbfrm2"/></td>
    </tr>
</table>
</form>
</fieldset>
</div>
</body>
</html>
<?php CloseDb(); ?>