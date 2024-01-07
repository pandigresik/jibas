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
require_once('../inc/sessioninfo.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../inc/getheader.php');

OpenDb();

$departemen='yayasan';
$kriteria = $_REQUEST['kriteria'];
$noanggota = $_REQUEST['noanggota'];
$nama = $_REQUEST['nama'];
$sqlDate = "SELECT DAY(now()),MONTH(now()),YEAR(now())";
$resultDate = QueryDb($sqlDate);
$rDate = @mysqli_fetch_row($resultDate);

$tglAwal = $rDate[0]."-".$rDate[1]."-".$rDate[2];
if (isset($_REQUEST['tglAwal']))
	$tglAwal = $_REQUEST['tglAwal'];

$tglAkhir = $rDate[0]."-".$rDate[1]."-".$rDate[2];	
if (isset($_REQUEST['tglAkhir']))
	$tglAkhir = $_REQUEST['tglAkhir'];
	
if ($kriteria == 'all' || $kriteria == '')
{
	$title = "<tr><td>Berdasarkan&nbsp;:&nbsp;100 Peminjaman Yang Terlambat Terakhir</td></tr>";
}
elseif ($kriteria == 'tglpinjam')
{
	$title = "<tr><td width='20'>Berdasarkan</td><td>&nbsp;:&nbsp;Tanggal Peminjaman</td></tr>";
	$title.= "<tr><td width='20'>Periode</td><td>&nbsp;:&nbsp;$tglAwal s.d. $tglAkhir</td></tr>";
}
elseif ($kriteria == 'tglkembali')
{
	$title = "<tr><td width='20'>Berdasarkan</td><td>&nbsp;:&nbsp;Tanggal Kembali</td></tr>";
	$title.= "<tr><td width='20'>Periode</td><td>&nbsp;:&nbsp;$tglAwal s.d. $tglAkhir</td></tr>";
}
elseif ($kriteria == 'nip')
{
	$title = "<tr><td width='20'>Berdasarkan</td><td>&nbsp;:&nbsp;NIP Pegawai</td></tr>";
	$title.= "<tr><td width='20'>Pegawai</td><td>&nbsp;:&nbsp;$noanggota - $nama</td></tr>";
}
elseif ($kriteria == 'nis')
{
	$title = "<tr><td width='20'>Berdasarkan</td><td>&nbsp;:&nbsp;NIS Siswa</td></tr>";
	$title.= "<tr><td width='20'>Siswa</td><td>&nbsp;:&nbsp;$noanggota - $nama</td></tr>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../sty/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SimTaka [Cetak Daftar Peminjaman Yang Terlambat]</title>
</head>

<body>
<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>DATA PEMINJAMAN YANG TERLAMBAT</strong></font><br /> </center><br /><br />

<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:verdana; font-size:12px">
<?=$title?>
</table>
<br>
  
<?php
$sql = "SELECT DATE_FORMAT(now(),'%Y-%m-%d')";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$now = $row[0];

if ($kriteria=='all' || $kriteria=='')
	$sql = "SELECT *
			  FROM pinjam
			 WHERE status=1
			   AND tglkembali<'".$now."'
			 ORDER BY tglpinjam
			 LIMIT 100";
elseif ($kriteria=='tglpinjam')
	$sql = "SELECT *
			  FROM pinjam
			 WHERE status=1
			   AND tglpinjam BETWEEN '".MySqlDateFormat($tglAwal)."' AND '".MySqlDateFormat($tglAkhir)."'
			   AND tglkembali<'".$now."'	
			 ORDER BY tglpinjam";
elseif ($kriteria=='tglkembali')
	$sql = "SELECT *
			  FROM pinjam
			 WHERE status=1
			   AND tglkembali BETWEEN '".MySqlDateFormat($tglAwal)."' AND '".MySqlDateFormat($tglAkhir)."'
			   AND tglkembali<'".$now."'
			 ORDER BY tglpinjam";
else
	$sql = "SELECT *
			  FROM pinjam
			 WHERE status=1
			   AND (nis = '$noanggota' OR nip = '$noanggota')
			   AND tglkembali<'".$now."'
			 ORDER BY tglpinjam";
			 
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);			 
?>
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="tab" id="table">
<tr height="30">
	<td width='4%' height="30" align="center" class="header">No</td>
	<td width='18%' align="center" class="header">Tanggal Pinjam</td>
	<td width='18%' align="center" class="header">Jadwal Kembali</td>
	<td width='25%' align="center" class="header">Anggota</td>
	<td width='*' align="center" class="header">Pustaka</td>
	<td width='7%' align="center" class="header">Telat<br>(<em>hari</em>)</td>
</tr>
<?php
  if ($num>0)
  {
	$cnt = 0;
	while ($row=@mysqli_fetch_array($result))
	{
	  $cnt += 1;
	  
	  $jenisanggota = $row['info1'];
	  $idanggota = $row['idanggota'];
	  if ($jenisanggota == "siswa")
	  {
		  $idanggota = $row['nis'];
		  $sql = "SELECT nama
					FROM jbsakad.siswa
				   WHERE nis = '".$idanggota."'";
	  }
	  elseif ($jenisanggota == "pegawai")
	  {
		  $idanggota = $row['nip'];
		  $sql = "SELECT nama
					FROM jbssdm.pegawai
				   WHERE nip = '".$idanggota."'";
	  }
	  else
	  {
		  $idanggota = $row['idmember'];
		  $sql = "SELECT nama
					FROM jbsperpus.anggota
				   WHERE noregistrasi = '".$idanggota."'";
	  }
	  $res3 = QueryDb($sql);
	  $row3 = mysqli_fetch_row($res3);
	  $namaanggota = $row3[0];
	  
	  $sql = "SELECT judul
				FROM pustaka p, daftarpustaka d
			   WHERE d.pustaka = p.replid
				 AND d.kodepustaka = '".$row['kodepustaka']."'";
	  $res = QueryDb($sql);
	  $r = @mysqli_fetch_row($res);
	  $judul = $r[0];
	  
	  $color = '#000000';
	  $weight = '';
	  $alt = 'OK';
	  $img = '<img src="../img/ico/Valid.png" width="16" height="16" title='.$alt.' />';
	  if ($row['tglkembali']<=$now)
	  {
		if ($row['tglkembali']==$now)
		{
		  $alt = 'Hari&nbsp;ini&nbsp;batas&nbsp;pengembalian&nbsp;terakhir';
		  $color='#cb6e01';
		  $weight='font-weight:bold';
		}
		elseif ($row['tglkembali']<$now)
		{
		  $diff = @mysqli_fetch_row(QueryDb("SELECT DATEDIFF('".$now."','".$row['tglkembali']."')"));
		  $alt = 'Terlambat&nbsp;'.$diff[0].'&nbsp;hari';
		  $color='red';
		  $weight='font-weight:bold';
		}
		$img='<img src="../img/ico/Alert2.png" width="16" height="16" title='.$alt.' />';
	  }  ?>
	  <tr height="25" style="color:<?=$color?>; <?=$weight?>">
		<td align="center"><?=$cnt?></td>
		<td align="center"><?=LongDateFormat($row['tglpinjam'])?></td>
		<td align="center"><?=LongDateFormat($row['tglkembali'])?></td>
		<td align="left"><?=$idanggota?><br><?=$namaanggota?></td>
		<td align="left"><?=$row['kodepustaka'] . "<br>$judul" ?></td>
		<td align="center"><?=$diff[0]?></td>
	  </tr>
<?php  } //while
  }
  else
  {  ?>
	<tr>
	  <td height="25" colspan="6" align="center" class="nodata">Tidak ada data</td>
	</tr>
<?php
  }
?>	
</table>

</td></tr></table>
</body>
<?php
CloseDb();
?>
<script language="javascript">
window.print();
</script>
</html>