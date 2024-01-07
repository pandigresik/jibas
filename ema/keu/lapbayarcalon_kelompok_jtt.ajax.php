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
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
	
$idpenerimaan = 0;
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
    
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];    
	
$kelompok = -1;
if (isset($_REQUEST['kelompok']))
	$kelompok = (int)$_REQUEST['kelompok'];
	
$statuslunas = -1;
if (isset($_REQUEST['lunas']))
	$statuslunas = (int)$_REQUEST['lunas'];

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
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Pembayaran Calon Siswa</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh() {	
	document.location.href = "lapbayarcalon_kelompok_jtt.php?idtahunbuku=<?=$idtahunbuku?>&kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&statuslunas=<?=$statuslunas ?>";	
}

function cetak() {
	var total = document.getElementById("tes").value;
	
	var addr = "lapbayarcalon_kelompok_jtt_cetak.php?idtahunbuku=<?=$idtahunbuku?>&kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&statuslunas=<?=$statuslunas ?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'CetakLapPembayaranJttCalonSiswa','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
	var total = document.getElementById("tes").value;
	var addr = "lapbayarcalon_kelompok_jtt_excel.php?idtahunbuku=<?=$idtahunbuku?>&kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&statuslunas=<?=$statuslunas ?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'ExcelLapPembayaranJttCalonSiswa','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {	
	var varbaris=document.getElementById("varbaris").value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "lapbayarcalon_kelompok_jtt.php?idtahunbuku=<?=$idtahunbuku?>&kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&statuslunas=<?=$statuslunas ?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function change_page(page) {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarcalon_kelompok_jtt.php?idtahunbuku=<?=$idtahunbuku?>&kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&statuslunas=<?=$statuslunas ?>&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarcalon_kelompok_jtt.php?idtahunbuku=<?=$idtahunbuku?>&kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&statuslunas=<?=$statuslunas ?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&page="+hal+"&hal="+hal;
}

function change_baris() {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarcalon_kelompok_jtt.php?idtahunbuku=<?=$idtahunbuku?>&kelompok=<?=$kelompok ?>&idpenerimaan=<?=$idpenerimaan ?>&statuslunas=<?=$statuslunas ?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<?php
OpenDb();

QueryDb("USE jbsfina");

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

// Dapatkan banyaknya pembayaran yang telah terjadi untuk pembayaran terpilih di kelas terpilih
if ($statuslunas == -1)
	if ($kelompok == -1)
		$sql = "SELECT MAX(jumlah) 
		          FROM ((SELECT c.replid, count(p.replid) as jumlah 
								 FROM penerimaanjttcalon p, besarjttcalon b, jbsakad.calonsiswa c 
								WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku'
								  AND b.idpenerimaan = '$idpenerimaan' GROUP BY c.replid) AS x);";
	else
		$sql = "SELECT MAX(jumlah) 
		          FROM ((SELECT c.replid, count(p.replid) as jumlah 
								 FROM penerimaanjttcalon p, besarjttcalon b, jbsakad.calonsiswa c 
							   WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku'
								  AND c.idkelompok = '$kelompok' AND b.idpenerimaan = '$idpenerimaan' GROUP BY c.replid) AS x);";
else
	if ($kelompok == -1)
		$sql = "SELECT MAX(jumlah) 
		          FROM ((SELECT c.replid, count(p.replid) as jumlah 
							    FROM penerimaanjttcalon p, besarjttcalon b, jbsakad.calonsiswa c 
								WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku' 
								 AND b.idpenerimaan = '$idpenerimaan' AND b.lunas = '$statuslunas' GROUP BY c.replid) AS x);";
	else
		$sql = "SELECT MAX(jumlah) 
		          FROM ((SELECT c.replid, count(p.replid) as jumlah 
								 FROM penerimaanjttcalon p, besarjttcalon b, jbsakad.calonsiswa c 
								WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku' 
								  AND c.idkelompok = '$kelompok' AND b.idpenerimaan = '$idpenerimaan' AND b.lunas = '$statuslunas' GROUP BY c.replid) AS x);";

$max_n_cicilan = FetchSingle($sql);
$table_width = 810 + $max_n_cicilan * 90;

$sql = "SELECT d.nama, d.departemen FROM datapenerimaan d WHERE d.replid='$idpenerimaan'";
$row = FetchSingleRow($sql);
$namapenerimaan = $row[0];
$departemen = $row[1];

if ($kelompok != -1)
{
	$sql = "SELECT kelompok FROM jbsakad.kelompokcalonsiswa WHERE replid='$kelompok'";
	$namakelompok = FetchSingle($sql);
}
?>

<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
<?php if ($max_n_cicilan > 0 || $statuslunas == 2) { ?>
    
	<br />
	<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width?>" align="left" bordercolor="#000000">
        <tr height="30" align="center" class="header">
        <td width="30">No</td>
        <td width="90" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nopendaftaran','<?=$urutan?>')">No. Reg <?=change_urut('nopendaftaran',$urut,$urutan)?></td>
        <td width="140" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="65" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kelompok','<?=$urutan?>')">Kel <?=change_urut('kelompok',$urut,$urutan)?></td>
        <?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
                $n = $i + 1; ?>
                <td width="120" ><?="Bayaran-$n" ?></td>	
        <?php  } ?>
        <td width="95" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('lunas','<?=$urutan?>')">Status <?=change_urut('lunas',$urut,$urutan)?></td>
        <td width="125" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('besar','<?=$urutan?>')"><?=$namapenerimaan ?> <?=change_urut('besar',$urut,$urutan)?></td>
        <td width="125">Total Besar Pembayaran</td>
		<td width="125">Total Diskon</td>
        <td width="125">Total Tunggakan</td>
        <td width="200">Keterangan</td>
	</tr>

<?php
if ($statuslunas == -1) 
{
	if ($kelompok == -1) 
	{
		$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya
						    FROM besarjttcalon b, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k 
						   WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' AND c.idkelompok = k.replid";
								  
		$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon 
								   FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k 
								  WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' AND c.idkelompok = k.replid";
					 
		$sql_tot = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
		              FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
					 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' AND c.idkelompok = k.replid 
				 	 ORDER BY c.nama";
		
		$sql = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
		          FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
				 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
				   AND c.idkelompok = k.replid ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	} 
	else 
	{
		$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya
							 FROM besarjttcalon b, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
							WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
							 AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid ORDER BY c.nama";
								 
		$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon  
								 FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
								WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
								 AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid ORDER BY c.nama";
					   
		$sql_tot = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
		              FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
				 	 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
					   AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid ORDER BY c.nama";
		
		$sql = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
		          FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
				 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' 
				   AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	}
} 
else 
{
	if ($kelompok == -1) 
	{
		$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya  
							FROM besarjttcalon b, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
							WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
							AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
									
		$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon  
									FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
									WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
									AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
						
		$sql_tot = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
					  FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
					 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
					   AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
	
		$sql = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
					 FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
					WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' 
					  AND c.idkelompok = k.replid AND b.lunas = '$statuslunas' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	} 
	else 
	{
		$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBayar  
							FROM besarjttcalon b, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
						   WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
							 AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
								  
		$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon
								FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
								 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
								  AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
						   
		$sql_tot = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas
		              FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
						 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
						   AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'"; 
		
		$sql = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
		          FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
					WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
					  AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid AND b.lunas = '$statuslunas' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	}
}

$result_tot = QueryDb($sql_tot);
$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
$jumlah = mysqli_num_rows($result_tot);
$akhir = ceil($jumlah/5)*5;

if ($page==0)
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
		$infojtt = "<font color=red><strong>Belum Lunas</strong></font>";
?>
    <tr height="40">
        <td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nopendaftaran'] ?></td>
        <td><?=$row['nama'] ?></td>
        <td align="center"><?=$row['kelompok'] ?></td>
    <?php
	$sql = "SELECT count(*) FROM penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	$nbayar = $row2[0];
	$nblank = $max_n_cicilan - $nbayar;
	
	$totalbayar = 0;
	$totaldiskon = 0;
	if ($nbayar > 0)
	{
		$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah, info1 FROM penerimaanjttcalon WHERE idbesarjttcalon = '$idbesarjtt 'ORDER BY tanggal";
		$result2 = QueryDb($sql);
		while ($row2 = mysqli_fetch_row($result2))
		{
			$totalbayar += $row2[1] + $row2[2];
			$totaldiskon += $row2[2];
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
            <tr height="20"><td align="center">&nbsp;</td></tr>
            </table>
        </td>
    <?php }?>
        <td align="center"><?=$infojtt ?></td>
        <td align="right"><?=FormatRupiah($besarjtt) ?></td>
        <td align="right"><?=FormatRupiah($totalbayar) ?></td>
		<td align="right"><?=FormatRupiah($totaldiskon) ?></td>
        <td align="right"><?=FormatRupiah($besarjtt - $totalbayar)?></td>
        <td><?=$ketjtt ?></td>
    </tr>
<?php
}
?>
    <input type="hidden" name="tes" id="tes" value="<?=$total?>"/>
<?php
if ($total-1 == $page)
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
    <?php 
	} 
	?>
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
    
<?php } else { ?>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
        <br />Tambah data pembayaran pada departemen <?=$departemen?> <?php if ($namakelompok) echo  ", kelompok ".$namakelompok ?> dan kategori <?=$namapenerimaan?> di menu Penerimaan Pembayaran pada bagian Penerimaan.
        
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