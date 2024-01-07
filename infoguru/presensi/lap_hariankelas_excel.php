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
header('Content-Disposition: attachment; filename=Laporan_Harian_Kelas.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');


$semester = $_REQUEST['semester'];
$kelas = $_REQUEST['kelas'];
$tglawal = $_REQUEST['tglawal'];
$tglakhir = $_REQUEST['tglakhir'];
$urut = $_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];

OpenDb();
$sql = "SELECT t.departemen, a.tahunajaran, s.semester, k.kelas, t.tingkat FROM tahunajaran a, kelas k, tingkat t, semester s, presensiharian p WHERE p.idkelas = k.replid AND k.idtingkat = t.replid AND k.idtahunajaran = a.replid AND p.idsemester = s.replid AND s.replid = '$semester' AND k.replid = '".$kelas."'";  
$result = QueryDB($sql);	
$row = mysqli_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Laporan Presensi Harian Siswa Per Kelas]</title>
<style type="text/css">
<!--
.style4 {font-family: Verdana}
.style5 {font-size: 14px}
.style7 {font-family: Verdana; font-weight: bold; font-size: 12px; }
.style9 {
	font-family: Verdana;
	font-size: 16px;
	font-weight: bold;
}
-->
</style>
</head>

<body>

<table width="100%" border="0" cellspacing="0">
  <tr>
    <th scope="row" colspan="8"><span class="style9">Laporan Presensi Harian Setiap Kelas</span></th>
  </tr>
</table>
<br />
<table width="16%">
<tr>
	<td width="83%" colspan="2"><span class="style7">Departemen</span></td>
    <td width="17%" colspan="4"><span class="style7">: 
      <?=$row['departemen']?>
    </span></td>
</tr>
<tr>
	<td colspan="2"><span class="style7">Tahun Ajaran</span></td>
    <td colspan="4"><span class="style7">: 
      <?=$row['tahunajaran']?>
    </span></td>
</tr>
<tr>
	<td colspan="2"><span class="style7">Semester</span></td>
    <td colspan="4"><span class="style7">: 
      <?=$row['semester']?>
    </span></td>
</tr>
<!--<tr>
	<td><strong>Tingkat</strong></td>
    <td><strong>: <?=$row['tingkat']?></strong></td>
</tr>-->
<tr>
	<td colspan="2"><span class="style7">Kelas</span></td>
    <td colspan="4"><span class="style7">: 
      <?=$row['tingkat'].' - '.$row['kelas']?>
    </span></td>
</tr>
<tr>
	<td colspan="2"><span class="style7">Periode Presensi</span></td>
    <td colspan="4"><span class="style7">: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></span></td>
</tr>
</table>
<br />
<?php 	OpenDb();
	$sql = "SELECT s.nis, s.nama, SUM(ph.hadir) as hadir, SUM(ph.ijin) as ijin, SUM(ph.sakit) as sakit, SUM(ph.alpa) as alpa, SUM(ph.cuti) as cuti, s.idkelas, s.aktif FROM siswa s LEFT JOIN (phsiswa ph INNER JOIN presensiharian p ON p.replid = ph.idpresensi) ON ph.nis = s.nis WHERE s.idkelas = '$kelas' AND p.idsemester = '$semester' AND (((p.tanggal1 BETWEEN '$tglawal' AND '$tglakhir') OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) OR (('$tglawal' BETWEEN p.tanggal1 AND p.tanggal2) OR ('$tglakhir' BETWEEN p.tanggal1 AND p.tanggal2))) GROUP BY s.nis ORDER BY $urut $urutan";
	
	$result = QueryDb($sql);
	$jum = mysqli_num_rows($result);
	if ($jum > 0) { 
?>
	<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left">
   	<tr height="30" align="center">
    	<td width="5%" bgcolor="#CCCCCC" class="style5 style4 header"><strong>No</strong></td>
		<td width="15%" bgcolor="#CCCCCC" class="style5 style4 header"><strong>N I S</strong></td>
        <td width="*" bgcolor="#CCCCCC" class="style5 style4 header"><strong>Nama</strong></td>
   		<td width="5%" bgcolor="#CCCCCC" class="style5 style4 header"><strong>Hadir</strong></td>
		<td width="5%" bgcolor="#CCCCCC" class="style5 style4 header"><strong>Ijin</strong></td>            
		<td width="5%" bgcolor="#CCCCCC" class="style5 style4 header"><strong>Sakit</strong></td>
        <td width="5%" bgcolor="#CCCCCC" class="style5 style4 header"><strong>Alpa</strong></td>     
        <td width="5%" bgcolor="#CCCCCC" class="style5 style4 header"><strong>Cuti</strong></td>
        
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { 
	   
    if ($row[8] == 0) { 
		$tanda = "*";
	?>
	<tr height="25" style="color:#FF0000">
	<?php } else { 
		$tanda = "";
	?>
    <tr height="25">
    <?php } ?>        	
    
    	<td align="center"><?=++$cnt?></td>
        <td align="center"><?=$row[0]?><?=$tanda?></td>
        <td><?=$row[1]?></td>
        <td align="center"><?=$row[2]?></td>
        <td align="center"><?=$row[3]?></td>    
        <td align="center"><?=$row[4]?></td>
        <td align="center"><?=$row[5]?></td>    
        <td align="center"><?=$row[6]?></td>
    </tr>
<?php } 
	CloseDb() ?>	
    <!-- END TABLE CONTENT -->
</table>	
<?php 	} ?>
<?php 	if ($row[8] == 0) 
			$tanda = "*";
			echo "Ket: *Status siswa tidak aktif lagi";
    	?>
</body>
<script language="javascript">
window.print();
</script>

</html>