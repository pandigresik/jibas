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

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];
	
$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$kriteria = 0;
if (isset($_REQUEST['kriteria']))
	$kriteria = (int)$_REQUEST['kriteria'];

$keyword = "";
if (isset($_REQUEST['keyword']))
	$keyword = $_REQUEST['keyword'];
		
$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];	

$urut=$_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Pengeluaran]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>LAPORAN PENGELUARAN</strong></font><br /> </center><br /><br />

<table border="0">
<tr>
	<td width="90"><strong>Departemen </strong></td>
    <td><strong>: <?=$departemen ?></strong> </td>
</tr>
<tr>
	<td width="90"><strong>Tanggal </strong></td>
    <td><strong>: <?=LongDateFormat($tanggal1) . " s/d 	" . LongDateFormat($tanggal2) ?></strong></td>
</tr>

</table>
<br />

<table id="table" class="tab" border="1" style="border-collapse:collapse" width="100%" bordercolor="#000000">
<tr height="30" align="center">
	<td class="header" width="4%">No</td>
    <td class="header" width="8%" >Tanggal</td>
    <td class="header" width="15%">Pengeluaran</td>
    <td class="header" width="18%">Pemohon</td>
    <td class="header" width="10%">Penerima</td>
    <td class="header" width="12%">Jumlah</td>
    <td class="header" width="*">Keperluan</td>
    <td class="header" width="7%">Petugas</td>
</tr>
<?php
OpenDb();
if ($kriteria == 1)
	$sqlwhere = " AND p.namapemohon LIKE '%$keyword%'";
else if ($kriteria == 2)
	$sqlwhere = " AND p.penerima LIKE '%$keyword%'";
else if ($kriteria == 3)
	$sqlwhere = " AND p.petugas LIKE '%$keyword%'";
else if ($kriteria == 4)
	$sqlwhere = " AND p.keperluan LIKE '%$keyword%'";
else if ($kriteria == 5)
	$sqlwhere = " AND p.keterangan LIKE '%$keyword%'";
		
$sql = "SELECT p.replid AS id, d.nama AS namapengeluaran, p.keperluan, p.keterangan, p.jenispemohon, 
	               p.nip, p.nis, p.pemohonlain, p.penerima, date_format(p.tanggal, '%d-%b-%Y') as tanggal, date_format(p.tanggalkeluar, '%d-%b-%Y') as tanggalkeluar, 
				   p.petugas, p.jumlah 
		     FROM pengeluaran p, jurnal j, datapengeluaran d 
			WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			  AND p.idpengeluaran = d.replid AND d.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' 
			      $sqlwhere 
		 ORDER BY $urut $urutan"; 

OpenDb();
$result = QueryDb($sql);
$cnt = 0;
$totalbiaya = 0;
while ($row = mysqli_fetch_array($result)) {
	
	if ($row['jenispemohon'] == 1) {
		$idpemohon = $row['nip'];
		$sql = "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$idpemohon."'";
		$jenisinfo = "pegawai";
	} else if ($row['jenispemohon'] == 2) {
		$idpemohon = $row['nis'];
		$sql = "SELECT nama FROM jbsakad.siswa WHERE nis = '".$idpemohon."'";
		$jenisinfo = "siswa";
	} else {
		$idpemohon = "";
		$sql = "SELECT nama FROM pemohonlain WHERE replid = '".$row['pemohonlain']."'";
		$jenisinfo = "pemohon lain";
	}
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	$namapemohon = $row2[0];
	
	$totalbiaya += $row['jumlah'];
?>
<tr height="25">
	<td align="center" valign="top"><?=++$cnt ?></td>
    <td align="center" valign="top"><?=$row['tanggal'] ?></td>
    <td valign="top"><?=$row['namapengeluaran'] ?></td>
    <td valign="top"><?=$idpemohon ?> <?=$namapemohon ?><br />
	<em>(<?=$jenisinfo ?>)</em>
    </td>
    <td valign="top"><?=$row['penerima'] ?></td>
    <td align="right" valign="top"><?=FormatRupiah($row['jumlah']) ?></td>
    <td valign="top">
    <strong>Keperluan: </strong><?=$row['keperluan'] ?><br />
    <strong>Keterangan: </strong><?=$row['keterangan'] ?>
	</td>
    <td valign="top" align="center"><?=$row['petugas'] ?></td>
</tr>
<?php
}
CloseDb();
?>
<tr height="30">
	<td colspan="5" align="center" bgcolor="#999900">
    <font color="#FFFFFF"><strong>T O T A L</strong></font>
    </td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbiaya) ?></strong></font></td>
    <td colspan="2" bgcolor="#999900">&nbsp;</td>
</tr>
</table>
 <!-- END TABLE CONTENT -->
    
	</td>
</tr>
    </table>
</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>