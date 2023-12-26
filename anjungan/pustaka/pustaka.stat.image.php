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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../library/chartfactory.php");

$perpus = $_REQUEST['perpus'];
$bln1 = $_REQUEST['bln1'];
$thn1 = $_REQUEST['thn1'];
$bln2 = $_REQUEST['bln2'];
$thn2 = $_REQUEST['thn2'];
$jum = $_REQUEST['jum'];

$filter = "";
if ($perpus != -1)
	$filter = " AND d.perpustakaan = $perpus";

$bartitle = "Statistik Pustaka Terfavorit";
$pietitle = "";
$xtitle = "Judul";
$ytitle = "Jumlah";

$sql = "SELECT @rownum:=@rownum+1, COUNT(*) AS num
          FROM jbsperpus.pinjam p, jbsperpus.daftarpustaka d, jbsperpus.pustaka pu, (SELECT @rownum:=0) r
         WHERE p.tglpinjam BETWEEN '$thn1-$bln1-1' AND '$thn2-$bln2-31'
           AND d.kodepustaka = p.kodepustaka
           AND pu.replid = d.pustaka
               $filter
         GROUP BY judul
         ORDER BY num DESC
         LIMIT $jum";
//echo $sql;
//exit();

$CF = new ChartFactory();
$CF->SqlData($sql, $bartitle, $pietitle, $xtitle, $ytitle);
$CF->DrawBarChart();
?>