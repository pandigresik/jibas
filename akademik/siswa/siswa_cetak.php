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
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['tahunajaran'])) 
	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

OpenDb();
$sql = "SELECT a.tahunajaran, t.tingkat, k.kelas FROM kelas k, tahunajaran a, tingkat t WHERE k.replid = '$kelas' AND a.replid = '$tahunajaran' AND t.replid = '$tingkat' AND k.idtingkat = t.replid AND k.idtahunajaran = a.replid";
$result = QueryDb($sql);
$row =@mysqli_fetch_array($result);
$namatahun = $row['tahunajaran'];
$namatingkat = $row['tingkat'];
$namakelas = $row['kelas'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Pendataan Siswa]</title>
</head>

<body >
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center>
  <font size="4"><strong>DATA SISWA PER KELAS</strong></font><br />
 </center><br /><br />
 <table width="100%">    
	<tr>
		<td width="15%"><strong>Departemen</strong> </td> 
		<td width="*"><strong>:&nbsp;<?=$departemen?></strong></td>
	</tr>
    <tr>
		<td><strong>Tahun Ajaran </strong></td>
		<td><strong>:&nbsp;<?=$namatahun?></strong></td>        		
    </tr>
    <tr>
		<td><strong>Kelas</strong></td>
		<td><strong>:&nbsp;<?=$namatingkat." - ".$namakelas?></strong></td>        		
    </tr>
    
	</table>
<br />
<table border="1" width="100%" id="table" class="tab" bordercolor="#000000">
<tr>		
	<td height="30" align="center" class="header" width="4%">No</td>
	<td height="30" align="center" class="header" width="10%">NIS</td>
    <td height="30" align="center" class="header" width="10%">NISN</td>
    <td height="30" align="center" class="header" width="25%">Nama</td>
    <td height="30" align="center" class="header" width="20%">Asal Sekolah</td>
    <td height="30" align="center" class="header" width="*">Tempat Tanggal Lahir</td>
    <td height="30" align="center" class="header" width="10%">Status</td>
    
</tr>
<?php 
//$sql = "SELECT nis,nama,asalsekolah,tmplahir,tgllahir,s.aktif,DAY(tgllahir),MONTH(tgllahir),YEAR(tgllahir),s.replid FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = $kelas AND k.idtahunajaran = $tahunajaran AND k.idtingkat = $tingkat AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.alumni=0 ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
$sql = "SELECT nis,nama,asalsekolah,tmplahir,tgllahir,s.aktif,DAY(tgllahir),MONTH(tgllahir),YEAR(tgllahir),s.replid,s.nisn FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = '$kelas' AND k.idtahunajaran = '$tahunajaran' AND k.idtingkat = '$tingkat' AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.alumni=0 ORDER BY $urut $urutan";
$result = QueryDb($sql);
CloseDb();
if ($page==0)
	$cnt = 0;
else
	$cnt = (int)$page*(int)$varbaris;
while ($row = @mysqli_fetch_row($result)) {

?>	
<tr>        			
	<td height="25" align="center"><?=++$cnt?></td>
	<td height="25" align="center"><?=$row[0]?></td>
	<td height="25" align="left"><?=$row[10]?></td>
	<td height="25"><?=$row[1]?></td>
	<td height="25"><?=$row[2]?></td>
	<td height="25"><?=$row[3].', '.$row[6].'&nbsp;'.NamaBulan($row[7]).'&nbsp;'.$row[8]?></td>
	<td height="25" align="center">
	<?php if ($row[5] == 1) 
       		echo 'Aktif'; 
		else
			echo 'Tidak Aktif';			
	?></td>   
</tr>
<?php }	?>			
</table>
   </td>
  </tr>
<!--<tr>
   	<td align="right">Halaman <strong><?=$page+1?></strong> dari <strong><?=$total?></strong> halaman</td>
</tr>-->  
</table>
</body>
<script language="javascript">window.print();</script>


</html>