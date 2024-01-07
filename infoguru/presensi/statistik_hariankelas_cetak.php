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

$semester = $_REQUEST['semester'];
$tingkat = $_REQUEST['tingkat'];
$tglawal = $_REQUEST['tglawal'];
$tglakhir = $_REQUEST['tglakhir'];

OpenDb();
$sql = "SELECT t.departemen, a.tahunajaran, s.semester, t.tingkat 
          FROM tahunajaran a, kelas k, tingkat t, semester s, presensiharian p 
         WHERE p.idkelas = k.replid AND k.idtingkat = t.replid AND k.idtahunajaran = a.replid AND a.aktif = 1  
           AND p.idsemester = s.replid AND s.replid = '$semester' AND t.replid = '".$tingkat."'";

$result = QueryDB($sql);	
$row = mysqli_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOGURU [Cetak Statistik Kehadiran Harian Setiap Kelas]</title>
</head>

<body>

<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?=getHeader($row['departemen'])?>
	
<center>
  <font size="4"><strong>STATISTIK KEHADIRAN HARIAN SETIAP SISWA</strong></font><br />
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
	<td><strong>Semester</strong></td>
    <td><strong>: <?=$row['semester']?></strong></td>
</tr>
<tr>
	<td><strong>Tingkat</strong></td>
    <td><strong>: <?=$row['tingkat'] ?></strong></td>
</tr>
<tr>
	<td><strong>Periode Presensi</strong></td>
    <td><strong>: <?=format_tgl($tglawal).' s/d '. format_tgl($tglakhir) ?></strong></td>
</tr>
</table>
<br />
<?php 	OpenDb();
	$sql = "SELECT DISTINCT k.kelas, k.replid FROM presensiharian p, kelas k WHERE p.idkelas = k.replid AND k.idtingkat = '$tingkat' AND p.idsemester = '$semester' AND ((p.tanggal1 BETWEEN '$tglawal' AND '$tglakhir') OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) ORDER BY k.kelas, p.tanggal1 ";	
	
	$result = QueryDb($sql);
	$jum = mysqli_num_rows($result);
	if ($jum > 0) { 
?>
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
   	<tr height="30" align="center">
    	<td width="5%" class="header">No</td>
		<td width="10%" class="header">Kelas</td>
        <td width="*" class="header"></td>
    </tr>
<?php 	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) { ?>
    <tr height="25">    	
    	<td align="center"><?=++$cnt?></td>
        <td align="center"><?=$row[0]?></td>
         <td align="center"><img src="statistik_harianbatang.php?semester=<?=$semester?>&kelas=<?=$row[1]?>&tglawal=<?=$tglawal?>&tglakhir=<?=$tglakhir?>" />
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