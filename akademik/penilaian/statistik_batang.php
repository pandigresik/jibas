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
require_once('../include/config.php');
require_once('../include/db_functions.php');


OpenDb();
$sql = "SELECT k.kelas, round(SUM(nilaiujian)/(COUNT(DISTINCT u.replid)*COUNT(DISTINCT s.nis)),2) AS rata FROM nilaiujian n, siswa s, ujian u, jenisujian j, kelas k, tahunajaran a WHERE n.idujian = u.replid AND u.idsemester = '".$_REQUEST['semester']."' AND u.idkelas = k.replid AND u.idjenis = '".$_REQUEST['ujian']."' AND u.idrpp = '".$_REQUEST['rpp']."' AND u.idpelajaran = '".$_REQUEST['pelajaran']."' AND s.nis = n.nis AND u.idjenis = j.replid AND s.idkelas = k.replid AND s.aktif = 1 AND k.idtingkat = '".$_REQUEST['tingkat']."' AND k.aktif = 1 AND k.idtahunajaran = a.replid AND a.aktif = 1 GROUP BY k.replid ORDER BY k.kelas, u.tanggal, s.nama";
echo "ada nih".$sql;


$title = "Rata-rata Nilai Ujian Kelas per RPP";
$xtitle = "Kelas";
$ytitle = "Rata-rata Nilai Ujian";


/*$CF = new ChartFactory();
$CF->SqlData($sql, $title, $xtitle, $ytitle);
if ($type == "bar")
	$CF->DrawBarChart();
elseif($type == "pie")
	$CF->DrawPieChart();
*/
?>