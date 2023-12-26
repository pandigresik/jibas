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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');


$departemen=$_REQUEST['departemen'];
$idproses=$_REQUEST['idproses'];
$iddasar=$_REQUEST['iddasar'];
//$dasar=$_REQUEST['dasar'];
//$tabel=$_REQUEST['tabel'];

for ($i=1;$i<=17;$i++) {
   if ($iddasar == $i) {
	$dasar = $kriteria[$i];
	$judul = $kriteria_judul[$i];	
	$tabel = $kriteria_tabel[$i];	
   }				
}

if ($departemen=="-1" && $idproses<0)
	$kondisi=" AND a.replid=s.idproses ";
if ($departemen<>"-1" && $idproses<0)
	$kondisi=" AND a.departemen='$departemen' AND a.replid=s.idproses ";
if ($departemen<>"-1" && $idproses>0)
	$kondisi=" AND s.idproses='$idproses' AND a.replid=s.idproses AND a.departemen='$departemen' ";
	
	OpenDb();
	$sql = "SELECT s.replid FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) {
		
?>

<!--<frameset rows="22%,*" border="0" frameborder="No" noresize="false">
    <frame src="statistik_grafik_header.php?iddasar=<?=$iddasar?>&departemen=<?=$departemen?>&idproses=<?=$idproses?>" name="grafik_header" scrolling="no">-->
    
<frameset cols="55%,45%" border="0" framespacing="0" frameborder="no">
	<frame name="grafik_statistik" src="grafik.php?dasar=<?=$dasar?>&iddasar=<?=$iddasar?>&departemen=<?=$departemen?>&tabel=<?=$tabel?>&idproses=<?=$idproses?>" scrolling="auto">
   	<frameset rows="50%,*" border="1" frameborder="1">
		<frame name="table_statistik" src="statistik_table.php?dasar=<?=$dasar?>&departemen=<?=$departemen?>&idproses=<?=$idproses?>&tabel=<?=$tabel?>&nama_judul=<?=$judul?>&iddasar=<?=$iddasar?>" id="table_statistik" scrolling="auto" style="border-bottom:1; border-bottom-color:#000000; border-bottom-style:solid" frameborder="0">
		<frame name="detail_statistik" src="../blank_white.php" id="detail_statistik" scrolling="auto">
	</frameset>
</frameset>
<!--</frameset>-->
<noframes></noframes>
<?php } else { ?>
<head>
<link rel="stylesheet" type="text/css"  href="../style/style.css">
</head>
<body>
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	<td align="left" valign="top" style="background-repeat:no-repeat; background-image:url(../images/ico/b_statistik.png)">
    <table width="100%" border="0" height="100%">
   	<tr>
  		<td align="center">
		<font size = "2" color ="red"><b>Tidak ditemukan adanya data.<br />
	Tambah data calon siswa pada departemen <?=$departemen?> di menu Pendataan Calon Siswa pada bagian PSB. 
		</b></font>        
    	</td>
  	</tr>
	</table>
</tr>
</table>

	</td>
</tr>
</table>
</body>
<?php } ?>