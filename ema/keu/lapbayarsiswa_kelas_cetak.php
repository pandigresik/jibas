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
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
$idkategori = "";
if (isset($_REQUEST['idkategori']))
	$idkategori = $_REQUEST['idkategori'];

$idpenerimaan = 0;
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$idangkatan = 0;
if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];
	
$idtingkat = -1;
if (isset($_REQUEST['idtingkat']))
	$idtingkat = (int)$_REQUEST['idtingkat'];

$idkelas = -1;
if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];

$statuslunas = -1;
if (isset($_REQUEST['lunas']))
	$statuslunas = (int)$_REQUEST['lunas'];

$varbaris=5;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
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
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>DATA PEMBAYARAN SETIAP KELAS</strong></font><br />
 </center><br /><br />
<table width="29%">
<tr>
	<td width="25%" class="news_content1"><strong>Departemen</strong></td>
    <td class="news_content1">: 
      <?=$departemen ?></td>
    <td class="news_content1"><strong>Kategori</strong></td>
    <td class="news_content1">&nbsp;</td>
</tr>
<tr>
  <td class="news_content1"><strong>Tingkat</strong></td>
  <td class="news_content1">&nbsp;</td>
  <td class="news_content1"><strong>Pembayaran</strong></td>
  <td class="news_content1">&nbsp;</td>
</tr>
<tr>
  <td class="news_content1"><strong>Kelas</strong></td>
  <td class="news_content1">&nbsp;</td>
  <td class="news_content1"><strong>Status</strong></td>
  <td class="news_content1">&nbsp;</td>
</tr>
<tr>
  <td class="news_content1"><strong>Angkatan</strong></td>
  <td class="news_content1">&nbsp;</td>
  <td class="news_content1">&nbsp;</td>
  <td class="news_content1">&nbsp;</td>
</tr>
<tr></tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="center"><img src="<?="statimage.php?type=bar&krit=$kriteria" ?>" /></td>
      </tr>
      <tr>
        <td align="center"><img src="<?="statimage.php?type=pie&krit=$kriteria" ?>" /></td>
      </tr>
      <tr>
        <td align="center">
        <?php
		if ($kriteria == 1) 
		{
			$bartitle = "Banyaknya Pegawai berdasarkan Bagian";
			$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Bagian";
			$xtitle = "Bagian";
			$ytitle = "Jumlah";
		
			$sql = "SELECT bagian, count(replid), bagian AS XX FROM 
					$db_name_sdm.pegawai
					WHERE aktif=1 GROUP BY bagian";	
		}
		if ($kriteria == 2) 
		{
			$bartitle = "Banyaknya Pegawai berdasarkan Agama";
			$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Agama";
			$xtitle = "Agama";
			$ytitle = "Jumlah";
		
			$sql = "SELECT agama, count(replid), agama AS XX FROM 
					$db_name_sdm.pegawai
					WHERE aktif=1 GROUP BY agama";	
		}
		if ($kriteria == 3) 
		{
			$bartitle = "Banyaknya Pegawai berdasarkan Gelar";
			$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Gelar";
			$xtitle = "Gelar";
			$ytitle = "Jumlah";
		
			$sql = "SELECT gelar, count(replid), gelar AS XX FROM 
					$db_name_sdm.pegawai
					WHERE aktif=1 GROUP BY gelar";	
		}
		
		if ($kriteria == 4)
		{
			$bartitle = "Banyaknya Pegawai berdasarkan Jenis Kelamin";
			$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Jenis Kelamin";
			$xtitle = "Jenis Kelamin";
			$ytitle = "Jumlah";
			$sql	=  "SELECT IF(kelamin='l','Laki - laki','Perempuan') as X, COUNT(nip), kelamin AS XX FROM $db_name_sdm.pegawai  WHERE aktif=1 GROUP BY X";
		}
		
		if ($kriteria == 5)
		{
			$bartitle = "Banyaknya Pegawai berdasarkan Status Aktif";
			$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Status Aktif";
			$xtitle = "Status Aktif";
			$ytitle = "Jumlah";
			$sql	=  "SELECT IF(aktif=1,'Aktif','Tidak Aktif') as X, COUNT(nip), aktif AS XX FROM $db_name_sdm.pegawai GROUP BY X";
		}
		
		if ($kriteria == 6)
		{
			$bartitle = "Banyaknya Pegawai berdasarkan Status Menikah";
			$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Status Menikah";
			$xtitle = "Menikah";
			$ytitle = "Jumlah";
			$sql	=  "SELECT IF(nikah='menikah','Menikah','Belum Menikah') as X, COUNT(nip), nikah AS XX FROM $db_name_sdm.pegawai  WHERE aktif=1 GROUP BY X";
		}
		if ($kriteria == 7) 
		{
			$bartitle = "Banyaknya Pegawai berdasarkan Suku";
			$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Suku";
			$xtitle = "Suku";
			$ytitle = "Jumlah";
		
			$sql = "SELECT suku, count(replid), suku AS XX FROM 
					$db_name_sdm.pegawai
					WHERE aktif=1 GROUP BY suku";	
		}
		if ($kriteria == 8)
		{
			$bartitle = "Banyaknya Pegawai berdasarkan Tahun Kelahiran";
			$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Tahun Kelahiran";
			$xtitle = "Tahun Lahir";
			$ytitle = "Jumlah";
			$sql = "SELECT YEAR(tgllahir) as X, count(replid), YEAR(tgllahir) AS XX FROM 
					$db_name_sdm.pegawai
					WHERE aktif=1 GROUP BY X ORDER BY X ";
		}
		if ($kriteria == 9)
		{
			$bartitle = "Banyaknya Pegawai berdasarkan Usia";
			$pietitle = "Prosentase Banyaknya Pegawai berdasarkan Usia";
			$xtitle = "Usia (tahun)";
			$ytitle = "Jumlah";
			$sql = "SELECT G, COUNT(nip), XX FROM (
					  SELECT nip, IF(usia < 20, '<20',
								  IF(usia >= 20 AND usia <= 30, '20-30',
								  IF(usia >= 30 AND usia <= 40, '30-40',
								  IF(usia >= 40 AND usia <= 50, '40-50','>50')))) AS G,
								  IF(usia < 20, '1',
								  IF(usia >= 20 AND usia <= 30, '2',
								  IF(usia >= 30 AND usia <= 40, '3',
								  IF(usia >= 40 AND usia <= 50, '4','5')))) AS GG,
								  IF(usia < 20, 'YEAR(now())__YEAR(tgllahir)<20',
								  IF(usia >= 20 AND usia <= 30, 'YEAR(now())__YEAR(tgllahir)>=20 AND YEAR(now())__YEAR(tgllahir)<=30',
								  IF(usia >= 30 AND usia <= 40, 'YEAR(now())__YEAR(tgllahir)>=30 AND YEAR(now())__YEAR(tgllahir)<=40',
								  IF(usia >= 40 AND usia <= 50, 'YEAR(now())__YEAR(tgllahir)>=40 AND YEAR(now())__YEAR(tgllahir)<=50','YEAR(now())__YEAR(tgllahir)>=50')))) AS XX FROM
						(SELECT nip, YEAR(now())-YEAR(tgllahir) AS usia FROM $db_name_sdm.pegawai WHERE aktif=1) AS X) AS X GROUP BY G ORDER BY GG";
		}
		//echo $sql;
		?>
        <table width="100%" border="1" class="tab" align="center">
          <tr>
            <td height="25" align="center" class="header">No.</td>
            <td height="25" align="center" class="header"><?=$xtitle?></td>
            <td height="25" align="center" class="header"><?=$ytitle?></td>
            </tr>
          <?php
		  OpenDb();
		  $result = QueryDb($sql);
		  $cnt=1;
		  while ($row = @mysqli_fetch_row($result)){
		  ?>
          <tr>
            <td width="15" height="20" align="center"><?=$cnt?></td>
            <td height="20">&nbsp;&nbsp;<?=$row[0]?></td>
            <td height="20" align="center"><?=$row[1]?> orang</td>
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