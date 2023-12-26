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
require_once('include/db_functions.php');
require_once('include/getheader.php'); 

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];
	
$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Rugi Laba]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>LAPORAN RUGI LABA</strong></font><br /> </center><br /><br />

<table border="0">
<tr>
	<td width="90"><strong>Departemen </strong></td>
    <td><strong>: <?=$departemen ?></strong></td>
</tr>
<tr>
	<td><strong>Tanggal </strong></td>
    <td><strong>: <?=LongDateFormat($tanggal1) ?> s/d <?=LongDateFormat($tanggal2) ?></strong></td>
</tr>
</table>
<br />
<table id="table" border="1" cellpadding="8" cellpadding="0" width="100%">
<tr height="30">
	<td colspan="6"><strong><font size="2">PENDAPATAN</font></strong></td>
</tr>
<?php
OpenDb();
$sql = "SELECT nama, kode, SUM(debet) AS debet, SUM(kredit) As kredit FROM (( 
SELECT DISTINCT j.replid, ra.nama, ra.kode, jd.debet, jd.kredit FROM rekakun ra, katerekakun k,
jurnal j, jurnaldetail jd WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode
AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' 
AND ra.kategori = 'PENDAPATAN' GROUP BY j.replid, ra.nama, ra.kode ORDER BY ra.kode) AS X) 
GROUP BY nama, kode";
$result = QueryDb($sql);
$cnt = 0;
$totalpendapatan = 0;

if (mysqli_num_rows($result) >0) {
	while($row = mysqli_fetch_array($result)) {
		$debet = $row['kredit'] - $row['debet'];
		$debet = FormatRupiah($debet);
		$kredit = "$nbsp";
		
		$totalpendapatan += ($row['kredit'] - $row['debet']);
?>
<tr height="25">
	<td width="2%" align="right">&nbsp;</td>
    <td width="5%" align="left" valign="top"><?=$row['kode'] ?></td>
    <td align="left" width="*" valign="top"><?=$row['nama'] ?></td>
    <td align="right" width="18%" valign="top"><?=$debet ?></td> 
    <td align="right" width="18%" valign="top"><?=$kredit ?></td>
    <td width="20%">&nbsp;</td>
</tr>
<?php } //end while  
}
?>
<tr height="30">
	<td>&nbsp;</td>
    <td colspan="4"><strong>SUB TOTAL PENDAPATAN</strong></td>
    <td align="right"><strong><?=FormatRupiah($totalpendapatan) ?></strong></td>
</tr>
<tr height="5">
	<td colspan="6">&nbsp;</td>
</tr>
<tr height="30">
	<td colspan="6"><strong><font size="2">BIAYA</font></strong></td>
</tr>
<?php
$sql = "SELECT nama, kode, SUM(debet) AS debet, SUM(kredit) As kredit FROM (( 
SELECT DISTINCT j.replid, ra.nama, ra.kode, jd.debet, jd.kredit FROM rekakun ra, katerekakun k,
jurnal j, jurnaldetail jd WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode 
AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori = 'BIAYA' 
GROUP BY j.replid, ra.nama, ra.kode ORDER BY ra.kode) AS X) GROUP BY nama, kode";
$result = QueryDb($sql);
$cnt = 0;
$totalbiaya = 0;
if (mysqli_num_rows($result) >0) {
	while($row = mysqli_fetch_array($result)) {
		$kredit = $row['debet'] - $row['kredit'];
		$kredit = FormatRupiah($kredit);
		$debet = "$nbsp";
		
		$totalbiaya += ($row['debet'] - $row['kredit']);
?>
<tr height="25">
	<td width="2%" align="right">&nbsp;</td>
    <td width="5%" align="left" valign="top"><?=$row['kode'] ?></td>
    <td align="left" width="*" valign="top"><?=$row['nama'] ?></td>
    <td align="right" width="18%" valign="top"><?=$debet ?></td> 
    <td align="right" width="18%" valign="top"><?=$kredit ?></td>
    <td width="20%">&nbsp;</td>
</tr>
<?php } //end while  
}
?>

<tr height="30">
	<td>&nbsp;</td>
    <td colspan="4"><strong>SUB TOTAL BIAYA</strong></td>
    <td align="right"><strong><?=FormatRupiah($totalbiaya) ?></strong></td>
</tr>
<tr height="5">
	<td colspan="6">&nbsp;</td>
</tr>

<tr>
	<td colspan="4">
    	<strong><font size="4"><strong>
		<?php if ($totalpendapatan < $totalbiaya) echo  "RUGI"; else echo  "LABA"; ?></strong></font></strong></td>
    <td colspan="2" align="right"><strong><font size="4"><?=FormatRupiah($totalpendapatan - $totalbiaya) ?></font></strong></td>
</tr>

</table>

</td></tr></table>

<script language="javascript">window.print();</script>

</body>
</html>