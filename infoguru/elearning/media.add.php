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
require_once("../include/sessionchecker.php");
require_once("media.add.func.php");
require_once("setting.php");

$idChannel = $_REQUEST["idChannel"];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Media E-Learning Baru</title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/validatorx.js"></script>
    <script language="javascript" src="../script/jsonutil.js"></script>
    <script language="javascript" src="media.add.js?m=<?=filemtime("media.add.js")?>"></script>
    <script language="javascript" src="setting.js?m=<?=filemtime("setting.js")?>"></script>
</head>

<body topmargin="10" leftmargin="10">
<br>
<input type="hidden" id="idChannel" name="idChannel" value="<?=$idChannel?>">

<table border="0" cellspacing="0" cellpadding="5">
<tr>
    <td width="120" align="right">
        <strong>Judul:</strong>
    </td>
    <td width="600"  align="left">
        <input type="text" id="judul" name="judul" style="font-size: 14px; height: 25px; width: 600px" maxlength="255">
    </td>
</tr>
<tr>
    <td align="right">
        <strong>Urutan:</strong>
    </td>
    <td align="left">
        <input type="text" id="urutan" name="urutan" style="font-size: 14px; height: 25px; width: 60px" maxlength="3">
    </td>
</tr>
<tr>
    <td align="right">
        <strong>Prioritas:</strong>
    </td>
    <td align="left">
        <select id="prioritas" name="prioritas" style="font-size: 14px">
            <option value="1">Materi Utama</option>
            <option value="2">Pelengkap</option>
        </select>
    </td>
</tr>
<tr>
    <td align="right">
        <strong>Kategori:</strong><br>
        <i>dari JIBAS CBE</i>
    </td>
    <td align="left">
<?php   ShowCbKategori(0) ?>
    </td>
</tr>
<tr>
    <td valign="top" align="right">
        <strong>Video:</strong>
    </td>
    <td valign="top" align="left">
        <table border="0" cellpadding="2" cellspacing="0">
        <tr>
            <td valign="top" align="left">
                <input type="file" id="fileVideo" name="fileVideo" accept=".mp4" onchange="playSelectedFile()" style="height: 30px; font-size: 14px; width: 500px"><br>
            </td>
            <td valign="top" align="left">
                <span style="color: blue; font-style: italic">
                    &bull;&nbsp;Video File Type: *.mp4<br>
                    &bull;&nbsp;Maximum Video File Size: <?= $G_MAX_VIDEO_SIZE ?> MB<br>
                </span>
            </td>
        </tr>
        </table>

        <table border="0" width="900" cellpadding="10" cellspacing="0" style="border-width: 1px; border-color: #ececec; ">
        <tr>
            <td width="500" style="background-color: #666">
                <video id="video" onerror="failed(event)" controls="controls" preload="none" style="height: 300px; width: 500px;"></video>
            </td>
            <td align="left" width="400" valign="top" style="background-color: #daf4fc;">
                <span style="font-weight: bold">THUMBNAIL IMAGE:</span><br>
                <input type="button" value="Create Thumbnail Image" class="but" style="height: 25px" onclick="createPreview()"><br>
                <img id="previewImage" name="previewImage" />
                <input type="hidden" id="coverImage" name="coverImage" value="">
            </td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Deskripsi:
    </td>
    <td align="left">
        <textarea rows="3" cols="80" id="deskripsi" name="deskripsi"></textarea>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Objektif:
    </td>
    <td align="left">
        <textarea rows="3" cols="80" id="objektif" name="objektif"></textarea>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Pertanyaan:
    </td>
    <td align="left">
        <textarea rows="3" cols="80" id="pertanyaan" name="pertanyaan"></textarea>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Kata Kunci:<br>
        <i>(untuk pencarian)</i>
    </td>
    <td align="left">
        <input type="text" id="kataKunci" name="kataKunci" style="font-size: 14px; height: 25px; width: 600px" maxlength="255">
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Berkas:<br>
        <span style="color: blue; font-style: italic">
            Max Size: <?=$G_MAX_FILE_SIZE?> MB
        </span>
    </td>
    <td align="left">
        <table id="tableFile" cellpadding='0' cellspacing='0'>
        <tbody>

        </tbody>
        <tfoot>
        <tr>
            <td align='left'>
                <input type='hidden' name='nFile' id='nFile' value='0'>
                <span style='color: blue; cursor: pointer;' onclick='addFile()'>
                    tambah berkas
                </span>
            </td>
        </tr>
        </tfoot>
        </table>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        &nbsp;
    </td>
    <td align="left">
        <input type="button" class="but" style="height: 30px" value="Simpan" onclick="simpanMedia()">
    </td>
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>
