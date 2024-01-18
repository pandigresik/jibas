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

$perpustakaan = $_REQUEST['perpustakaan'];
$type = $_REQUEST['type'];
$krit = $_REQUEST['krit']; //1 Statistik peminjam terbanyak 
$key = $_REQUEST['key'];
$Limit = $_REQUEST['Limit'];
$key = explode(',', (string) $key);
if ($krit==2)
{
	require_once("../lib/chartfactory2.php");
}
else
{
	require_once("../lib/chartfactory.php");
}

$filter="";
if ($perpustakaan!='-1')
{
	$filter=" AND d.perpustakaan=$perpustakaan";
}

if ($krit == 1) 
{
	$bartitle = "Statistik Peminjam Terbanyak";
	$pietitle = "Prosentase Peminjam Terbanyak";
	$xtitle = "Anggota";
	$ytitle = "Jumlah";
	
	$sql = "SELECT x.idanggota, COUNT(x.replid) AS num 
			  FROM
				   (SELECT p.replid, IF(p.nis IS NOT NULL, p.nis, IF(p.nip IS NOT NULL, p.nip, p.idmember)) AS idanggota
					  FROM pinjam p, daftarpustaka d
				     WHERE p.tglpinjam BETWEEN '".$key[0]."' AND '$key[1]'
					   AND d.kodepustaka = p.kodepustaka $filter) AS x
			 GROUP BY x.idanggota
		     ORDER BY num DESC
		     LIMIT $Limit";	
}
elseif ($krit == 2)
{
	$bartitle = "Statistik Pustaka Favorit";
	$pietitle = "Prosentase Pustaka";
	$xtitle = "Judul";
	$ytitle = "Jumlah";
	
	$sql = "SELECT judul, count(*) as num
		      FROM pinjam p, daftarpustaka d, pustaka pu
			 WHERE p.tglpinjam BETWEEN '".$key[0]."' AND '$key[1]'
			   AND d.kodepustaka=p.kodepustaka
			   AND pu.replid=d.pustaka $filter
			 GROUP BY judul
			 ORDER BY num DESC
			 LIMIT ".$Limit;
}
elseif ($krit == 3)
{
	$bartitle = "Statistik Peminjaman";
	$pietitle = "Prosentase Peminjaman";
	$xtitle = "Judul";
	$ytitle = "Jumlah";
	
	$sql = "SELECT DATE_FORMAT(p.tglpinjam, '%M %Y'), count(*) as num
			  FROM pinjam p, daftarpustaka d, pustaka pu
			 WHERE p.tglpinjam BETWEEN '".$key[0]."' AND '$key[1]'
			   AND d.kodepustaka=p.kodepustaka
			   AND pu.replid=d.pustaka $filter
			 GROUP BY MONTH(p.tglpinjam), YEAR(p.tglpinjam)
			 ORDER BY p.tglpinjam ASC";
}

$CF = new ChartFactory();
$CF->SqlData($sql, $bartitle, $pietitle, $xtitle, $ytitle);
if ($type == "bar")
	$CF->DrawBarChart();
elseif($type == "pie")
	$CF->DrawPieChart();
?>