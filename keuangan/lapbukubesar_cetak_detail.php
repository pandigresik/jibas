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

$kategori = "";
if (isset($_REQUEST['kategori']))
	$kategori = $_REQUEST['kategori'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];

$koderek = "";
if (isset($_REQUEST['koderek']))
	$koderek = $_REQUEST['koderek'];	
$urut = "j.tanggal";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Detail Buku Besar]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>
<?php
	OpenDb(); 
?>

<center><font size="4"><strong>BUKU BESAR</strong></font><br /> </center><br /><br />

<table border="0">
<tr>
	<td width="90"><strong>Departemen </strong></td>
    <td><strong>: <?=$departemen ?></strong></td>
</tr>
<tr>
	<td><strong>Tanggal </strong></td>
    <td><strong>: <?=LongDateFormat($tanggal1) ?> s/d <?=LongDateFormat($tanggal2) ?></strong></td>
</tr>
<tr>
	<td><strong>Rekening </strong></td>
    <td><strong>: <?=$koderek. " - ".GetValue("rekakun", "nama", "kode='$koderek'") ?></strong></td>
</tr>
</table>
<br />
<table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000"s>
<tr height="30">
	<td class="header" width="4%" align="center">No</td>
    <td class="header" width="13%" align="center">No. Jurnal/Tgl</td>
    <td class="header" width="9%" align="center">Petugas</td>
    <td class="header" width="*" align="center">Transaksi</td>
    <td class="header" width="12%" align="center">Debet</td>
    <td class="header" width="12%" align="center">Kredit</td>
</tr>
<?php
$sql = "SELECT date_format(j.tanggal, '%d-%b-%Y') AS tanggal, j.petugas, j.transaksi, j.keterangan, j.nokas, jd.debet, jd.kredit FROM jurnal j, jurnaldetail jd WHERE j.replid = jd.idjurnal AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND jd.koderek = '$koderek' ORDER BY $urut $urutan, j.petugas";
$result = QueryDb($sql);
$cnt = 0;
$totaldebet = 0;
$totalkredit = 0;
while($row = mysqli_fetch_array($result)) {
	$totaldebet += $row['debet'];
	$totalkredit += $row['kredit'];
?>
<tr height="25">
	<td valign="top" align="center"><?=++$cnt ?></td>
    <td valign="top" align="center"><strong><?=$row['nokas'] ?></strong><br /><em><?=$row['tanggal'] ?></em></td>
    <td valign="top" align="left"><?=$row['petugas'] ?></td>
    <td valign="top" align="left"><?=$row['transaksi'] ?><br />
    <?php if ($row['keterangan'] <> "") { ?>
    <strong>Keterangan: </strong><?=$row['keterangan'] ?>
    <?php } ?>
    </td>
    <td valign="top" align="right"><?=FormatRupiah($row['debet']) ?></td>
    <td valign="top" align="right"><?=FormatRupiah($row['kredit']) ?></td>
</tr>
<?php
}
CloseDb();
?>
<tr height="30">
	<td colspan="4" align="center" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totaldebet) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalkredit) ?></strong></font></td>
</tr>
</table>

</td></tr></table>

<script language="javascript">window.print();</script>

</body>
</html>