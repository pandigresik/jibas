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
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/getheader.php'); 

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];

$idtahunbuku = "";
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Rekapitulasi Pengeluaran]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>
<center><font size="4"><strong>LAPORAN REKAPITULASI PENGELUARAN</strong></font><br /> </center><br /><br />

<table border="0">
<tr>
	<td width="90"><strong>Departemen </strong></td>
    <td><strong>: <?=$departemen ?></strong></td>
</tr>
<tr>
	<td><strong>Tanggal </strong></td>
    <td><strong>: <?=LongDateFormat($tanggal1) . " s/d 	" . LongDateFormat($tanggal2) ?></strong></td>
</tr>

</table>
<br />

<table id="table" class="tab" style="border-collapse:collapse" border="1" width="100%" bordercolor="#000000">
<tr height="30">
	<td width="10%" class="header" align="center">No</td>
    <td width="50%" class="header" align="center">Pengeluaran</td>
    <td width="*" class="header" align="center">Jumlah</td>
</tr>
<?php
OpenDb();

$sql = "SELECT d.replid AS id, d.nama, SUM(p.jumlah) AS jumlah FROM pengeluaran p, datapengeluaran d WHERE p.idpengeluaran = d.replid AND d.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY d.replid, d.nama ORDER BY d.nama";

OpenDb();
$result = QueryDb($sql);
$cnt = 0;
$total = 0;
while ($row = mysqli_fetch_array($result)) {
	$total += $row['jumlah'];
?>
<tr height="25">
	<td align="center"><?=++$cnt ?></td>
    <td align="left"><?=$row['nama'] ?></td>
    <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
</tr>
<?php
}
CloseDb();
?>
<tr height="30">
	<td bgcolor="#999900" colspan="2" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
    <td bgcolor="#999900" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font></td>
</tr>
</table>

</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>