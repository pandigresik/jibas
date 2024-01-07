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
header('Content-Disposition: attachment; filename=LaporanDataSiswaYangTidakHadir.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$semester = $_REQUEST['semester'];
$tingkat = $_REQUEST['tingkat'];
$kelas = $_REQUEST['kelas'];
$departemen = $_REQUEST['departemen'];
$tglawal = $_REQUEST['tglawal'];
$tglakhir = $_REQUEST['tglakhir'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

OpenDb();

$filter1 = "AND t.departemen = '".$departemen."'";
if ($tingkat <> -1) 
	$filter1 = "AND k.idtingkat = $tingkat";

$filter2 = "";
if ($kelas <> -1) 
	$filter2 = "AND k.replid = $kelas";
	
OpenDb();
$sql = "SELECT t.departemen, a.tahunajaran, s.semester, k.kelas, t.tingkat FROM tahunajaran a, kelas k, tingkat t, semester s, presensiharian p WHERE p.idkelas = k.replid AND k.idtingkat = t.replid AND k.idtahunajaran = a.replid AND p.idsemester = s.replid AND s.replid = $semester $filter1 $filter2";  

$result = QueryDB($sql);	
$row = mysqli_fetch_array($result);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Laporan  Siswa Tidak Hadir]</title>
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
    <th scope="row" colspan="12"><span class="style1">Laporan Data Siswa yang Tidak Hadir</span></th>
  </tr>
</table>
<br />
<table width="27%">
<tr>
	<td width="43%"><span class="style4">Departemen</span></td>
    <td width="57%" colspan="12"><span class="style4">: <?=$row['departemen']?></span></td>
</tr>
<tr>
	<td><span class="style4">Tahun Ajaran</span></td>
    <td colspan="12"><span class="style4">: <?=$row['tahunajaran']?></span></td>
</tr>
<tr>
	<td><span class="style4">Semester</span></td>
    <td colspan="12"><span class="style4">: <?=$row['semester']?></span></td>
</tr>
<tr>
	<td><span class="style4">Tingkat</span></td>
    <td colspan="12"><span class="style4">: <?php if ($tingkat == -1) echo "Semua Tingkat"; else echo $row['tingkat']; ?></span></td>
</tr>
<tr>
	<td><span class="style4">Kelas</span></td>
    <td colspan="12"><span class="style4">: <?php if ($kelas == -1) echo "Semua Kelas"; else echo $row['kelas']; ?></span></td>
</tr>
<tr>
	<td><span class="style4">Periode Presensi</span></td>
    <td colspan="12"><span class="style4">: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></span></td>
</tr>
</table>
<br />
<?php 		
	$sql = "SELECT DISTINCT s.nis, s.nama, l.nama, DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), pp.statushadir, pp.catatan, s.telponsiswa, s.hpsiswa, s.namaayah, s.telponortu, s.hportu, k.kelas FROM siswa s, presensipelajaran p, ppsiswa pp, pelajaran l, kelas k, tingkat t WHERE pp.idpp = p.replid AND s.idkelas = k.replid AND p.idsemester = $semester AND pp.nis = s.nis AND k.idtingkat = t.replid $filter1 $filter2 AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idpelajaran = l.replid AND pp.statushadir <> 0 ORDER BY $urut $urutan";
	//echo $sql;
	$result = QueryDb($sql);			 
	$jum_hadir = mysqli_num_rows($result);
	if ($jum_hadir > 0) { 
	
?>      

    <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left">
   	<tr height="30" align="center" bgcolor="#CCCCCC" class="style6 style5 header">
    	<td width="5%">No</td>
		<td width="8%">N I S</td>
		<td width="15%">Nama</td>  
        <td width="8%">Kelas</td>            
		<td width="10%">Pelajaran</td>
        <td width="5%">Tanggal</td>
        <td>Presensi</td>
        <td width="10%">Keterangan</td>            
        <td width="7%">Tlp Siswa</td>
        <td width="10%">HP Siswa</td>
        <td width="15%">Orang Tua</td>
        <td width="7%">Tlp Ortu</td>
        <td width="10%">HP Ortu</td>     
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { 
		switch ($row[6]){
			case 1 : $st="Ijin";
			break;
			case 2 : $st="Sakit";
			break;	
			case 3 : $st="Alpa";
			break;
			case 4 : $st="Cuti";
			break;
		}	
?>
    <tr height="25" valign="middle">    	
    	<td align="center"><span class="style7"><?=++$cnt?></span></td>
		<td align="center"><span class="style7"><?=$row[0]?></span></td>
        <td><span class="style7"><?=$row[1]?></span></td>
        <?php if ($kelas == -1) { ?>
        <td align="center"><span class="style7"><?=$row[13]?></span></td>
        <?php } ?>
        <td><span class="style7"><?=$row[2]?></span></td>
        <td align="center"><span class="style7"><?=$row[3].'-'.$row[4].'-'.substr((string) $row[5],2,2)?></span></td>
        <td align="center"><span class="style7"><?=$st ?></span></td>
        <td><span class="style7"><?=$row[7] ?></span></td>
        <td><span class="style7"><?=$row[8]?></span></td>
        <td><span class="style7"><?=$row[9]?></span></td>
        <td><span class="style7"><?=$row[10]?></span></td>
        <td><span class="style7"><?=$row[11]?></span></td>
        <td><span class="style7"><?=$row[12]?></span></td>     
    </tr>
<?php } 
	CloseDb() ?>	
    <!-- END TABLE CONTENT -->
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