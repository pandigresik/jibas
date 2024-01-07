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

//echo 'haloo';
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
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Laporan Refleksi Mengajar]</title>
</head>

<body>
<?=getHeader($row['departemen'])?>

<table border="0" cellpadding="5" width="100%" align="left">
<tr>
	<td align="left" valign="top" colspan="2"><center>
  <font size="4"><strong>LAPORAN REFLEKSI MENGAJAR</strong></font><br />
 </center><br /><br />
</td></tr>
<tr><td>
<table>
<tr>
	<td width="25%"><strong>Departemen</strong></td>
    <td><strong>: <?=$row['departemen']?></strong></td>
</tr>
<tr>
	<td><strong>Tahun Ajaran</strong></td>
    <td><strong>: <?=$row['tahunajaran']?></strong></td>
</tr>
<tr>
	<td><strong>Semester</strong></td>
    <td><strong>: <?=$row['semester']?></strong></td>
</tr>
<tr>
	<td><strong>Tingkat</strong></td>
    <td><strong>: <?php if ($tingkat == -1) echo "Semua Tingkat"; else echo $row['tingkat']; ?></strong></td>
</tr>
<tr>
	<td><strong>Kelas</strong></td>
    <td><strong>: <?php if ($kelas == -1) echo "Semua Kelas"; else echo $row['kelas']; ?></strong></td>
</tr>
<tr>
	<td><strong>Pelajaran</strong></td>
    <td><strong>: <?php if ($pelajaran == -1) echo "Semua Pelajaran"; else echo $row['nama']; ?></strong></td>
</tr>
<tr>
	<td><strong>Periode Presensi</strong></td>
    <td><strong>: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></strong></td>
</tr>
</table>
</td></tr><tr><td>
<?php 		
	OpenDb();
	$sql = "SELECT DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), p.jam, s.status, p.materi, p.objektif, p.refleksi, p.rencana, p.keterangan, p.replid, l.nama, k.kelas FROM presensipelajaran p, kelas k, pelajaran l, statusguru s, tingkat t WHERE p.idkelas = k.replid AND p.idpelajaran = l.replid AND p.gurupelajaran = '$nip' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.jenisguru = s.replid AND p.idsemester = '$semester' AND p.idkelas = k.replid AND k.idtingkat = t.replid $filter1 $filter2 $filter3 ORDER BY $urut $urutan";
	
	$result = QueryDb($sql);			 
	$jum_hadir = mysqli_num_rows($result);
	if ($jum_hadir > 0) { 
?>      
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
   	<tr height="30">
    	<td class="header" align="center" width="5%">No</td>
        <td class="header" align="center" width="10%">Tanggal</td>
		<td class="header" align="center" width="5%">Jam</td>
        <?php if ($kelas == -1) { ?>
        <td width="5%" height="30" align="center" class="header">Kelas</td>
		<?php } ?>
		<td class="header" align="center" width="20%">Status</td>
        <?php if ($pelajaran == -1) { ?>
        <td width="10%" height="30" align="center" class="header">Pelajaran</td>            
        <?php } ?>            
		<td class="header" align="center" width="60%">Refleksi</td>          
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { ?>
    <tr height="25">    	    	 			
		<td align="center" ><?=++$cnt?></td>
        <td align="center" ><?=$row[0].' '.$bulan[$row[1]].' '.substr((string) $row[2],2,2)?></td>
        <td align="center" ><?=substr((string) $row[3],0,5)?></td>
		<?php if ($kelas == -1) { ?>
        <td align="center"><?=$row[12]?></td>
        <?php } ?>
        <td><?=$row[4]?></td>        
		<?php if ($pelajaran == -1) { ?>
        <td><?=$row[11]?></td>
        <?php } ?>
        <td>
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
      	</td>                  	
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