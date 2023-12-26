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

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Laporan_Tabungan_Siswa_'.$nis.'.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$idtahunbuku = $_REQUEST['idtahunbuku'];
$nis = $_REQUEST['nis'];
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];
$datetime1 = "$tanggal1 00:00:00";
$datetime2 = "$tanggal2 23:59:59";

OpenDb();
$sql = "SELECT s.nama, k.kelas, t.tingkat, t.departemen 
          FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
			WHERE s.nis = '$nis' AND s.idkelas = k.replid AND k.idtingkat = t.replid";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namasiswa = $row[0];
$kelas = $row[1];
$tingkat = $row[2];
$departemen = $row[3];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Tabungan Per Siswa]</title>
</head>

<body>
<center><font size="4"><strong>DATA TABUNGAN SISWA</strong></font><br /> </center><br /><br />`
<table border="0">
<tr>
	<td><strong>Siswa </strong></td>
    <td><strong>: <?=$nis . " - " . $namasiswa?></strong></td>
</tr>
<tr>
	<td><strong>Kelas </strong></td>
    <td><strong>: <?=$tingkat." - ".$kelas ?></strong></td>
</tr>
<tr>
	<td><strong>Tanggal </strong></td>
    <td><strong>: <?=LongDateFormat($tanggal1) . " s/d " . LongDateFormat($tanggal2) ?></strong></td>
</tr>
</table>
<br />

<?php
require_once("laporan.siswa.content.report.body.php");
?>


</body>
</html>
<?php
CloseDb();
?>