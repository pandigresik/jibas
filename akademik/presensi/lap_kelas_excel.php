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
header('Content-Disposition: attachment; filename=LaporanPresensiPelajaranPerKelas.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$pelajaran = $_REQUEST['pelajaran'];
$kelas = $_REQUEST['kelas'];
$semester = $_REQUEST['semester'];
$tglawal = $_REQUEST['tglawal'];
$tglakhir = $_REQUEST['tglakhir'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

OpenDb();
if ($pelajaran == -1) {
	$filter = "";
} else { 
	$filter = "AND p.replid = '$pelajaran' ";
}
	
$sql = "SELECT p.departemen, p.nama, k.kelas, t.tahunajaran, s.semester FROM pelajaran p, kelas k, tahunajaran t, semester s WHERE  k.replid = '$kelas' AND k.idtahunajaran = t.replid AND s.replid = '$semester' $filter";   
$result = QueryDB($sql);

$row = mysqli_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Laporan Presensi Pelajaran Per Kelas]</title>
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
    <th scope="row" colspan="12"><span class="style1">Laporan Presensi Pelajaran Per Kelas</span></th>
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
	<td><span class="style4">Kelas</span></td>
    <td colspan="12"><span class="style4">: <?=$row['kelas']?></span></td>
</tr>
<tr>
	<td><span class="style4">Pelajaran</span></td>
    <td colspan="12"><span class="style4">: <?=$row['nama']?></span></td>
</tr>
<tr>
	<td><span class="style4">Periode Presensi</span></td>
    <td colspan="12"><span class="style4">: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></span></td>
</tr>
</table>
<br />
<?php 		
	OpenDb();
	if ($pelajaran == -1) {		
		$pel = "Semua Pelajaran";
		$sql = "SELECT DISTINCT s.nis, s.nama, s.telponsiswa, s.hpsiswa, s.namaayah, s.telponortu, s.hportu, s.aktif FROM siswa s, presensipelajaran p, ppsiswa pp, kelas k WHERE pp.idpp = p.replid AND pp.nis = s.nis AND s.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' ORDER BY $urut $urutan";
		
	} else {
		$pel = $row ['pelajaran'];
		$sql = "SELECT DISTINCT s.nis, s.nama, s.telponsiswa, s.hpsiswa, s.namaayah, s.telponortu, s.hportu, s.aktif FROM siswa s, presensipelajaran p, ppsiswa pp, kelas k WHERE pp.idpp = p.replid AND pp.nis = s.nis AND s.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idpelajaran = '$pelajaran' ORDER BY $urut $urutan"; 
	}	
	//echo $sql;
	$result = QueryDb($sql);			 
	$jum_hadir = mysqli_num_rows($result);
	if ($jum_hadir > 0) { 
?>

    <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left">
   	<tr height="30" align="center">
    	<td bgcolor="#CCCCCC" class="style6 style5 header">No</td>
		<td bgcolor="#CCCCCC" class="style6 style5 header">N I S</td>
		<td bgcolor="#CCCCCC" class="style6 style5 header">Nama</td>            
		<td bgcolor="#CCCCCC" class="style6 style5 header">Jml Hadir</td>
        <td bgcolor="#CCCCCC" class="style6 style5 header">Jml Tak Hadir</td>
        <td bgcolor="#CCCCCC" class="style6 style5 header">Jml Total</td>
        <td bgcolor="#CCCCCC" class="style6 style5 header">%</td>            
        <td bgcolor="#CCCCCC" class="style6 style5 header">Tlp Siswa</td>
        <td bgcolor="#CCCCCC" class="style6 style5 header">HP Siswa</td>
        <td bgcolor="#CCCCCC" class="style6 style5 header">Orang Tua</td>
        <td bgcolor="#CCCCCC" class="style6 style5 header">Tlp Ortu</td>
        <td bgcolor="#CCCCCC" class="style6 style5 header">HP Ortu</td>       
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { 
		$tanda = "";
		if ($row[7] == 0) 
			$tanda = "*";
	?>
    <tr height="25" valign="middle">    	
    	<td align="center" ><span class="style7"><?=++$cnt?></span></td>
		<td align="center"><?=$row[0]?><span class="style7"><?=$tanda?></span></td>
        <td><span class="style7"><?=$row[1]?></span></td>
  		<td align="center"><span class="style7">
		<?php  if ($pelajaran == -1) {		
				$sql1 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir = 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' " ;	
			} else {
				$sql1 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir = 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idpelajaran = '$pelajaran'" ;	
			}
		
				//echo $sql1;			
				$result1 = QueryDb($sql1);
				$row1 = @mysqli_fetch_array($result1);
				$hadir = $row1[0];
				echo $row1[0]; 	?></span></td>
        <td align="center"><span class="style7">
		<?php 	if ($pelajaran == -1) {		
				$sql2 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir <> 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir'  " ;
			} else {
				$sql2 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir <> 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idpelajaran = '$pelajaran'" ;					
			}
				$result2 = QueryDb($sql2);
				$row2 = @mysqli_fetch_array($result2);
				$absen = $row2[0];
				echo $row2[0]; ?></span></td>
        <td align="center"><span class="style7">
			<?php 	$tot = $hadir + $absen;
				echo $tot;	?></span></td>
        <td align="center"><span class="style7">
			<?php 	if ($tot == 0) 
					$tot = 1;
				$prs = (( $hadir/$tot)*100); 
				echo round($prs,2).'%'; ?></span></td>
        <td><span class="style7"><?=$row[2]?></span></td>
        <td><span class="style7"><?=$row[3]?></span></td>    
        <td><span class="style7"><?=$row[4]?></span></td>
        <td><span class="style7"><?=$row[5]?></span></td>    
        <td><span class="style7"><?=$row[6]?></span></td>   
    </tr>
<?php } 
	CloseDb() ?>	
    <!-- END TABLE CONTENT -->
    </table>
<?php 	} ?>
	
	</td>
</tr>
<tr>
	<td><?php 	if ($row[7] == 0) 
			$tanda = "*";
			echo "Ket: *Status siswa tidak aktif lagi";
    	?>
    </td>
</tr> 
</table>
</body>
<script language="javascript">
window.print();
</script>
</html>