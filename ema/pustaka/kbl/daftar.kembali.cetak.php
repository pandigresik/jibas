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
require_once('../../inc/sessioninfo.php');
require_once('../../inc/common.php');
require_once('../../inc/config.php');
require_once('../../inc/db_functions.php');
require_once('../../inc/getheader.php');
require_once('../../inc/rupiah.php');

OpenDb();

$departemen='yayasan';
$kriteria='all';
if (isset($_REQUEST['kriteria']))
	$kriteria=$_REQUEST['kriteria'];
	
if ($kriteria=='nip')
	$statuspeminjam=0;
elseif ($kriteria=='nis')
	$statuspeminjam=1;
	
$noanggota = $_REQUEST['noanggota'];
$nama = $_REQUEST['nama'];

$sqlDate="SELECT DAY(now()),MONTH(now()),YEAR(now())";
$resultDate = QueryDb($sqlDate);
$rDate = @mysqli_fetch_row($resultDate);

$tglAwal = $rDate[0]."-".$rDate[1]."-".$rDate[2];
if (isset($_REQUEST['tglAwal']))
	$tglAwal = $_REQUEST['tglAwal'];
	
$tglAkhir = $rDate[0]."-".$rDate[1]."-".$rDate[2];	
if (isset($_REQUEST['tglAkhir']))
	$tglAkhir = $_REQUEST['tglAkhir'];
	
$denda=0;
if (isset($_REQUEST['denda']))
	$denda=$_REQUEST['denda'];
	
if ($kriteria=='all' || $kriteria=='')
{
	$title = "<tr><td>Berdasarkan&nbsp;:&nbsp;100&nbsp;Pengembalian&nbsp;Terakhir</td></tr>";
}
elseif ($kriteria=='tglpinjam')
{
	$title = "<tr><td width='20'>Berdasarkan</td><td>&nbsp;:&nbsp;Tanggal Peminjaman</td></tr>";
	$title.= "<tr><td width='20'>Periode</td><td>&nbsp;:&nbsp;$tglAwal s.d. $tglAkhir</td></tr>";
}
elseif ($kriteria=='tglkembali')
{
	$title = "<tr><td width='20'>Berdasarkan</td><td>&nbsp;:&nbsp;Tanggal Kembali</td></tr>";
	$title.= "<tr><td width='20'>Periode</td><td>&nbsp;:&nbsp;$tglAwal s.d. $tglAkhir</td></tr>";
}
elseif ($kriteria=='nip')
{
	$title = "<tr><td width='20'>Berdasarkan</td><td>&nbsp;:&nbsp;NIP Pegawai</td></tr>";
	$title.= "<tr><td width='20'>Pegawai</td><td>&nbsp;:&nbsp;$noanggota - $nama</td></tr>";
}
elseif ($kriteria=='nis')
{
	$title = "<tr><td width='20'>Berdasarkan</td><td>&nbsp;:&nbsp;NIS Siswa</td></tr>";
	$title.= "<tr><td width='20'>Siswa</td><td>&nbsp;:&nbsp;$noanggota - $nama</td></tr>";
}
elseif ($kriteria=='denda')
{
	if ($denda==0)
		$besardenda = "Tanpa Denda";
	elseif ($denda==1)
		$besardenda = "Dibawah Rp 5.000";
	elseif ($denda==2)
		$besardenda = "Dibawah Rp 10.000";
	elseif ($denda==3)
		$besardenda = "Diatas Rp 5.000";
	$title = "<tr><td width='20'>Berdasarkan</td><td>&nbsp;:&nbsp;Denda</td></tr>";
	$title.= "<tr><td width='20'>Besarnya&nbsp;denda</td><td>&nbsp;:&nbsp;$besardenda</td></tr>";
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Daftar Pengembalian]</title>
</head>

<body>
<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center><font size="4"><strong>DATA PENGEMBALIAN</strong></font><br /> </center><br /><br />

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

if ($kriteria=='all')
	$sql = "SELECT *
			  FROM jbsperpus.pinjam
			 WHERE status=2
			 ORDER BY tglditerima DESC
			 LIMIT 100";
elseif ($kriteria=='tglpinjam')
	$sql = "SELECT *, replid
			  FROM jbsperpus.pinjam
			 WHERE status=2
			   AND tglpinjam BETWEEN '".MySqlDateFormat($tglAwal)."' AND '".MySqlDateFormat($tglAkhir)."'
			 ORDER BY tglditerima DESC";
elseif ($kriteria=='tglkembali')
	$sql = "SELECT *, replid
			  FROM jbsperpus.pinjam
			 WHERE status=2
			   AND tglkembali BETWEEN '".MySqlDateFormat($tglAwal)."' AND '".MySqlDateFormat($tglAkhir)."'
			 ORDER BY tglditerima DESC";
elseif ($kriteria=='nip' || ($kriteria=='nis'))
	$sql = "SELECT *, replid
		      FROM jbsperpus.pinjam
			 WHERE status=2
			   AND idanggota='$noanggota'
			   AND tglkembali<'".$now."'
			 ORDER BY tglditerima DESC";
elseif ($kriteria=='denda')
{
	if ($denda==0)
		$sql = "SELECT *, p.replid AS replid
			      FROM jbsperpus.pinjam p, jbsperpus.denda d
				 WHERE p.status=2
				   AND p.replid=d.idpinjam
				   AND d.denda='0'
				 ORDER BY p.tglditerima DESC";
	elseif ($denda==1)
		$sql = "SELECT *, p.replid AS replid
				  FROM jbsperpus.pinjam p, jbsperpus.denda d
				 WHERE p.status=2
				   AND p.replid=d.idpinjam
				   AND d.denda>0
				   AND d.denda<5000
				 ORDER BY p.tglditerima DESC";
	elseif ($denda==2)
		$sql = "SELECT *, p.replid AS replid
				  FROM jbsperpus.pinjam p, jbsperpus.denda d
				 WHERE p.status=2
				   AND p.replid=d.idpinjam
				   AND d.denda>0
				   AND d.denda<10000
				 ORDER BY p.tglditerima DESC";
	elseif ($denda==3)
		$sql = "SELECT *, p.replid AS replid
		          FROM jbsperpus.pinjam p, jbsperpus.denda d
				 WHERE p.status=2
				   AND p.replid=d.idpinjam
				   AND d.denda>10000
				 ORDER BY p.tglditerima DESC";			
}
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
?>
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="tab" id="table">
<tr height="30">
	<td width='4%' align="center" class="header">No</td>
	<td width='10%' align="center" class="header">Tanggal<br>Pengembalian</td>
	<td width='10%' align="center" class="header">Jadwal Kembali</td>
	<td width='10%' align="center" class="header">Tanggal Pinjam</td>
	<td width='15%' align="center" class="header">Anggota</td>
	<td width='*' align="center" class="header">Kode Pustaka</td>
	<td width='15%' align="center" class="header">Denda</td>
</tr>
<?php
	if ($num>0)
	{
		$cnt = 0;
		while ($row = @mysqli_fetch_array($result))
		{
			$cnt += 1;
			
			$sql = "SELECT denda
					  FROM jbsperpus.denda
					 WHERE idpinjam='".$row['replid']."'";
			$res2 = QueryDb($sql);
			$row2 = @mysqli_fetch_array($res2);
			$denda = $row2['denda'];
			
			$kodepustaka = $row['kodepustaka'];
			$sql = "SELECT p.judul
					  FROM jbsperpus.daftarpustaka dp, jbsperpus.pustaka p
					 WHERE dp.pustaka = p.replid
					   AND dp.kodepustaka = '".$kodepustaka."'";
			$res2 = QueryDb($sql);
			$row2 = @mysqli_fetch_array($res2);
			$judul = $row2['judul'];
			
			$idanggota = $row['idanggota'];
			$jenisanggota = $row['info1'];
			
			if ($jenisanggota == "siswa")
			{
				$sql = "SELECT nama
						  FROM jbsakad.siswa
						 WHERE nis = '".$idanggota."'";
			}
			elseif ($jenisanggota == "pegawai")
			{
				$sql = "SELECT nama
						  FROM jbssdm.pegawai
						 WHERE nip = '".$idanggota."'";
			}
			else
			{
				$sql = "SELECT nama
						  FROM jbsperpus.anggota
						 WHERE noregistrasi = '".$idanggota."'";
			}
			$res2 = QueryDb($sql);
			$row2 = mysqli_fetch_row($res2);
			$namaanggota = $row2[0];  ?>
			
			<tr style="color:<?=$color?>; <?=$weight?>">
				<td align='center'><?=$cnt?></td>
				<td align="center"><?=LongDateFormat($row['tglditerima'])?></td>
				<td align="center"><?=LongDateFormat($row['tglkembali'])?></td>
				<td align="center"><?=LongDateFormat($row['tglpinjam'])?></td>
				<td align="left">
					<font style='font-size: 9px'><?=$idanggota?></font><br>
					<font style='font-size: 11px; font-weight: bold;'><?=$namaanggota?></font>
				</td>
				<td align="left">
					<font style='font-size: 9px'><?=$kodepustaka?></font><br>
					<font style='font-size: 11px; font-weight: bold;'><?=$judul?></font>
				</td>				
                <td align="right"><?=FormatRupiah($denda)?></td>
			</tr>
<?php
		}
	}
	else
	{
		?>
        <tr>
			<td height="25" colspan="8" align="center" class="nodata">Tidak ada data</td>
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