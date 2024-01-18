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

$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];

if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];

if (isset($_REQUEST['idtingkat']))
	$idtingkat = (int)$_REQUEST['idtingkat'];

if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];
	
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
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Tunggakan Iuran Sukarela Siswa Per Kelas]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
</head>

<body>

<?php
OpenDb();
if ($idtingkat == -1) {
	$sql = "SELECT p.nis, datediff('$tgl', max(tanggal)) AS x FROM $db_name_fina.penerimaaniuran p, siswa s WHERE p.idpenerimaan = '$idpenerimaan' AND s.nis = p.nis AND s.idangkatan = '$idangkatan' GROUP BY p.nis HAVING x >= $telat ORDER BY tanggal DESC";
} else {
	if ($idkelas == -1)
		$sql = "SELECT p.nis, datediff('$tgl', max(tanggal)) AS x FROM $db_name_fina.penerimaaniuran p, siswa s, kelas k WHERE p.idpenerimaan = '$idpenerimaan' AND s.nis = p.nis AND s.idangkatan = '$idangkatan' AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' GROUP BY p.nis HAVING x >= $telat ORDER BY tanggal DESC";
	else
		$sql = "SELECT p.nis, datediff('$tgl', max(tanggal)) AS x FROM $db_name_fina.penerimaaniuran p, siswa s WHERE p.idpenerimaan = '$idpenerimaan' AND s.nis = p.nis AND s.idangkatan = '$idangkatan' AND s.idkelas = '$idkelas' GROUP BY p.nis HAVING x >= $telat ORDER BY tanggal DESC";
} 
//echo "$sql<br>";
$result = QueryDb($sql);
$nisstr = "";
while($row = mysqli_fetch_row($result)) {
	if (strlen($nisstr) > 0)
		$nisstr = $nisstr . ",";
	$nisstr = $nisstr . "'" . $row[0] . "'";
}
//echo "$nisstr<br>";
if (strlen($nisstr) == 0) {
	echo "Tidak ditemukan data!";
	CloseDb();
	exit();
}

$sql = "SELECT MAX(jumlah) FROM (SELECT nis, count(replid) AS jumlah FROM $db_name_fina.penerimaaniuran WHERE nis IN ($nisstr) GROUP BY nis) AS X";
//echo "$sql<br>";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_cicilan = $row[0];
$table_width = 810 + $max_n_cicilan * 90;

//Dapatkan namapenerimaan
$sql = "SELECT nama, departemen FROM $db_name_fina.datapenerimaan WHERE replid='$idpenerimaan'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
$departemen = $row[1];

$namatingkat = "";
$namakelas = "";
if ($idtingkat <> -1) {
	if ($idkelas <> -1) {
		$sql = "SELECT tingkat, kelas FROM kelas k, tingkat t WHERE k.replid = '$idkelas' AND k.idtingkat = t.replid AND t.replid = '".$idtingkat."'";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$namatingkat = $row[0]." - ";
		$namakelas = $row[1];	
	} else {
		$sql = "SELECT tingkat FROM tingkat t WHERE t.replid = '".$idtingkat."'";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$namatingkat = $row[0];
	}
} else {
	$namakelas = "Semua Kelas";
}
?>

<table border="0" cellpadding="10" cellspacing="5" width="<?=$table_width ?>" align="left">
<tr><td align="left" valign="top">

<?php getHeader($departemen) ?>

<center><font size="4"><strong>LAPORAN TUNGGAKAN <?=strtoupper((string) $namapenerimaan) ?><br />
</strong></font><br /> </center><br />
<table border="0">
<tr>
	<td class="news_content1"><strong>Departemen </strong></td>
    <td class="news_content1">: 
          <?=$departemen?>    </td>
</tr>
<tr>
	<td class="news_content1">	  <strong>
	  <?php if ($idtingkat <> -1 && $idkelas == -1) echo "Tingkat"; else echo "Kelas"; ?>
	</strong> </td>
    <td class="news_content1">: 
          <?=$namatingkat.$namakelas?>    </td>
</tr>

<tr>
	<td class="news_content1"><strong>Telat Bayar </strong></td>
    <td class="news_content1">: 
          <?=$telat ?> 
      hari dari tanggal 
      <?=LongDateFormat($tanggal)?>    </td>
</tr>
</table>
<br />

<table class="tab" id="table" border="1" cellpadding="0" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
<tr height="30">
	<td class="header" width="30" align="center">No</td>
    <td class="header" width="80" align="center">NIS</td>
    <td class="header" width="140" align="center">Nama</td>
    <td class="header" width="50" align="center">Kelas</td>
    <?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
			$n = $i + 1; ?>
    		<td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
    <?php  } ?>
    <td class="header" width="80" align="center">Telat<br /><em>(hari)</em></td>
    <td class="header" width="100" align="center">Total Pembayaran</td>
</tr>
<?php
OpenDb();
$sql = "SELECT s.nis, s.nama, k.kelas, t.tingkat FROM siswa s, kelas k, tingkat t WHERE s.idkelas = k.replid AND k.idtingkat = t.replid AND s.nis IN ($nisstr) "; 
$result = QueryDb($sql);
//if ($page==0)
	$cnt = 0;
//else 
	//$cnt = (int)$page*(int)$varbaris;

$totalbiayaall = 0;
$totalbayarall = 0;

while ($row = mysqli_fetch_array($result)) {
	$nis = $row['nis']; ?>
<tr height="40">
	<td align="center"><?=++$cnt ?></td>
    <td align="center"><?=$row['nis'] ?></td>
    <td><?=$row['nama'] ?></td>
    <td align="center"><?php if ($idkelas == -1) echo $row['tingkat']." - "; ?><?=$row['kelas'] ?></td>
<?php $sql = "SELECT count(*) FROM $db_name_fina.penerimaaniuran WHERE nis = '$nis' AND idpenerimaan = '".$idpenerimaan."'";
	//echo "$sql<br>";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	$nbayar = $row2[0];
	$nblank = $max_n_cicilan - $nbayar;
	$totalbayar = 0;
	
	if ($nbayar > 0) {
		$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah FROM $db_name_fina.penerimaaniuran WHERE nis = '$nis' AND idpenerimaan = '$idpenerimaan' ORDER BY tanggal";
		$result2 = QueryDb($sql);
		while ($row2 = mysqli_fetch_row($result2)) {
			$totalbayar = $totalbayar + $row2[1]; ?>
            <td>
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
                <tr height="20"><td align="center"><?=FormatRupiah($row2[1]) ?></td></tr>
                <tr height="20"><td align="center"><?=$row2[0] ?></td></tr>
                </table>
            </td>
 <?php 	}
 		$totalbayarall += $totalbayar;
	}	
	for ($i = 0; $i < $nblank; $i++) { ?>
	    <td>
            <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
            <tr height="20"><td align="center">&nbsp;</td></tr>
            <tr height="20"><td align="center">&nbsp;</td></tr>
            </table>
        </td>
    <?php }?>
    <td align="center">
<?php $sql = "SELECT datediff('$tgl', max(tanggal)) FROM $db_name_fina.penerimaaniuran WHERE nis = '$nis' AND idpenerimaan = '".$idpenerimaan."'";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	echo $row2[0]; ?>
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
</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>