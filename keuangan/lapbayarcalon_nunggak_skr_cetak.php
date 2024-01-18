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

$urut=$_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];

if (isset($_REQUEST['kelompok']))
	$kelompok = (int)$_REQUEST['kelompok'];
	
$telat = 0;
if (isset($_REQUEST['telat']))
	$telat = (int)$_REQUEST['telat'];
	
$tanggal = "";
if (isset($_REQUEST['tanggal']))
	$tanggal = $_REQUEST['tanggal'];
	
$tgl = MySqlDateFormat($tanggal);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Tunggakan Iuran Sukarela Calon Siswa Per Kelas]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>

<?php
OpenDb();
if ($kelompok == -1)
	$sql = "SELECT p.idcalon, datediff('$tgl', max(tanggal)) AS x FROM penerimaaniurancalon p, jbsakad.calonsiswa c, jbsakad.prosespenerimaansiswa r WHERE p.idpenerimaan = '$idpenerimaan' AND c.replid = p.idcalon AND c.idproses = r.replid AND r.aktif = 1 GROUP BY p.idcalon HAVING x >= '$telat' ORDER BY tanggal DESC";
else
	$sql = "SELECT p.idcalon, datediff('$tgl', max(tanggal)) AS x FROM penerimaaniurancalon p, jbsakad.calonsiswa c WHERE p.idpenerimaan = '$idpenerimaan' AND c.replid = p.idcalon AND c.idkelompok = '$kelompok' GROUP BY p.idcalon HAVING x >= '$telat' ORDER BY tanggal DESC";
 
//echo  "$sql<br>";
$result = QueryDb($sql);
$nisstr = "";
while($row = mysqli_fetch_row($result)) {
	if (strlen($nisstr) > 0)
		$nisstr = $nisstr . ",";
	$nisstr = $nisstr . "'" . $row[0] . "'";
}
//echo  "$nisstr<br>";
if (strlen($nisstr) == 0) {
	echo  "Tidak ditemukan data!";
	CloseDb();
	exit();
}

$sql = "SELECT MAX(jumlah) FROM (SELECT idcalon, count(replid) AS jumlah FROM penerimaaniurancalon WHERE idcalon IN ($nisstr) GROUP BY idcalon) AS X";
//echo  "$sql<br>";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_cicilan = $row[0];
$table_width = 810 + $max_n_cicilan * 90;

//Dapatkan namapenerimaan
$sql = "SELECT nama, departemen FROM datapenerimaan WHERE replid='$idpenerimaan'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
$departemen = $row[1];

$namakelompok = "Semua Kelompok";
if ($kelompok <> -1) {
	$sql = "SELECT proses, kelompok FROM jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p WHERE k.replid = '$kelompok' AND k.idproses = p.replid";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$namaproses = $row[0];
	$namakelompok = $row[1];	
} else  {
	$sql = "SELECT proses FROM jbsakad.prosespenerimaansiswa p WHERE p.aktif = 1";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$namaproses = $row[0];
}
?>

<table border="0" cellpadding="10" cellpadding="5" width="<?=$table_width ?>" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>LAPORAN TUNGGAKAN <?=strtoupper((string) $namapenerimaan) ?><br />
</strong></font><br /> </center><br />
<table border="0">
<tr>
	<td><strong>Departemen </strong></td>
    <td><strong>: <?=$departemen?></strong></td>
</tr>
<tr>
	<td><strong>Proses</strong></td>
    <td><strong>: <?=$namaproses?></strong></td>
</tr>
<tr>
	<td><strong>Kelompok</strong></td>
    <td><strong>: <?=$namakelompok?></strong></td>
</tr>
<tr>
	<td><strong>Telat Bayar </strong></td>
    <td><strong>: <?=$telat ?> hari dari tanggal <?=LongDateFormat($tanggal)?></strong></td>
</tr>
</table>
<br />

<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
<tr height="30">
	<td class="header" width="30" align="center">No</td>
    <td class="header" width="80" align="center">No. Reg</td>
    <td class="header" width="140">Nama</td>
    <td class="header" width="50" align="center">Kel</td>
    <?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
			$n = $i + 1; ?>
    		<td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
    <?php  } ?>
    <td class="header" width="80" align="center">Telat<br /><em>(hari)</em></td>
    <td class="header" width="100" align="center">Total Pembayaran</td>
</tr>
<?php
OpenDb();
$sql = "SELECT c.replid, c.nopendaftaran, c.nama, k.kelompok FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k WHERE c.idkelompok = k.replid AND c.replid IN ($nisstr) ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
$result = QueryDb($sql);
//if ($page==0)
	$cnt = 0;
//else 
	//$cnt = (int)$page*(int)$varbaris;
$totalbiayaall = 0;
$totalbayarall = 0;

while ($row = mysqli_fetch_array($result)) {
	$replid = $row['replid']; ?>
<tr height="40">
	<td align="center"><?=++$cnt ?></td>
    <td align="center"><?=$row['nopendaftaran'] ?></td>
    <td><?=$row['nama'] ?></td>
    <td align="center"><?=$row['kelompok'] ?></td>
<?php $sql = "SELECT count(*) FROM penerimaaniurancalon WHERE idcalon = '$replid' AND idpenerimaan = '".$idpenerimaan."'";
	//echo  "$sql<br>";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	$nbayar = $row2[0];
	$nblank = $max_n_cicilan - $nbayar;
	$totalbayar = 0;
	
	if ($nbayar > 0) {
		$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah FROM penerimaaniurancalon WHERE idcalon = '$replid' AND idpenerimaan = '$idpenerimaan' ORDER BY tanggal";
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
<?php $sql = "SELECT max(datediff('$tgl', tanggal)) FROM penerimaaniurancalon WHERE idcalon = '$replid' AND idpenerimaan = '".$idpenerimaan."'";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	echo  $row2[0]; ?>
    </td>
    <td align="right"><?=FormatRupiah($totalbayar) ?></td>
</tr>
<?php
}
?>
<tr height="40">
	<td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbayarall) ?></strong></font></td>
</tr>
</table>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>
<?php CloseDb() ?>

</td>
</tr>
    </table>
</td></tr></table></body>
</html>
<script language="javascript">window.print();</script>