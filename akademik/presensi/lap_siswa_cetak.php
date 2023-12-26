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
$pelajaran=$_REQUEST['pelajaran'];
$tglawal = $_REQUEST['tglawal'];
$tglakhir = $_REQUEST['tglakhir'];
$urut = $_REQUEST['urut'];	
$urutan = $_REQUEST['urutan'];
$urut1 = $_REQUEST['urut1'];	
$urutan1 = $_REQUEST['urutan1'];

OpenDb();
$sql = "SELECT departemen FROM tahunajaran t, kelas k, siswa s WHERE s.nis='$nis' AND s.idkelas=k.replid AND k.idtahunajaran=t.replid";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$departemen = $row[0];

$sql = "SELECT nama FROM siswa WHERE nis='$nis'";   
$result = QueryDB($sql);	
$row = mysqli_fetch_array($result);
//if ($pelajaran=='-1') {
	$filter="";
//} else {
//	$filter=" AND l.replid='$pelajaran'";
//}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Laporan Presensi Siswa]</title>
</head>

<body>

<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?=getHeader($departemen)?>

<center>
  <font size="4"><strong>LAPORAN PRESENSI PELAJARAN SISWA</strong></font><br />
 </center><br /><br />
<table>
<tr>
	<td><strong>Siswa</strong></td>
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
	$sql = "SELECT k.kelas, DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), p.jam, pp.catatan, l.nama, g.nama, p.materi, pp.replid FROM  presensipelajaran p, ppsiswa pp, jbssdm.pegawai g, kelas k, pelajaran l WHERE  pp.idpp = p.replid AND p.idkelas = k.replid AND p.idpelajaran = l.replid AND p.gurupelajaran = g.nip AND pp.nis = '$nis' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND pp.statushadir = 0 $filter ORDER BY $urut $urutan" ;
	$result = QueryDb($sql);			 
	$jum_hadir = mysqli_num_rows($result);
	
	$sql1 = "SELECT k.kelas, DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), p.jam, pp.catatan, l.nama, g.nama, p.materi, pp.replid FROM presensipelajaran p, ppsiswa pp, jbssdm.pegawai g, kelas k, pelajaran l WHERE  pp.idpp = p.replid AND p.idkelas = k.replid AND p.idpelajaran = l.replid AND p.gurupelajaran = g.nip AND pp.nis = '$nis' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND pp.statushadir <> 0 $filter ORDER BY $urut1 $urutan1" ;
	$result1 = QueryDb($sql1);			 
	$jum_absen = mysqli_num_rows($result1);

	if ($jum_hadir > 0) { 
	?>
	
    <fieldset>
        <legend><strong>Data Kehadiran</strong></legend>
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
   	<tr>		
    	<td width="5%" height="30" align="center" class="header">No</td>      	
      	<td width="5%" height="30" align="center" class="header">Tanggal</td>            
      	<td width="5%" height="30" align="center" class="header">Jam</td>        
        <td width="5%" height="30" align="center" class="header">Kelas</td>
      	<td width="*" height="30" align="center" class="header">Catatan</td>
      	<td width="15%" height="30" align="center" class="header">Pelajaran</td>
      	<td width="15%" height="30" align="center" class="header">Guru</td>
      	<td width="25%" height="30" align="center" class="header">Materi</td>       
    </tr>
	<?php 
    $cnt = 1;
    while ($row = @mysqli_fetch_row($result)) {					
    ?>	
    <tr>        			
        <td height="25" align="center"><?=$cnt?></td>      	
      	<td height="25" align="center"><?=$row[1].'-'.$row[2].'-'.substr((string) $row[3],2,2)?></td>
      	<td height="25" align="center"><?=substr((string) $row[4],0,5)?></td>
        <td height="25" align="center"><?=$row[0]?></td>        
      	<td height="25"><?=$row[5]?></td>
      	<td height="25"><?=$row[6]?></td>
      	<td height="25"><?=$row[7]?></td>
      	<td height="25"><?=$row[8]?></td>    
    </tr>
<?php 	$cnt++;
    } 
    CloseDb();	?>
    </table>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>	
    </fieldset>
    
<?php 	} 
	if ($jum_absen > 0) { 
	?>
   	<br />
    <fieldset>
        <legend><strong>Data Ketidakhadiran</strong></legend>
    
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center">
    <tr>		
		<td width="5%" height="30" align="center" class="header">No</td>
      	<td width="5%" height="30" align="center" class="header">Tanggal</td>            
      	<td width="5%" height="30" align="center" class="header">Jam</td>
        <td width="5%" height="30" align="center" class="header">Kelas</td>
      	<td width="*" height="30" align="center" class="header">Catatan</td>
      	<td width="15%" height="30" align="center" class="header">Pelajaran</td>
      	<td width="15%" height="30" align="center" class="header">Guru</td>
      	<td width="25%" height="30" align="center" class="header">Materi</td>      	
    </tr>
	<?php 
    $cnt = 1;
    while ($row1 = @mysqli_fetch_row($result1)) {					
    ?>	
    <tr>        			
        <td height="25" align="center"><?=$cnt?></td>        
        <td height="25" align="center"><?=$row1[1].'-'.$row1[2].'-'.substr((string) $row1[3],2,2)?></td>
        <td height="25" align="center"><?=substr((string) $row1[4],0,5)?></td>
        <td height="25" align="center"><?=$row1[0]?></td>
        <td height="25"><?=$row1[5]?></td>
        <td height="25"><?=$row1[6]?></td>
        <td height="25"><?=$row1[7]?></td>
        <td height="25"><?=$row1[8]?></td>        
    </tr>
<?php 	$cnt++;
    } 
    CloseDb();	?>
	  </table>	 

	</fieldset>
<?php 	} ?>    
	<br />
    <table width="100%" border="0" align="center">
    <tr>
        <td width="21%"><b>Jumlah Kehadiran</b></td>
        <td><b>: <?=$jum_hadir ?></b></td>
    </tr>
    <tr>
        <td><b>Jumlah Ketidakhadiran</b></td>
        <td><b>: <?=$jum_absen ?></b></td>
    </tr>
    <tr>
        <td><b>Jumlah Seharusnya</b></td>
        <td><b>: <?php $total = $jum_hadir+$jum_absen;
                echo $total ?></b></td>
    </tr>
    <tr>
        <td><b>Presentase Kehadiran</b></td>
        <td><b>: <?php 	if ($total == 0) 
                    $total = 1;
                $prs = (( $jum_hadir/$total)*100) ;
                echo (int)$prs ?>%</b></td>
    </tr>
	</table>

</td></tr>
</table>    
</body>
<?php if ($_REQUEST['lihat'] == 1) { ?>
<script language="javascript">
window.print();
</script>
<?php } ?> 
</html>