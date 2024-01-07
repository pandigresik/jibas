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

$urut = "nama";
$urutan = "ASC";
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

$idpenerimaan = 0;
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];

OpenDb();
$sql = "SELECT departemen FROM $db_name_fina.datapenerimaan  WHERE replid='$idpenerimaan'";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$departemen = $row[0];
CloseDb();
	
$kelompok = -1;
if (isset($_REQUEST['kelompok']))
	$kelompok = (int)$_REQUEST['kelompok'];
	
if (isset($_REQUEST['lunas']))
	$statuslunas = (int)$_REQUEST['lunas'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Pembayaran Iuran Wajib Siswa Per Kelas]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
</head>

<body>
<?php
OpenDb();

$sql = "SELECT replid FROM jbsfina.tahunbuku WHERE departemen='$departemen' AND aktif=1";
$idtahunbuku = FetchSingle($sql);

// Dapatkan banyaknya pembayaran yang telah terjadi untuk pembayaran terpilih di kelas terpilih
if ($statuslunas == -1)
	if ($kelompok == -1)
		$sql = "SELECT MAX(jumlah) 
				  FROM ((SELECT c.replid, count(p.replid) as jumlah 
						   FROM jbsfina.penerimaanjttcalon p, jbsfina.besarjttcalon b, jbsakad.calonsiswa c 
						  WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku'
							AND b.idpenerimaan = '$idpenerimaan' GROUP BY c.replid) AS x);";
	else
		$sql = "SELECT MAX(jumlah) 
				  FROM ((SELECT c.replid, count(p.replid) as jumlah 
						   FROM jbsfina.penerimaanjttcalon p, jbsfina.besarjttcalon b, jbsakad.calonsiswa c 
						  WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku'
							AND c.idkelompok = '$kelompok' AND b.idpenerimaan = '$idpenerimaan' GROUP BY c.replid) AS x);";
else
	if ($kelompok == -1)
		$sql = "SELECT MAX(jumlah) 
				  FROM ((SELECT c.replid, count(p.replid) as jumlah 
						   FROM jbsfina.penerimaanjttcalon p, jbsfina.besarjttcalon b, jbsakad.calonsiswa c 
						  WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku' 
							AND b.idpenerimaan = '$idpenerimaan' AND b.lunas = '$statuslunas' GROUP BY c.replid) AS x);";
	else
		$sql = "SELECT MAX(jumlah) 
				  FROM ((SELECT c.replid, count(p.replid) as jumlah 
						   FROM jbsfina.penerimaanjttcalon p, jbsfina.besarjttcalon b, jbsakad.calonsiswa c 
						  WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku' 
							AND c.idkelompok = '$kelompok' AND b.idpenerimaan = '$idpenerimaan' AND b.lunas = '$statuslunas' GROUP BY c.replid) AS x);";

$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_cicilan = $row[0];
$table_width = 810 + $max_n_cicilan * 90;

//Dapatkan namapenerimaan
$sql = "SELECT nama FROM $db_name_fina.datapenerimaan WHERE replid='$idpenerimaan'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];

?>
<table border="0" cellpadding="10" cellspacing="5" width="<?=$table_width + 50 ?>" align="left">
<tr><td align="left" valign="top">

<?php getHeader($departemen) ?>

<center><font size="4"><strong>LAPORAN PEMBAYARAN IURAN WAJIB CALON SISWA</strong></font><br /> </center><br /><br />

<br />


<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
<tr height="30" align="center">
	<td class="header" width="30" align="center">No</td>
    <td class="header" width="80" align="center">No. Reg</td>
    <td class="header" width="140">Nama</td>
    <td class="header" width="50" align="center">Kel</td>
    <?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
			$n = $i + 1; ?>
    		<td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
    <?php  } ?>
    <td class="header" width="80" align="center">Status</td>
    <td class="header" width="125" align="center"><?=$namapenerimaan ?></td>
    <td class="header" width="125" align="center">Total Pembayaran</td>
    <td class="header" width="125" align="center">Total Tunggakan</td>
    <td class="header" width="200" align="center">Keterangan</td>
</tr>

<?php
OpenDb();
if ($statuslunas == -1) 
{
	if ($kelompok == -1) 
	{
		$sql_tot = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
					  FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsfina.besarjttcalon b 
					 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' AND c.idkelompok = k.replid 
					 ORDER BY c.nama";
		
		$sql = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
				  FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsfina.besarjttcalon b 
				 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
				   AND c.idkelompok = k.replid ORDER BY $urut $urutan"; 
	} 
	else 
	{
		$sql_tot = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
					  FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsfina.besarjttcalon b 
					 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
					   AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid ORDER BY c.nama";
		
		$sql = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
				  FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsfina.besarjttcalon b 
				 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' 
				   AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid ORDER BY $urut $urutan"; 
	}
} 
else 
{
	if ($kelompok == -1) 
	{
		$sql_tot = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
					  FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsfina.besarjttcalon b 
					 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
					   AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
	
		$sql = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
				  FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsfina.besarjttcalon b
				 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' 
				   AND c.idkelompok = k.replid AND b.lunas = '$statuslunas' ORDER BY $urut $urutan"; 
	} 
	else 
	{
		$sql_tot = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas
					  FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsfina.besarjttcalon b
					 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
					   AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'"; 
		
		$sql = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
				  FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsfina.besarjttcalon b 
				 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
				   AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid AND b.lunas = '$statuslunas' ORDER BY $urut $urutan"; 
	}
}

$result = QueryDb($sql);
if ($page==0)
	$cnt = 0;
else 
	$cnt = (int)$page*(int)$varbaris;
$totalbiayaall = 0;
$totalbayarall = 0;

while ($row = mysqli_fetch_array($result)) {
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
	$totalbiayaall += $besarjtt;
		
?>
<tr height="40">
	<td align="center"><?=++$cnt ?></td>
    <td align="center"><?=$row['nopendaftaran'] ?></td>
    <td><?=$row['nama'] ?></td>
    <td align="center"><?=$row['kelompok'] ?></td>
    <?php
	$sql = "SELECT count(*) FROM $db_name_fina.penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	$nbayar = $row2[0];
	$nblank = $max_n_cicilan - $nbayar;
	$totalbayar = 0;
	
	if ($nbayar > 0) {
		$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah FROM $db_name_fina.penerimaanjttcalon WHERE idbesarjttcalon = '$idbesarjtt' ORDER BY tanggal";
		$result2 = QueryDb($sql);
		while ($row2 = mysqli_fetch_row($result2)) {
			$totalbayar = $totalbayar + $row2[1]; ?>
            <td>
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000">
                <tr height="20"><td align="center"><?=FormatRupiah($row2[1]) ?></td></tr>
                <tr height="20"><td align="center"><?=$row2[0] ?></td></tr>
                </table>
            </td>
 <?php 	}
 		$totalbayarall += $totalbayar;
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
    <td align="right"><?=FormatRupiah($besarjtt - $totalbayar) ?></td>
    <td><?=$ketjtt ?></td>
</tr>
<?php
}
?>
<tr height="40">
	<td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
	<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbiayaall) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbayarall) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbiayaall - $totalbayarall) ?></strong></font></td>
    <td bgcolor="#999900">&nbsp;</td>
</tr>
</table>
<?php CloseDb() ?>

</td>
</tr>
    </table>
</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>