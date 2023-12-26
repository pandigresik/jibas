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
require_once('library/jurnal.php');
require_once('library/repairdatajtt.php');

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

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "nama";	
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
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Pembayaran Siswa Per Kelas</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">

function refresh() 
{	
	document.location.href = "lapbayarsiswa_kelas_jtt.php?idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&lunas=<?=$statuslunas ?>&idtingkat=<?=$idtingkat?>&departemen=<?=$departemen?>";	
}

function cetak() {
	var total = document.getElementById("tes").value;
	
	var addr = "lapbayarsiswa_kelas_jtt_cetak.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&lunas=<?=$statuslunas ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'CetakLapPembayaranJtt','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
	var total = document.getElementById("tes").value;
	
	var addr = "lapbayarsiswa_kelas_jtt_excel.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&lunas=<?=$statuslunas ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'CetakLapPembayaranJttExcel','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan)
{		
	var varbaris = document.getElementById("varbaris").value;	
	if (urutan == "ASC")
		urutan = "DESC";
	else 
		urutan="ASC";
		
	document.location.href = "lapbayarsiswa_kelas_jtt.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&lunas=<?=$statuslunas ?>&idtingkat=<?=$idtingkat?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function change_page(page) {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarsiswa_kelas_jtt.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&lunas=<?=$statuslunas ?>&idtingkat=<?=$idtingkat?>&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() 
{
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarsiswa_kelas_jtt.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&lunas=<?=$statuslunas ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&page="+hal+"&hal="+hal;
}

function change_baris() {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarsiswa_kelas_jtt.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&lunas=<?=$statuslunas ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<?php

OpenDb();

//  -- get idtahunbuku --------------------------------------------------------------
$sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
$res = QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
	CloseDb();
	
	echo "<script>";
	echo "alert ('Belum ada Tahun buku yang Aktif di departemen ".$departemen.". Silakan isi/aktifkan Tahun Buku di menu Referensi!');";
	echo "</script>";
	
	exit();
}
$row = mysqli_fetch_row($res);
$idtahunbuku = $row[0];

// -- Dapatkan banyaknya pembayaran yang telah terjadi untuk pembayaran terpilih di kelas terpilih ----
if ($statuslunas == -1) 
{
	// status belum lunas
	if ($idtingkat == -1) 
	{		
		// semua tingkat & kelas
		$sql = "SELECT MAX(jumlah), COUNT(nis) 
		        FROM ((SELECT b.nis AS nis, COUNT(p.replid) AS jumlah 
						 FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k 
						WHERE b.info2 = '$idtahunbuku' AND b.idpenerimaan = $idpenerimaan AND b.nis = s.nis AND s.idangkatan = $idangkatan 
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
							WHERE b.info2 = '$idtahunbuku' AND b.idpenerimaan = $idpenerimaan AND b.nis = s.nis AND s.idangkatan = $idangkatan 
							  AND s.idkelas = k.replid AND k.idtingkat = $idtingkat GROUP BY s.nis) AS x)";
		} 
		else 
		{
			// tingkat & kelas terpilih
			$sql = "SELECT MAX(jumlah), COUNT(nis) 
			        FROM ((SELECT b.nis AS nis, COUNT(p.replid) AS jumlah 
					         FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s 
						    WHERE b.info2 = '$idtahunbuku' AND b.idpenerimaan = $idpenerimaan AND b.nis = s.nis AND s.idkelas = $idkelas 
								  AND s.idangkatan = $idangkatan GROUP BY s.nis) AS x)";
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
						 WHERE b.nis = s.nis AND s.idangkatan = $idangkatan AND b.idpenerimaan = $idpenerimaan 
						   AND b.lunas = $statuslunas AND s.idkelas = k.replid  GROUP BY s.nis) AS x)";					
	} 
	else 
	{
		if ($idkelas == -1) 
		{
			// semua kelas di tingkat terpilih						
			$sql = "SELECT MAX(jumlah), COUNT(nis) 
					  FROM ((SELECT b.nis AS nis, COUNT(p.replid) AS jumlah 
						 	  FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k 
							 WHERE b.nis = s.nis AND s.idangkatan = $idangkatan AND b.idpenerimaan = $idpenerimaan 
							   AND b.lunas = $statuslunas AND s.idkelas = k.replid AND k.idtingkat = $idtingkat GROUP BY s.nis) AS x)";					
		} 
		else 
		{
			// tingkat & kelas terpilih
			$sql = "SELECT MAX(jumlah), COUNT(nis) 
					  FROM ((SELECT b.nis AS nis, COUNT(p.replid) AS jumlah 
							  FROM penerimaanjtt p RIGHT JOIN  besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s 
							 WHERE b.nis = s.nis AND s.idangkatan = $idangkatan AND b.idpenerimaan = $idpenerimaan 
								AND b.lunas = $statuslunas AND s.idkelas = $idkelas GROUP BY s.nis) AS x)";												
		}
	}
}

// -- calculate table width ----------------------------------------
$row = FetchSingleRow($sql);
$max_n_cicilan = $row[0];
$ndata = $row[1];
$table_width = 810 + $max_n_cicilan * 90;

// -- Dapatkan namapenerimaan --------------------------------------
$sql = "SELECT d.nama, d.departemen FROM datapenerimaan d WHERE d.replid = $idpenerimaan";
$row = FetchSingleRow($sql);
$namapenerimaan = $row[0];
$departemen = $row[1];

if ($idkelas <> -1) 
{
	$sql = "SELECT kelas FROM jbsakad.kelas WHERE replid=$idkelas";
	$namakelas = FetchSingle($sql);
}

if ($idtingkat <> -1)
{
	$sql = "SELECT tingkat FROM jbsakad.tingkat WHERE replid=$idtingkat";
	$namatingkat = FetchSingle($sql);
} 
?>

<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
<?php
if ($max_n_cicilan > 0 || $ndata > 0)
{
	?>
    <input type="hidden" name="total" id="total" value="<?=$total?>"/>
    <table width="100%" border="0" align="center">
    <tr>
    	<td valign="bottom">
    <a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
    <a href="JavaScript:excel()"><img src="images/ico/excel.png" border="0" onMouseOver="showhint('Buka dengan Microsoft Excel!', this, event, '50px')"/>&nbsp;Excel</a>&nbsp;    	</td>
	</tr>
	</table>
	<br />
	<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width?>" align="left" bordercolor="#000000">
        <tr height="30" align="center" class="header">
        <td width="30">No</td>
        <td width="80" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nis','<?=$urutan?>')">N I S  <?=change_urut('nis',$urut,$urutan)?></td>
        <td width="140" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama  <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="75" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kelas','<?=$urutan?>')">Kls <?=change_urut('kelas',$urut,$urutan)?></td>
        <?php 	for($i = 0; $i < $max_n_cicilan; $i++)
			{ 
                $n = $i + 1; ?>
                <td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
        <?php  } ?>
        <td width="90" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('lunas','<?=$urutan?>')">Status  <?=change_urut('lunas',$urut,$urutan)?></td>
        <td width="125" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('besar','<?=$urutan?>')"><?=$namapenerimaan ?> <?=change_urut('besar',$urut,$urutan)?></td>
        <td width="125">Total Besar Pembayaran</td>
		<td width="125">Total Diskon</td>
        <td width="125">Total Tunggakan</td>
        <td class="header" width="200">Keterangan</td>
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
						 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan 
						   AND s.idangkatan = $idangkatan AND s.idkelas = k.replid AND k.idtingkat = t.replid"; 
		
		$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas 
		          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
					WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan 
					  AND s.idangkatan = $idangkatan AND s.idkelas = k.replid AND k.idtingkat = t.replid 
			   ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
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
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan 
							   AND s.idkelas = k.replid AND k.idtingkat = $idtingkat AND k.idtingkat = t.replid"; 
			
			$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas 
			          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
						WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan 
						  AND s.idkelas = k.replid AND k.idtingkat = $idtingkat AND k.idtingkat = t.replid 
			      ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
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
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idkelas = $idkelas 
							   AND s.idkelas = k.replid AND s.idangkatan = $idangkatan"; 
			
			$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, b.replid AS id, b.besar, b.keterangan, b.lunas 
			          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k 
						WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idkelas = $idkelas 
						  AND s.idkelas = k.replid AND s.idangkatan = $idangkatan 
				   ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
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
						 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan
						   AND s.idkelas = k.replid AND k.idtingkat = t.replid AND b.lunas = $statuslunas"; 
		
		$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas
		          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
				   WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan 
					  AND s.idkelas = k.replid AND k.idtingkat = t.replid AND b.lunas = $statuslunas 
				ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
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
							 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan 
							   AND s.idkelas = k.replid AND k.idtingkat = $idtingkat AND k.idtingkat = t.replid AND b.lunas = $statuslunas";
			
			$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas 
			          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
						WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idangkatan = $idangkatan 
						  AND s.idkelas = k.replid AND k.idtingkat = $idtingkat AND k.idtingkat = t.replid 
						  AND b.lunas = $statuslunas 
				   ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
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
						 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idkelas = $idkelas 
						   AND s.idkelas = k.replid AND b.lunas = $statuslunas AND s.idangkatan = $idangkatan"; 
									
			$sql = "SELECT DISTINCT b.nis, s.nama, k.kelas, b.replid AS id, b.besar, b.keterangan, b.lunas 
			          FROM penerimaanjtt p RIGHT JOIN besarjtt b ON p.idbesarjtt = b.replid, jbsakad.siswa s, jbsakad.kelas k
					 WHERE b.info2 = '$idtahunbuku' AND s.nis = b.nis AND b.idpenerimaan = $idpenerimaan AND s.idkelas = $idkelas 
					   AND s.idkelas = k.replid AND b.lunas = $statuslunas AND s.idangkatan = $idangkatan 
				  ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
		}
	}
}

$result_tot = QueryDb($sql_tot);
$total = ceil(mysqli_num_rows($result_tot) / (int)$varbaris);
$jumlah = mysqli_num_rows($result_tot);
$akhir = ceil($jumlah / 5) * 5;

if ($page == 0)
	$cnt = 0;
else 
	$cnt = (int)$page*(int)$varbaris;

$result = QueryDb($sql);
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
		$infojtt = "<font color=red><strong>Belum Lunas</strong></font>"; ?>
		
    <tr height="40">
        <td align="center"><?=++$cnt ?></td>
        <td align="center" ><?=$row['nis'] ?></td>
        <td><?=$row['nama'] ?></td>
        <td align="center"><?php if ($idkelas == -1) echo $row['tingkat']." - "; ?><?=$row['kelas'] ?></td>
		
<?php 	$sql = "SELECT COUNT(p.replid)
				FROM penerimaanjtt p, jurnal j 
		        WHERE p.idjurnal=j.replid AND j.idtahunbuku=$idtahunbuku AND p.idbesarjtt=$idbesarjtt";
		$nbayar = FetchSingle($sql);
		$nblank = $max_n_cicilan - $nbayar;
		
		$totalbayar = 0;
		$totaldiskon = 0;
		if ($nbayar > 0) 
		{
			$sql = "SELECT DATE_FORMAT(p.tanggal, '%d-%b-%y'), p.jumlah, p.info1 
			          FROM penerimaanjtt p, jurnal j 
						WHERE p.idjurnal = j.replid AND j.idtahunbuku = $idtahunbuku AND p.idbesarjtt = $idbesarjtt ORDER BY p.tanggal";
			$result2 = QueryDb($sql);
			while ($row2 = mysqli_fetch_row($result2))
			{
				$totalbayar = $totalbayar + $row2[1] + $row2[2];
				$totaldiskon = $totaldiskon + $row2[2];	?>
            <td>
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse " bordercolor="#000000">
                <tr height="20"><td align="center"><?=FormatRupiah($row2[1]) ?></td>
                </tr>
                <tr height="20"><td align="center"><?=$row2[0] ?></td></tr>
                </table>
            </td>
 <?php 	}
 		//echo  "totalbayar=".$totalbayar;
		$totalBayarAll += $totalbayar;
		$totalDiskonAll += $totaldiskon;
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

if ($total - 1 == $page)
{
	$totalBiayaAll = FetchSingle($sql_sum_biaya);
	
	$row = FetchSingleRow($sql_sum_bayar_diskon);
	$totalBayarAll = $row[0] + $row[1];
	$totalDiskonAll = $row[1];
?>    
    <tr height="40">
        <td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalBiayaAll) ?></strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalBayarAll) ?></strong></font></td>
		<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalDiskonAll) ?></strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalBiayaAll - $totalBayarAll) ?></strong></font></td>
        <td bgcolor="#999900">&nbsp;</td>
    </tr>
<?php } ?>
    <input type="hidden" name="tes" id="tes" value="<?=$total?>"/>
    </table>
	<script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
	<?php CloseDb() ?>
     <?php if ($page==0){ 
		$disback="style='display:none;'";
		$disnext="style=''";
		}
		if ($page<$total && $page>0){
		$disback="style=''";
		$disnext="style=''";
		}
		if ($page==$total-1 && $page>0){
		$disback="style=''";
		$disnext="style='display:none;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='display:none;'";
		$disnext="style='display:none;'";
		}
	?>
    </td>
</tr> 
<tr>
    <td>
    <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left" colspan="2">Halaman
		<input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
		<input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
	  	dari <?=$total?> halaman, <?=$ndata?> data
	     
 		</td>
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
<?php } else { ?>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="250">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
        <br />Tambah data pembayaran pada 
		<?php 	if ($idtingkat <> -1) {	
				if ($idkelas <> -1) 
                    echo  "kelas ".$namakelas; 
                else 
                        echo  "tingkat ".$namatingkat;
			} else {
				echo  "departemen ".$departemen;
			}	
		?> dan kategori <?=$namapenerimaan?> di menu Penerimaan Pembayaran pada bagian Penerimaan.
        
        </b></font>
	</td>
</tr>
</table>  

<?php } ?>
    </td>
</tr>
</table>
<?php
CloseDb();
?>
</form>
</body>
</html>