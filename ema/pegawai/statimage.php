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
require_once("../inc/config.php");
require_once("../inc/db_functions.php");
require_once("../inc/common.php");
require_once("../lib/chartfactory.php");

$type = $_REQUEST['type'];
$krit = $_REQUEST['krit'];


if ($krit == 1) 
{
	$bartitle = "Banyaknya Pegawai berdasarkan Bagian";
	$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Bagian";
	$xtitle = "Bagian";
	$ytitle = "Jumlah";

	$sql = "SELECT bagian, count(replid) FROM 
	        $db_name_sdm.pegawai
			WHERE aktif=1 GROUP BY bagian";	
}
if ($krit == 2) 
{
	$bartitle = "Banyaknya Pegawai berdasarkan Agama";
	$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Agama";
	$xtitle = "Agama";
	$ytitle = "Jumlah";

	$sql = "SELECT agama, count(replid) FROM 
	        $db_name_sdm.pegawai
			WHERE aktif=1 GROUP BY agama";	
}
if ($krit == 3) 
{
	$bartitle = "Banyaknya Pegawai berdasarkan Gelar";
	$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Gelar";
	$xtitle = "Gelar";
	$ytitle = "Jumlah";

	$sql = "SELECT gelar, count(replid) FROM 
	        $db_name_sdm.pegawai
			WHERE aktif=1 GROUP BY gelar";	
}

if ($krit == 4)
{
	$bartitle = "Banyaknya Pegawai berdasarkan Jenis Kelamin";
	$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Jenis Kelamin";
	$xtitle = "Jenis Kelamin";
	$ytitle = "Jumlah";
	$sql	=  "SELECT IF(kelamin='l','Laki - laki','Perempuan') as X, COUNT(nip) FROM $db_name_sdm.pegawai  WHERE aktif=1 GROUP BY X";
}

if ($krit == 5)
{
	$bartitle = "Banyaknya Pegawai berdasarkan Status Aktif";
	$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Status Aktif";
	$xtitle = "Status Aktif";
	$ytitle = "Jumlah";
	$sql	=  "SELECT IF(aktif=1,'Aktif','Tidak Aktif') as X, COUNT(nip) FROM $db_name_sdm.pegawai GROUP BY X";
}

if ($krit == 6)
{
	$bartitle = "Banyaknya Pegawai berdasarkan Status Menikah";
	$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Status Menikah";
	$xtitle = "Menikah";
	$ytitle = "Jumlah";
	$sql	=  "SELECT IF(nikah='menikah','Menikah','Belum Menikah') as X, COUNT(nip) FROM $db_name_sdm.pegawai  WHERE aktif=1 GROUP BY X";
}
if ($krit == 7) 
{
	$bartitle = "Banyaknya Pegawai berdasarkan Suku";
	$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Suku";
	$xtitle = "Suku";
	$ytitle = "Jumlah";

	$sql = "SELECT suku, count(replid) FROM 
	        $db_name_sdm.pegawai
			WHERE aktif=1 GROUP BY suku";	
}
if ($krit == 8)
{
	$bartitle = "Banyaknya Pegawai berdasarkan Tahun Kelahiran";
	$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Tahun Kelahiran";
	$xtitle = "Tahun Lahir";
	$ytitle = "Jumlah";
	$sql = "SELECT YEAR(tgllahir) as X, count(replid) FROM 
	        $db_name_sdm.pegawai
			WHERE aktif=1 GROUP BY X ORDER BY X ";
}
if ($krit == 9)
{
	$bartitle = "Banyaknya Pegawai berdasarkan Usia";
	$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Usia";
	$xtitle = "Usia (tahun)";
	$ytitle = "Jumlah";
	$sql = "SELECT G, COUNT(nip) FROM (
	          SELECT nip, IF(usia < 20, '<20',
                          IF(usia >= 20 AND usia <= 30, '20-30',
                          IF(usia >= 30 AND usia <= 40, '30-40',
                          IF(usia >= 40 AND usia <= 50, '40-50','>50')))) AS G,
						  IF(usia < 20, '1',
                          IF(usia >= 20 AND usia <= 30, '2',
                          IF(usia >= 30 AND usia <= 40, '3',
                          IF(usia >= 40 AND usia <= 50, '4','5')))) AS GG FROM
                (SELECT nip, YEAR(now())-YEAR(tgllahir) AS usia FROM $db_name_sdm.pegawai WHERE aktif=1) AS X) AS X GROUP BY G ORDER BY GG";
}
$CF = new ChartFactory();
$CF->SqlData($sql, $bartitle, $pietitle, $xtitle, $ytitle);
if ($type == "bar")
	$CF->DrawBarChart();
elseif($type == "pie")
	$CF->DrawPieChart();
?>