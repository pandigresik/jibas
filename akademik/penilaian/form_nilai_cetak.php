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
$departemen = $_REQUEST['departemen'];
$tahunajaran = $_REQUEST['tahunajaran'];
$semester = $_REQUEST['semester'];
$kelas = $_REQUEST['kelas'];
$pelajaran = $_REQUEST['pelajaran'];
$nip = $_REQUEST['nip'];

OpenDb();
$sql = "SELECT k.kelas, s.semester, a.tahunajaran, t.tingkat, l.nama,
			   p.nama AS guru FROM kelas k, semester s, tahunajaran a,
			   tingkat t, pelajaran l, jbssdm.pegawai p
		 WHERE k.replid = $kelas
		   AND s.replid = $semester
		   AND a.replid = $tahunajaran
		   AND k.idtahunajaran = a.replid
		   AND k.idtingkat = t.replid
		   AND l.replid = $pelajaran
		   AND p.nip = '".$nip."'";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$namakelas = $row['kelas'];
$namasemester = $row['semester'];
$namatahunajaran = $row['tahunajaran'];
$namatingkat = $row['tingkat'];
$namapelajaran = $row['nama'];
$namaguru = $row['guru'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Form Pengisian Nilai Siswa]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">
<?=getHeader($departemen)?>
<center>
  <font size="4"><strong>FORM PENGISIAN NILAI SISWA </strong></font><br />
 </center><br /><br />

<br />

<table>
<tr>
  	<td><strong>Departemen</strong></td>
  	<td width="30%"><strong>:&nbsp;<?=$departemen ?></strong></td>
    <td rowspan="2" valign="top"><strong>Tanggal</strong></td>  	
  	<td rowspan="2" valign="top"><strong>:&nbsp;_________________</strong></td>
</tr>
<tr>
    <td><strong>Tahun Ajaran</strong></td>
  	<td><strong>:&nbsp;<?=$namatahunajaran?></strong>
    </td>
</tr>
<tr>
    <td><strong>Semester</strong></td>
  	<td><strong>:&nbsp;<?=$namasemester?></strong></td>
    <td rowspan="2" valign="top"><strong>Jenis Pengujian</strong></td>  
  	<td rowspan="2" valign="top"><strong>:&nbsp;_______________________________________</strong></td>
</tr>
<tr>
    <td><strong>Kelas</strong></td>
  	<td><strong>:&nbsp;<?=$namatingkat ?>&nbsp;-&nbsp;<?=$namakelas?></strong></td>
</tr>
<tr>
    <td><strong>Pelajaran</strong></td>
  	<td><strong>:&nbsp;<?=$namapelajaran?></strong></td>
    <td valign="top" rowspan="2"><strong>Keterangan</strong></td>
   	<td valign="top" rowspan="2"><strong>:&nbsp;_______________________________________</strong></td>		
</tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left">
        <tr>
            <td width="5%" height="30" align="center" class="header">No</td>
            <td width="10%" height="30" align="center" class="header">N I S</td>
            <td width="30%" height="30" align="center" class="header">Nama</td>
            <td width="10%" height="30" align="center" class="header">Nilai</td>	
            <td width="55%" height="30" align="center" class="header">Keterangan</td>
        </tr>
        
        <?php
       
        $sql = "SELECT nis, nama FROM siswa
			     WHERE idkelas = $kelas
				   AND aktif = 1
				   AND alumni = 0
				 ORDER BY nama";
        $result = QueryDb($sql);
        
        while($row = @mysqli_fetch_array($result)){
        
        ?>
        <tr>
            <td height="25" align="center"><?=++$i ?></td>
            <td height="25" align="center"><?=$row['nis'] ?></td>
            <td height="25"><?=$row['nama'] ?></td>
            <td height="25"></td>
            <td height="25"></td>
        </tr>
        <?php } ?>
    </table>
    </td>
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
            <td valign="bottom" align="center"><strong><?=$namaguru?></strong>
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