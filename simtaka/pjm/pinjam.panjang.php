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
require_once('pinjam.panjang.func.php');

OpenDb();

if (isset($_REQUEST['Simpan']))
    SimpanData();

$kodepustaka = $_REQUEST['kodepustaka'];
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
<script type="text/javascript" src="pinjam.panjang.js"></script>
</head>
<body>
<font style='font-size: 18px; font-weight: bold; font-family: Arial;'>Perpanjang Peminjaman</font><br><br>

<form name='main' method='post' onsubmit="return confirm('Data sudah benar?')">
<table border='0' cellpadding='5' cellspacing='0'>
<tr>
    <td align='right'>Kode Pustaka:</td>
    <td align='left'>
        <input type='textbox' id='kodepustaka' name='kodepustaka' value='<?=$kodepustaka?>'
               readonly style='background-color: #ccc; border-style: none' size='30'>
    </td>
</tr>
<tr>
    <td align='right'>Judul:</td>
    <td align='left'>
        <strong><?=GetTitle()?></strong>
    </td>
</tr>
<tr>
    <td align='right'>Tanggal<br>Kembali:</td>
    <td align='left'>
        <input name="tglkembali" id="tglkembali" type="text"
			   value="<?=GetReturnDate()?>" style="width:100px;" readonly="readonly" />
        <a href="javascript:TakeDate('tglkembali')" >
            <img src="../img/ico/calendar.png" width="16" height="16" border="0" />
        </a>
    </td>
</tr>
<tr>
    <td align='right' valign='top'>Keterangan:</td>
    <td align='left'>
        <textarea id='keterangan' name='keterangan' rows='2' cols='37'></textarea>
    </td>
</tr>
<tr>
    <td align='center' colspan='2'>
        <input type='submit' name='Simpan' value='Simpan'>
        <input type='button' value='Tutup' onclick='window.close()'> 
    </td>
</tr>
</table>
</form>

</body>
</html>
<?php CloseDb(); ?>