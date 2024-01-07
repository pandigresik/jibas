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

/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Laporan_Pencarian_Pengeluaran.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');


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

$urut=$_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Pengeluaran]</title>
</head>

<body>
<center><font size="4" face="Verdana"><strong>LAPORAN PENGELUARAN</strong></font><br /> 
</center>
<br /><br />
<table border="0">
<tr>
	<td width="90"><font size="2" face="Arial"><strong>Departemen </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$departemen ?>
    </strong> </font></td>
</tr>
<tr>
	<td width="90"><font size="2" face="Arial"><strong>Tanggal </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=LongDateFormat($tanggal1) . " s/d 	" . LongDateFormat($tanggal2) ?>
    </strong></font></td>
</tr>
</table>
<br />

<table id="table" class="tab" border="1" style="border-collapse:collapse" width="100%" bordercolor="#000000">
<tr height="30" align="center">
	<td width="4%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No</font></strong></td>
    <td width="8%" bgcolor="#CCCCCC" class="header" ><strong><font size="2" face="Arial">Tanggal</font></strong></td>
    <td width="15%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Pengeluaran</font></strong></td>
    <td width="18%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Pemohon</font></strong></td>
    <td width="10%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Penerima</font></strong></td>
    <td width="12%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Jumlah</font></strong></td>
    <td width="*" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Keperluan</font></strong></td>
    <td width="7%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Petugas</font></strong></td>
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
			      $sqlwhere";

OpenDb();
$result = QueryDb($sql);
$cnt = 0;
$totalbiaya = 0;
while ($row = mysqli_fetch_array($result)) {
	$bg1="#ffffff";
	if ($cnt==0 || $cnt%2==0)
		$bg1="#fcffd3";
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
<tr height="25" bgcolor="<?=$bg1?>">
	<td align="center" valign="top"><font size="2" face="Arial">
	  <?=++$cnt ?>
	</font></td>
    <td align="center" valign="top"><font size="2" face="Arial">
      <?=$row['tanggal'] ?>
    </font></td>
    <td valign="top"><font size="2" face="Arial">
      <?=$row['namapengeluaran'] ?>
    </font></td>
    <td valign="top"><font size="2" face="Arial">
      <?=$idpemohon ?> 
      <?=$namapemohon ?>
      <br />
	  <em>(
	  <?=$jenisinfo ?>
    )</em> </font></td>
    <td valign="top"><font size="2" face="Arial">
      <?=$row['penerima'] ?>
    </font></td>
    <td align="right" valign="top"><font size="2" face="Arial">
      <?=$row['jumlah'] ?>
    </font></td>
    <td valign="top">
      <font size="2" face="Arial"><strong>Keperluan: </strong>
      <?=$row['keperluan'] ?>
      <br />
      <strong>Keterangan: </strong>
      <?=$row['keterangan'] ?>	
    </font></td>
    <td valign="top" align="center"><font size="2" face="Arial">
      <?=$row['petugas'] ?>
    </font></td>
</tr>
<?php
}
CloseDb();
?>
<tr height="30">
	<td colspan="5" align="center" bgcolor="#999900">
    <font color="#FFFFFF" size="2" face="Arial"><strong>T O T A L</strong></font>    </td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2"><strong><?=$totalbiaya ?></strong></font></td>
    <td colspan="2" bgcolor="#999900"><font size="2">&nbsp;</font></td>
</tr>
</table>
<!-- END TABLE CONTENT -->
</td>
</tr>
</table>
</body>
</html>
<script language="javascript">window.print();</script>