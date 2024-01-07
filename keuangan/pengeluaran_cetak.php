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
require_once('include/sessioninfo.php');
require_once('library/departemen.php');
require_once('library/jurnal.php');

$idpengeluaran = 0;
if (isset($_REQUEST['idpengeluaran']))
	$idpengeluaran = (int)$_REQUEST['idpengeluaran'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
	
$idtransaksi = 0;
if (isset($_REQUEST['idtransaksi']))
	$idtransaksi = (int)$_REQUEST['idtransaksi'];

$nokas = "";
if (isset($_REQUEST['nokas']))
	$nokas = (int)$_REQUEST['nokas'];
	
OpenDb();
$sql = "SELECT nama FROM datapengeluaran WHERE replid = $idpengeluaran";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapengeluaran = $row[0];

$sql = "SELECT tahunbuku FROM tahunbuku WHERE replid = $idtahunbuku";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tahunbuku = $row[0];

$sql = "SELECT jenispemohon, nip, nis, pemohonlain, penerima, tanggal, tanggalkeluar, jumlah, keperluan, petugas, keterangan FROM pengeluaran WHERE replid = $idtransaksi";
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
$jumlah = FormatRupiah($row['jumlah']);
$keperluan = $row['keperluan'];
$keterangan = $row['keterangan'];
$petugas = $row['petugas'];

if ($jpemohon == 1) 
	$sql = "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$idpemohon."'";
else if ($jpemohon == 2)
	$sql = "SELECT nama FROM jbsakad.siswa WHERE nis = '".$idpemohon."'";
else
	$sql = "SELECT nama FROM pemohonlain WHERE replid = '".$idpemohon."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapemohon = $row[0];

CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cetak Kuitansi</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function cetakbukti(id) {
	newWindow('buktipengeluaran.php?idtransaksi='+id, 'BuktiPengeluaran','360','600','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	<td align="left" valign="top" background="" style="background-repeat:no-repeat">
	<table width="100%" border="0">
  	<tr><td>
    <table border="0"width="100%">
    <!-- TABLE TITLE -->
    <tr>
     
      <td width="50%" align="right" valign="top"><div align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pembayaran Pengeluaran</font></div></td>
    </tr>
    
    <tr>
      <td align="left" valign="top"><div align="right"><a href="pengeluaran.php" target="_parent">
        <font size="1" color="#000000"><b>Pengeluaran</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Pembayaran Pengeluaran</b></font> </div></td>
    </tr>
	</table>
    </td></tr>
	</table>
	<br /><br />
	<table width="60%" border="0" height="100%" cellspacing="2" cellpadding="2" align="center">
    <tr>
    	<td valign="top" align="center" width="">
        	<fieldset style="background:url(images/bttable400.png)">
            <legend></legend>
            <table border="0" cellpadding="2" cellspacing="2" align="center" width="80%">
			<tr>
                <td width="35%" align="left"><strong>Tahun Buku</strong></td>
                <td width="65%" align="left"><strong>: <?=$tahunbuku?></strong>            	</td>
            </tr>
            <tr>
            	<td align="left"><strong>Pembayaran</strong> </td>
            	<td align="left"><strong>: <?=$namapengeluaran?></strong></td>
            </tr>
            <tr>
            	<td align="left"><strong>Pemohon</strong></td>
                <td align="left"><strong>: <?=$idpemohon . " - " . $namapemohon ?></strong></td>
            </tr>
            <tr>
                <td align="left"><strong>Penerima</strong></td>
                <td align="left"><strong>: <?=$penerima?></strong></td>
            </tr>
            <tr>
                <td align="left"><strong>Tanggal</strong></td>
                <td align="left"><strong>: <?=LongDateFormat($tanggal) ?></strong></td>
            </tr>	
            <tr>
                <td align="left"><strong>Jumlah</strong></td>
                <td align="left"><strong>: <?=$jumlah ?></strong></td>
            </tr>
            <tr>
                <td valign="top" align="left"><strong>Keperluan</strong></td>
                <td align="left"><strong>: <?=$keperluan?></strong></td>
            </tr>
            <tr>
                <td valign="top" align="left"><strong>Keterangan</strong></td>
                <td align="left"><strong>: <?=$keterangan ?></strong></td>
            </tr>
            <tr height="50">
                <td align="center" colspan="3" valign="bottom" >
               	<input type="button" value="Cetak" class="but" onclick="cetakbukti(<?=$idtransaksi ?>)" />
				<input type="button" value="Tutup" class="but" onclick="document.location.href = 'pengeluaran_blank.php'" />
            </td>
            </tr>
            </table>
			</fieldset>
        </td>
    </tr>
    </table>
<!-- EOF CONTENT -->
</td></tr>
</table>
</body>
</html>