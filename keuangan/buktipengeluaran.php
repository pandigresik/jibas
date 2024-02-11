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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/rupiah.php');
require_once('include/sessioninfo.php');
require_once('include/db_functions.php');
require_once('include/getheader.php');

$idtransaksi = $_REQUEST['idtransaksi'];

OpenDb();

$sql = "SELECT jenispemohon, nip, nis, pemohonlain, penerima,
			   date_format(tanggal, '%Y-%m-%d') as tanggal, date_format(tanggalkeluar, '%Y-%m-%d') as tanggalkeluar,
			   jumlah, keperluan, petugas, keterangan, idjurnal
		  FROM pengeluaran
		 WHERE replid='$idtransaksi'";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$jpemohon = $row['jenispemohon'];
if ($jpemohon == 1)
	$idpemohon = $row['nip'];
else if ($jpemohon == 2)
	$idpemohon = $row['nis'];
else
	$idpemohon = $row['pemohonlain'];
	
$penerima = $row['penerima'];
$tanggal = $row['tanggal'];
$tanggalkeluar = $row['tanggalkeluar'];
$jumlah = $row['jumlah'];
$keperluan = $row['keperluan'];
$keterangan = $row['keterangan'];
$petugas = $row['petugas'];
$idjurnal = $row['idjurnal'];

if ($jpemohon == 1) 
	$sql = "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$idpemohon."'";
else if ($jpemohon == 2)
	$sql = "SELECT nama FROM jbsakad.siswa WHERE nis = '".$idpemohon."'";
else
	$sql = "SELECT nama FROM pemohonlain WHERE replid = $idpemohon";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapemohon = $row[0];
if ($jpemohon == 3) 
	$idpemohon = "";

$sql = "SELECT date_format(now(), '%Y-%m-%d') as tanggal";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tglcetak = $row[0];

$sql = "SELECT nokas FROM jurnal WHERE replid = '".$idjurnal."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$nokas = $row[0];

$sql = "SELECT departemen FROM pengeluaran p, jurnal j, tahunbuku t WHERE p.replid='$idtransaksi' AND p.idjurnal=j.replid AND j.idtahunbuku=t.replid";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$departemen = $row['departemen'];

$sql = "SELECT replid, nama, alamat1 FROM jbsumum.identitas WHERE departemen='$departemen'";
$result = QueryDb($sql); 
$row = @mysqli_fetch_array($result);
$idHeader = $row['replid'];
$namaHeader = $row['nama'];
$alamatHeader = $row['ALAMAT1'];
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Bukti Pengeluaran]</title>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
	
<table border="0" cellpadding="0" cellspacing="0" width="340" align="center">
<?php for($i = 0; $i < 2; $i++) { ?>
<tr>
<td align="center" valign="top">
	<table border="0" cellpadding="0" cellspacing="3" width="330" align="center">
	<?php if ($i == 0) { ?>		
	<tr>
		<td align="center" width='15%'>
			<img src='<?= $full_url."library/gambar.php?replid=$idHeader&table=jbsumum.identitas" ?>' height='30' />
		</td>
		<td align="left">
			<font style='font-size:14px'><strong><?=$namaHeader?></strong></font><br>
			<font style='font-size:10px'><?=$alamatHeader?></font>
		</td>
	</tr>
	<?php } else { ?>
	<tr height="1">
		<td align="center" width='15%'>&nbsp;</td>
		<td align="left">&nbsp;</td>
	</tr>
	<?php } ?>
	<tr>
		<td align="right" colspan='2'>
			<font size="1"><strong>No. <?=$nokas ?></strong></font>
		</td>
	</tr>
    <tr>
		<td align="center" colspan='2'>
			<font size="1"><strong>BUKTI PENGELUARAN KAS</strong></font>
		</td>
	</tr>
    <tr>
		<td align="left" colspan='2'>
		Telah dibayarkan kepada:
        <table border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
        	<td width="20">&nbsp;</td>
        	<td width="70">Nama</td>
            <td>:&nbsp;<strong><?=$idpemohon . "  " . $namapemohon ?></strong></td>
        </tr>
		<tr>
        	<td>&nbsp;</td>
        	<td>Tanggal</td>
            <td>:&nbsp;<strong><?=LongDateFormat($tanggal) ?></strong></td>
        </tr>
        <tr>
        	<td colspan="3" valign="top">uang sejumlah
            <font style="font-size:11px; font-weight:bold; font-style:italic;">
			<?= FormatRupiah($jumlah) ?> (<?= KalimatUang($jumlah) ?>) 
            </font>
			untuk <?=$keperluan ?>
            </td>
        </tr>
        </table>
		<br>
			
        <table border="0" width="100%">
		<tr><td valign="top">
		&#149;&nbsp;<em>Tgl cetak: <?= date('d/m/Y H:i:s') ?></em><br>
		&#149;&nbsp;<em>Keterangan: <?=$keterangan ?></em>
		</td></tr>
		</table>
		<br>
		<table border="1" width="100%" style="border-collapse:collapse">
		<tr height="60">
		<td align="center" width="33%" valign="top">Menyetujui<br /><br /><br />______________</td>
		<td align="center" width="33%" valign="top">Staf Keuangan<br /><br /><br /><?=$petugas ?></td>
		<td align="center" width="33%" valign="top">Penerima<br /><br /><br /><?=$namapemohon ?></td>
		</tr>
		</table>
		
    </td></tr>
    </table>
</td></tr>	
<tr>
	<td align='right'>
<?php if ($i == 0) { ?>
	<hr width="350" style="border-style:dashed; line-height:1px; color:#666;" />
<?php } ?>	
	</td>
</tr>	
<?php } //for ?>
</table>

</body>
<script language="javascript">
window.print();
</script>
</html>