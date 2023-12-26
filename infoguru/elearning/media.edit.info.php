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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../library/debugger.php');
require_once("../include/sessionchecker.php");
require_once("media.add.func.php");

$no = $_REQUEST["no"];
$idMedia = $_REQUEST["idMedia"];

OpenDb();

$sql = "SELECT id, judul, urutan, prioritas, objektif, deskripsi, pertanyaan, katakunci, 
               IF(idkategori IS NULL, 0, idkategori) AS idkategori, idchannel
          FROM jbsel.media 
         WHERE id = $idMedia";
$res = QueryDbEx($sql);
$row = mysqli_fetch_array($res);

$idChannel = $row['idchannel'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Edit Informasi Media E-Learning</title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/validatorx.js"></script>
    <script language="javascript" src="media.edit.info.js"></script>
</head>

<body topmargin="10" leftmargin="10">
<span style="font-size: 18px">Ubah Media Info</span><br>
<br>
<input type="hidden" id="no" name="no" value="<?=$no?>">
<input type="hidden" id="idMedia" name="idMedia" value="<?=$idMedia?>">

<table border="0" cellspacing="0" cellpadding="5">
<tr>
    <td width="120" align="right">
        <strong>Judul:</strong>
    </td>
    <td width="600"  align="left">
        <input type="text" id="judul" name="judul" style="font-size: 14px; height: 25px; width: 600px" maxlength="255" value="<?=$row['judul']?>">
    </td>
</tr>
<tr>
    <td align="right">
        <strong>Urutan:</strong>
    </td>
    <td align="left">
        <input type="text" id="urutan" name="urutan" style="font-size: 14px; height: 25px; width: 60px" maxlength="3" value="<?=$row['urutan']?>">
    </td>
</tr>
<tr>
    <td align="right">
        <strong>Prioritas:</strong>
    </td>
    <td align="left">
        <select id="prioritas" name="prioritas" style="font-size: 14px">
            <option value="1" <?= IntIsSelected($row['prioritas'], 1) ?>>Materi Utama</option>
            <option value="2" <?= IntIsSelected($row['prioritas'], 2) ?>>Pelengkap</option>
        </select>
    </td>
</tr>
<tr>
    <td align="right">
        <strong>Kategori:</strong><br>
        <i>dari JIBAS CBE</i>
    </td>
    <td align="left">
<?php   ShowCbKategori($row['idkategori']) ?>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Deskripsi:
    </td>
    <td align="left">
        <textarea rows="3" cols="80" id="deskripsi" name="deskripsi"><?=$row['deskripsi']?></textarea>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Objektif:
    </td>
    <td align="left">
        <textarea rows="3" cols="80" id="objektif" name="objektif"><?=$row['objektif']?></textarea>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Pertanyaan:
    </td>
    <td align="left">
        <textarea rows="3" cols="80" id="pertanyaan" name="pertanyaan"><?=$row['pertanyaan']?></textarea>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Kata Kunci:<br>
        <i>(untuk pencarian)</i>
    </td>
    <td align="left">
        <input type="text" id="kataKunci" name="kataKunci" style="font-size: 14px; height: 25px; width: 600px" maxlength="255" value="<?=$row['katakunci']?>">
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        &nbsp;
    </td>
    <td align="left">
        <input type="button" class="but" style="height: 30px" value="Simpan" onclick="simpanMediaInfo()">
    </td>
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>
