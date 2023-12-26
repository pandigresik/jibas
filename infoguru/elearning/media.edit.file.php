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

$no = $_REQUEST["no"];
$idMedia = $_REQUEST["idMedia"];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tambah File Media E-Learning Baru</title>
    <script language="javascript" src="../script/jquery-1.9.1.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/validatorx.js"></script>
    <script language="javascript" src="media.edit.file.js?m=<?=filemtime("media.edit.file.js")?>"></script>
    <script language="javascript" src="setting.js?m=<?=filemtime("setting.js")?>"></script>
</head>

<body topmargin="10" leftmargin="10">
<span style="font-size: 18px">Tambah Media File</span><br>
<br>
<input type="hidden" id="no" name="no" value="<?=$no?>">
<input type="hidden" id="idMedia" name="idMedia" value="<?=$idMedia?>">

<table border="0" cellspacing="0" cellpadding="5">
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
        <input type="button" class="but" style="height: 30px" value="Simpan" onclick="simpanMediaFile()">
    </td>
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>
