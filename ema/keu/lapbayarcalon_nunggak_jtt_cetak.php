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

$urut=$_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];


if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];

if (isset($_REQUEST['kelompok']))
	$kelompok = (int)$_REQUEST['kelompok'];
	
if (isset($_REQUEST['telat']))
	$telat = (int)$_REQUEST['telat'];
	
$tanggal = "";
if (isset($_REQUEST['tanggal'])) 
	$tanggal = $_REQUEST['tanggal'];

$departemen = "";
if (isset($_REQUEST['departemen'])) 
	$departemen = $_REQUEST['departemen'];
OpenDb();

$tgl = MySqlDateFormat($tanggal);	
$sql = "SELECT replid FROM jbsfina.tahunbuku WHERE departemen='$departemen' AND aktif=1";
$idtahunbuku = FetchSingle($sql); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Laporan Tunggakan Iuran Wajib Calon Siswa Per Kelompok]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
</head>

<body>
<?php
OpenDb();
/*
if ($kelompok == -1)
	$sql = "SELECT idbesarjttcalon, datediff('$tgl', max(tanggal)) as x FROM $db_name_fina.penerimaanjttcalon p , $db_name_fina.besarjttcalon b, calonsiswa c, prosespenerimaansiswa r WHERE p.idbesarjttcalon = b.replid AND b.lunas = 0 AND c.replid = b.idcalon AND b.idpenerimaan = $idpenerimaan AND c.idproses = r.replid AND r.aktif = 1 GROUP BY idbesarjttcalon HAVING x >= $telat UNION SELECT b.replid, '-' FROM $db_name_fina.besarjttcalon b, calonsiswa c, prosespenerimaansiswa r WHERE b.replid NOT IN (SELECT idbesarjttcalon FROM $db_name_fina.penerimaanjttcalon p) AND b.lunas = 0 AND c.replid = b.idcalon AND b.idpenerimaan = $idpenerimaan AND c.idproses = r.replid AND r.aktif = 1";	
else
	$sql = "SELECT idbesarjttcalon, datediff('$tgl', max(tanggal)) as x FROM $db_name_fina.penerimaanjttcalon p , $db_name_fina.besarjttcalon b, calonsiswa c WHERE p.idbesarjttcalon = b.replid AND b.lunas = 0 AND c.replid = b.idcalon AND c.idkelompok = $kelompok AND b.idpenerimaan = $idpenerimaan GROUP BY idbesarjttcalon HAVING x >= $telat UNION SELECT b.replid, '-' FROM $db_name_fina.besarjttcalon b, calonsiswa c, prosespenerimaansiswa r WHERE b.replid NOT IN (SELECT idbesarjttcalon FROM $db_name_fina.penerimaanjttcalon p) AND b.lunas = 0 AND c.replid = b.idcalon AND b.idpenerimaan = $idpenerimaan AND c.idkelompok = $kelompok";	
*/
//echo "$sql<br>";
if ($kelompok == -1) 
{
	$sql = "SELECT idbesarjttcalon, datediff('$tgl', max(tanggal)) as x 
			  FROM jbsfina.penerimaanjttcalon p , jbsfina.besarjttcalon b, jbsakad.calonsiswa c, jbsakad.prosespenerimaansiswa r 
			   WHERE p.idbesarjttcalon = b.replid AND b.lunas = 0 AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
				  AND c.replid = b.idcalon AND c.idproses = r.replid AND r.aktif = 1 
		   GROUP BY idbesarjttcalon 
			 HAVING x >= $telat ORDER BY idbesarjttcalon ";
} 
else 
{
	$sql = "SELECT idbesarjttcalon, datediff('$tgl', max(tanggal)) as x 
			  FROM jbsfina.penerimaanjttcalon p , jbsfina.besarjttcalon b, jbsakad.calonsiswa c 
				WHERE p.idbesarjttcalon = b.replid AND b.lunas = 0 AND b.info2='$idtahunbuku' AND c.replid = b.idcalon 
				  AND c.idkelompok = '$kelompok' AND b.idpenerimaan = '$idpenerimaan' 
		   GROUP BY idbesarjttcalon 
			  HAVING x >= $telat ORDER BY idbesarjttcalon ";
}
//echo $sql;
$result = QueryDb($sql);
$idstr = "";
while($row = mysqli_fetch_row($result)) {
	if (strlen($idstr) > 0)
		$idstr = $idstr . ",";
	$idstr = $idstr . $row[0];
}
//echo "$idstr<br>";
if (strlen($idstr) == 0) {
	echo "Tidak ditemukan data!";
	CloseDb();
	exit();
}

$sql = "SELECT MAX(jumlah) FROM (SELECT idbesarjttcalon, count(replid) AS jumlah FROM $db_name_fina.penerimaanjttcalon WHERE idbesarjttcalon IN ($idstr) GROUP BY idbesarjttcalon) AS X";
//echo "$sql<br>";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_cicilan = $row[0];
$table_width = 810 + $max_n_cicilan * 90;

//Dapatkan namapenerimaan
$sql = "SELECT nama, departemen FROM $db_name_fina.datapenerimaan WHERE replid = '".$idpenerimaan."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
$departemen = $row[1];

$namakelompok = "Semua Kelompok";
if ($kelompok <> -1) {
	$sql = "SELECT proses, kelompok FROM kelompokcalonsiswa k, prosespenerimaansiswa p WHERE k.replid = '$kelompok' AND k.idproses = p.replid";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$namaproses = $row[0];
	$namakelompok = $row[1];	
} else  {
	$sql = "SELECT proses FROM prosespenerimaansiswa p WHERE p.aktif = 1";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$namaproses = $row[0];
}
?>

<table border="0" cellpadding="10" cellspacing="5" width="<?=$table_width + 50 ?>" align="left">
<tr><td align="left" valign="top">

<?php getHeader($departemen) ?>

<center><font size="4"><strong>LAPORAN TUNGGAKAN <?=strtoupper((string) $namapenerimaan) ?><br />
</strong></font><br /> </center><br />
<table border="0">
<tr>
	<td class="news_content1"><strong>Departemen </strong></td>
    <td class="news_content1">: 
      <?=$departemen?></td>
</tr>
<tr>
	<td class="news_content1"><strong>Proses</strong></td>
    <td class="news_content1">: 
      <?=$namaproses?></td>
</tr>
<tr>
	<td class="news_content1"><strong>Kelompok</strong></td>
    <td class="news_content1">: 
      <?=$namakelompok?></td>
</tr>
<tr>
	<td class="news_content1"><strong>Telat Bayar </strong></td>
    <td class="news_content1">: 
      <?=$telat ?> 
      hari dari tanggal 
      <?=LongDateFormat($tanggal)?></td>
</tr>
</table>
<br />

<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
<tr height="30">
	<td class="header" width="30" align="center">No</td>
    <td class="header" width="80" align="center">No. Reg</td>
    <td class="header" width="140" align="center">Nama</td>
    <td class="header" width="50" align="center">Kel</td>
    <?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
			$n = $i + 1; ?>
    		<td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
    <?php  } ?>
    <td class="header" width="80" align="center">Telat<br /><em>(hari)</em></td>
    <td class="header" width="120" align="center"><?=$namapenerimaan ?></td>
    <td class="header" width="120" align="center">Total Pembayaran</td>
    <td class="header" width="120" align="center">Total Tunggakan</td>
    <td class="header" width="200" align="center">Keterangan</td>
</tr>
<?php
OpenDb();
$sql = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas FROM calonsiswa c, kelompokcalonsiswa k, $db_name_fina.besarjttcalon b WHERE c.replid = b.idcalon AND c.idkelompok = k.replid AND b.replid IN ($idstr) ORDER BY c.nama"; 
$result = QueryDb($sql);
//if ($page==0)
	$cnt = 0;
//else 
	//$cnt = (int)$page*(int)$varbaris;
$totalbiayaall = 0;
$totalbayarall = 0;

while ($row = mysqli_fetch_array($result)) {
	$idbesarjtt = $row['id'];
	$besarjtt = $row['besar'];
	$ketjtt = $row['keterangan'];
	$lunasjtt = $row['lunas'];
	$infojtt = "<font color=red><strong>Belum Lunas</strong></font>";
	if ($lunasjtt == 1)
		$infojtt = "<font color=blue><strong>Lunas</strong></font>";
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
    <td align="center">
<?php $sql = "SELECT max(datediff('$tgl', tanggal)) FROM $db_name_fina.penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	echo $row2[0]; ?>
    </td>
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
<!-- END TABLE CONTENT -->
    
	</td>
</tr>
    </table>
</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>