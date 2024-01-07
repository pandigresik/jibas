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
require_once('include/db_functions.php');
require_once('include/getheader.php'); 

$idkategori = $_REQUEST['idkategori'];
$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
$nis = (string)$_REQUEST['nis'];
$idtahunbuku = (int)$_REQUEST['idtahunbuku'];

OpenDb();	
$sql = "SELECT departemen FROM tahunbuku WHERE replid='$idtahunbuku'"; 	
$result = QueryDb($sql);    
$row = mysqli_fetch_row($result);	
$departemen =  $row[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Penerimaan Pembayaran Sukarela]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>


<center><font size="4"><strong>PENERIMAAN PEMBAYARAN SUKARELA</strong></font><br /> </center><br /><br />
<table border="0">
<tr>
	<td width="120"><strong>Departemen</strong></td>
    <td><strong>: 
<?=$departemen; ?>
    </strong></td>
</tr>
<tr>
	<td><strong>Tahun Buku</strong></td>
    <td><strong>:
<?php $sql = "SELECT tahunbuku FROM tahunbuku WHERE replid='$idtahunbuku'"; 	
	$result = QueryDb($sql);    
	$row = mysqli_fetch_row($result);
	echo  $row[0]; ?>
    </strong></td>
</tr>

<tr>
	<td><strong>Kategori</strong></td>
    <td><strong>:
<?php $sql = "SELECT kategori FROM kategoripenerimaan WHERE kode='$idkategori' ORDER BY urutan";	
	$result = QueryDb($sql);    
	$row = mysqli_fetch_row($result);
	echo  $row[0]; ?>
    </strong></td>
</tr>
<tr>
	<td><strong>Jenis Penerimaan</strong></td>
    <td><strong>:
<?php $sql = "SELECT nama FROM datapenerimaan WHERE replid = '".$idpenerimaan."'"; 			
	$result = QueryDb($sql);    
	$row = mysqli_fetch_row($result);
	echo  $row[0]; ?>
    </strong></td>
</tr>
</table>
<br />
<?php
$sql = "SELECT s.replid, nama, telponsiswa as telpon, hpsiswa as hp, kelas, alamatsiswa as alamattinggal, tingkat 
          FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
			WHERE s.idkelas = k.replid AND k.idtingkat = t.replid AND nis = '".$nis."'";

$result = QueryDb($sql);
if (mysqli_num_rows($result) == 0) 
{
	CloseDb();
	exit();
} 
else 
{
	$row = mysqli_fetch_array($result);
	
	$nama = $row['nama'];
	$telpon = $row['telpon'];
	$hp = $row['hp'];
	$namakelas = $row['kelas'];
	$namatingkat = $row['tingkat'];
	$alamattinggal = $row['alamattinggal'];
	$replid = $row['replid'];
}

$sql = "SELECT nama FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
?>

     	
<fieldset>
<legend></legend>
<table border="0" width="100%" cellpadding="2" cellspacing="2">
<tr height="25">
    <td colspan="4" class="header" align="center">Data Siswa</td>
</tr>
<tr valign="top">                    
    <td width="5%"><strong>N I S</strong></td>
    <td><strong>:</strong></td>
    <td><strong><?=$nis ?></strong> </td><td rowspan="5" width="25%">
    <img src='<?="library/gambar.php?replid=".$replid."&table=jbsakad.siswa";?>' width='100' height='100'></td>
</tr>
<tr>
    <td valign="top"><strong>Nama</strong></td>
    <td valign="top"><strong>:</strong></td> 
    <td><strong><?=$nama ?></strong></td>
</tr>
<tr>
    <td><strong>Kelas</strong></td>
     <td><strong>:</strong></td>
    <td><strong><?=$namatingkat.' - '.$namakelas ?></strong></td>
</tr>
<tr>
    <td><strong>HP</strong></td>
     <td><strong>:</strong></td>
    <td><strong><?=$hp ?></strong></td>
</tr>
<tr>
    <td><strong>Telepon</strong></td>
     <td><strong>:</strong></td>
    <td><strong><?=$telpon ?></strong></td>
</tr>

<tr>
    <td valign="top"><strong>Alamat</strong></td>
    <td valign="top"><strong>:</strong></td>
    <td colspan="2" rowspan="2" valign="top"><strong>
      <?=$alamattinggal ?>
    </strong></td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>           

</table>            
</fieldset>          
</td>
</tr>
<?php  
$sql =  "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d-%b-%Y') as tanggal, p.keterangan, p.jumlah, p.petugas,
				jd.koderek AS rekkas, ra.nama AS namakas
		   FROM penerimaaniuran p, jurnal j, jurnaldetail jd, rekakun ra 
		  WHERE j.replid = p.idjurnal
			AND j.replid = jd.idjurnal
			AND jd.koderek = ra.kode
			AND j.idtahunbuku = '$idtahunbuku'
			AND p.idpenerimaan = '$idpenerimaan'
			AND p.nis = '$nis'
			AND ra.kategori = 'HARTA'
		 ORDER BY p.tanggal, p.replid";

$result = QueryDb($sql);    
?>
<tr>
<td align="center" colspan="2">
  
<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
<tr height="30" align="center">
    <td class="header" width="5%">No</td>
    <td class="header" width="15%">No. Jurnal/Tgl</td>
    <td class="header" width="15%">Rek. Kas</td>
    <td class="header" width="15%">Jumlah</td>
    <td class="header" width="*">Keterangan</td>
    <td class="header" width="15%">Petugas</td>
</tr>
<?php 

$cnt = 0;
$total = 0;
while ($row = mysqli_fetch_array($result)) {
    $total += $row['jumlah'];
?>
<tr height="25">
    <td align="center"><?=++$cnt?></td>
    <td align="center"><?="<strong>" . $row['nokas'] . "</strong><br><i>" . $row['tanggal']?></i></td>
	<td align="left"><?= $row['rekkas'] . " " . $row['namakas']  ?> </td>
    <td align="right"><?=FormatRupiah($row['jumlah'])?></td>
    <td align="left"><?=$row['keterangan'] ?></td>
    <td align="center"><?=$row['petugas'] ?></td>
</tr>
<?php
}
?>
<tr height="35">
    <td bgcolor="#996600" colspan="3" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
    <td bgcolor="#996600" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font></td>
    <td bgcolor="#996600" colspan="3">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
<?php
CloseDb();
?>
            
		
</body>
</html>
<script language="javascript">window.print();</script>