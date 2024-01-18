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
require_once('../inc/sessionchecker.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');

$krit = ['', 'Agama', 'Asal Sekolah', 'Golongan Darah', 'Jenis Kelamin', 'Kewarganegaraan', 'Kode Pos Siswa', 'Kondisi Siswa', 'Pekerjaan Ayah', 'Pekerjaan Ibu', 'Pendidikan Ayah', 'Pendidikan Ibu', 'Penghasilan Orang Tua', 'Status Aktif', 'Status Siswa', 'Suku', 'Tahun Kelahiran', 'Usia'];

$departemen = '-1';
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$angkatan = '';
if (isset($_REQUEST['angkatan']))
	$angkatan = $_REQUEST['angkatan'];

$kriteria = '1';
if (isset($_REQUEST['kriteria']))
	$kriteria = $_REQUEST['kriteria'];
    
$kondisi = '';
if (isset($_REQUEST['kondisi']))
	$kondisi = $_REQUEST['kondisi'];    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
$filter = "";

if ($departemen == "-1")
    $filter = "AND a.replid = s.idangkatan ";

if ($departemen != "-1" && $angkatan == "")
    $filter = "AND a.replid = s.idangkatan
               AND a.departemen = '".$departemen."'";

if ($departemen != "-1" && $angkatan != "")
    $filter = "AND a.replid = s.idangkatan
               AND s.idangkatan = $angkatan
               AND a.departemen = '".$departemen."'";
		
if ($kriteria == 1) 
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.agama IS NULL" : "AND s.agama = '".$kondisi."'";
    $title = "Agama";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.agama
              FROM siswa s, angkatan a, kelas k 
             WHERE s.idkelas = k.replid
               AND a.aktif = 1
               AND s.aktif = 1
                   $kondisi
                   $filter
             ORDER BY s.nama";	
}
elseif ($kriteria == 2) 
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.asalsekolah IS NULL" : "AND s.asalsekolah = '".$kondisi."'";
    $title = "Asal Sekolah";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.asalsekolah
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif = 1
               AND s.aktif = 1
                   $kondisi
                   $filter
             ORDER BY s.nama";	
}
elseif ($kriteria == 3) 
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.darah = ''" : "AND s.darah = '".$kondisi."'";
    $title = "Golongan Darah";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.darah
              FROM siswa s, angkatan a, kelas k 
             WHERE s.idkelas = k.replid
               AND a.aktif = 1
               AND s.aktif = 1
                   $kondisi
                   $filter
             ORDER BY s.nama";	
}
elseif ($kriteria == 4)
{
    $title = "Jenis Kelamin";
    $sql = "SELECT s.nis, s.nama, k.kelas, IF(s.kelamin = 'l', 'Laki-laki', 'Perempuan')
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif = 1
               AND s.aktif = 1
               AND s.kelamin = '$kondisi'
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 5)
{
    $title = "Warga Negara";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.warga
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif = 1
               AND s.aktif = 1
               AND s.warga = '$kondisi'
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 6)
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.kodepossiswa IS NULL" : "AND s.kodepossiswa = '".$kondisi."'";
    $title = "Kode Pos";
    $sql = "SELECT s.nis, s.nama, kelas k, s.kodepossiswa
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif = 1
               AND s.aktif = 1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 7)
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.kondisi IS NULL" : "AND s.kondisi = '".$kondisi."'";
    $title = "Kondisi Siswa";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.kondisi
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif = 1
               AND s.aktif = 1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 8)
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.pekerjaanayah IS NULL" : "AND s.pekerjaanayah = '".$kondisi."'";
    $title = "Pekerjaan Ayah";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.pekerjaanayah
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif = 1
               AND s.aktif = 1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 9)
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.pekerjaanibu IS NULL" : "AND s.pekerjaanibu = '".$kondisi."'";
    $title = "Pekerjaan Ibu";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.pekerjaanibu
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif=1
               AND s.aktif=1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 10)
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.pendidikanayah IS NULL" : "AND s.pendidikanayah = '".$kondisi."'";
    $title = "Pendidikan Ayah";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.pendidikanayah
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif=1
               AND s.aktif=1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 11)
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.pendidikanibu IS NULL" : "AND s.pendidikanibu = '".$kondisi."'";
    $title = "Pendidikan Ibu";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.pendidikanibu
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif=1
               AND s.aktif=1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 12)
{
    if ($kondisi == "< 1 juta")
        $kondisi = "AND s.penghasilanayah + s.penghasilanibu < 1000000";
    elseif ($kondisi == "1 juta - 2,5 juta")
        $kondisi = "AND (s.penghasilanayah + s.penghasilanibu >= 1000001 AND s.penghasilanayah + s.penghasilanibu <= 2500000)";
    elseif ($kondisi == "2,5 juta - 5 juta")
        $kondisi = "AND (s.penghasilanayah + s.penghasilanibu >= 2500001 AND s.penghasilanayah + s.penghasilanibu <= 5000000)";
    elseif ($kondisi == "> 5 juta")
        $kondisi = "AND s.penghasilanayah + s.penghasilanibu >= 5000001";
    $title = "Penghasilan Ayah + Ibu (Rupiah)";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.penghasilanayah + s.penghasilanibu
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif=1
               AND s.aktif=1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 13)
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.aktif IS NULL" : "AND s.aktif = '".$kondisi."'";
    $title = "Status Aktif";
    $sql = "SELECT s.nis, s.nama, k.kelas, IF(s.aktif = 1,'Aktif','Tidak Aktif')
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif=1
               AND s.aktif=1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 14)
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.status IS NULL" : "AND s.status = '".$kondisi."'";
    $title = "Status Siswa";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.status as X
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif=1
               AND s.aktif=1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 15)
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.suku IS NULL" : "AND s.suku = '".$kondisi."'";
    $title = "Suku";
    $sql = "SELECT s.nis, s.nama, k.kelas, s.suku as X
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif=1
               AND s.aktif=1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 16)
{
    $kondisi = strlen(trim((string) $kondisi)) == 0 ? "AND s.tgllahir IS NULL" : "AND YEAR(s.tgllahir) = '".$kondisi."'";
    $title = "Tahun Lahir";
    $sql = "SELECT s.nis, s.nama, k.kelas, YEAR(s.tgllahir) as X
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif=1
               AND s.aktif=1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
elseif ($kriteria == 17)
{
    if ($kondisi == "<6")
        $kondisi = "AND YEAR(NOW()) - YEAR(s.tgllahir) < 6";
    elseif ($kondisi == "6-12")
        $kondisi = "AND (YEAR(NOW()) - YEAR(s.tgllahir) >= 6 AND YEAR(NOW()) - YEAR(s.tgllahir) <= 12)";
    elseif ($kondisi == "13-15")
        $kondisi = "AND (YEAR(NOW()) - YEAR(s.tgllahir) >= 13 AND YEAR(NOW()) - YEAR(s.tgllahir) <= 15)";
    elseif ($kondisi == "16-18")
        $kondisi = "AND (YEAR(NOW()) - YEAR(s.tgllahir) >= 16 AND YEAR(NOW()) - YEAR(s.tgllahir) <= 18)";
    elseif ($kondisi = ">18")
        $kondisi = "AND YEAR(NOW()) - YEAR(s.tgllahir) > 18";
        
    $title = "Usia (tahun)";
    $sql = "SELECT s.nis, s.nama, k.kelas, YEAR(NOW()) - YEAR(s.tgllahir) as X
              FROM siswa s, angkatan a, kelas k
             WHERE s.idkelas = k.replid
               AND a.aktif=1
               AND s.aktif=1
                   $kondisi
                   $filter
             ORDER BY s.nama";
}
		
		?>
<table width="100%" border="1" class="tab" align="center">
<tr height="25">
    <td width="7%"  align="center" class="header">No.</td>
    <td width="12%" align="left" class="header">NIS</td>
    <td width="25%" align="left" class="header">Nama</td>
    <td width="12%" align="left" class="header">Kelas</td>
    <td width="*" align="left" class="header"><?=$title?></td>
</tr>
<?php
OpenDb();
$result = QueryDb($sql);
$cnt = 1;
while ($row = @mysqli_fetch_row($result))
{   ?>
    <tr>
        <td align="center"><?=$cnt?></td>
        <td align="left"><?=$row[0]?></td>
        <td align="left"><?=$row[1]?></td>
        <td align="left"><?=$row[2]?></td>
        <td align="left"><?=$row[3]?></td>
    </tr>
<?php  $cnt++;
}
CloseDb();
?>
</table>
</body>
</html>