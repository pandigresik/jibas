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

$urut=$_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];

if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];

if (isset($_REQUEST['idtingkat']))
	$idtingkat = (int)$_REQUEST['idtingkat'];

if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];
	
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
<title>JIBAS KEU [Laporan Tunggakan Iuran Wajib Siswa Per Kelas]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<?php
OpenDb();

$sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
$idtahunbuku = FetchSingle($sql);

if ($idtingkat == -1) 
{
	$sql = "SELECT idbesarjtt, datediff('$tgl', max(tanggal)) as x 
	          FROM penerimaanjtt p, besarjtt b, jbsakad.siswa s 
				WHERE p.idbesarjtt = b.replid AND b.lunas = 0 AND b.info2='$idtahunbuku' AND b.idpenerimaan = '$idpenerimaan' 
				  AND s.nis = b.nis AND s.idangkatan = '$idangkatan' 
			GROUP BY idbesarjtt HAVING x >= $telat";
/*			  "UNION 
			  SELECT b.replid, '-' FROM besarjtt b, jbsakad.siswa s 
			   WHERE b.replid IN (SELECT idbesarjtt FROM penerimaanjtt) AND b.lunas = 0 AND b.info2='$idtahunbuku' AND b.idpenerimaan = $idpenerimaan
				  AND s.nis = b.nis AND s.idangkatan = $idangkatan"; */
} 
else 
{
	if ($idkelas == -1) 
	{
		$sql = "SELECT idbesarjtt, datediff('$tgl', max(tanggal)) as x 
		          FROM penerimaanjtt p , besarjtt b, jbsakad.siswa s, jbsakad.kelas k 
					WHERE p.idbesarjtt = b.replid AND b.lunas = 0 AND b.info2 = '$idtahunbuku' AND b.idpenerimaan = '$idpenerimaan'
					  AND s.nis = b.nis AND s.idangkatan = '$idangkatan' AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' 
			   GROUP BY idbesarjtt 
				  HAVING x >= $telat";
				  /*UNION SELECT b.replid, '-' FROM besarjtt b, jbsakad.siswa s, jbsakad.kelas k WHERE b.replid IN (SELECT idbesarjtt FROM penerimaanjtt p) AND b.lunas = 0 AND s.nis = b.nis AND s.idangkatan = $idangkatan AND b.idpenerimaan = $idpenerimaan AND s.idkelas = k.replid AND k.idtingkat = $idtingkat";*/
	} 
	else 
	{
		$sql = "SELECT idbesarjtt, datediff('$tgl', max(tanggal)) as x 
		          FROM penerimaanjtt p , besarjtt b, jbsakad.siswa s 
					WHERE p.idbesarjtt = b.replid AND b.lunas = 0 AND b.info2='$idtahunbuku' AND b.idpenerimaan = '$idpenerimaan'
					  AND s.nis = b.nis AND s.idkelas = '$idkelas' AND s.idangkatan = '$idangkatan'  
			   GROUP BY idbesarjtt 
			  	  HAVING x >= $telat";
				  /*UNION SELECT b.replid, '-' FROM besarjtt b, jbsakad.siswa s WHERE b.replid IN (SELECT idbesarjtt FROM penerimaanjtt p) AND b.lunas = 0 AND s.nis = b.nis AND s.idkelas = $idkelas AND s.idangkatan = $idangkatan  AND b.idpenerimaan = $idpenerimaan";*/
	}
}

$result = QueryDb($sql);
$idstr = "";
while($row = mysqli_fetch_row($result)) {
	if (strlen($idstr) > 0)
		$idstr = $idstr . ",";
	$idstr = $idstr . $row[0];
}
//echo  "$idstr<br>";
if (strlen($idstr) == 0) {
	echo  "Tidak ditemukan data!";
	CloseDb();
	exit();
}

$sql = "SELECT MAX(jumlah) FROM (SELECT idbesarjtt, count(replid) AS jumlah FROM penerimaanjtt WHERE idbesarjtt IN ($idstr) GROUP BY idbesarjtt) AS X";
//echo  "$sql<br>";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_cicilan = $row[0];
$table_width = 810 + $max_n_cicilan * 90;

//Dapatkan namapenerimaan
$sql = "SELECT nama, departemen FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
$departemen = $row[1];

$namatingkat = "";
$namakelas = "";
if ($idtingkat <> -1) {
	if ($idkelas <> -1) {
		$sql = "SELECT tingkat, kelas FROM jbsakad.kelas k, jbsakad.tingkat t WHERE k.replid = '$idkelas' AND k.idtingkat = t.replid AND t.replid = '".$idtingkat."'";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$namatingkat = $row[0]." - ";
		$namakelas = $row[1];	
	} else {
		$sql = "SELECT tingkat FROM jbsakad.tingkat t WHERE t.replid = '".$idtingkat."'";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$namatingkat = $row[0];
	}
} else {
	$namakelas = "Semua Kelas";
}
?>

<table border="0" cellpadding="10" cellpadding="5" width="<?=$table_width + 50 ?>" align="left">
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
	<td><strong><?php if ($idtingkat <> -1 && $idkelas == -1) echo  "Tingkat"; else echo  "Kelas"; ?></strong></td>
    <td><strong>: <?=$namatingkat.$namakelas?></strong></td>
</tr>

<tr>
	<td><strong>Telat Bayar </strong></td>
    <td><strong>: <?=$telat ?> hari dari tanggal <?=LongDateFormat($tgl)?></strong></td>
</tr>
</table>
<br />

<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
<tr height="30">
	<td class="header" width="30" align="center">No</td>
    <td class="header" width="80" align="center">N I S</td>
    <td class="header" width="140">Nama</td>
    <td class="header" width="50" align="center">Kelas</td>
    <?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
			$n = $i + 1; ?>
    		<td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
    <?php  } ?>
    <td class="header" width="80" align="center">Telat<br /><em>(hari)</em></td>
    <td class="header" width="125" align="center"><?=$namapenerimaan ?></td>
    <td class="header" width="125" align="center">Total Pembayaran</td>
    <td class="header" width="125" align="center">Total Diskon</td>
    <td class="header" width="125" align="center">Total Tunggakan</td>
    <td class="header" width="200" align="center">Keterangan</td>
</tr>
<?php
OpenDb();
$sql = "SELECT b.nis, s.nama, k.kelas, t.tingkat, b.replid AS id, b.besar, b.keterangan, b.lunas FROM jbsakad.siswa s, jbsakad.kelas k, besarjtt b, jbsakad.tingkat t WHERE s.nis = b.nis AND s.idkelas = k.replid AND k.idtingkat = t.replid AND b.replid IN ($idstr) ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
$result = QueryDb($sql);
//if ($page==0)
	$cnt = 0;
//else 
	//$cnt = (int)$page*(int)$varbaris;
$totalbiayaall = 0;
$totalbayarall = 0;
$totaldiskonall = 0;

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
    <td align="center"><?=$row['nis'] ?></td>
    <td><?=$row['nama'] ?></td>
    <td align="center"><?php if ($idkelas == -1) echo  $row['tingkat']." - "; ?><?=$row['kelas'] ?></td>
    <?php
	$sql = "SELECT count(*) FROM penerimaanjtt WHERE idbesarjtt = $idbesarjtt";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	$nbayar = $row2[0];
	$nblank = $max_n_cicilan - $nbayar;
	$totalbayar = 0;
	$totaldiskon = 0;
	
	if ($nbayar > 0) {
		$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah, info1 FROM penerimaanjtt WHERE idbesarjtt = '$idbesarjtt' ORDER BY tanggal";
		$result2 = QueryDb($sql);
		while ($row2 = mysqli_fetch_row($result2)) {
			$totalbayar = $totalbayar + $row2[1];
            $totaldiskon = $totaldiskon + $row2[2];
			?>
            <td>
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000">
                <tr height="20"><td align="center"><?=FormatRupiah($row2[1]) ?></td></tr>
                <tr height="20"><td align="center"><?=$row2[0] ?></td></tr>
                </table>
            </td>
 <?php 	}
 		$totalbayarall += $totalbayar;
		$totaldiskonall += $totaldiskon;
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
<?php $sql = "SELECT datediff('$tgl', max(tanggal)) FROM penerimaanjtt WHERE idbesarjtt = '".$idbesarjtt."'";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	echo  $row2[0]; ?>
    </td>
    <td align="right"><?=FormatRupiah($besarjtt) ?></td>
    <td align="right"><?=FormatRupiah($totalbayar) ?></td>
    <td align="right"><?=FormatRupiah($totaldiskon) ?></td>
    <td align="right"><?=FormatRupiah($besarjtt - $totalbayar - $totaldiskon) ?></td>
    <td><?=$ketjtt ?></td>
</tr>
<?php
}
?>
<tr height="40">
	<td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
	<td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbiayaall) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbayarall) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totaldiskonall) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbiayaall - $totalbayarall - $totaldiskonall) ?></strong></font></td>
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