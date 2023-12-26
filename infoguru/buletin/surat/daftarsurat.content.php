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
require_once('../../include/sessionchecker.php');
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('daftarsurat.content.func.php');

OpenDb();

$nip = SI_USER_ID();
$departemen = $_REQUEST['departemen'];
$jenis = $_REQUEST['jenis'];
$kategori = $_REQUEST['kategori'];
$bulan1 = $_REQUEST['bulan1'];
$tahun1 = $_REQUEST['tahun1'];
$bulan2 = $_REQUEST['bulan2'];
$tahun2 = $_REQUEST['tahun2'];
$searchby = $_REQUEST['searchby'];
$keyword = $_REQUEST['keyword'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<link href="../../script/themes/base/jquery.ui.all.css" rel="stylesheet" type="text/css" />  
<script src="../../script/jquery-1.9.1.js"></script>
<script src="../../script/jquery-ui-1.10.3.custom.min.js"></script>
<script src="../../script/tools.js"></script>
<script src='daftarsurat.content.js'></script>
</head>
<body>
<br>
<table id='tabListSurat' border='1'
       style='border-width: 1px; border-color: #bbb; border-collapse: collapse;'
       width='100%' cellspacing='0' cellpadding='2'>
<tr height='22'>
    <td align='center' valign='middle' class='header' width='3%'>No</td>
    <td align='center' valign='middle' class='header' width='15%'>Tanggal/Nomor</td>
    <td align='center' valign='middle' class='header' width='*'>Perihal/Kategori</td>
    <td align='center' valign='middle' class='header' width='5%'>Jumlah<br>Berkas</td>
    <td align='center' valign='middle' class='header' width='14%'>Sumber</td>
    <td align='center' valign='middle' class='header' width='14%'>Tujuan</td>
    <td align='center' valign='middle' class='header' width='14%'>Tembusan</td>
    <td align='center' valign='middle' class='header' width='10%'>Komentar</td>
    <td align='center' valign='middle' class='header' width='3%'>&nbsp;</td>
</tr>
<?php
ShowListSurat();
?>
</table>    

</body>
</html>
<?php
CloseDb();
?>