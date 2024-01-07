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
$semester = $_REQUEST['semester'];
$tglawal = $_REQUEST['tglawal'];
$tglakhir = $_REQUEST['tglakhir'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

OpenDb();
if ($pelajaran == -1) {
	$filter = "";
} else { 
	$filter = "AND p.replid = $pelajaran ";
}
	
$sql = "SELECT p.departemen, p.nama, k.kelas, t.tahunajaran, s.semester, g.tingkat FROM pelajaran p, kelas k, tahunajaran t, semester s, tingkat g WHERE  k.replid = '$kelas' AND k.idtahunajaran = t.replid AND s.replid = '$semester' AND k.idtingkat = g.replid $filter";   
$result = QueryDB($sql);

$row = mysqli_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOGURU [Cetak Laporan Presensi Pelajaran Per Kelas]</title>
</head>

<body>

<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?=getHeader($row['departemen'])?>
	
<center>
  <font size="4"><strong>LAPORAN PRESENSI PELAJARAN PER KELAS</strong></font><br />
 </center><br /><br />
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
	<td><strong>Kelas</strong></td>
    <td><strong>: <?=$row['tingkat'].' - '.$row['kelas']?></strong></td>
</tr>
<tr>
	<td><strong>Pelajaran</strong></td>
    <td><strong>: <?=$row['nama']?></strong></td>
</tr>
<tr>
	<td><strong>Periode Presensi</strong></td>
    <td><strong>: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></strong></td>
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

    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
   	<tr height="30">
    	<td class="header" align="center" width="5%">No</td>
		<td class="header" align="center" width="8%">N I S</td>
		<td class="header" align="center" width="15%">Nama</td>            
		<td class="header" align="center" width="5%">Jml Hadir</td>
        <td class="header" align="center" width="8%">Jml Tak Hadir</td>
        <td class="header" align="center" width="5%">Jml Total</td>
        <td class="header" align="center" width="7%">%</td>            
        <td class="header" align="center" width="7%">Tlp Siswa</td>
        <td class="header" align="center" width="10%">HP Siswa</td>
        <td class="header" align="center" width="13%">Orang Tua</td>
        <td class="header" align="center" width="7%">Tlp Ortu</td>
        <td class="header" align="center" width="10%">HP Ortu</td>       
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { 
		$tanda = "";
		if ($row[7] == 0) 
			$tanda = "*";
	?>
    <tr height="25">    	
    	<td align="center"><?=++$cnt?></td>
		<td align="center"><?=$row[0]?><?=$tanda?></td>
        <td><?=$row[1]?></td>
  		<td align="center">
		<?php  if ($pelajaran == -1) {		
				$sql1 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir = 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' " ;	
			} else {
				$sql1 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir = 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idpelajaran = '$pelajaran'" ;	
			}
		
				//echo $sql1;			
				$result1 = QueryDb($sql1);
				$row1 = @mysqli_fetch_array($result1);
				$hadir = $row1[0];
				echo $row1[0]; 	?></td>
        <td align="center">
		<?php 	if ($pelajaran == -1) {		
				$sql2 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir <> 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir'  " ;
			} else {
				$sql2 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir <> 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idpelajaran = '$pelajaran'" ;					
			}
				$result2 = QueryDb($sql2);
				$row2 = @mysqli_fetch_array($result2);
				$absen = $row2[0];
				echo $row2[0]; ?></td>
        <td align="center">
			<?php 	$tot = $hadir + $absen;
				echo $tot;	?></td>
        <td align="center">
			<?php 	if ($tot == 0) 
					$tot = 1;
				$prs = (( $hadir/$tot)*100); 
				echo round($prs,2).'%'; ?></td>
        <td><?=$row[2]?></td>
        <td><?=$row[3]?></td>    
        <td><?=$row[4]?></td>
        <td><?=$row[5]?></td>    
        <td><?=$row[6]?></td>   
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