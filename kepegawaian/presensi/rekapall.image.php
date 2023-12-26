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
require_once("../include/db_functions_2.php");
require_once("../include/common.php");
require_once("../include/chartfactory.php");
require_once("../include/as-diagrams.php");

$type = $_REQUEST['type'];
$nip = $_REQUEST['nip'];
$tahun30 = $_REQUEST['tahun30'];
$bulan30 = $_REQUEST['bulan30'];
$tanggal30 = $_REQUEST['tanggal30'];
$tahun = $_REQUEST['tahun'];
$bulan = $_REQUEST['bulan'];
$tanggal = $_REQUEST['tanggal'];

$sql = "SELECT IF(`status` = 1, 'Hadir',
               IF(`status` = 2, 'Izin',
               IF(`status` = 3, 'Sakit',
               IF(`status` = 4, 'Cuti',
               IF(`status` = 5, 'Alpa', 'Bebas'))))) AS statusname,
               COUNT(replid)
          FROM jbssdm.presensi
         WHERE tanggal BETWEEN '$tahun30-$bulan30-$tanggal30' AND '$tahun-$bulan-$tanggal'
         GROUP BY statusname";


$CF = new ChartFactory();
$CF->SqlData($sql, $bartitle, $pietitle, $xtitle, $ytitle);
if ($type == "bar")
    $CF->DrawBarChart();		  
else
    $CF->DrawPieChart();		  
?>