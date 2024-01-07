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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/getheader.php');
require_once('../include/db_functions.php');

$nis = $_REQUEST['nis'];
$departemen = $_REQUEST['departemen'];
$idtahunbuku = $_REQUEST['idtahunbuku'];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Laporan Pembayaran Per Siswa]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr><td align="left" valign="top">

<?php getHeader($departemen) ?>

<center><font size="4"><strong>DATA PEMBAYARAN SISWA</strong></font><br /> </center><br /><br />

<?php
$sql = "SELECT s.nama
		  FROM siswa s
		 WHERE s.nis = '$nis' ";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namasiswa = $row[0];

$sql = "SELECT t.tahunbuku
		  FROM jbsfina.tahunbuku t
		 WHERE t.replid = '".$idtahunbuku."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tahunbuku = $row[0];		 
?>
<table border="0">
<tr>
	<td class="news_content1"><strong>Siswa </strong></td>
    <td class="news_content1">: 
      <?= $nis . " - " . $namasiswa?></td>
</tr>
<tr>
	<td class="news_content1"><strong>Tahun Buku </strong></td>
    <td class="news_content1">: 
      <?= $tahunbuku ?></td>
</tr>
</table>
<br />

<table border="1" style="border-collapse:collapse" cellpadding="2" cellspacing="2" width="100%">
<?php
$sql = "SELECT DISTINCT b.replid, b.besar, b.lunas, b.keterangan, d.nama
		  FROM jbsfina.besarjtt b, jbsfina.penerimaanjtt p, jbsfina.datapenerimaan d
		 WHERE p.idbesarjtt = b.replid
		   AND b.idpenerimaan = d.replid
		   AND b.nis='$nis'
		   AND b.info2='$idtahunbuku'
		 ORDER BY nama";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result))
{
	$idbesarjtt = $row['replid'];
	$namapenerimaan = $row['nama']; 
	$besar = $row['besar'];
	$lunas = $row['lunas'];
	$keterangan = $row['keterangan'];
	
	$sql = "SELECT SUM(jumlah), SUM(info1)
			  FROM jbsfina.penerimaanjtt
			 WHERE idbesarjtt = '".$idbesarjtt."'";
	$result2 = QueryDb($sql);
	$pembayaran = 0;
	$diskon = 0;
	if (mysqli_num_rows($result2))
	{
		$row2 = mysqli_fetch_row($result2);
		$pembayaran = $row2[0] + $row2[1];
		$diskon = $row2[1];
	};
	$sisa = $besar - $pembayaran;
	
	$sql = "SELECT jumlah, DATE_FORMAT(tanggal, '%d-%b-%Y') AS ftanggal
			  FROM jbsfina.penerimaanjtt
			 WHERE idbesarjtt='$idbesarjtt'
			 ORDER BY tanggal DESC, replid DESC LIMIT 1";
	$result2 = QueryDb($sql);
	$byrakhir = 0;
	$tglakhir = "";
	if (mysqli_num_rows($result2))
	{
		$row2 = mysqli_fetch_row($result2);
		$byrakhir = $row2[0];
		$tglakhir = $row2[1];
	};	?>
    <tr height="35">
        <td colspan="4" bgcolor="#99CC00"><font size="2"><strong><em><?=$namapenerimaan?></em></strong></font></td>
    </tr>    
    <tr height="25">
        <td width="20%" bgcolor="#CCFF66"><strong>Total Bayaran</strong> </td>
        <td width="15%" bgcolor="#FFFFFF" align="right"><?=FormatRupiah($besar) ?></td>
        <td width="22%" bgcolor="#CCFF66" align="center"><strong>Pembayaran Terakhir</strong></td>
        <td width="43%" bgcolor="#CCFF66" align="center"><strong>Keterangan</strong></td>
    </tr>
    <tr height="25">
        <td bgcolor="#CCFF66"><strong>Jumlah Besar Pembayaran</strong> </td>
        <td bgcolor="#FFFFFF" align="right"><?=FormatRupiah($pembayaran) ?></td>
        <td bgcolor="#FFFFFF" align="center" valign="top" rowspan="2"><?=FormatRupiah($byrakhir) . "<br><i>" . $tglakhir . "</i>" ?> </td>
        <td bgcolor="#FFFFFF" align="left" valign="top" rowspan="2"><?=$keterangan ?></td>
    </tr>
	<tr height="25">
        <td bgcolor="#CCFF66"><strong>Jumlah Diskon</strong> </td>
        <td bgcolor="#FFFFFF" align="right"><?=FormatRupiah($diskon) ?></td>
    </tr>
    <tr height="25">
        <td bgcolor="#CCFF66"><strong>Sisa Bayaran</strong> </td>
        <td bgcolor="#FFFFFF" align="right"><?=FormatRupiah($sisa) ?></td>
    </tr>
    <tr height="3">
        <td colspan="4" bgcolor="#E8E8E8">&nbsp;</td>
    </tr>
<?php 
} //while iuran wajib

$sql = "SELECT DISTINCT p.idpenerimaan, d.nama
		  FROM jbsfina.penerimaaniuran p, jbsfina.datapenerimaan d, jbsfina.jurnal j
		 WHERE p.idpenerimaan = d.replid
		   AND p.idjurnal = j.replid
		   AND j.idtahunbuku = '$idtahunbuku'
		   AND p.nis='$nis'
		 ORDER BY nama";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result))
{
	$idpenerimaan = $row['idpenerimaan'];
	$namapenerimaan = $row['nama'];
	
	$sql = "SELECT SUM(jumlah)
			  FROM jbsfina.penerimaaniuran
			 WHERE idpenerimaan='$idpenerimaan' AND nis='$nis'";
	$result2 = QueryDb($sql);
	$pembayaran = 0;
	if (mysqli_num_rows($result2))
	{
		$row2 = mysqli_fetch_row($result2);
		$pembayaran = $row2[0];
	};

	$sql = "SELECT jumlah, DATE_FORMAT(tanggal, '%d-%b-%Y') AS ftanggal
			  FROM jbsfina.penerimaaniuran
			 WHERE idpenerimaan='$idpenerimaan' AND nis='$nis'
			 ORDER BY tanggal DESC LIMIT 1";
	$result2 = QueryDb($sql);
	$byrakhir = 0;
	$tglakhir = "";
	if (mysqli_num_rows($result2)) {
		$row2 = mysqli_fetch_row($result2);
		$byrakhir = $row2[0];
		$tglakhir = $row2[1];
	};	
?>
 	<tr height="35">
        <td colspan="4" bgcolor="#99CC00"><font size="2"><strong><em><?=$namapenerimaan?></em></strong></font></td>
    </tr>  
   	<tr height="25">
        <td width="22%" bgcolor="#CCFF66" align="center"><strong>Total Pembayaran</strong> </td>
        <td width="22%" bgcolor="#CCFF66" align="center"><strong>Pembayaran Terakhir</strong></td>
        <td width="50%" colspan="2" bgcolor="#CCFF66" align="center"><strong>Keterangan</strong></td>
    </tr>
    <tr height="25">
        <td bgcolor="#FFFFFF" align="center"><?=FormatRupiah($pembayaran) ?></td>
        <td bgcolor="#FFFFFF" align="center"><?=FormatRupiah($byrakhir) . "<br><i>" . $tglakhir . "</i>" ?></td>
        <td colspan="2" bgcolor="#FFFFFF" align="left">&nbsp;</td>
    </tr>
    <tr height="3">
        <td colspan="4" bgcolor="#E8E8E8">&nbsp;</td>
    </tr>
<?php
} //while iuran sukarela
?>

</table>

<?php
CloseDb();
?>

</table>

</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>