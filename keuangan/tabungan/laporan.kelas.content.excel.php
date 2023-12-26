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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php'); 
require_once('../library/jurnal.php');
require_once('laporan.kelas.content.func.php');

$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

OpenDb();

ReadRequest();
GetNames();

/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Laporan_Tabungan_Per_Kelas.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$sql = "SELECT departemen FROM jbsakad.angkatan WHERE replid='$idangkatan'"; 	
$departemen = FetchSingle($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Tabungan Per Kelas]</title>
</head>

<body>

<table border="0">
<tr>
<td><strong>Departemen:</strong></td>
   <td><?=$departemen?></td>
</tr>
<tr>
	<td><strong>Angkatan:</strong></td>
   <td><?=$namaangkatan?></td>
</tr>
<tr>
	<td><strong>Tingkat:</strong></td>
   <td><?=$namatingkat?></td>
</tr>
<tr>
	<td><strong>Kelas:</strong></td>
   <td><?=$namakelas?></td>
</tr>
</table>
<br>

<?php
$pageLimit = false;
require_once("laporan.kelas.content.body.php");
?>
	
</td></tr></table>

</body>
</html>
<?php
CloseDb()
?>
