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
$kelas = $_REQUEST['kelas'];
$nip = $_REQUEST['nip'];
$pelajaran = $_REQUEST['pelajaran'];

OpenDb();
$sql = "SELECT k.kelas AS namakelas, s.semester AS namasemester, a.tahunajaran, a.departemen, 
			   l.nama, t.tingkat, p.nama AS guru, s.departemen as dep 
		  FROM kelas k, semester s, tahunajaran a, pelajaran l, tingkat t, jbssdm.pegawai p 
		 WHERE k.replid = $kelas AND s.replid = $semester AND  k.idtahunajaran = a.replid AND l.replid = $pelajaran 
		   AND t.replid = k.idtingkat AND p.nip = '".$nip."'";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Form Pengisian Komentar Rapor Siswa]</title>
</head>

<body topmargin="0" leftmargin="0">
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">
<?=getHeader($row['dep'])?>
<center>
  <font size="4"><strong>FORM PENGISIAN KOMENTAR RAPOR SISWA</strong></font><br />
 </center><br /><br />

<br />
<table>
<tr>
  	<td><strong>Departemen</strong></td>
  	<td width="30%"><strong>:&nbsp;<?=$row['departemen'] ?></strong></td>
    <td rowspan="2" valign="top"><strong>Tanggal</strong></td>  	
  	<td rowspan="2" valign="top"><strong>:&nbsp;_________________</strong></td>
</tr>
<tr>
    <td><strong>Tahun Ajaran</strong></td>
  	<td><strong>:&nbsp;<?=$row['tahunajaran']?></strong>
    </td>
</tr>
<tr>
    <td><strong>Semester</strong></td>
  	<td><strong>:&nbsp;<?=$row['namasemester']?></strong></td>
    <td rowspan="2" valign="top"><strong>Jenis Pengujian</strong></td>  
  	<td rowspan="2" valign="top"><strong>:&nbsp;_______________________________________</strong></td>
</tr>
<tr>
    <td><strong>Kelas</strong></td>
  	<td><strong>:&nbsp;<?=$row['tingkat'] ?>&nbsp;-&nbsp;<?=$row['namakelas']?></strong></td>
</tr>
<tr>
    <td><strong>Pelajaran</strong></td>
  	<td><strong>:&nbsp;<?=$row['nama']?></strong></td>
    <td valign="top" rowspan="2"><strong>Keterangan</strong></td>
   	<td valign="top" rowspan="2"><strong>:&nbsp;_______________________________________</strong></td>		
</tr>
</table>
<br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left">
  	<tr height="30" class="header" align="center">
        <td width="4%">No</td>
        <td width="12%">N I S</td>
        <td width="20%">Nama</td>
        <td width="*">Komentar</td>
  	</tr>
<?php  $sql_get_siswa="SELECT nis,nama,aktif
					  FROM jbsakad.siswa
					 WHERE idkelas='$kelas'
					   AND aktif = 1
					   AND alumni = 0
					 ORDER BY nama";
	$result_get_siswa=QueryDb($sql_get_siswa);
 	$cnt=1;
  	while ($row_siswa=@mysqli_fetch_array($result_get_siswa))
	{
 		$tanda = "";
        if ($row_siswa['aktif'] == 0) 
            $tanda = "*"; ?>
    <tr height="70">
        <td align="center"><?=$cnt?></td>
        <td align="center"><?=$row_siswa['nis']?><?=$tanda?></td>
        <td><?=$row_siswa['nama']?></td>
        <td>&nbsp;</td>
    </tr>
  <?php $cnt++;
  	}
  ?>
	</table>
	</td>
</tr>
<tr>
	<td><?="Ket: *Status siswa tidak aktif lagi"; ?></td>
</tr>
<tr>
	<td>
	<table width="100%" border="0">
        <tr>
            <td width="80%" align="left"></td>
            <td width="20%" align="center"><br><br>Guru</td>
        </tr>
        <tr>
            <td colspan="2" align="right">&nbsp;<br /><br /><br /><br /><br /></td>
        </tr>
        <tr>		
            <td></td>
            <td valign="bottom" align="center"><strong><?=$row['guru']?></strong>
            <br /><hr />
            <strong>NIP. <?=$nip?></strong>
        </tr>
    </table>
    </td>
</tr>
</table>  
</body>
<?php
CloseDb();
?>
<script language="javascript">
window.print();
</script>
</html>
