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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/errorhandler.php');
require_once('../library/stringbuilder.php');
require_once('vendor.user.dialog.func.php');

OpenDb();

$vendorId = $_REQUEST["vendorid"];
$vendorName = GetVendorName($vendorId);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Staf Vendor</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah2.js" ></script>
    <script language="javascript" src="../script/request.factory.js?r=<?=filemtime('../script/request.factory.js')?>"></script>
    <script language="javascript" src="vendor.user.dialog.js?r=<?=filemtime('vendor.user.dialog.js')?>"></script>
</head>

<body >
<table border="0" width="100%" cellpadding="10">
<tr>
    <td align="left" valign="top">

        <span style="font-size: 14pt">Staf Vendor</span><br><br>
        <strong>Vendor: <?= "$vendorName ($vendorId)"?></strong><br><br>

        <input type="hidden" id="vendorid" name="vendorid" value="<?=$vendorId?>">

        <table border="0" width="100%" cellpadding="5" cellspacing="2">
        <tr>
            <td align="right" width="15%"><strong>Pilih Staf:</strong></td>
            <td align="left" width="*">
<?php           ShowSelectPetugas($vendorId); ?>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                <strong>Tingkat:</strong>
            </td>
            <td align="left" valign="top">
                <select id="tingkat" name="tingkat" style="width: 250px">
                    <option value="1">Manager</option>
                    <option value="2" selected>Operator</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <input type="button" class="but" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanPetugasVendor()">
                <input type="button" class="but" value="Tutup" style="width: 80px; height: 30px;"  onclick="window.close()">
            </td>
        </tr>
        </table>

    </td>
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>
