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
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');

$urut = "NIS";
$urutan = "ASC";
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

OpenDb();
QueryDb("USE jbsfina");

$sql = "SELECT departemen FROM jbsakad.angkatan WHERE replid='$idangkatan'"; 	
$result = QueryDb($sql);    
$row = mysqli_fetch_row($result);	
$departemen = $row[0];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Pembayaran Iuran Sukarela Siswa Per Kelas]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
</head>

<body>

<?php
$sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
$idtahunbuku = FetchSingle($sql);

if ($idtingkat == -1) 
{
	$sql = "SELECT MAX(jml) FROM ((SELECT p.nis, COUNT(p.replid) as jml 
								     FROM penerimaaniuran p, jurnal j, jbsakad.siswa s
									WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku'
									  AND p.nis = s.nis AND s.idangkatan = '$idangkatan'
									  AND p.idpenerimaan = '$idpenerimaan' GROUP BY p.nis) as X)";
} 
else 
{
	if ($idkelas == -1)
		$sql = "SELECT MAX(jml) FROM ((SELECT p.nis, COUNT(p.replid) as jml 
										 FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k 
										WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
										  AND p.nis = s.nis AND s.idangkatan = '$idangkatan' AND p.idpenerimaan = '$idpenerimaan' 
										  AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' GROUP BY p.nis) as X)";
	else
		$sql = "SELECT MAX(jml) FROM ((SELECT p.nis, COUNT(p.replid) as jml 
								         FROM penerimaaniuran p, jurnal j, jbsakad.siswa s 
										WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
										  AND p.nis = s.nis AND s.idkelas = '$idkelas' AND s.idangkatan = '$idangkatan' 
										  AND p.idpenerimaan = '$idpenerimaan' GROUP BY p.nis) as X)";
}
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_bayar = $row[0];
$table_width = 520 + $max_n_bayar * 100;

$sql = "SELECT nama FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
?>

<table border="0" cellpadding="10" cellpadding="5" width="<?=$table_width + 50 ?>" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>LAPORAN PEMBAYARAN IURAN SUKARELA SISWA</strong></font><br /> </center><br /><br />


<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
<tr height="30" align="center">
	<td class="header" width="30" align="center">No</td>
    <td class="header" width="90" align="center">N I S</td>
    <td class="header" width="160">Nama</td>
    <td class="header" width="50" align="center">Kelas</td>
<?php for($i = 0; $i < $max_n_bayar; $i++) { ?>
	<td class="header" width="100" align="center">Bayaran-<?=$i + 1 ?></td>
<?php  } ?>
    <td class="header" width="100" align="center">Total Pembayaran</td>
    <!--<td class="header" width="200" align="center">Keterangan</td>-->
</tr>
<?php

if ($idtingkat == -1) 
{
	$sql_tot = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
	              FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND p.nis = s.nis AND s.idkelas = k.replid AND s.idangkatan = '$idangkatan' 
				   AND p.idpenerimaan = '$idpenerimaan' AND k.idtingkat = t.replid ORDER BY s.nama";
	
	$sql = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
	          FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
			 WHERE p.nis = s.nis AND s.idkelas = k.replid AND s.idangkatan = '$idangkatan' 
			   AND p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			   AND p.idpenerimaan = '$idpenerimaan' AND k.idtingkat = t.replid 
		  ORDER BY $urut $urutan"; 
} 
else 
{
	if ($idkelas == -1)
	{
		$sql_tot = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
		              FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
					 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
					   AND p.nis = s.nis AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' 
					   AND s.idangkatan = '$idangkatan' AND p.idpenerimaan = '$idpenerimaan' AND k.idtingkat = t.replid ORDER BY s.nama";
		
		$sql = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
		          FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND p.nis = s.nis AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' AND s.idangkatan = '$idangkatan' 
				   AND p.idpenerimaan = '$idpenerimaan' AND k.idtingkat = t.replid ORDER BY $urut $urutan"; 
	} 
	else 
	{
		$sql_tot = "SELECT DISTINCT p.nis, s.nama, k.kelas 
		              FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k 
					 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
					   AND p.nis = s.nis AND s.idkelas = k.replid AND s.idkelas = '$idkelas' 
					   AND s.idangkatan = '$idangkatan' AND p.idpenerimaan = '$idpenerimaan' ORDER BY s.nama";
		
		$sql = "SELECT DISTINCT p.nis, s.nama, k.kelas 
		          FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND p.nis = s.nis AND s.idkelas = k.replid AND s.idkelas = '$idkelas' 
				   AND s.idangkatan = '$idangkatan' AND p.idpenerimaan = '$idpenerimaan' ORDER BY $urut $urutan"; 
	}
}

QueryDb("USE jbsfina");

$result = QueryDb($sql);
$cnt = 0;
$totalall = 0;
while ($row = mysqli_fetch_array($result)) { 
	$nis = $row['nis'];
?>
	
    <tr height="40">
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nis'] ?></td>
        <td align="left"><?=$row['nama'] ?></td>
        <td align="center"><?=$row['kelas'] ?></td>
<?php 	$sql = "SELECT date_format(p.tanggal, '%d-%b-%y') as tanggal, jumlah 
	           FROM penerimaaniuran p, jurnal j
			  WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			    AND nis = '".$row['nis']."' AND idpenerimaan = '".$idpenerimaan."'";
		$result2 = QueryDb($sql);
		$nbayar = mysqli_num_rows($result2);
		$nblank = $max_n_bayar - $nbayar;
		
		$totalbayar = 0;
		while ($row2 = mysqli_fetch_array($result2)) {
			$totalbayar += $row2['jumlah']; ?>
            <td>
                <table border="1" width="100%" style="border-collapse:collapse" cellspacing="0" cellpadding="0" bordercolor="#000000">
                <tr height="20"><td align="center"><?=FormatRupiah($row2['jumlah']) ?></td></tr>
                <tr height="20"><td align="center"><?=$row2['tanggal'] ?></td></tr>
                </table>
            </td>
<?php 	} //end for 
		$totalall += $totalbayar;

		for ($i = 0; $i < $nblank; $i++) { ?>        
            <td>
                <table border="1" width="100%" style="border-collapse:collapse" cellspacing="0" cellpadding="0" bordercolor="#000000">
                <tr height="20"><td align="center">&nbsp;</td></tr>
                <tr height="20"><td align="center">&nbsp;</td></tr>
                </table>
            </td>
<?php 	} //end for ?>        
		<td align="right"><?=FormatRupiah($totalbayar) ?></td>
       <!--<td align="right"><?=$row['keterangan'] ?></td>-->
    </tr>
<?php } //end for ?>
	<tr height="30">
    	<td bgcolor="#999900" align="center" colspan="<?=4 + $max_n_bayar ?>"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td bgcolor="#999900" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($totalall) ?></strong></font></td>
        <!--<td bgcolor="#999900">&nbsp;</td>-->
    </tr>
</table>
<?php
CloseDb();
?>

</td>
</tr>
    </table>
</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>