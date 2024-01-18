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
require_once('../inc/db_functions.php');
require_once('../inc/common.php');

$departemen = '-1';
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$angkatan = '';
if (isset($_REQUEST['angkatan']))
	$angkatan = $_REQUEST['angkatan'];
$kriteria = '1';
if (isset($_REQUEST['kriteria']))
	$kriteria = $_REQUEST['kriteria'];
if ($departemen=='-1'){
	$ndepartemen = 'Semua Departemen';
} else {
	$ndepartemen = $departemen;
}	
if ($angkatan==''){
	$nangkatan = 'Semua Angkatan';
} else {
	OpenDb();
	$sql = "SELECT * FROM angkatan WHERE replid=$angkatan";
	$result = QueryDb($sql);
	$row = @mysqli_fetch_array($result);
	$nangkatan = $row['angkatan'];
}	
$krit = ['', 'Agama', 'Asal Sekolah', 'Golongan Darah', 'Jenis Kelamin', 'Kewarganegaraan', 'Kode Pos Siswa', 'Kondisi Siswa', 'Pekerjaan Ayah', 'Pekerjaan Ibu', 'Pendidikan Ayah', 'Pendidikan Ibu', 'Penghasilan Orang Tua', 'Status Aktif', 'Status Siswa', 'Suku', 'Tahun Kelahiran', 'Usia'];
$nkriteria = $krit[$kriteria];
$dep = $departemen;
if ($departemen=='-1')
	$dep = "yayasan";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Rapor]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($dep) ?>
	
<center>
  <font size="4"><strong>STATISTIK KESISWAAN</strong></font><br />
 </center><br /><br />
<table>
<tr>
	<td width="25%" class="news_content1"><strong>Departemen</strong></td>
    <td class="news_content1">: 
      <?=$ndepartemen?></td>
</tr>
<tr>
	<td class="news_content1"><strong>Angkatan</strong></td>
    <td class="news_content1">: 
      <?=$nangkatan?></td>
</tr>
<tr>
	<td class="news_content1"><strong>Kriteria</strong></td>
    <td class="news_content1">: 
      <?=$nkriteria ?></td>
</tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="center"><img src="<?="statimage.php?type=bar&dep=$departemen&angkatan=$angkatan&krit=$kriteria" ?>" /></td>
      </tr>
      <tr>
        <td align="center"><img src="<?="statimage.php?type=pie&dep=$departemen&angkatan=$angkatan&krit=$kriteria" ?>" /></td>
      </tr>
      <tr>
        <td align="center">
        <?php
		$filter = "";
		if ($departemen == "-1")
			$filter="AND a.replid=s.idangkatan ";
		if ($departemen != "-1" && $angkatan == "")
			$filter="AND a.departemen='$departemen' AND a.replid=s.idangkatan ";
		if ($departemen != "-1" && $angkatan != "")
			$filter="AND s.idangkatan=$angkatan AND a.replid=s.idangkatan AND a.departemen='$departemen' ";
		
		if ($kriteria == 1) 
		{
			$xtitle = "Agama";
			$ytitle = "Jumlah";
		
			$sql = "SELECT s.agama, count(s.replid), s.agama AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.agama";	
		}
		elseif ($kriteria == 2) 
		{
			$xtitle = "Asal Sekolah";
			$ytitle = "Jumlah";
		
			$sql = "SELECT s.asalsekolah, count(s.replid), s.asalsekolah AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.asalsekolah";	
		}
		elseif ($kriteria == 3) 
		{
			$xtitle = "Golongan Darah";
			$ytitle = "Jumlah";
		
			$sql = "SELECT s.darah, count(s.replid), s.darah AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.darah";	
		}
		elseif ($kriteria == 4)
		{
			$xtitle = "Jenis Kelamin";
			$ytitle = "Jumlah";
			$sql	=  "SELECT IF(s.kelamin='l','Laki - laki','Perempuan') as X, COUNT(s.nis), s.kelamin AS XX FROM siswa s, angkatan a WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X";
		}
		elseif ($kriteria == 5)
		{
			$xtitle = "Warga Negara";
			$ytitle = "Jumlah";
			$sql = "SELECT s.warga, count(s.replid), s.warga AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.warga ORDER BY s.warga DESC";
		}
		elseif ($kriteria == 6)
		{
			$xtitle = "Kodepos";
			$ytitle = "Jumlah";
			$sql = "SELECT s.kodepossiswa, count(s.replid), s.kodepossiswa AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.kodepossiswa ";
		}
		elseif ($kriteria == 7)
		{
			$xtitle = "Kondisi";
			$ytitle = "Jumlah";
			$sql = "SELECT s.kondisi, count(s.replid), s.kondisi AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.kondisi ";
		}
		elseif ($kriteria == 8)
		{
			$xtitle = "Pekerjaan Ayah";
			$ytitle = "Jumlah";
			$sql = "SELECT s.pekerjaanayah, count(s.replid), s.pekerjaanayah AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pekerjaanayah ";
		}
		elseif ($kriteria == 9)
		{
			$xtitle = "Pekerjaan Ibu";
			$ytitle = "Jumlah";
			$sql = "SELECT s.pekerjaanibu, count(s.replid), s.pekerjaanibu AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pekerjaanibu ";
		}
		elseif ($kriteria == 10)
		{
			$xtitle = "Pendidikan Ayah";
			$ytitle = "Jumlah";
			$sql = "SELECT s.pendidikanayah, count(s.replid), s.pendidikanayah AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pendidikanayah ";
		}
		elseif ($kriteria == 11)
		{
			$xtitle = "Pendidikan Ibu";
			$ytitle = "Jumlah";
			$sql = "SELECT s.pendidikanibu, count(s.replid), s.pendidikanibu AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY s.pendidikanibu ";
		}
		elseif ($kriteria == 12)
		{
			$xtitle = "Penghasilan (rupiah)";
			$ytitle = "Jumlah";
			$sql = "SELECT G, COUNT(nis), G FROM (
					  SELECT nis, IF(s.penghasilanibu + s.penghasilanayah < 1000000, '< 1 juta',
								  IF(s.penghasilanibu + s.penghasilanayah >= 1000001 AND s.penghasilanibu + s.penghasilanayah <= 2500000, '1 juta - 2,5 juta',
								  IF(s.penghasilanibu + s.penghasilanayah >= 2500001 AND s.penghasilanibu + s.penghasilanayah <= 5000000, '2,5 juta - 5 juta',
								  IF(s.penghasilanibu + s.penghasilanayah >= 5000001 , '> 5 juta', 'Tidak Ada Data')))) AS G
					    FROM siswa s, angkatan a
					   WHERE a.aktif = 1
					     AND s.aktif = 1
						     $filter
					) AS X 		 
					GROUP BY G";
		}
		elseif ($kriteria == 13)
		{
			$xtitle = "Status Aktif";
			$ytitle = "Jumlah";
			$sql	=  "SELECT IF(s.aktif=1,'Aktif','Tidak Aktif') as X, COUNT(s.nis), s.aktif AS XX FROM siswa s, angkatan a WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X";
		}
		elseif ($kriteria == 14)
		{
			$xtitle = "Status Siswa";
			$ytitle = "Jumlah";
			$sql = "SELECT s.status as X, count(s.replid), s.status AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X ";
		}
		elseif ($kriteria == 15)
		{
			$xtitle = "Suku";
			$ytitle = "Jumlah";
			$sql = "SELECT s.suku as X, count(s.replid), s.suku AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X ";
		}
		elseif ($kriteria == 16)
		{
			$xtitle = "Tahun Lahir";
			$ytitle = "Jumlah";
			$sql = "SELECT YEAR(s.tgllahir) as X, count(s.replid), YEAR(s.tgllahir) AS XX FROM 
					siswa s, angkatan a 
					WHERE a.aktif=1 AND s.aktif=1 $filter GROUP BY X ORDER BY X ";
		}
		elseif ($kriteria == 17)
		{
			$xtitle = "Usia (tahun)";
			$ytitle = "Jumlah";
			$sql = "SELECT G, COUNT(nis), G FROM (
					  SELECT nis, IF(YEAR(NOW()) - YEAR(s.tgllahir) < 6, '<6',
								  IF(YEAR(NOW()) - YEAR(s.tgllahir) >= 6 AND YEAR(NOW()) - YEAR(s.tgllahir) <= 12, '6-12',
								  IF(YEAR(NOW()) - YEAR(s.tgllahir) >= 13 AND YEAR(NOW()) - YEAR(s.tgllahir) <= 15, '13-15',
								  IF(YEAR(NOW()) - YEAR(s.tgllahir) >= 16 AND YEAR(NOW()) - YEAR(s.tgllahir) <= 18, '16-18', '>18')))) AS G
						FROM siswa s, angkatan a
					   WHERE a.aktif = 1
					     AND s.aktif = 1
						     $filter
					) AS X 		 
					GROUP BY G";
		}
		
		?>
        <table width="100%" border="1" class="tab" align="center">
          <tr>
            <td height="25" align="center" class="header">No.</td>
            <td width="377" height="25" align="center" class="header"><?=$xtitle?></td>
            <td width="760" height="25" align="center" class="header"><?=$ytitle?></td>
            </tr>
          <?php
		  OpenDb();
		  $result = QueryDb($sql);
		  $cnt=1;
		  while ($row = @mysqli_fetch_row($result)){
		  ?>
          <tr>
            <td width="45" height="20" align="center"><?=$cnt?></td>
            <td height="20">&nbsp;&nbsp;<?=$row[0]?></td>
            <td height="20" align="center"><?=$row[1]?> siswa</td>
            </tr>
          <?php
		  $cnt++;
		  }
		  ?>
        </table>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>

	</td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>

</html>