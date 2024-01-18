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
require_once("../include/db_functions_2.php");
require_once("../include/common.php");
require_once("../include/chartfactory.php");
require_once("../include/as-diagrams.php");

$stat = $_REQUEST['stat'];
$type = $_REQUEST['type'];
$dotype = "";
if (isset($_REQUEST['dotype']))
	$dotype = $_REQUEST['dotype'];

if (strlen((string) $dotype) > 0) {
	$s = explode("-", (string) $dotype);
	$type = $s[0];
	$stat = $s[1];
}

if ($stat == 1) 
{
	$bartitle = "Banyaknya Pegawai Per Satuan Kerja";
	$pietitle = "Prosentase Banyaknya Pegawai Per Satuan Kerja";
	$xtitle = "Satuan Kerja";
	$ytitle = "Jumlah";

	$sql = "SELECT j.satker, count(pj.replid) FROM 
	        pegjab pj, peglastdata pl, pegawai p, jabatan j 
			WHERE pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND pj.nip = p.nip AND NOT j.satker IS null
			  AND p.aktif=1 GROUP BY satker;";	
}
elseif ($stat == 2)
{
	$bartitle = "Banyaknya Pegawai Berdasarkan Pendidikan";
	$pietitle = "Prosentase Banyaknya Pegawai Berdasarkan Pendidikan";
	$xtitle = "Pendidikan";
	$ytitle = "Jumlah";

	$sql = "SELECT ps.tingkat, COUNT(p.nip) FROM
            pegawai p, peglastdata pl, pegsekolah ps, jbsumum.tingkatpendidikan pk
            WHERE p.nip = pl.nip AND pl.idpegsekolah = ps.replid AND ps.tingkat = pk.pendidikan
		     AND p.aktif = 1  GROUP BY ps.tingkat";	
}
elseif ($stat == 3)
{
	$bartitle = "Banyaknya Pegawai Berdasarkan Golongan";
	$pietitle = "Prosentase Banyaknya Pegawai Berdasarkan Golongan";
	$xtitle = "Golongan";
	$ytitle = "Jumlah";

	$sql = "SELECT pg.golongan, COUNT(p.nip) FROM pegawai p, peglastdata pl, peggol pg, golongan g
            WHERE p.nip = pl.nip AND pl.idpeggol = pg.replid AND pg.golongan = g.golongan AND p.aktif = 1 
			 GROUP BY pg.golongan ORDER BY g.urutan";	
}
elseif ($stat == 4)
{
	$bartitle = "Banyaknya Pegawai Berdasarkan Usia";
	$pietitle = "Prosentase Banyaknya Pegawai Berdasarkan Usia";
	$xtitle = "Usia";
	$ytitle = "Jumlah";

	$sql = "SELECT G, COUNT(nip) FROM (
	          SELECT nip, IF(usia < 24, '<24',
                          IF(usia >= 24 AND usia <= 29, '24-29',
                          IF(usia >= 30 AND usia <= 34, '30-34',
                          IF(usia >= 35 AND usia <= 39, '35-39',
                          IF(usia >= 40 AND usia <= 44, '40-44',
                          IF(usia >= 45 AND usia <= 49, '45-49',
                          IF(usia >= 50 AND usia <= 55, '50-55', '>56'))))))) AS G,
						  IF(usia < 24, '1',
                          IF(usia >= 24 AND usia <= 29, '2',
                          IF(usia >= 30 AND usia <= 34, '3',
                          IF(usia >= 35 AND usia <= 39, '4',
                          IF(usia >= 40 AND usia <= 44, '5',
                          IF(usia >= 45 AND usia <= 49, '6',
                          IF(usia >= 50 AND usia <= 55, '7', '8'))))))) AS GG FROM
                (SELECT nip, FLOOR(DATEDIFF(NOW(), tgllahir) / 365) AS usia FROM pegawai WHERE aktif = 1 ) AS X) AS X GROUP BY G ORDER BY GG";	
}
elseif ($stat == 5)
{
	$bartitle = "Banyaknya Pejabat Struktural Berdasarkan Diklat";
	$pietitle = "Prosentase Banyaknya Pejabat Struktural Berdasarkan Diklat";
	$xtitle = "Diklat";
	$ytitle = "Jumlah";
	
	$sql = "SELECT diklat, COUNT(nip) FROM 
	        ( SELECT p.nip, IF(NOT pl.idpegdiklat IS NULL, 'Sudah', 'Belum') AS Diklat
		  	  FROM   pegawai p, peglastdata pl, pegjab pj, jenisjabatan jj
         	  WHERE p.aktif = 1 AND pl.nip = p.nip AND pl.idpegjab = pj.replid AND pj.jenis = jj.jenis AND jj.jabatan='S'
            ) AS X
            GROUP BY diklat;";
}
elseif ($stat == 6)
{
	$bartitle = "Banyaknya Pegawai Berdasarkan Jenis Kelamin";
	$pietitle = "Prosentase Banyaknya Pegawai Berdasarkan Jenis Kelamin";
	$xtitle = "Jenis Kelamin";
	$ytitle = "Jumlah";
	
	$sql = "SELECT p.kelamin, count(p.nip)
			  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
		     WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid
   			   AND pj.idjabatan = j.replid AND NOT j.satker IS NULL
		     GROUP BY p.kelamin";
}
elseif ($stat == 7)
{
	$bartitle = "Banyaknya Pegawai Berdasarkan Status Pernikahan";
	$pietitle = "Prosentase Banyaknya Pegawai Berdasarkan Status Pernikahan";
	$xtitle = "Jenis Kelamin";
	$ytitle = "Jumlah";
	
	$sql = "SELECT p.nikah, count(p.nip)
			  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
		     WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid
   			   AND pj.idjabatan = j.replid AND NOT j.satker IS NULL
		     GROUP BY p.nikah";
}

$CF = new ChartFactory();
$CF->SqlData($sql, $bartitle, $pietitle, $xtitle, $ytitle);
if ($type == "bar")
	$CF->DrawBarChart();
elseif($type == "pie")
	$CF->DrawPieChart();		  
?>