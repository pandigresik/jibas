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
require_once('library/jurnal.php');
require_once('library/repairdatajtt.php');

$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
	
if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];

if (isset($_REQUEST['idtingkat']))
	$idtingkat = (int)$_REQUEST['idtingkat'];
	
if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];
	
if (isset($_REQUEST['lunas']))
	$statuslunas = (int)$_REQUEST['lunas'];

OpenDb();	

$sql = "SELECT departemen FROM jbsakad.angkatan WHERE replid='$idangkatan'"; 	
$departemen = FetchSingle($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Pembayaran Iuran Wajib Siswa Per Kelas]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<?php
$sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
$idtahunbuku = FetchSingle($sql);

// Dapatkan banyaknya pembayaran yang telah terjadi untuk pembayaran terpilih di kelas terpilih
if ($statuslunas == -1) 
{
	// status belum lunas
	if ($idtingkat == -1) 
	{		
		// semua tingkat & kelas
		$sql = "SELECT MAX(jumlah), COUNT(nis) 
		        FROM ((SELECT b.nis AS nis, COUNT(p.replid) AS jumlah 
						 FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k 
						WHERE b.info2 = '$idtahunbuku' AND b.idpenerimaan = '$idpenerimaan' AND b.nis = s.nis AND s.idangkatan = '$idangkatan' 
						  AND s.idkelas = k.replid GROUP BY s.nis) AS x)";							
	} 
	else 
	{ 
		if ($idkelas == -1) 
		{			
			// semua kelas di tingkat terpilih
			$sql = "SELECT MAX(jumlah), COUNT(nis) 
			        FROM ((SELECT b.nis AS nis, COUNT(p.replid) AS jumlah 
							 FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k 
							WHERE b.info2 = '$idtahunbuku' AND b.idpenerimaan = '$idpenerimaan' AND b.nis = s.nis AND s.idangkatan = '$idangkatan' 
							  AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' GROUP BY s.nis) AS x)";
		} 
		else 
		{
			// tingkat & kelas terpilih
			$sql = "SELECT MAX(jumlah), COUNT(nis) 
			        FROM ((SELECT b.nis AS nis, COUNT(p.replid) AS jumlah 
					           FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s 
								 WHERE b.info2 = '$idtahunbuku' AND b.idpenerimaan = '$idpenerimaan' AND b.nis = s.nis AND s.idkelas = '$idkelas' 
								   AND s.idangkatan = '$idangkatan' GROUP BY s.nis) AS x)";
		}
	}
} 
else 
{
	if ($idtingkat == -1) 
	{
		// semua tingkat & kelas 
		$sql = "SELECT MAX(jumlah), COUNT(nis) 
		        FROM ((SELECT b.nis AS nis, COUNT(p.replid) AS jumlah 
							  FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k 
							 WHERE b.nis = s.nis AND s.idangkatan = '$idangkatan' AND b.idpenerimaan = '$idpenerimaan' 
							   AND b.lunas = '$statuslunas' AND s.idkelas = k.replid  GROUP BY s.nis) AS x)";					
	} 
	else 
	{
		if ($idkelas == -1) 
		{
			// semua kelas di tingkat terpilih						
			$sql = "SELECT MAX(jumlah), COUNT(nis) 
					  FROM ((SELECT b.nis AS nis, COUNT(p.replid) AS jumlah 
						 		  FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k 
								 WHERE b.nis = s.nis AND s.idangkatan = '$idangkatan' AND b.idpenerimaan = '$idpenerimaan' 
								   AND b.lunas = '$statuslunas' AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' GROUP BY s.nis) AS x)";					
		} 
		else 
		{
			// tingkat & kelas terpilih
			$sql = "SELECT MAX(jumlah), COUNT(nis) 
					  FROM ((SELECT b.nis AS nis, COUNT(p.replid) AS jumlah 
								  FROM penerimaanjtt p RIGHT JOIN  besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s 
								 WHERE b.nis = s.nis AND s.idangkatan = '$idangkatan' AND b.idpenerimaan = '$idpenerimaan' 
									AND b.lunas = '$statuslunas' AND s.idkelas = '$idkelas' GROUP BY s.nis) AS x)";												
		}
	}
}
$max_n_cicilan = FetchSingle($sql);
$table_width = 810 + $max_n_cicilan * 90;

//Dapatkan namapenerimaan
$sql = "SELECT nama FROM datapenerimaan WHERE replid='$idpenerimaan'";
$namapenerimaan = FetchSingle($sql);

$namatingkat = "(all)";
$namakelas = "(all)";
if ($idkelas <> -1) 
{
	$sql = "SELECT kelas FROM jbsakad.kelas WHERE replid='$idkelas'";
	$namakelas = FetchSingle($sql);
}

if ($idtingkat <> -1)
{
	$sql = "SELECT tingkat FROM jbsakad.tingkat WHERE replid='$idtingkat'";
	$namatingkat = FetchSingle($sql);
} 

$sql = "SELECT angkatan, departemen FROM jbsakad.angkatan WHERE replid='$idangkatan'";
$row = FetchSingleRow($sql);
$angkatan = $row[0];
$departemen = $row[1];
?>

<table border="0" cellpadding="10" cellpadding="5" width="<?=$table_width + 50 ?>" align="left">
<tr><td align="left" valign="top">

<?= getHeader($departemen) ?>

<center><font size="4"><strong>LAPORAN PEMBAYARAN IURAN WAJIB SISWA</strong></font><br /> </center><br /><br />
<br />

<table border="0">
<tr>
	<td><strong>Departemen:</strong></td>
   <td><?=$departemen?></td>
</tr>
<tr>
	<td><strong>Angkatan:</strong></td>
   <td><?=$angkatan?></td>
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

<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
<tr height="30">
	<td class="header" width="30" align="center">No</td>
    <td class="header" width="80" align="center">N I S</td>
    <td class="header" width="140" align="center">Nama</td>
    <td class="header" width="50" align="center">Kelas</td>
    <?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
			$n = $i + 1; ?>
    		<td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
    <?php  } ?>
    <td class="header" width="80" align="center">Status</td>
    <td class="header" width="125" align="center"><?=$namapenerimaan ?></td>
    <td class="header" width="125" align="center">Total Besar Pembayaran</td>
	<td class="header" width="125" align="center">Total Diskon</td>
    <td class="header" width="125" align="center">Total Tunggakan</td>
    <td class="header" width="200" align="center">Keterangan</td>
</tr>

<?php
if ($statuslunas == -1) 
{
	if ($idtingkat == -1) 
	{
		// semua tingkat & kelas
		$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya
						   FROM besarjtt b, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t
						  WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan 
						    AND s.idangkatan = $idangkatan AND s.idkelas = k.replid AND k.idtingkat = t.replid";
						   
		$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon
						  FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t
						 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan 
						   AND s.idangkatan = $idangkatan AND s.idkelas = k.replid AND k.idtingkat = t.replid";
						   
		$sql_tot = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas 
		              FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t
						 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' 
						   AND s.idangkatan = '$idangkatan' AND s.idkelas = k.replid AND k.idtingkat = t.replid"; 
		
		$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas 
		          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
					WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' 
					  AND s.idangkatan = '$idangkatan' AND s.idkelas = k.replid AND k.idtingkat = t.replid 
			   ORDER BY $urut $urutan";
	} 
	else 
	{
		if ($idkelas == -1) 
		{
			// semua kelas di tingkat terpilih
			$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya
							  FROM besarjtt b, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan 
							   AND s.idkelas = k.replid AND k.idtingkat = $idtingkat AND k.idtingkat = t.replid";
							   
			$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon
							  FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan 
							   AND s.idkelas = k.replid AND k.idtingkat = $idtingkat AND k.idtingkat = t.replid";
							   
			$sql_tot = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas 
			              FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' AND s.idangkatan = '$idangkatan' 
							   AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' AND k.idtingkat = t.replid"; 
			
			$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas 
			          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
						WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' AND s.idangkatan = '$idangkatan'
						  AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' AND k.idtingkat = t.replid 
			      ORDER BY $urut $urutan";
		} 
		else 
		{
			// tingkat & kelas terpilih
			$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya 
							  FROM besarjtt b, jbsakad.siswa s, jbsakad.kelas k
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idkelas = $idkelas 
							   AND s.idkelas = k.replid AND s.idangkatan = $idangkatan";
							   
			$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon 
									FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k
									WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idkelas = $idkelas 
									AND s.idkelas = k.replid AND s.idangkatan = $idangkatan";
									
			$sql_tot = "SELECT DISTINCT b.nis, s.nama, k.kelas, b.replid AS id, b.besar, b.keterangan, b.lunas 
			              FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' AND s.idkelas = '$idkelas' 
							   AND s.idkelas = k.replid AND s.idangkatan = '".$idangkatan."'"; 
			
			$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, b.replid AS id, b.besar, b.keterangan, b.lunas 
			          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k 
						WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' AND s.idkelas = '$idkelas' 
						  AND s.idkelas = k.replid AND s.idangkatan = '$idangkatan' 
				   ORDER BY $urut $urutan";
		}
	} 
} 
else 
{
	if ($idtingkat == -1) 
	{
		// semua tingkat & kelas
		$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya
						  FROM besarjtt b, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t
						 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan
						   AND s.idkelas = k.replid AND k.idtingkat = t.replid AND b.lunas = $statuslunas";
						   
		$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon 
						  FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t
						 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan
						   AND s.idkelas = k.replid AND k.idtingkat = t.replid AND b.lunas = $statuslunas";
						   
		$sql_tot = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas 
		              FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t
						 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' AND s.idangkatan = '$idangkatan'
						   AND s.idkelas = k.replid AND k.idtingkat = t.replid AND b.lunas = '".$statuslunas."'"; 
		
		$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas
		          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
				   WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' AND s.idangkatan = '$idangkatan' 
					  AND s.idkelas = k.replid AND k.idtingkat = t.replid AND b.lunas = '$statuslunas' 
				ORDER BY $urut $urutan"; 
	} 
	else 
	{
		if ($idkelas == -1) 
		{
			$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya
							  FROM besarjtt b, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan 
							   AND s.idkelas = k.replid AND k.idtingkat = $idtingkat AND k.idtingkat = t.replid AND b.lunas = $statuslunas";
							   
			$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon 
							  FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan 
							   AND s.idkelas = k.replid AND k.idtingkat = $idtingkat AND k.idtingkat = t.replid AND b.lunas = $statuslunas";
							   
			$sql_tot = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas 
			              FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' AND s.idangkatan = '$idangkatan' 
							   AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' AND k.idtingkat = t.replid AND b.lunas = '".$statuslunas."'";
			
			$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas 
			          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
						WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' AND s.idangkatan = '$idangkatan' 
						  AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' AND k.idtingkat = t.replid 
						  AND b.lunas = '$statuslunas' 
				   ORDER BY $urut $urutan"; 
		} 
		else 
		{
			$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya
							  FROM besarjtt b, jbsakad.siswa s, jbsakad.kelas k
						 	 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idkelas = $idkelas 
							  AND s.idkelas = k.replid AND b.lunas = $statuslunas AND s.idangkatan = $idangkatan";
							  
			$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon  
							  FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k
						 	 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idkelas = $idkelas 
							  AND s.idkelas = k.replid AND b.lunas = $statuslunas AND s.idangkatan = $idangkatan";
						   
			$sql_tot = "SELECT DISTINCT b.nis, s.nama, k.kelas, b.replid AS id, b.besar, b.keterangan, b.lunas 
			              FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' AND s.idkelas = '$idkelas' 
							   AND s.idkelas = k.replid AND b.lunas = '$statuslunas' AND s.idangkatan = '".$idangkatan."'"; 
									
			$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, b.replid AS id, b.besar, b.keterangan, b.lunas 
			          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k
						WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = '$idpenerimaan' AND s.idkelas = '$idkelas' 
						  AND s.idkelas = k.replid AND b.lunas = '$statuslunas' AND s.idangkatan = '$idangkatan' 
				   ORDER BY $urut $urutan"; 
		}
	}
}
		
OpenDb();
$result_tot = QueryDb($sql_tot);
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result))
{
	$idbesarjtt = $row['id'];
	$besarjtt = $row['besar'];
	$ketjtt = $row['keterangan'];
	$lunasjtt = $row['lunas'];
	if ($lunasjtt == 1)
		$infojtt = "<font color=blue><strong>Lunas</strong></font>";
	elseif ($lunasjtt == 2)
		$infojtt = "<font color=green><strong>Gratis</strong></font>";
	else	
		$infojtt = "<font color=red><strong>Belum Lunas</strong></font>";
?>
<tr height="40">
	<td align="center"><?=++$cnt ?></td>
    <td align="center"><?=$row['nis'] ?></td>
    <td><?=$row['nama'] ?></td>
    <td align="center"><?php if ($idkelas == -1) echo  $row['tingkat']." - "; ?><?=$row['kelas'] ?></td>
    <?php
	$sql = "SELECT count(*) FROM penerimaanjtt WHERE idbesarjtt = '".$idbesarjtt."'";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	$nbayar = $row2[0];
	$nblank = $max_n_cicilan - $nbayar;
	$totalbayar = 0;
	$totaldiskon = 0;
	if ($nbayar > 0)
	{
		$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah, info1 FROM penerimaanjtt WHERE idbesarjtt = '$idbesarjtt' ORDER BY tanggal";
		$result2 = QueryDb($sql);
		while ($row2 = mysqli_fetch_row($result2))
		{
			$totalbayar = $totalbayar + $row2[1] + $row2[2];
			$totaldiskon = $totaldiskon + $row2[2];
			?>
            <td>
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000">
                <tr height="20"><td align="center"><?=FormatRupiah($row2[1]) ?></td></tr>
                <tr height="20"><td align="center"><?=$row2[0] ?></td></tr>
                </table>
            </td>
 <?php 	}
	}	
	for ($i = 0; $i < $nblank; $i++) { ?>
	    <td>
            <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000"> 
            <tr height="20"><td align="center">&nbsp;</td></tr>
            <tr height="20"><td align="center">&nbsp;</td></tr>
            </table>
        </td>
    <?php }?>
    <td align="center"><?=$infojtt ?></td>
    <td align="right"><?=FormatRupiah($besarjtt) ?></td>
    <td align="right"><?=FormatRupiah($totalbayar) ?></td>
	<td align="right"><?=FormatRupiah($totaldiskon) ?></td>
    <td align="right"><?=FormatRupiah($besarjtt - $totalbayar) ?></td>
    <td align="right"><?=$ketjtt ?></td>
</tr>
<?php
}
?>
<tr height="40">
<?php
	$totalBiayaAll = FetchSingle($sql_sum_biaya);
	
	$row = FetchSingleRow($sql_sum_bayar_diskon);
	$totalBayarAll = $row[0] + $row[1];
	$totalDiskonAll = $row[1];
?>
	<td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
	<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalBiayaAll) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalBayarAll) ?></strong></font></td>
	<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalDiskonAll) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalBiayaAll - $totalBayarAll) ?></strong></font></td>
    <td bgcolor="#999900">&nbsp;</td>
</tr>
</table>
<?php
CloseDb()
?>   
	</td>
</tr>
    </table>
</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>