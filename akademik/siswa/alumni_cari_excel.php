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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Data_Siswa.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$cari=$_REQUEST['cari'];
$jenis=$_REQUEST['jenis'];
$departemen=$_REQUEST['departemen'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];
	
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];

$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

if ($jenis=="tgllulus")
$namajenis="Tahun&nbsp;Lulus";
if ($jenis=="nis")
$namajenis="NIS";
if ($jenis=="nama")
$namajenis="Nama&nbsp;Siswa";
if ($jenis=="panggilan")
$namajenis="Panggilan&nbsp;Siswa";
if ($jenis=="agama")
$namajenis="Agama";
if ($jenis=="suku")
$namajenis="Suku";
if ($jenis=="status")
$namajenis="Status&nbsp;Siswa";
if ($jenis=="kondisi")
$namajenis="Kondisi&nbsp;Siswa";
if ($jenis=="darah")
$namajenis="Golongan&nbsp;Darah";
if ($jenis=="alamatsiswa")
$namajenis="Alamat&nbsp;Siswa";
if ($jenis=="asalsekolah")
$namajenis="Asal&nbsp;Sekolah";
if ($jenis=="namaayah")
$namajenis="Nama&nbsp;Ayah";
if ($jenis=="namaibu")
$namajenis="Nama&nbsp;Ibu";
if ($jenis=="alamatortu")
$namajenis="Alamat&nbsp;Orang&nbsp;Tua";
if ($jenis=="keterangan")
$namajenis="Keterangan";

if ($cari=="")
$namacari="";
else
$namacari=$cari;


OpenDb();
	if ($jenis!="kondisi" && $jenis!="status" && $jenis!="agama" && $jenis!="suku" && $jenis!="darah" && $jenis!="idangkatan" && $jenis!="tgllulus") {
		$sql = "SELECT s.replid, s.nis, s.nama, s.idkelas, k.kelas, s.tmplahir, s.tgllahir, s.statusmutasi, s.aktif, s.alumni, t.tingkat, a.tgllulus from jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.alumni a WHERE s.$jenis LIKE '%$cari%' AND k.replid=a.klsakhir AND k.idtingkat=t.replid AND a.departemen='$departemen' AND s.nis = a.nis ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	} elseif ($jenis == "tgllulus") {
		$sql = "SELECT s.replid, s.nis, s.nama, s.idkelas, k.kelas, s.tmplahir, s.tgllahir, s.statusmutasi, s.aktif, s.alumni, t.tingkat, a.tgllulus from jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.alumni a WHERE YEAR(a.tgllulus) = '$cari' AND k.replid=a.klsakhir AND k.idtingkat=t.replid AND a.departemen='$departemen' AND s.nis = a.nis ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	} else { 
		$sql = "SELECT s.replid, s.nis, s.nama, s.idkelas, k.kelas, s.tmplahir, s.tgllahir, s.statusmutasi, s.aktif, s.alumni, t.tingkat, a.tgllulus from jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.alumni a WHERE s.$jenis = '$cari' AND k.replid=a.klsakhir AND k.idtingkat=t.replid AND a.departemen='$departemen' AND s.nis = a.nis ORDER BY $urut $urutan ";//LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	}
$result=QueryDb($sql);

if (@mysqli_num_rows($result)<>0){
?>
<html>
<head>
<title>
Data Alumni
</title>
<style type="text/css">
<!--
-->
</style>
<style type="text/css">
<!--
.style2 {
	font-size: 16px;
	font-weight: bold;
}
.style3 {font-weight: bold}
.style4 {color: #000000}
-->
</style>
</head>
<body>
<table width="100%" border="0">
  <tr>
    <td colspan="7">
    <table width="100%" border="0">
  <tr>
    <td colspan="2"><div align="center" class="style3 style2">PENCARIAN ALUMNI</div></td>
    </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>

    <td width="9%"><strong>Departemen</strong></td>
    <td width="91%" align="left"><strong>:&nbsp;
        <?=$departemen?>
    </strong></td>
  </tr>
  <tr>
    <td colspan="2">
    Pencarian berdasarkan <strong><?=$namajenis?></strong> dengan kata kunci <strong>'<?=$namacari;?>'</strong></td>
    <td align="left"><strong>:&nbsp;
           
	</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>    </td>
  </tr>
  <tr>
    <td colspan="7"><table border="1" width="100%" bordercolordark="#000000">
<tr height="30" bordercolor="#000000">
<td width="3" valign="middle" bgcolor="#999999" class="header"><div align="center" class="style4">No.</div></td>
<td width="20" valign="middle" bgcolor="#999999" class="header"><div align="center" class="style4">NIS</div></td>
<td valign="middle" bgcolor="#999999" class="header"><div align="center" class="style4">Nama</div></td>
<td valign="middle" bgcolor="#999999" class="header"><div align="center" class="style4">Kelas Terakhir</div></td>
<td valign="middle" bgcolor="#999999" class="header"><div align="center" class="style4">Tingkat Terakhir</div></td>
<td valign="middle" bgcolor="#999999" class="header"><div align="center" class="style4">Tanggal Lulus</div></td>
</tr>
<?php
	$cnt=1;
	while ($row=@mysqli_fetch_array($result)){
	?>
	<tr height="25" bordercolor="#000000">
	<td width="3" align="center"><?=$cnt?></td>
	<td align="left"><?=$row['nis']?></td>
	<td align="left"><?=$row['nama']?></td>
	<td align="left"><?=$row['kelas']?></td>
	<td align="left"><?=$row['tingkat']?></td>
	<td align="left"><?=$row['tgllulus']?></td>
	</tr>
	<?php
		$cnt++;
}
	?>
</table></td>
  </tr>
</table>


</body>
</html>
<?php
}
CloseDb();
?>