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
require_once("../include/sessionchecker.php");
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../include/theme.php');

$stat = $_REQUEST['stat'];
if ($stat==5){
	$satker = $_REQUEST['satker'];
	$status = $_REQUEST['status'];
	$judul = "Daftar Pegawai Berdasarkan Diklat";
} elseif ($stat==6){
	$sat = $_REQUEST['sat'];
	$nikah  = $_REQUEST['nikah'];
	$judul = "Daftar Pegawai Berdasarkan Status Pernikahan";
} elseif ($stat==7){
	$sat = $_REQUEST['sat'];
	$jk  = $_REQUEST['jk'];
	$judul = "Daftar Pegawai Berdasarkan Jenis Kelamin";
} else {
	$ref = $_REQUEST['ref'];
	if ($stat == 1) 
	{
		$subjudul = "Satuan Kerja";
	} 
	elseif ($stat == 2)
	{
		$subjudul = "Tingkat Pendidikan";
	}
	elseif ($stat == 3)
	{
		$subjudul = "Golongan";
	}
	elseif ($stat == 4)
	{
		$subjudul = "Usia";
	}
	$judul = "Daftar Pegawai Berdasarkan ".$subjudul;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function DetailPegawai(nip) {
	var addr = "detailpegawai.php?nip="+nip;
	newWindow(addr, 'DetailPegawai','680','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function cetak() {
	var addr = "statdetail_cetak.php?stat=<?=$stat?>&ref=<?=$ref?>";
	newWindow(addr, 'CetakDetail','680','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top"><?php include("../include/headercetak.php") ?>
<center>
    <font size="4"><strong><?=$judul?></strong></font><br />
   </center><br /><br />
   
<?php if ($stat==5){ ?>
	<strong>Satuan Kerja : <?=$satker?></strong><br />
	<strong>Status Diklat: <?php if ($status == "S") echo "Sudah"; else echo "Belum"; ?></strong><br /><br />
	<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
	<tr height="35">
		<td class="header" align="center" width="7%">No</td>
		<td class="header" align="center" width="40%">NIP</td>
		<td class="header" align="center" width="40%">Nama</td>
	  </tr>
	<?php
	OpenDb();
	if ($status == "S")
		$sql = "SELECT DISTINCT p.nip, TRIM(CONCAT(IFNULL(p.gelarawal,''), ' ', p.nama, ' ', IFNULL(p.gelarakhir,''))) AS fnama 
				  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j, satker
				 WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid
				   AND pj.idjabatan = j.replid AND j.satker = '$satker'
				   AND NOT pl.idpegdiklat IS NULL
				 ORDER BY p.nama";
	else
		$sql = "SELECT DISTINCT p.nip, TRIM(CONCAT(IFNULL(p.gelarawal,''), ' ', p.nama, ' ', IFNULL(p.gelarakhir,''))) AS fnama 
				  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j, satker
				 WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid
				   AND pj.idjabatan = j.replid AND j.satker = '$satker'
				   AND pl.idpegdiklat IS NULL
				 ORDER BY p.nama";	
	$result = QueryDb($sql);
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) {
	?>
	<tr height="20">
		<td align="center" valign="top"><?=++$cnt?></td>
		<td align="center" valign="top"><?=$row[0]?></td>
		<td align="left" valign="top"><?=$row[1] ?></td>
	  </tr>
	<?php
	}
	CloseDb();
	?>
	</table>
<?php } elseif ($stat==6){ ?>
	<strong>Satuan Kerja : <?=$sat?></strong><br />
	<strong>Status Pernikahan : <?=$nikah?></strong><br /><br />
	<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
	<tr height="35">
		<td class="header" align="center" width="7%">No</td>
		<td class="header" align="center" width="40%">NIP</td>
		<td class="header" align="center" width="40%">Nama</td>
	  </tr>
	<?php
	OpenDb();
	$sql = "SELECT p.nip, TRIM(CONCAT(IFNULL(p.gelarawal,''), ' ', p.nama, ' ', IFNULL(p.gelarakhir,''))) AS fnama 
			FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
			WHERE p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND j.satker='$sat' AND p.nikah='$nikah' AND p.aktif=1 
			ORDER BY p.nama";	
	$result = QueryDb($sql);
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) {
	?>
	<tr height="20">
		<td align="center" valign="top"><?=++$cnt?></td>
		<td align="center" valign="top"><?=$row[0]?></td>
		<td align="left" valign="top"><?=$row[1] ?></td>
	  </tr>
	<?php
	}
	CloseDb();
	?>
	</table>
<?php } elseif ($stat==7){ ?>
	<strong>Satuan Kerja : <?=$sat?></strong><br />
	<strong>Kelamin : <?php if ($jk == "L") echo "Pria"; else echo "Wanita"; ?></strong><br /><br />
	<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
	<tr height="35">
		<td class="header" align="center" width="7%">No</td>
		<td class="header" align="center" width="40%">NIP</td>
		<td class="header" align="center" width="40%">Nama</td>
	  </tr>
	<?php
	OpenDb();
	$sql = "SELECT p.nip, TRIM(CONCAT(IFNULL(p.gelarawal,''), ' ', p.nama, ' ', IFNULL(p.gelarakhir,''))) AS fnama 
			FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
			WHERE p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND j.satker='$sat' AND p.kelamin='$jk' AND p.aktif=1
			ORDER BY p.nama";	
	$result = QueryDb($sql);
	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) {
	?>
	<tr height="20">
		<td align="center" valign="top"><?=++$cnt?></td>
		<td align="center" valign="top"><?=$row[0]?></td>
		<td align="left" valign="top"><?=$row[1] ?></td>
	  </tr>
	<?php
	}
	CloseDb();
	?>
	</table>
<?php } else { ?>
	<?php
	if ($stat == 1) 
	{
		$info = "Satuan Kerja";
		$sql = "SELECT p.nip, TRIM(CONCAT(IFNULL(p.gelarawal,''), ' ', p.nama, ' ', IFNULL(p.gelarakhir,''))) AS fnama 
				FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
				WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND j.satker='$ref' ORDER BY p.nama";	
	} 
	elseif ($stat == 2)
	{
		$info = "Tingkat Pendidikan";
		$sql = "SELECT p.nip, TRIM(CONCAT(IFNULL(p.gelarawal,''), ' ', p.nama, ' ', IFNULL(p.gelarakhir,''))) AS fnama 
				FROM pegawai p, peglastdata pl, pegsekolah ps
				WHERE p.aktif = 1 AND  p.nip = pl.nip AND pl.idpegsekolah = ps.replid AND ps.tingkat = '$ref' ORDER BY p.nama";
	}
	elseif ($stat == 3)
	{
		$info = "Golongan";
		$sql = "SELECT p.nip, TRIM(CONCAT(IFNULL(p.gelarawal,''), ' ', p.nama, ' ', IFNULL(p.gelarakhir,''))) AS fnama 
				FROM pegawai p, peglastdata pl, peggol pg
				WHERE p.aktif = 1 AND  p.nip = pl.nip AND pl.idpeggol = pg.replid AND pg.golongan = '$ref' ORDER BY p.nama";
	}
	elseif ($stat == 4)
	{
		$info = "Usia";
		$sql = "SELECT nip, fnama FROM (
				  SELECT nip, fnama, IF(usia < 24, '<24',
							  IF(usia >= 24 AND usia <= 29, '24-29',
							  IF(usia >= 30 AND usia <= 34, '30-34',
							  IF(usia >= 35 AND usia <= 39, '35-39',
							  IF(usia >= 40 AND usia <= 44, '40-44',
							  IF(usia >= 45 AND usia <= 49, '45-49',
							  IF(usia >= 50 AND usia <= 55, '50-55', '>56'))))))) AS G FROM
					(SELECT nip, TRIM(CONCAT(IFNULL(p.gelarawal,''), ' ', p.nama, ' ', IFNULL(p.gelarakhir,''))) AS fnama, 
							FLOOR(DATEDIFF(NOW(), tgllahir) / 365) AS usia FROM pegawai p WHERE aktif = 1) AS X) AS XX 
				WHERE G = '".$ref."'";
	}
	
	?>
		<strong><?=$info?> : <?=$ref?></strong><br /><br />
		<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
			<tr height="35">
				<td class="header" align="center" width="7%">No</td>
				<td class="header" align="center" width="40%">NIP</td>
				<td class="header" align="center" width="40%">Nama</td>
			</tr>
			<?php
			OpenDb();
			$result = QueryDb($sql);
			$cnt = 0;
			while ($row = mysqli_fetch_row($result)) {
			?>
			<tr height="20">
				<td align="center" valign="top"><?=++$cnt?></td>
				<td align="center" valign="top"><?=$row[0]?></b></td>
				<td align="left" valign="top"><?=$row[1] ?></td>
			</tr>
			<?php
			}
			CloseDb();
			?>
	</table>
<?php } ?>
</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>