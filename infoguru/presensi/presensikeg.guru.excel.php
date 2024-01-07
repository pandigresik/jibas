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
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../library/datearith.php');
require_once('../sessionchecker.php');

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=LaporanPresensiKegiatanGuru.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$nip = SI_USER_ID();
$idkegiatan = $_REQUEST['idkegiatan'];
$bulan = $_REQUEST['bulan'];
$tahun = $_REQUEST['tahun'];

OpenDb();

$sql = "SELECT nama
          FROM jbssdm.pegawai
         WHERE nip = '".$nip."'";     
$res = QueryDB($sql);	
$row = mysqli_fetch_row($res);
$nama = $row[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOGURU [Cetak Laporan Presensi Kegiatan Guru]</title>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-family: Verdana;
}
.style4 {font-family: Verdana; font-weight: bold; font-size: 12px; }
.style5 {font-family: Verdana}
.style6 {font-size: 12px}
.style7 {font-family: Verdana; font-size: 12px; }
-->
</style>
</head>

<body>

<table width="100%" border="0" cellspacing="0">
<tr>
    <th scope="row" colspan="8"><span class="style1">Laporan Presensi Kegiatan Guru</span></th>
</tr>
</table>
<br />

<table width="27%">
<tr>
	<td><span class="style4">Nama</span></td>
    <td width="57%" colspan="7"><span class="style4">: <?= $nip .' - '. $nama ?></span></td>
</tr>
</table>
<br />

<?php
$showbutton = false;
require_once("presensikeg.guru.report.php");
?>

</body>
</html>