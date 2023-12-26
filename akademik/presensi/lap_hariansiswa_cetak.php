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
$nis = $_REQUEST['nis'];
$tglawal = $_REQUEST['tglawal'];
$tglakhir = $_REQUEST['tglakhir'];
$urut = $_REQUEST['urut'];	
$urutan = $_REQUEST['urutan'];

OpenDb();
$sql = "SELECT nama FROM siswa WHERE nis='$nis'";   
$result = QueryDB($sql);	
$row = @mysqli_fetch_array($result);

OpenDb();
$sql = "SELECT DAY(p.tanggal1), MONTH(p.tanggal1), YEAR(p.tanggal1), DAY(p.tanggal2), MONTH(p.tanggal2), YEAR(p.tanggal2), ph.hadir, ph.ijin, ph.sakit, ph.alpa, ph.cuti, ph.keterangan, s.nama, m.semester, k.kelas, m.departemen FROM presensiharian p, phsiswa ph, siswa s, semester m, kelas k WHERE ph.idpresensi = p.replid AND ph.nis = s.nis AND ph.nis = '$nis' AND p.idsemester = m.replid AND p.idkelas = k.replid AND (((p.tanggal1 BETWEEN '$tglawal' AND '$tglakhir') OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) OR (('$tglawal' BETWEEN p.tanggal1 AND p.tanggal2) OR ('$tglakhir' BETWEEN p.tanggal1 AND p.tanggal2))) ORDER BY $urut $urutan ";

$result = QueryDb($sql);
$jum = @mysqli_num_rows($result);
$r = @mysqli_fetch_array($result);
$departemen = $r['departemen'];
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Laporan Presensi Harian Siswa]</title>
</head>

<body>

<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?=getHeader($departemen)?>
	
<center>
  <font size="4"><strong>LAPORAN PRESENSI HARIAN SISWA</strong></font><br />
 </center><br /><br />
<table>
<tr>
	<td width="25%"><strong>Siswa</strong></td>
    <td><strong>: <?=$nis.' - '.$row['nama']?></strong></td>
</tr>
<!--<tr>
	<td><strong>Nama</strong></td>
    <td><strong>: <?=$row['nama']?></strong></td>
</tr>-->
<tr>
	<td><strong>Periode Presensi</strong></td>
    <td><strong>: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></strong></td>
</tr>
</table>
<br />
<?php 	
	OpenDb();
	$sql = "SELECT DAY(p.tanggal1), MONTH(p.tanggal1), YEAR(p.tanggal1), DAY(p.tanggal2), MONTH(p.tanggal2), YEAR(p.tanggal2), ph.hadir, ph.ijin, ph.sakit, ph.alpa, ph.cuti, ph.keterangan, s.nama, m.semester, k.kelas, m.departemen FROM presensiharian p, phsiswa ph, siswa s, semester m, kelas k WHERE ph.idpresensi = p.replid AND ph.nis = s.nis AND ph.nis = '$nis' AND p.idsemester = m.replid AND p.idkelas = k.replid AND (((p.tanggal1 BETWEEN '$tglawal' AND '$tglakhir') OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) OR (('$tglawal' BETWEEN p.tanggal1 AND p.tanggal2) OR ('$tglakhir' BETWEEN p.tanggal1 AND p.tanggal2))) ORDER BY $urut $urutan ";

	$result = QueryDb($sql);
	$jum = @mysqli_num_rows($result);
	if ($jum > 0) { 
?>
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
   	<tr height="30" align="center">
    	<td width="5%" class="header">No</td>
		<td width="25%" class="header">Tanggal</td>
        <td width="8%" class="header">Semester</td>
        <td width="8%" class="header">Kelas</td>
   		<td width="5%" class="header">Hadir</td>
		<td width="5%" class="header">Ijin</td>            
		<td width="5%" class="header">Sakit</td>
        <td width="5%" class="header">Alpa</td>
        <td width="5%" class="header">Cuti</td>      
        <td width="*" class="header">Keterangan</td>      
    </tr>
<?php 	
	$cnt = 0;
	$h=0;
	$i=0;
	$s=0;
	$a=0;
	$c=0;
	while ($row = mysqli_fetch_row($result)) { ?>
    <tr height="25">    	
    	<td align="center"><?=++$cnt?></td>
		<td align="center"><?=$row[0].' '.$bulan[$row[1]].' '.$row[2].' - '.$row[3].' '.$bulan[$row[4]].' '.$row[5]?></td>
        <td align="center"><?=$row[13]?></td>
        <td align="center"><?=$row[14]?></td>
        <td align="center"><?=$row[6]?></td>
		<td align="center"><?=$row[7]?></td>
       	<td align="center"><?=$row[8]?></td>       
        <td align="center"><?=$row[9]?></td>
        <td align="center"><?=$row[10]?></td>
        <td><?=$row[11]?></td>
    </tr>
<?php 
	$h+=$row[6];
	$i+=$row[7];
	$s+=$row[8];
	$a+=$row[9];
	$c+=$row[10];
	} 
	CloseDb() ?>
	<tr>	
		<td width="5%" colspan="4" align="right" bgcolor="#CCCCCC"><strong>Jumlah&nbsp;&nbsp;</strong></td>
   		<td width="5%" height="25" align="center"><?=$h?></td>
		<td width="5%" height="25" align="center"><?=$i?></td>            
		<td width="5%" height="25" align="center"><?=$s?></td>
        <td width="5%" height="25" align="center"><?=$a?></td>
        <td width="5%" height="25" align="center"><?=$c?></td>      
        <td width="*" bgcolor="#CCCCCC"></td>
    </tr>
	<!-- END TABLE CONTENT -->
    </table>	
<?php 	} ?>
	</td>
</tr>    
</table>
</body>
<?php if ($_REQUEST['lihat'] == 1) { ?>
<script language="javascript">
window.print();
</script>
<?php } ?>
</html>