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
require_once('daftarsurat.header.func.php');

OpenDb();

$departemen = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<script src='../../script/jquery-1.9.1.js'></script>
<script src='daftarsurat.header.js'></script>
</head>
<body>
<table width="100%" border="0" cellspacing="0">
<tr>
    <td width='40%'>
    
    <table border='0' cellpadding='2' cellspacing='0'>
    <tr>
        <td width='100px' align='right'>Departemen:</td>
        <td align='left' colspan='3'><?php ShowCbDepartemen() ?></td>
    </tr>
    <tr>
        <td align='right'>Jenis:</td>
        <td width='140px' align='left'>
            <select id='cbJenis' name='cbJenis' class='inputbox' onchange='showBlank()'>
                <option value='ALL'>(Semua Surat)</option>
                <option value='IN'>MASUK</option>
                <option value='OUT'>KELUAR</option>
            </select>
        </td>
        <td width='100px' align='right'>Kategori:</td>
        <td align='left'>
            <span id='divCbKategori'>
            <?php ShowCbKategori($departemen) ?>
            </span>
        </td>
    </tr>
    <tr>
        <td width='100px' align='right'>Bulan:</td>
        <td align='left' colspan='3'><?php ShowCbDate90() ?></td>
    </tr>
    <tr>
        <td width='100px' align='right'>Pencarian:</td>
        <td align='left' colspan='3'>
            <select id='cbSearchBy' onchange='showBlank()'>
                <option value='1'>Perihal</option>
                <option value='2'>Nomor</option>
                <option value='3'>Deskripsi</option>
                <option value='4'>Keterangan</option>
            </select>
            <input type='text' id='txKeyword' size='20' class='inputbox'>
            <input type='button' class='but' onclick='doSearch()' value='Cari'>&nbsp;&nbsp;
            <input type='button' class='but' onclick='doList()' value='Lihat Semua'>
        </td>
    </tr>
    </table>
    
    </td>
    <td width='*' align="right" valign="middle">
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Kotak Surat</font><br />
        <a href="../../home.php" target="framecenter">
        <font size="1" color="#000000"><b>Home</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Kotak Surat</b></font>
    </td>
</tr>

</table>

</body>
</html>
<?php
CloseDb();
?>