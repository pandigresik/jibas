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
require_once('pustaka.adddel.tambahpustaka.func.php');

$idperpustakaan = (int)$_REQUEST['idperpustakaan'];
$idpustaka = (int)$_REQUEST['idpustaka'];

OpenDb();

if (isset($_REQUEST['Simpan'])) Save();
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
<script type="text/javascript" src="pustaka.adddel.tambahpustaka.js"></script>
</head>
<body>
<table width='100%' cellpadding='10'>
<tr>
<td align='left' valign='top'>

<img src='../img/book.png' height='24'>&nbsp;
<font style='font-size: 18px;'>Tambah Pustaka</font>
<br><br>        
<?php
$sql = "SELECT judul
          FROM jbsperpus.pustaka
         WHERE replid = '".$idpustaka."'";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$judul = $row[0];

echo "Judul: <strong>$judul</strong><br>";
?>
<form name='main' onsubmit='return ValidateSubmit()'>
<input type='hidden' id='idpustaka' name='idpustaka' value='<?=$idpustaka?>'>
<table border='0' cellpadding='5' cellspacing='0' width='90%'>
<tr height='30'>
    <td width='10%' class='header'>No</td>
    <td width='*' class='header'>Perpustakaan</td>
    <td width='12%' class='header'>Jumlah</td>
</tr>
<?php
if ($idperpustakaan == -1)
    $sql = "SELECT replid, nama
              FROM jbsperpus.perpustakaan
             ORDER BY nama";
else
    $sql = "SELECT replid, nama
              FROM jbsperpus.perpustakaan
             WHERE replid = '".$idperpustakaan."'";
$res = QueryDb($sql);
$cnt = 0;
while($row = mysqli_fetch_row($res))
{
    $cnt += 1;
    $replid = $row[0];
    $nama = $row[1];
?>
    <tr>
        <input type='hidden' name='replid<?=$cnt?>' id='replid<?=$cnt?>' value='<?=$replid?>'>
        <td align='center'><?=$cnt?></td>
        <td align='left'><?=$nama?></td>
        <td align='left'>
            <input type='textbox' size='5' maxlength='5' id='jumlah<?=$cnt?>' name='jumlah<?=$cnt?>'>
        </td>
    </tr>
<?php
}
?>
<input type='hidden' id='npus' name='npus'value='<?=$cnt?>'>
</table>
<input type='Submit' value='Simpan' name='Simpan' class='but'>
<input type='button' value='Tutup' onclick='window.close()'>
</form>
</td>    
</tr>    
</table>
</body>
</html>
<?php
CloseDb();
?>