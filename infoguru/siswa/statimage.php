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
require_once('../include/sessionchecker.php');
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../library/chartfactory.php");

$type = $_REQUEST['type'];
$dep = $_REQUEST['dep'];
$angkatan = $_REQUEST['angkatan'];
$krit = $_REQUEST['krit'];

$filter = "";
if ($dep == "-1")
	$filter="AND a.replid=s.idangkatan ";
if ($dep != "-1" && $angkatan == "")
	$filter="AND a.departemen='$dep' AND a.replid=s.idangkatan ";
if ($dep != "-1" && $angkatan != "")
	$filter="AND s.idangkatan='$angkatan' AND a.replid=s.idangkatan AND a.departemen='$dep' ";

if ($krit == 1) 
{
	$bartitle = "Banyaknya Siswa berdasarkan Agama";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Agama";
	$xtitle = "Agama";
	$ytitle = "Jumlah";

	$sql = "SELECT s.agama, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.agama";	
}
elseif ($krit == 2) 
{
	$bartitle = "Banyaknya Siswa berdasarkan Asal Sekolah";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Asal Sekolah";
	$xtitle = "Sekolah";
	$ytitle = "Jumlah";

	$sql = "SELECT s.asalsekolah, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.asalsekolah";	
}
elseif ($krit == 3) 
{
	$bartitle = "Banyaknya Siswa berdasarkan Golongan Darah";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Golongan Darah";
	$xtitle = "Golongan";
	$ytitle = "Jumlah";

	$sql = "SELECT s.darah, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.darah";	
}
elseif ($krit == 4)
{
	$bartitle = "Banyaknya Siswa berdasarkan Jenis Kelamin";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Jenis Kelamin";
	$xtitle = "Jenis Kelamin";
	$ytitle = "Jumlah";
	$sql	=  "SELECT IF(s.kelamin='l','Laki - laki','Perempuan') as X, COUNT(s.nis) FROM siswa s, angkatan a WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X";
}
elseif ($krit == 5)
{
	$bartitle = "Banyaknya Siswa berdasarkan Kewarganegaraan";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Kewarganegaraan";
	$xtitle = "Warga Negara";
	$ytitle = "Jumlah";
	$sql = "SELECT s.warga, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.warga ORDER BY s.warga DESC";
}
elseif ($krit == 6)
{
	$bartitle = "Banyaknya Siswa berdasarkan Kodepos";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Kodepos";
	$xtitle = "Kodepos";
	$ytitle = "Jumlah";
	$sql = "SELECT s.kodepossiswa, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.kodepossiswa ";
}
elseif ($krit == 7)
{
	$bartitle = "Banyaknya Siswa berdasarkan Kondisi Siswa";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Kondisi Siswa";
	$xtitle = "Kondisi";
	$ytitle = "Jumlah";
	$sql = "SELECT s.kondisi, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.kondisi ";
}
elseif ($krit == 8)
{
	$bartitle = "Banyaknya Siswa berdasarkan Pekerjaan Ayah";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Pekerjaan Ayah";
	$xtitle = "Pekerjaan";
	$ytitle = "Jumlah";
	$sql = "SELECT s.pekerjaanayah, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pekerjaanayah ";
}
elseif ($krit == 9)
{
	$bartitle = "Banyaknya Siswa berdasarkan Pekerjaan Ibu";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Pekerjaan Ibu";
	$xtitle = "Pekerjaan";
	$ytitle = "Jumlah";
	$sql = "SELECT s.pekerjaanibu, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pekerjaanibu ";
}
elseif ($krit == 10)
{
	$bartitle = "Banyaknya Siswa berdasarkan Pendidikan Ayah";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Pendidikan Ayah";
	$xtitle = "Tingkat Pendidikan";
	$ytitle = "Jumlah";
	$sql = "SELECT s.pendidikanayah, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pendidikanayah ";
}
elseif ($krit == 11)
{
	$bartitle = "Banyaknya Siswa berdasarkan Pendidikan Ibu";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Pendidikan Ibu";
	$xtitle = "Tingkat Pendidikan";
	$ytitle = "Jumlah";
	$sql = "SELECT s.pendidikanibu, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pendidikanibu ";
}
elseif ($krit == 12)
{
	$bartitle = "Banyaknya Siswa berdasarkan \nPenghasilan Orang Tua";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan \nPenghasilan Orang Tua";
	$xtitle = "Penghasilan (rupiah)";
	$ytitle = "Jumlah";
	$sql = "SELECT G, COUNT(nis) FROM (
	          SELECT nis, IF(peng < 1000000, '< 1 juta',
                          IF(peng >= 1000001 AND peng <= 2500000, '1 juta - 2,5 juta',
                          IF(peng >= 2500001 AND peng <= 5000000, '2,5 juta - 5 juta',
                          IF(peng >= 5000001 , '> 5 juta', 'Tidak Ada Data')))) AS G,
						  IF(peng < 1000000, '1',
                          IF(peng >= 1000001 AND peng <= 2500000, '2',
                          IF(peng >= 2500001 AND peng <= 5000000, '3',
                          IF(peng >= 5000001 , '4', '5')))) AS GG FROM
                (SELECT s.nis, FLOOR(s.penghasilanibu + s.penghasilanayah) AS peng FROM siswa s, angkatan a WHERE a.aktif=1 AND s.aktif=1 $filter ) AS X) AS X GROUP BY G ORDER BY GG";
}
elseif ($krit == 13)
{
	$bartitle = "Banyaknya Siswa berdasarkan Status Aktif";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Status Aktif";
	$xtitle = "Aktif / Tidak Aktif";
	$ytitle = "Jumlah";
	$sql	=  "SELECT IF(s.aktif=1,'Aktif','Tidak Aktif') as X, COUNT(s.nis) FROM siswa s, angkatan a WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X";
}
elseif ($krit == 14)
{
	$bartitle = "Banyaknya Siswa berdasarkan Status";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Status";
	$xtitle = "Status Siswa";
	$ytitle = "Jumlah";
	$sql = "SELECT s.status as X, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X ";
}
elseif ($krit == 15)
{
	$bartitle = "Banyaknya Siswa berdasarkan Suku";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Suku";
	$xtitle = "Suku";
	$ytitle = "Jumlah";
	$sql = "SELECT s.suku as X, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X ";
}
elseif ($krit == 16)
{
	$bartitle = "Banyaknya Siswa berdasarkan Tahun Kelahiran";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Tahun Kelahiran";
	$xtitle = "Tahun Lahir";
	$ytitle = "Jumlah";
	$sql = "SELECT YEAR(s.tgllahir) as X, count(s.replid) FROM 
	        siswa s, angkatan a 
			WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X ORDER BY X ";
}
elseif ($krit == 17)
{
	$bartitle = "Banyaknya Siswa berdasarkan Usia";
	$pietitle = "Prosentase Banyaknya Siswa berdasarkan Usia";
	$xtitle = "Usia (tahun)";
	$ytitle = "Jumlah";
	$sql = "SELECT G, COUNT(nis) FROM (
	          SELECT nis, IF(usia < 6, '<6',
                          IF(usia >= 6 AND usia <= 12, '6-12',
                          IF(usia >= 13 AND usia <= 15, '13-15',
                          IF(usia >= 16 AND usia <= 18, '16-18','>18')))) AS G,
						  IF(usia < 6, '1',
                          IF(usia >= 6 AND usia <= 12, '2',
                          IF(usia >= 13 AND usia <= 15, '3',
                          IF(usia >= 16 AND usia <= 18, '4','5')))) AS GG FROM
                (SELECT nis, YEAR(now())-YEAR(s.tgllahir) AS usia FROM siswa s, angkatan a WHERE a.aktif=1 AND s.aktif=1 $filter ) AS X) AS X GROUP BY G ORDER BY GG";
}
$CF = new ChartFactory();
$CF->SqlData($sql, $bartitle, $pietitle, $xtitle, $ytitle);
if ($type == "bar")
	$CF->DrawBarChart();
elseif($type == "pie")
	$CF->DrawPieChart();
?>