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
require_once('../include/getheader.php');
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
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Laporan Presensi Pengajar]</title>
</head>

<body>

<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?=getHeader($row['departemen'])?>
	
<center>
  <font size="4"><strong>LAPORAN PRESENSI PENGAJAR</strong></font><br />
 </center><br /><br />
<table>

<tr>
	<td><strong>Guru</strong></td>
    <td><strong>: <?=$nip.' - '.$row['nama']?></strong></td>
</tr>
<tr>
	<td width="120"><strong>Departemen</strong></td>
    <td ><strong>: <?=$row['departemen']?></strong></td>
</tr>
<tr>
	<td width="120"><strong>Tahun Ajaran</strong></td>
    <td ><strong>: <?=$row['tahunajaran']?></strong></td>
</tr>

<tr>
	<td><strong>Periode Presensi</strong></td>
    <td><strong>: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></strong></td>
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
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
   	<tr height="30">
    	<td class="header" align="center" width="5%">No</td>
		<td class="header" align="center" width="5%">Tanggal</td>
		<td class="header" align="center" width="5%">Pukul</td>            
		<td class="header" align="center" width="5%">Kelas</td>
        <td class="header" align="center" width="15%">Pelajaran</td>
        <td class="header" align="center" width="14%">Status</td>
        <td class="header" align="center" >Terlambat</td>
        <td class="header" align="center" width="5%">Jam</td>
        <td class="header" align="center" width="17%">Materi</td>
        <td class="header" align="center" width="25%">Keterangan</td>              
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { ?>
    <tr height="25">    	
    	<td align="center"><?=++$cnt?></td>
		<td align="center"><?=$row[0].'-'.$row[1].'-'.substr((string) $row[2],2,2)?></td>
        <td align="center"><?=substr((string) $row[3],0,5)?></td>
        <td align="center"><?=$row[4]?></td>
        <td><?=$row[5]?></td>
        <td><?=$row[6]?></td>
        <td align="center"><?=$row[7]?> menit</td>
        <td align="center"><?=$row[8]?></td>
        <td><?=$row[9]?></td>
        <td><?=$row[10]?></td>  
    </tr>
<?php } 
	CloseDb() ?>
   	</table>
   </td>
   </tr>
   <tr>
   <td>
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="400" align="left" bordercolor="#000000">
    <tr height="30">
		<td class="header" width="200">&nbsp;</td>
		<td align="center" class="header" width="100">Pertemuan</td>
		<td align="center" class="header" width="100">Jumlah Jam</td>
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
	<tr height="25">	
    	<td><strong><?=$row['status']?></strong></td>
    	<td align="center"><?=$row1[0]?></td> 	
		<td align="center"><?=$row1[1]?></td>    
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