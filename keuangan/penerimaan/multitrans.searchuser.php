<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 15 (January 02, 2019)
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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('multitrans.searchuser.func.php');

$departemen = $_REQUEST["departemen"];
$filter = isset($_REQUEST["filter"]) ? {$_REQUEST["filter"]} : "nama";
$data = isset($_REQUEST["data"]) ? {$_REQUEST["data"]} : "siswa";
$keyword = isset($_REQUEST["keyword"]) ? {$_REQUEST["keyword"]} : "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Cari Siswa/Calon Siswa</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css" />
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="multitrans.searchuser.js"></script>
</head>
<body topmargin="0" leftmargin="0">
<table border="0" cellpadding="10" width="100%">
<tr><td>

<fieldset>
    <legend><strong>Cari Siswa/Calon Siswa</strong></legend>
    <form name="main" method="post" onsubmit="return validateSearch()">
    <table border="0" width="100%">
    <tr>
        <td align="right" width="15%">Departemen:</td>
        <td align="left" width="*">
            <input type="text" readonly style="background-color:#ddd; font-size: 14px;" name="departemen" id="departemen" value="<?=$departemen?>">
        </td>
    </tr>
    <tr>
        <td align="right">Filter:</td>
        <td align="left">
            <select name="filter" id="filter" onchange="clearResult()" style="font-size: 14px">
                <option value="nama" <?= StringIsSelected($filter, "nama") ?>>Nama</option>
                <option value="noid" <?= StringIsSelected($filter, "noid") ?>>Nomor Induk</option>
            </select>
            <select name="data" id="data" onchange="clearResult()" style="font-size: 14px">
                <option value="siswa" <?= StringIsSelected($data, "siswa") ?>>Siswa</option>
                <option value="calon" <?= StringIsSelected($data, "calon") ?>>Calon Siswa</option>
            </select><br>
        </td>
    </tr>
    <tr>
        <td align="right">Cari:</td>
        <td align="left">
            <input type="text" id="keyword" name="keyword" style="background-color:#daefff; font-size: 14px; width: 280px;" maxlength="100" size="20" value="<?=$keyword?>">
            <input type="submit" name="cari" id="cari" value="Cari" class="but" style="width: 50px; height: 24px;"><br>
            <span id="info" style="color: #666"></span>
        </td>
    </tr>
    </table>
    </form>
</fieldset>
<br>
    <div id="searchBox" style="overflow: auto; height: 430px;">
<?php
    if (isset($_REQUEST['cari']))
    {
        OpenDb();
        SearchUser();
        CloseDb();
    }
?>
    </div>

</td></tr>
</table>
</body>
</html>