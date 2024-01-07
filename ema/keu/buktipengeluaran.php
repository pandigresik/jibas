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
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');

require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

$idtransaksi = $_REQUEST['idtransaksi'];

OpenDb();
$sql = "SELECT jenispemohon, nip, nis, pemohonlain, penerima, date_format(tanggal, '%Y-%m-%d') as tanggal, date_format(tanggalkeluar, '%Y-%m-%d') as tanggalkeluar, jumlah, keperluan, petugas, keterangan, idjurnal FROM $db_name_fina.pengeluaran WHERE replid = $idtransaksi";
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
	$sql = "SELECT nama FROM $db_name_sdm.pegawai WHERE nip = '".$idpemohon."'";
else if ($jpemohon == 2)
	$sql = "SELECT nama FROM siswa WHERE nis = '".$idpemohon."'";
else
	$sql = "SELECT nama FROM $db_name_fina.pemohonlain WHERE replid = '".$idpemohon."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapemohon = $row[0];
if ($jpemohon == 3) 
	$idpemohon = "";

$sql = "SELECT date_format(now(), '%Y-%m-%d') as tanggal";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tglcetak = $row[0];

$sql = "SELECT nokas FROM $db_name_fina.jurnal WHERE replid = '".$idjurnal."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$nokas = $row[0];

CloseDb();
OpenDb();
$sql = "SELECT departemen FROM $db_name_fina.pengeluaran p, $db_name_fina.jurnal j, $db_name_fina.tahunbuku t WHERE p.replid='$idtransaksi' AND p.idjurnal=j.replid AND j.idtahunbuku=t.replid";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$departemen = $row['departemen'];
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Bukti Pengeluaran]</title>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table border="0" cellpadding="5" cellspacing="0" width="740" align="center">
<tr><td>
<?php getHeader($departemen) ?>
<?php for($i = 0; $i < 2; $i++) { ?>
<tr>
<td colspan="2" align="center" valign="top">
	<table border="0" cellpadding="2" cellspacing="0" width="90%" align="center">
    <tr>
    	<td align="right"><font size="2"><strong>No. <?=$nokas ?></strong></font></td>
    </tr>
    <tr><td align="center">
    	<br />
        <font size="2"><strong>BUKTI PENGELUARAN KAS</strong></font>
    </td></tr>
    <tr><td align="left">
    	<br />Telah terima dari:<br />
        <table cellpadding="3" width="100%">
        <tr>
        	<td width="20">&nbsp;</td>
        	<td>Nama</td>
            <td>:&nbsp;<strong><?=$idpemohon . "  " . $namapemohon ?></strong></td>
        </tr>
        
        <tr>
        	<td>&nbsp;</td>
            <td>Tanggal </td>
            <td>:&nbsp;<strong><?=LongDateFormat($tanggal) ?></strong></td>
        </tr>
      
        <tr>
        	<td colspan="2">Uang sejumlah</td>
            <td background="../img/bkmoney.png">
            <font size="2"><strong><em>
            <?=FormatRupiah($jumlah) ?>
            </em></strong></font>
            </td>
        </tr>
        <tr>
        	<td colspan="2">&nbsp;</td>
        	<td background="../img/bkmoney.png">
            (<font size="2"><strong><em>
            <?=KalimatUang($jumlah) ?>
            </em></strong></font>)
            </td>
        </tr><p>
        <tr>
        	<td colspan="3">Untuk <?=$keperluan ?></td>
        </tr>
        </table>
        
        <table border="0" width="100%" cellpadding="2" cellspacing="2">
        <tr height="160">
        	<td width="40%">
            <fieldset>
            <legend><em><strong>Keterangan</strong></em></legend>
            <table border="0" width="100%">
            <tr height="80"><td valign="top">
            <i>Tanggal dikeluarkan: <?=LongDateFormat($tanggalkeluar) ?></i><br />
            <i><?=$keterangan ?></i>
            </td></tr>
            </table>
            </fieldset>
            </td>
            <td align="right">
            <?=$G_LOKASI . ", " . LongDateFormat($tglcetak) ?><br /><br />
            <table border="1" width="100%" style="border-collapse:collapse">
            <tr height="90">
            <td align="center" width="33%" valign="top">Menyetujui<br /><br /><br /><br /><br />______________</td>
            <td align="center" width="33%" valign="top">Staf Keuangan<br /><br /><br /><br /><br /> <?=$petugas ?></td>
            <td align="center" width="33%" valign="top">Penerima<br /><br /><br /><br /><br /> <?=$namapemohon ?></td>
            </tr>
            </table>
            </td>
        </tr>
        </table>
    </td></tr>
    </table>
</td></tr>
</table>

</td></tr>
</table>
<?php if ($i == 0) { ?>
<hr width="750" style="border-style:dashed; line-height:1px; color:#999999;" />
<?php } ?>

<?php } //for ?>
</body>
<script language="javascript">
window.print();
</script>
</html>