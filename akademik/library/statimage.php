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
require_once("../include/chartfactory.php");
$kode="";
if (isset($_REQUEST['kode']))
$kode = $_REQUEST['kode'];

$departemen = $_REQUEST['departemen'];
$key = $_REQUEST['key'];
$keyword = $_REQUEST['keyword'];
$type = $_REQUEST['type'];

if ($kode=="0"){
if ($departemen!="-1")
	$dep="AND p.departemen='$departemen'";

if ($key=="-1")
	$kunci="AND p.replid='$key'";
}

$bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nop', 'Des'];

//Untuk yang dari calon siswa
if ($kode=="0"){
	if ($keyword=="1"){
		$title = "Jumlah Calon Siswa Aktif Berdasarkan Agama";
		$xtitle = "Agama";
		$ytitle = "Jumlah Calon Siswa";

		$sql = "SELECT c.agama as agama, COUNT(c.nama) FROM jbsakad.calonsiswa c, jbsumum.agama a, jbsakad.prosespenerimaansiswa p WHERE c.aktif=1 AND c.agama=a.agama AND c.idproses=p.replid $kunci GROUP BY c.agama ORDER BY a.urutan";
	}
}

//Untuk yang dari siswa
if ($kode=="1"){
	if ($keyword=="1"){
		$title = "Jumlah Siswa Aktif Berdasarkan Agama";
		$xtitle = "Agama";
		$ytitle = "Jumlah Siswa";

		$sql = "SELECT s.agama as agama, COUNT(s.nis) FROM jbsakad.siswa s, jbsumum.agama a WHERE s.aktif=1 AND s.agama=a.agama GROUP BY s.agama ORDER BY a.urutan";
	}
}


$CF = new ChartFactory();
$CF->SqlData($sql, $title, $xtitle, $ytitle);
if ($type == "bar")
	$CF->DrawBarChart();
elseif($type == "pie")
	$CF->DrawPieChart();
?>