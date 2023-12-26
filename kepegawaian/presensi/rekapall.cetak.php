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
require_once('../include/config.php');
require_once('../include/common.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script type="application/x-javascript" src="../script/jquery-1.9.0.js"></script>
<script language="javascript" src="rekapall.cetak.js"></script>
</head>
<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

    <?php include("../include/headercetak.php") ?>
    <center>
      <font size="4"><strong>Rekapitulasi Presensi Pegawai</strong></font><br />
    </center>
    
    <br /><br />
<?php
OpenDb();

$tahun30 = $_REQUEST['tahun30'];
$bulan30 = $_REQUEST['bulan30'];
$tanggal30 = $_REQUEST['tanggal30'];
$tahun = $_REQUEST['tahun'];
$bulan = $_REQUEST['bulan'];
$tanggal = $_REQUEST['tanggal'];

?>

<strong>Tanggal: <?= $tanggal30 . " " . NamaBulan($bulan30) . " " . $tahun30 . " s/d " . $tanggal . " " . NamaBulan($bulan) . " " . $tahun ?></strong><br /><br />

<input type="hidden" name="tahun30" id="tahun30" value="<?=$tahun30?>">    
<input type="hidden" name="bulan30" id="bulan30" value="<?=$bulan30?>">    
<input type="hidden" name="tanggal30" id="tanggal30" value="<?=$tanggal30?>">
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>">    
<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>">    
<input type="hidden" name="tanggal" id="tanggal" value="<?=$tanggal?>">

<table border="0" cellpadding="2" cellspacing="0" width="870">
<tr>
    <td align="center" width="50%">
    <img height="250" src="<?= "rekapall.image.php?type=bar&nip=$nip&tahun30=$tahun30&bulan30=$bulan30&tanggal30=$tanggal30&tahun=$tahun&bulan=$bulan&tanggal=$tanggal" ?>" />    
    </td>
    <td align="center" width="50%">
    <img height="250" src="<?= "rekapall.image.php?type=pie&nip=$nip&tahun30=$tahun30&bulan30=$bulan30&tanggal30=$tanggal30&tahun=$tahun&bulan=$bulan&tanggal=$tanggal" ?>" />    
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
    <br><strong>Hadir:</strong><br>
    <div id="tabHadir"></div>
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
    <br><strong>Izin:</strong><br>
    <div id="tabIzin"></div>
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
    <br><strong>Sakit:</strong><br>
    <div id="tabSakit"></div>
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
    <br><strong>Cuti:</strong><br>
    <div id="tabCuti"></div>
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
    <br><strong>Alpa:</strong><br>
    <div id="tabAlpa"></div>
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
    <br><strong>Bebas:</strong><br>
    <div id="tabBebas"></div>
    </td>
</tr>
</table>

</body>
<?php
CloseDb();
?>
</html>