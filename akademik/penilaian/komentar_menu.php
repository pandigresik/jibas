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
//require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/dpupdate.php');

$urut = $_REQUEST['urut'];
if ($urut=="")
	$urut="nama";
else 
	$urut = $_REQUEST['urut'];

$urutan = $_REQUEST['urutan'];
if ($urutan=="")
	$urutan="asc";
else 
	$urutan = $_REQUEST['urutan'];

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['pelajaran'])) 
	$pelajaran = $_REQUEST['pelajaran'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['jenis']))
    $jenis = $_REQUEST['jenis'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/aTR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Komentar Nilai Rapor</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh() 
{	
	document.location.reload();
}

function change_dep() 
{	
	parent.header.change_dep();
}

function tampil(nis) 
{
    var jenis = document.getElementById('jenis').value;
    if (jenis == 0)
	    parent.komentar_content.location.href="komentar.pel.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nis="+nis;
	else
        parent.komentar_content.location.href="komentar.sos.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nis="+nis;
}

function change_urut(urut,urutan) 
{
//alert ('WOWOR');
	if (urutan=="asc"){
	urutan="desc"
	} else {
	urutan="asc"
	}
	//if (confirm("Apakah anda yakin akan menghapus angkatan ini?"))
	document.location.href="komentar_menu.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&urut="+urut+"&urutan="+urutan;
	
}
</script>
<style type="text/css">
<!--
.style1 {
	font-size: 12px;
	font-weight: bold;
}
.style2 {color: #FFFF00}
.style3 {color: #FFFFFF}
-->
</style>
</head>

<body topmargin="5" leftmargin="5" style="background-color: #f5f5f5">
<form name="main" method="post" action="komentar_footer.php" enctype="multipart/form-data">

<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="semester" id="semester" value="<?=$semester ?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat ?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran ?>" />
<input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran ?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>" />
<input type="hidden" name="jenis" id="jenis" value="<?=$jenis ?>" />
<?php 
	OpenDb();		
	$sql = "SELECT DISTINCT k.replid, s.nis, s.nama, k.komentar 
			  FROM siswa s, komennap k, infonap i 
			 WHERE s.idkelas = $kelas AND k.nis = s.nis AND k.idinfo = i.replid 
			   AND i.idkelas = $kelas AND i.idpelajaran = $pelajaran AND i.idsemester= $semester ORDER BY $urut $urutan";
	$sql = "SELECT nis, nama
			  FROM siswa
			 WHERE idkelas = $kelas AND aktif = 1
		  ORDER BY $urut $urutan";			   
	$result = QueryDb($sql);		
	$cnt = 1;
	$jum = @mysqli_num_rows($result);
	if ($jum > 0) 
	{ ?>	

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
    <td align="left" valign="top" colspan="2">
    <strong>Pilih Siswa:</strong><br>
	<table border="1" width="100%" id="table" class="tab" bordercolor="#000000" style="border-collapse: collapse; border-width: 1px; border-color: #f5f5f5;" >
	<tr style="height: 1px;">
		<td colspan="2"></td>
	</tr>
<?php 	while ($row = @mysqli_fetch_array($result)) 
	{	?>
    <tr>        			
		<td height="25" align="center" onclick="tampil('<?=$row['nis']?>')" style="cursor:pointer" title="Klik untuk menampilkan komentar rapor siswa ini">
		<?=$cnt?>
        </td>
  		<td height="25" onclick="tampil('<?=$row['nis']?>')" style="cursor:pointer" title="Klik untuk menampilkan komentar rapor <?=$row['nama']?>">
		<i><?=$row['nis']?></i><br />
        <strong><?=$row['nama']?></strong>
        </td>
    </tr>
<?php $cnt++;
	} 
	CloseDb();	?>
	</table>   	 
    <!-- END TABLE CONTENT -->
    <script language='JavaScript'>
	   	Tables('table', 1, 0);
    </script> 
   	</td>
</tr>
<!-- END TABLE CENTER -->    
</table>
</form>
<?php  } 
?>
</body>
</html>