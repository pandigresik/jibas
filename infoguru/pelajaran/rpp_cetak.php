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
require_once("../include/sessionchecker.php");

$tingkat = $_REQUEST['tingkat'];
$semester = $_REQUEST['semester'];
$pelajaran = $_REQUEST['pelajaran'];

if (isset($_REQUEST['urut'])){
	$urut = $_REQUEST['urut'];
	} else {
	$urut = "koderpp";	
	}	

	
if (isset($_REQUEST['urutan'])){
	$urutan = $_REQUEST['urutan'];
	} else {
	$urutan = "ASC";
	}

OpenDb();
$sql = "SELECT t.departemen, t.tingkat, s.semester, p.nama FROM tingkat t, semester s, pelajaran p WHERE t.replid = '$tingkat' AND s.replid = '$semester' AND p.replid = '".$pelajaran."'";
//echo $sql;
$result = QueryDb($sql);
CloseDb();
$row = mysqli_fetch_array($result);
$departemen = $row['departemen'];
$namatingkat = $row['tingkat'];
$namasemester = $row['semester'];
$namapelajaran = $row['nama'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOGURU [Cetak Daftar Rencana Program Pengajaran]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center>
  <font size="4"><strong>DAFTAR RENCANA PROGRAM PEMBELAJARAN </strong></font><br />
 </center><br /><br />

<br />
<table>
<tr>
	<td><strong>Departemen</strong> </td> 
	<td><strong>:&nbsp;<?=$departemen?></strong></td>
</tr>
<tr>
	<td><strong>Tingkat</strong></td>
	<td><strong>:&nbsp;<?=$namatingkat?></strong></td>
</tr>
<tr>
	<td><strong>Semester</strong></td>
	<td><strong>:&nbsp;<?=$namasemester?></strong></td>
</tr>
<tr>
	<td><strong>Pelajaran</strong></td>
	<td><strong>:&nbsp;<?=$namapelajaran?></strong></td>
</tr>

</table>
<br />
	<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left" bordercolor="#000000">
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="8%" class="header" align="center">Kode</td>
        <td width="25%" class="header" align="center">Materi</td>
        <td width="*" class="header" align="center">Deskripsi</td>
        <td width="10%" class="header" align="center">Status</td>        
        
    </tr>
<?php 	OpenDb();
	$sql = "SELECT replid, koderpp, rpp, deskripsi, aktif FROM rpp WHERE idtingkat='$tingkat' AND idsemester='$semester' AND idpelajaran='$pelajaran' ORDER BY $urut $urutan"; 
	$result = QueryDb($sql);
	$cnt = 0;
	while ($row = mysqli_fetch_array($result)) { ?>
    <tr height="25">    	
    	<td align="center" ><?=++$cnt ?></td>
        <td><?=$row['koderpp'] ?></td>        
        <td><?=$row['rpp'] ?></td>        
        <td><?=$row['deskripsi'] ?></td>
        <td align="center">
			<?php if ($row['aktif'] == 1) 
					echo 'Aktif';
				else
					echo 'Tidak Aktif';
			?>		
        </td>
    </tr>
<?php } 
	CloseDb() ?>	
    <!-- END TABLE CONTENT -->
    </table>

</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>