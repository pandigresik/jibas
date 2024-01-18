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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=LaporanPresensiPengajar.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$tahunajaran = $_REQUEST['tahunajaran'];
$nip = $_REQUEST['nip'];
$tglawal = $_REQUEST['tglawal'];
$tglakhir = $_REQUEST['tglakhir'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

OpenDb();
	
$sql = "SELECT p.nama, t.tahunajaran, t.departemen FROM jbssdm.pegawai p, jbsakad.tahunajaran t WHERE nip = '$nip' AND t.replid = '$tahunajaran'" ;   
$result = QueryDB($sql);

$row = mysqli_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Laporan Presensi Pengajar]</title>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-family: Verdana;
}
.style4 {font-family: Verdana; font-weight: bold; font-size: 12px; }
.style5 {font-family: Verdana}
.style6 {font-size: 12px}
.style7 {font-family: Verdana; font-size: 12px; }
-->
</style>
</head>

<body>

<table width="100%" border="0" cellspacing="0">
  <tr>
    <th scope="row" colspan="10"><span class="style1">Laporan Presensi Pengajar</span></th>
  </tr>
</table>
<br />
<table width="27%">

<tr>
	<td width="43%"><span class="style4">Guru</span></td>
    <td width="57%" colspan="10"><span class="style4">: <?=$nip.' - '.$row['nama']?></span></td>
</tr>
<tr>
	<td width="120"><span class="style4">Departemen</span></td>
    <td colspan="10"><span class="style4">: <?=$row['departemen']?></span></td>
</tr>
<tr>
	<td width="120"><span class="style4">Tahun Ajaran</span></td>
    <td colspan="10"><span class="style4">: <?=$row['tahunajaran']?></span></td>
</tr>

<tr>
	<td><span class="style4">Periode Presensi</span></td>
    <td colspan="10"><span class="style4">: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></span></td>
</tr>
</table>
<br />
<?php 		
	OpenDb();
	$sql = "SELECT DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), p.jam, k.kelas, l.nama, s.status, p.keterlambatan, p.jumlahjam, p.materi, p.keterangan, p.replid FROM presensipelajaran p, kelas k, pelajaran l, statusguru s WHERE p.idkelas = k.replid AND p.idpelajaran = l.replid AND p.gurupelajaran = '$nip' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.jenisguru = s.replid AND k.idtahunajaran = '$tahunajaran' ORDER BY $urut $urutan";
	
	$result = QueryDb($sql);			 
	$jum_hadir = mysqli_num_rows($result);
	if ($jum_hadir > 0) { 
?>      
    <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left">
   	<tr height="30" align="center" bgcolor="#CCCCCC" class="style6 style5 header">
    	<td width="5%">No</td>
		<td width="5%">Tanggal</td>
		<td width="5%">Pukul</td>            
		<td width="5%">Kelas</td>
        <td width="15%">Pelajaran</td>
        <td width="14%">Status</td>
        <td >Terlambat</td>
        <td width="5%">Jam</td>
        <td width="17%">Materi</td>
        <td width="25%">Keterangan</td>              
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { ?>
    <tr height="25" valign="middle">    	
    	<td align="center"><span class="style7"><?=++$cnt?></span></td>
		<td align="center"><span class="style7"><?=$row[0].'-'.$row[1].'-'.substr((string) $row[2],2,2)?></span></td>
        <td align="center"><span class="style7"><?=substr((string) $row[3],0,5)?></span></td>
        <td align="center"><span class="style7"><?=$row[4]?></span></td>
        <td><span class="style7"><?=$row[5]?></span></td>
        <td><span class="style7"><?=$row[6]?></span></td>
        <td align="center"><span class="style7"><?=$row[7]?> menit</span></td>
        <td align="center"><span class="style7"><?=$row[8]?></span></td>
        <td><span class="style7"><?=$row[9]?></span></td>
        <td><span class="style7"><?=$row[10]?></span></td>  
    </tr>
<?php } 
	CloseDb() ?>
   	</table>
 	<br>
	<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="400" align="left">
    <tr height="30" align="center" bgcolor="#CCCCCC" class="style6 style5 header">
		<td width="200">&nbsp;</td>
		<td width="100">Pertemuan</td>
		<td width="100">Jumlah Jam</td>
	</tr>
<?php 	OpenDb();	
	$sql = "SELECT replid, status FROM statusguru ORDER BY status" ;
	$result = QueryDb($sql);	
	while ($row = @mysqli_fetch_array($result)) {
		$replid = $row['replid'];
		
		$sql1 = "SELECT COUNT(*), SUM(p.jumlahjam) FROM presensipelajaran p, pelajaran l, kelas k WHERE p.gurupelajaran = '$nip' AND tanggal BETWEEN '$tglawal' AND '$tglakhir' AND jenisguru = '$replid' AND p.idpelajaran = l.replid AND p.idkelas = k.replid AND k.idtahunajaran = '$tahunajaran' ";
		$result1 = QueryDb($sql1);	
		$row1 = @mysqli_fetch_row($result1);
?>
	<tr height="25" valign="middle">	
    	<td><span class="style7"><strong><?=$row['status']?></strong></span></td>
    	<td align="center"><span class="style7"><?=$row1[0]?></span></td> 	
		<td align="center"><span class="style7"><?=$row1[1]?></span></td>    
	</tr>
<?php 	} CloseDb(); ?>
	</table>    
<?php 	} ?>
	</td>
</tr>
</table>
</body>
<script language="javascript">
window.print();
</script>
</html>