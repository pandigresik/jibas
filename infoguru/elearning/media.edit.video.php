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
require_once("common.func.php");
require_once("setting.php");

$no = $_REQUEST["no"];
$idMedia = $_REQUEST["idMedia"];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ubah Media Video</title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/validatorx.js"></script>
    <script language="javascript" src="media.edit.video.js?m=<?=filemtime("media.edit.video.js")?>"></script>
    <script language="javascript" src="setting.js?m=<?=filemtime("setting.js")?>"></script>
</head>

<body topmargin="10" leftmargin="10">
<br>
<span style="font-size: 18px">Ubah Media Video</span><br>
<input type="hidden" id="no" value="<?=$no?>">
<input type="hidden" id="idMedia" value="<?=$idMedia?>">
<br>

<table border="0" cellpadding="2" cellspacing="0">
<tr>
    <td align="left" valign="top">
        <input type="file" id="fileVideo" name="fileVideo" onchange="playSelectedFile()" style="height: 30px; font-size: 14px; width: 500px"><br>
    </td>
    <td align="left" valign="top">
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
        <video id="video" onerror="failed(event)" controls="controls" preload="none" style="height: 300px; width: 500px"></video>
    </td>
    <td align="left" width="400" valign="top" style="background-color: #daf4fc;">
        <span style="font-weight: bold">THUMBNAIL IMAGE:</span><br>
        <input type="button" value="Create Thumbnail Image" class="but" style="height: 25px" onclick="createPreview()"><br>
        <img id="previewImage" name="previewImage" />
        <input type="hidden" id="coverImage" name="coverImage" value="">
    </td>
</tr>
</table>
<br>
<center>
<input type="button" class="but" style="height: 30px; width: 120px;" value="Simpan" onclick="simpanVideo()">
</center>
</body>
</html>