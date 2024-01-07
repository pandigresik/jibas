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
header('Content-Disposition: attachment; filename=LaporanRefleksiMengajar.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$pelajaran = $_REQUEST['pelajaran'];
$kelas = $_REQUEST['kelas'];
$tingkat = $_REQUEST['tingkat'];
$departemen = $_REQUEST['departemen'];
$semester = $_REQUEST['semester'];
$nip = $_REQUEST['nip'];
$tglawal = $_REQUEST['tglawal'];
$tglakhir = $_REQUEST['tglakhir'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

$filter1 = "AND t.departemen = '".$departemen."'";
if ($tingkat <> -1) 
	$filter1 = "AND k.idtingkat = '".$tingkat."'";

$filter2 = "";
if ($kelas <> -1) 
	$filter2 = "AND k.replid = '".$kelas."'";

$filter3 = "";
if ($pelajaran <> -1) 
	$filter3 = "AND p.idpelajaran = '".$pelajaran."'";

OpenDb();

//$sql = "SELECT l.nama,l.departemen,k.kelas,g.nama FROM pelajaran l, presensipelajaran p, kelas k, jbssdm.pegawai g WHERE p.idpelajaran = l.replid AND l.replid = $pelajaran AND p.gurupelajaran = '$nip' AND p.gurupelajaran = g.nip AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idkelas = k.replid AND p.idkelas = $kelas";

$sql = "SELECT t.departemen, a.tahunajaran, s.semester, k.kelas, t.tingkat, l.nama 
          FROM tahunajaran a, kelas k, tingkat t, semester s, presensipelajaran p, pelajaran l 
         WHERE p.idkelas = k.replid AND k.idtingkat = t.replid AND k.idtahunajaran = a.replid AND p.idsemester = s.replid 
           AND s.replid = '$semester' AND a.aktif = 1 AND p.idpelajaran = l.replid  $filter1 $filter2 $filter3";

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
    <th scope="row" colspan="7"><span class="style1">Laporan Refleksi Mengajar</span></th>
  </tr>
</table>
<br />
<table width="27%">
<tr>
	<td width="43%"><span class="style4">Departemen</span></td>
   	<td width="57%" colspan="7"><span class="style4">: <?=$row['departemen']?></span></td>
</tr>
<tr>
	<td><span class="style4">Tahun Ajaran</strong></td>
    <td colspan="7"><span class="style4">: <?=$row['tahunajaran']?></strong></td>
</tr>
<tr>
	<td><span class="style4">Semester</strong></td>
    <td colspan="7"><span class="style4">: <?=$row['semester']?></strong></td>
</tr>
<tr>
	<td><span class="style4">Tingkat</strong></td>
    <td colspan="7"><span class="style4">: <?php if ($tingkat == -1) echo "Semua Tingkat"; else echo $row['tingkat']; ?></strong></td>
</tr>
<tr>
	<td><span class="style4">Kelas</strong></td>
    <td colspan="7"><span class="style4">: <?php if ($kelas == -1) echo "Semua Kelas"; else echo $row['kelas']; ?></strong></td>
</tr>
<tr>
	<td><span class="style4">Pelajaran</strong></td>
    <td colspan="7"><span class="style4">: <?php if ($pelajaran == -1) echo "Semua Pelajaran"; else echo $row['nama']; ?></strong></td>
</tr>
<tr>
	<td><span class="style4">Periode Presensi</strong></td>
    <td colspan="7"><span class="style4">: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></strong></td>
</tr>
</table>
<br />
<?php 		
	OpenDb();
	$sql = "SELECT DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), p.jam, s.status, p.materi, p.objektif, p.refleksi, p.rencana, p.keterangan, p.replid, l.nama, k.kelas FROM presensipelajaran p, kelas k, pelajaran l, statusguru s, tingkat t WHERE p.idkelas = k.replid AND p.idpelajaran = l.replid AND p.gurupelajaran = '$nip' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.jenisguru = s.replid AND p.idsemester = '$semester' AND p.idkelas = k.replid AND k.idtingkat = t.replid $filter1 $filter2 $filter3 ORDER BY $urut $urutan";
	
	$result = QueryDb($sql);			 
	$jum_hadir = mysqli_num_rows($result);
	if ($jum_hadir > 0) { 
?>      
    <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left">
   	<tr height="30" align="center" bgcolor="#CCCCCC" class="style6 style5 header">
    	<td width="5%">No</td>
        <td width="10%">Tanggal</td>
		<td width="5%">Jam</td>
        <?php if ($kelas == -1) { ?>
        <td width="5%">Kelas</td>
		<?php } ?>
		<td width="20%">Status</td>
        <?php if ($pelajaran == -1) { ?>
        <td width="10%">Pelajaran</td>            
        <?php } ?>            
		<td width="60%">Refleksi</td>          
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { ?>
    <tr height="25" valign="middle">    	    	 			
		<td align="center" ><span class="style7"><?=++$cnt?></span></td>
        <td align="center" ><span class="style7"><?=$row[0].' '.$bulan[$row[1]].' '.substr((string) $row[2],2,2)?></span></td>
        <td align="center" ><span class="style7"><?=substr((string) $row[3],0,5)?></span></td>
		<?php if ($kelas == -1) { ?>
        <td align="center"><span class="style7"><?=$row[12]?></span></td>
        <?php } ?>
        <td><span class="style7"><?=$row[4]?></span></td>        
		<?php if ($pelajaran == -1) { ?>
        <td><span class="style7"><?=$row[11]?></span></td>
        <?php } ?>
        <td valign="middle"><span class="style7">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="10%">Materi</td>
                <td>:&nbsp;</td>
                <td width="90%"><?=$row[5]?> </td>  
            </tr>                
            <tr>
                <td valign="top">Rencana</td>
                <td valign="top">:&nbsp;</td> 
              	<td><?=$row[8]?></td>
            </tr>
            <tr>
                <td>Keterangan Kehadiran</td>
              	<td valign="top">:&nbsp;</td>
                <td valign="top"><?=$row[9]?></td>
            </tr>
            </table>    
      	</span></td>                  	
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