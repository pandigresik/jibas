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
require_once('../include/errorhandler.php');
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/sessioninfo.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php');

$departemen = $_REQUEST['departemen'];
$noid = $_REQUEST['noid'];
$jenis = $_REQUEST['jenis'];
$jumlah = $_REQUEST['jumlah'];
$ktransaksi = $_REQUEST['ktransaksi'];
$ktransaksi = str_replace("<", "&lt;", (string) $ktransaksi);
$ktransaksi = str_replace(">", "&gt;", $ktransaksi);
$petugas = getUserName();

OpenDb();

$sql = "SELECT replid, nama, alamat1 FROM jbsumum.identitas WHERE departemen='$departemen'";
$result = QueryDb($sql); 
$row = @mysqli_fetch_array($result);
$idHeader = $row['replid'];
$namaHeader = $row['nama'];
$alamatHeader = $row['alamat1'];

if ($jenis == "siswa")
    $sql = "SELECT s.nama, k.kelas, DATE_FORMAT(NOW(), '%d %M %Y') AS tanggal
              FROM jbsakad.siswa s, jbsakad.kelas k
             WHERE s.idkelas = k.replid
               AND s.nis = '".$noid."'";
else
    $sql = "SELECT c.nama, k.kelompok, DATE_FORMAT(NOW(), '%d %M %Y') AS tanggal
			  FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
             WHERE c.idkelompok = k.replid
               AND c.nopendaftaran = '".$noid."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$nama = $row[0];
$kelas = $row[1];
$tanggal = $row[2];
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Cetak Tanda Bukti Pembayaran</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css" />
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script type="application/x-javascript">
    $(function() {
        $("#divReportDetail").html(window.opener.GetReportDetail());
        $("#divReportDetail2").html(window.opener.GetReportDetail());
		
		window.print();
    });        
    </script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">

<table border="0" cellpadding="10" cellspacing="0" width="780">
<tr>
    <td align="center" width='15%'>
        <img src='<?= "../library/gambar.php?replid=$idHeader&table=jbsumum.identitas" ?>' height='30' />
    </td>
    <td align="left">
        <font style='font-size:14px'><strong><?=$namaHeader?></strong></font><br>
        <font style='font-size:10px'><?=$alamatHeader?></font>
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
        	<td width="60"><?php if ($jenis == "calon") echo  "No Pendaftaran"; else echo  "N I S"; ?> </td>
            <td>:&nbsp;<strong><?=$noid ?></strong></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>Nama</td>
            <td>:&nbsp;<strong><?=$nama?></strong></td>
        </tr>
		<tr>
        	<td>&nbsp;</td>
        	<td><?php if ($jenis == "calon") echo  "Kelompok"; else echo  "Kelas"; ?></td>
            <td>:&nbsp;<strong><?=$kelas?></strong></td>
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
			untuk pembayaran: 
            </td>
        </tr>
        </table>
    </td>
</tr>        
<tr>
    <td align="left" valign="top" colspan="2">       
        <div id="divReportDetail"></div>    
    </td>    
</tr>
<tr>
    <td align="left" valign="top" colspan="2">       
        
        <table border="0" cellpadding="0" cellspacing="0" width="85%">
        <tr>
        	<td width="75%" valign='top'>
			
			<table border="1" cellpadding="2" cellspacing="0" style="border-width:1px" width="100%">
			<tr>
				<td valign="top">
				<strong>Keterangan:</strong><br>
<?php              if (strlen(trim($ktransaksi)) > 0) { ?>                
                    &#149;&nbsp;<em><?=$ktransaksi?></em><br>
<?php              } ?>                
				&#149;&nbsp;<em>Tgl cetak: <?= date('d/m/Y H:i:s') ?></em><br>
				&#149;&nbsp;<em>Petugas: <?= $petugas ?></em><br>
				</td></tr>
			</table>
            
            </td>
            <td align="center">
				Yang menerima<br /><br /><br /><br /><br />
				( <?=getUserName() ?> )
            </td>
        </tr>
        </table>
        
    </td>    
</tr>
<tr>
    <td align="left" valign="top" colspan="2">       
        <hr width="740" style="border-style:dashed; line-height:1px; color:#666;" />
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
        	<td width="60"><?php if ($jenis == "calon") echo  "No Pendaftaran"; else echo  "N I S"; ?> </td>
            <td>:&nbsp;<strong><?=$noid ?></strong></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>Nama</td>
            <td>:&nbsp;<strong><?=$nama?></strong></td>
        </tr>
		<tr>
        	<td>&nbsp;</td>
        	<td><?php if ($jenis == "calon") echo  "Kelompok"; else echo  "Kelas"; ?></td>
            <td>:&nbsp;<strong><?=$kelas?></strong></td>
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
			untuk pembayaran: 
            </td>
        </tr>
        </table>
    </td>
</tr>        
<tr>
    <td align="left" valign="top" colspan="2">       
        <div id="divReportDetail2"></div>    
    </td>    
</tr>
<tr>
    <td align="left" valign="top" colspan="2">       
        
        <table border="0" cellpadding="0" cellspacing="0" width="85%">
        <tr>
        	<td width="75%" valign='top'>
			
			<table border="1" cellpadding="2" cellspacing="0" style="border-width:1px" width="100%">
			<tr>
				<td valign="top">
				<strong>Keterangan:</strong><br>
<?php              if (strlen(trim($ktransaksi)) > 0) { ?>                
                    &#149;&nbsp;<em><?=$ktransaksi?></em><br>
<?php              } ?>                                
				&#149;&nbsp;<em>Tgl cetak: <?= date('d/m/Y H:i:s') ?></em><br>
				&#149;&nbsp;<em>Petugas: <?= $petugas ?></em><br>
				</td></tr>
			</table>
            
            </td>
            <td align="center">
				Yang menyerahkan<br /><br /><br /><br /><br />
				( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )
            </td>
        </tr>
        </table>
        
    </td>    
</tr>
</table>

</body>
</html>