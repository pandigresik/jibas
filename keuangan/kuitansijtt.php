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

$id = $_REQUEST["id"];
$status = $_REQUEST["status"];
if ($status == "calon") 
{	
	$sql = "SELECT p.replid AS id, p.idbesarjttcalon, b.besar, c.nopendaftaran, c.nama, j.nokas, 
				   j.transaksi, date_format(p.tanggal, '%d-%b-%Y') as tanggal, p.keterangan, p.jumlah, 
				   p.petugas, j.idtahunbuku, p.info1 AS diskon 
			  FROM penerimaanjttcalon p, besarjttcalon b, jurnal j, jbsakad.calonsiswa c 
			 WHERE p.idbesarjttcalon = b.replid AND j.replid = p.idjurnal AND b.idcalon = c.replid AND p.replid = '$id'
		  ORDER BY p.tanggal, p.replid";
} 
else 
{
	$sql = "SELECT p.replid AS id, p.idbesarjtt, b.besar, b.nis, s.nama, j.nokas, 
				   j.transaksi, date_format(p.tanggal, '%d-%b-%Y') as tanggal, p.keterangan, p.jumlah, 
				   p.petugas, j.idtahunbuku, p.info1 AS diskon 
			  FROM penerimaanjtt p, besarjtt b, jurnal j, jbsakad.siswa s 
			 WHERE p.idbesarjtt = b.replid AND j.replid = p.idjurnal AND b.nis = s.nis AND p.replid = '$id' 
		  ORDER BY p.tanggal, p.replid";
} 

OpenDb();
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$nokas = $row[5];
$transaksi = $row[6];
$tanggal = $row[7];
$jumlah = $row[9];
$petugas = $row[10];
$diskon = $row[12];
$nis = $row[3];
$nama = $row[4];
$total = $row[2];
$idbesarjtt = $row[1];
$idtahunbuku = $row[11];

if ($status == "calon")
{
	$kname = "Kelompok";
	$sql = "SELECT k.kelompok
			  FROM jbsakad.calonsiswa cs, jbsakad.kelompokcalonsiswa k
			 WHERE cs.idkelompok = k.replid
			   AND cs.nopendaftaran = '".$nis."'";
}
else
{
	$kname = "Kelas";
	$sql = "SELECT k.kelas
			  FROM jbsakad.siswa s, jbsakad.kelas k
			 WHERE s.idkelas = k.replid
			   AND s.nis = '".$nis."'";	
}
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$kvalue = $row[0];

$sql = "SELECT SUM(jumlah), SUM(info1) FROM penerimaanjtt$status WHERE idbesarjtt$status = '".$idbesarjtt."'";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$jumlahbayar = $row[0];
$jumlahdiskon = $row[1];

$sql = "SELECT departemen FROM tahunbuku WHERE replid='$idtahunbuku'";
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
<title>JIBAS KEU [Kuitansi Pembayaran]</title>
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
			<font size="1"><strong>TANDA BUKTI PEMBAYARAN</strong></font>
		</td>
	</tr>
    <tr>
		<td align="left" colspan='2'>
		Telah terima dari:
        <table border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
        	<td width="20">&nbsp;</td>
        	<td width="60"><?php if ($_REQUEST["status"] == "calon") echo  "No Pendaftaran"; else echo  "N I S"; ?> </td>
            <td>:&nbsp;<strong><?=$nis ?></strong></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>Nama</td>
            <td>:&nbsp;<strong><?=$nama?></strong></td>
        </tr>
		<tr>
        	<td>&nbsp;</td>
        	<td><?=$kname?></td>
            <td>:&nbsp;<strong><?=$kvalue?></strong></td>
        </tr>
		<tr>
        	<td>&nbsp;</td>
        	<td>Tanggal</td>
            <td>:&nbsp;<strong><?= $tanggal ?></strong></td>
        </tr>
        <tr>
        	<td colspan="3" valign="top">uang sejumlah
            <font style="font-size:11px; font-weight:bold; font-style:italic;">
			<?= FormatRupiah($jumlah) ?> (<?= KalimatUang($jumlah) ?>)
            </font>
			untuk <?=$transaksi ?>
            </td>
        </tr>
        </table>
		<br>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
        	<td width="65%">
			
			<table border="1" cellpadding="2" cellspacing="0" style="border-width:1px" width="100%">
			<tr>
				<td valign="top">
				<strong>Keterangan:</strong><br>					
				<?php if ($jumlahbayar + $jumlahdiskon < $total) { ?>
				&#149;&nbsp;<em>Sisa pembayaran: <?=FormatRupiah($total - $jumlahbayar - $jumlahdiskon)?></em><br>
				<?php } ?>
				<?php if ($diskon != 0 ) { ?>
				&#149;&nbsp;<em>Sudah dipotong diskon: <?=FormatRupiah($diskon)?></em><br>
				<?php } ?>
				&#149;&nbsp;<em>Tgl cetak: <?= date('d/m/Y H:i:s') ?></em><br>
				&#149;&nbsp;<em>Petugas: <?= $petugas ?></em><br>
				</td></tr>
			</table>
            
            </td>
            <td align="center">
			<?php if ($i == 0) { ?>	
				Yang menerima<br /><br /><br /><br /><br />
				( <?=getUserName() ?> )
			<?php } else { ?>
				Yang menyerahkan<br /><br /><br /><br /><br />
				( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
			<?php } ?>
            </td>
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