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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Transaksi]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>LAPORAN TRANSAKSI</strong></font><br /> </center><br /><br />

<table border="0">
<tr>
	<td width="90"><strong>Departemen </strong></td>
    <td><strong>: <?=$departemen ?></strong></td>
</tr>
<tr>
	<td width="90"><strong>Tahun Buku </strong></td>
    <td><strong>: 
	<?php  OpenDb();
		$sql = "SELECT tahunbuku FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
	   	$result = QueryDb($sql);
	   	$row = mysqli_fetch_row($result);
	   	echo  $row[0];
    ?>
	</strong></td>
</tr>
<tr>
	<td><strong>Tanggal </strong></td>
    <td><strong>: <?=LongDateFormat($tanggal1) ?> s/d <?=LongDateFormat($tanggal2) ?></strong></td>
</tr>
</table>
<br />
<table class="tab" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="100%" align="left" bordercolor="#000000">
<tr height="30" align="center">
	<td class="header" width="4%">No</td>
    <td class="header" width="18%">No. Jurnal/Tanggal</td>
    <td class="header" width="8%">Petugas</td>
    <td class="header" width="*">Transaksi</td>
    <td class="header" width="15%">Debet</td>
    <td class="header" width="15%">Kredit</td>
</tr>
<?php
OpenDb();
$sql = "SELECT nokas, date_format(tanggal, '%d-%b-%Y') AS tanggal, petugas, transaksi, keterangan, debet, kredit 
          FROM transaksilog 
         WHERE departemen='$departemen' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND idtahunbuku = '$idtahunbuku'
         ORDER BY nokas DESC";
$result = QueryDb($sql);
$cnt = 0;
$totaldebet = 0;
$totalkredit = 0;
while($row = mysqli_fetch_array($result)) {
	$totaldebet += $row['debet'];
	$totalkredit += $row['kredit'];
?>
<tr height="25">
	<td align="center" valign="top"><?=++$cnt ?></td>
    <td align="center" valign="top"><strong><?=$row['nokas'] ?></strong><br /><?=$row['tanggal'] ?></td>
    <td align="center" valign="top"><?=$row['petugas'] ?></td>
    <td align="left" valign="top"><?=$row['transaksi'] ?>
    <?php if ($row['keterangan'] <> "") { ?>
    <br /><strong>Keterangan: </strong><?=$row['keterangan'] ?>
    <?php } ?>
    </td>
    <td align="right" valign="top"><?=FormatRupiah($row['debet']) ?></td>
    <td align="right" valign="top"><?=FormatRupiah($row['kredit']) ?></td>
</tr>
<?php
}
CloseDb();
?>
<tr height="30">
	<td colspan="4" align="center" bgcolor="#999900">
    <font color="#FFFFFF"><strong>T O T A L</strong></font>
    </td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totaldebet) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalkredit) ?></strong></font></td>
</tr>
</table>

</td></tr></table>

<script language="javascript">window.print();</script>

</body>
</html>