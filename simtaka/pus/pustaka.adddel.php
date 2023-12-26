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
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../inc/sessioninfo.php');
require_once('pustaka.adddel.func.php');

OpenDb();

$idpustaka = (int)$_REQUEST['idpustaka'];
$idperpustakaan = (int)$_REQUEST['perpus'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scr/tables.js"></script>
<script type="text/javascript" src="../scr/tools.js"></script>
<script type="text/javascript" src="../scr/jquery3/jquery-1.2.6.js"></script>
<script type="text/javascript" src="pustaka.adddel.js"></script>
</head>
<body>
	<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Tambah &amp; Hapus Pustaka</font><br />
        <a href="pustaka.php" class="welc">Pustaka</a><span class="welc"> > Tambah &amp; Hapus Pustaka</span>
        <br /><br /><br />
    </div>
    <div id="content">
        <table width="100%" border="0" cellspacing="5" cellpadding="0">
        <tr height="30">
            <td width='50%' align="left">
                <font style='font-size: 12px; font-weight: bold'>
                Judul Pustaka: <?= GetTitle() ?><br>
                </font>    
            </td>
            <td width='50%' align="right">
				<a href='#' onclick='location.reload()' title='Refresh'>
					<img src='../img/ico/refresh.png' height='16'>&nbsp;
					refresh
				</a>
				&nbsp;&nbsp;
				<a href='#' onclick='TambahPustaka()' title='Refresh'>
					<img src='../img/ico/tambah.png' height='16'>&nbsp;
					tambah pustaka
				</a>
				&nbsp;&nbsp;
				<a href='#' onclick='PrintBarcode()' title='Cetak Barcode'>
					<img src='../img/barcode.png' height='16'>&nbsp;
					cetak label &amp; barcode
				</a>
            </td>            
        </tr>
        </table>
        
		<input type='hidden' id='idperpustakaan' value='<?=$idperpustakaan?>'>
		<input type='hidden' id='idpustaka' value='<?=$idpustaka?>'>
        <table width="100%" border="1" cellspacing="0" cellpadding="5" class="tab" id="table">
        <tr class="header" height="30">
            <td width='3%' align="center">No</td>
            <td width='15%' align="center">Kode Pustaka</td>
            <td width='15%' align="center">Perpustakaan</td>
            <td width='10%' align="center">Status</td>
            <td width='*' align="center">Informasi</td>
			<td width='5%' align="center">Aktif</td>
			<td width='5%' align="center">Cetak Barcode<br><a href='#' onclick='ClearCheckBarcode()' style='color: blue'>clear</a></td>
            <td width='5%' align="center">Hapus</td>
        </tr>
<?php 	ShowList() ?>		
        </table>
    </div>
	<script language='JavaScript'>
		Tables('table', 1, 0);
	</script>
</body>
</html>
<?php CloseDb(); ?>