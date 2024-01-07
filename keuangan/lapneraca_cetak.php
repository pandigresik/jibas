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

$bln = 0;
if (isset($_REQUEST['bln']))
	$bln = (int)$_REQUEST['bln'];
	
$thn = 0;
if (isset($_REQUEST['thn']))
	$thn = (int)$_REQUEST['thn'];
	
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
<title>JIBAS KEU [Laporan Neraca]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="1000" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>LAPORAN NERACA</strong></font><br /> </center><br /><br />

<table border="0">
<tr>
	<td width="90"><strong>Departemen </strong></td>
    <td><strong>: <?=$departemen ?></strong></td>
</tr>
<tr>
	<td><strong>Per Tanggal </strong></td>
    <td><strong>: <?=LongDateFormat($tanggal2) ?></strong></td>
</tr>
</table>
<br />
<table border="1" width="100%" align="left" cellpadding="2" cellspacing="5">
<tr><td width="50%" valign="top">

<font size="2"><strong>HARTA</strong></font><br />
<table border="0" style="border-collapse:collapse" cellpadding="2" width="95%" align="center">
<tr height="28">
	<td width="2%">&nbsp;</td>
    <td colspan="6"><strong>AKTIVA LANCAR</strong><br /></td>
</tr>
<?php
OpenDb();
$sql = "SELECT jd.koderek, ra.nama, sum(jd.debet - jd.kredit) 
	          FROM jurnal j, jurnaldetail jd, rekakun ra 
			   WHERE j.replid = jd.idjurnal AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' 
				  AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' 
				  AND ra.kategori IN ('HARTA', 'PIUTANG') GROUP BY jd.koderek, ra.nama ORDER BY jd.koderek";
$result = QueryDb($sql);
$totalaktivalancar = 0;
while ($row = mysqli_fetch_row($result)) {
	$totalaktivalancar += (float)$row[2];
?>
<tr height="23">
	<td width="2%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="5%" align="left"><?=$row[0] ?></td>
    <td width="*" align="left"><?=$row[1] ?></td>
    <td width="28%" align="right"><?=FormatRupiah($row[2]) ?></td>
    <td width="30%"  align="right">&nbsp;</td>
    <td width="13">&nbsp;</td>
</tr>
<?php
}
?>
<tr height="23">
	<td width="2%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
	<td colspan="3" align="left"><strong><em>Sub Total Aktiva Lancar:</em></strong><br /></td>
    <td align="right"><strong><?=FormatRupiah($totalaktivalancar) ?></strong></td>
    <td>&nbsp;</td>
</tr>
</table>
<br />

<table border="0" style="border-collapse:collapse" cellpadding="2" width="95%" align="center">
<tr height="28">
	<td width="2%">&nbsp;</td>
    <td colspan="6"><strong>AKTIVA TETAP</strong><br /></td>
</tr>
<?php
$sql = "SELECT jd.koderek, ra.nama, sum(jd.debet - jd.kredit) 
			 FROM jurnal j, jurnaldetail jd, rekakun ra 
			WHERE j.replid = jd.idjurnal AND jd.koderek = ra.kode 
			  AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' 
			  AND ra.kategori = 'INVENTARIS' GROUP BY jd.koderek, ra.nama ORDER BY jd.koderek";
$result = QueryDb($sql);
$totalaktivatetap = 0;
while ($row = mysqli_fetch_row($result)) {
	$totalaktivatetap += (float)$row[2];
?>
<tr height="23">
	<td width="2%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="5%" align="left"><?=$row[0] ?></td>
    <td width="*" align="left"><?=$row[1] ?></td>
    <td width="28%" align="right"><?=FormatRupiah($row[2]) ?></td>
    <td width="30%"  align="right">&nbsp;</td>
    <td width="13">&nbsp;</td>
</tr>
<?php
}
?>
<tr height="23">
	<td width="2%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
	<td colspan="3" align="left"><strong><em>Sub Total Aktiva Tetap:</em></strong><br /></td>
    <td align="right"><strong><?=FormatRupiah($totalaktivatetap) ?></strong></td>
    <td>&nbsp;</td>
</tr>
<tr>
	<td colspan="6"><hr width="100%" style="border-style:dashed" /></td>
    <td align="right">+</td>
</tr>
<tr height="28">
    <td colspan="5" align="left"><font size="2"><strong>TOTAL HARTA</strong></font><br /></td>
    <td align="right"><font size="2"><strong><?=FormatRupiah($totalaktivatetap + $totalaktivalancar) ?></strong></font></td>
    <td>&nbsp;</td>
</tr>
</table>
</td>

<td width="50%" valign="top">

<font size="2"><strong>KEWAJIBAN</strong></font><br />
<table border="0" style="border-collapse:collapse" cellpadding="2" width="95%" align="center">
<tr height="28">
	<td width="2%">&nbsp;</td>
    <td colspan="6"><strong>HUTANG</strong><br /></td>
</tr>
<?php
$sql = "SELECT jd.koderek, ra.nama, sum(jd.kredit - jd.debet) FROM jurnal j, jurnaldetail jd, rekakun ra WHERE j.replid = jd.idjurnal AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori = 'UTANG' GROUP BY jd.koderek, ra.nama ORDER BY jd.koderek";
$result = QueryDb($sql);
$totalhutang = 0;
while ($row = mysqli_fetch_row($result)) {
	$totalhutang += (float)$row[2];
?>
<tr height="23">
	<td width="2%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="5%" align="left"><?=$row[0] ?></td>
    <td width="*" align="left"><?=$row[1] ?></td>
    <td width="28%" align="right"><?=FormatRupiah($row[2]) ?></td>
    <td width="30%"  align="right">&nbsp;</td>
    <td width="13">&nbsp;</td>
</tr>
<?php
}
?>
<tr height="23">
	<td width="2%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
	<td colspan="3" align="left"><strong><em>Sub Total Hutang:</em></strong><br /></td>
    <td align="right"><strong><?=FormatRupiah($totalhutang) ?></strong></td>
    <td>&nbsp;</td>
</tr>
</table>
<br />
<table  border="0" style="border-collapse:collapse" cellpadding="2" width="95%" align="center">
<tr height="28">
	<td width="2%">&nbsp;</td>
    <td colspan="6"><font size="1"><strong>MODAL</strong></font><br /></td>
</tr>
<?php
$sql = "SELECT tanggalmulai FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tanggal1 = $row[0];

$sql = "SELECT SUM(jd.kredit - jd.debet) FROM rekakun ra,
jurnal j, jurnaldetail jd WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori IN ('PENDAPATAN', 'MODAL')";
//echo  "$sql<br>";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$totalpendapatan = (float)$row[0];
//echo  "$totalpendapatan<br>";

$sql = "SELECT SUM(jd.debet - jd.kredit) FROM rekakun ra, jurnal j, jurnaldetail jd WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori = 'BIAYA'";
//echo  "$sql<br>";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$totalbiaya = (float)$row[0];
//echo  "$totalbiaya<br>";
$modalusaha = $totalpendapatan - $totalbiaya;
//echo  "$modalusaha<br>"; ?>
<tr height="23">
	<td width="2%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="5%" align="left">&nbsp;</td>
    <td width="*" align="left">Modal Usaha</td>
    <td width="28%" align="right"><?=FormatRupiah($modalusaha) ?></td>
    <td width="30%"  align="right">&nbsp;</td>
    <td width="13">&nbsp;</td>
</tr>
<tr height="23">
	<td width="2%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
	<td colspan="3" align="left"><strong><em>Sub Total Modal Usaha:</em></strong><br /></td>
    <td align="right"><strong><?=FormatRupiah($modalusaha) ?></strong></td>
    <td>&nbsp;</td>
</tr>
<tr>
	<td colspan="6"><hr width="100%" style="border-style:dashed" /></td>
    <td align="right">+</td>
</tr>
<tr height="28">
    <td colspan="5" align="left"><font size="2"><strong>TOTAL KEWAJIBAN DAN MODAL</strong></font><br /></td>
    <td align="right"><font size="2"><strong><?=FormatRupiah($modalusaha + $totalhutang) ?></strong></font></td>
    <td>&nbsp;</td>
</tr>
</table>
</td>

</tr>
</table>
<?php CloseDb() ?>

</td></tr></table>

<script language="javascript">window.print();</script>

</body>
</html>