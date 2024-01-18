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

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];

$calon = "";
if (isset($_REQUEST['calon']))
	$calon = $_REQUEST['calon'];
	
$tgl1 = explode(' ',(string) $tanggal1);
$tgl2 = explode(' ',(string) $tanggal2);

if ($calon == "calon") 
	$judul = "Calon ";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Audit Perubahan Data Iuran Wajib <?=\JUDUL?>Siswa]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>LAPORAN AUDIT PERUBAHAN DATA IURAN WAJIB <?=strtoupper((string) $judul)?>SISWA</strong></font><br /> </center><br /><br />

<table border="0">
<tr>
	<td width="90"><strong>Departemen </strong></td>
    <td><strong>: <?=$departemen ?></strong></td>
</tr>
<tr>
	<td width="90"><strong>Tanggal </strong></td>
    <td><strong>: <?=LongDateFormat($tgl1[0]) . " s/d 	" . LongDateFormat($tgl2[0]) ?></strong></td>
</tr>
</table>
<br />
<table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
<tr height="30" align = "center">
	<td class="header" width="4%">No</td>
    <td class="header" width="15%">Status Data</td>
    <td class="header" width="10%">Tanggal</td>
    <td class="header" width="15%">Jumlah</td>
	<td class="header" width="15%">Diskon</td>
    <td class="header" width="*">Keterangan</td>
    <td class="header" width="15%">Petugas</td>
</tr>
<?php
OpenDb();
$sql = "SELECT DISTINCT ai.petugas as petugasubah, j.transaksi, date_format(ai.tanggal, '%d-%b-%Y %H:%i:%s') as tanggalubah, ap.replid AS id, 
					ap.idaudit, ap.statusdata, j.nokas, date_format(ap.tanggal, '%d-%b-%Y') AS tanggal, ap.petugas, ap.keterangan, ap.jumlah, 
					ap.petugas, ai.alasan, ap.info1 AS diskon 
			 FROM auditpenerimaanjtt$calon ap, auditinfo ai, jurnal j 
			WHERE j.replid = ap.idjurnal AND j.idtahunbuku = '$idtahunbuku' AND ap.idaudit = ai.replid AND ai.departemen = '$departemen' 
			  AND ai.sumber='penerimaanjtt$calon' AND ai.tanggal BETWEEN '$tanggal1 00:00:00' AND '$tanggal2 23:59:59' 
		ORDER BY ap.idaudit DESC, ai.tanggal DESC, ap.statusdata ASC";
$result = QueryDb($sql);
$cnt = 0;
$no = 0;
while ($row = mysqli_fetch_array($result)) {
	$statusdata = "Data Lama";
	$bgcolor = "#FFFFFF";
	if ($row['statusdata'] == 1) {
		$statusdata = "Data Perubahan";
		$bgcolor = "#FFFFB7";
	}
		
	if ($cnt % 2 == 0) { ?>
	<tr>
		<td rowspan="4" align="center" bgcolor="#ededed"><strong><?=++$no ?></strong></td>
        <td colspan="7" align="left" style="background-color: #3994c6; color: #ffffff;"><em><strong>Perubahan dilakukan oleh <?=$row['petugasubah'] . " tanggal " . $row['tanggalubah'] ?></strong></em></td>
	</tr>
    <tr>
    	<td colspan="7" style="background-color: #e5fdff;"><strong>No. Jurnal :</strong> <?=$row['nokas'] ?>
         &nbsp;&nbsp;<strong>Alasan : </strong><?=$row['alasan'];?>
        <br /><strong>Transaksi :</strong> <?=$row['transaksi'] ?></td>
    </tr>
<?php  } ?>

	<tr>
		<td><?=$statusdata ?></td>
	    <td align="center"><?=$row['tanggal'] ?></td>
	    <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
		<td align="right"><?=FormatRupiah($row['diskon']) ?></td>
	    <td><?=$row['keterangan'] ?></td>
	    <td align="center"><?=$row['petugas']; ?></td>
	</tr>
<?php
	$cnt++;
}
CloseDb();
?>
</table>

</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>