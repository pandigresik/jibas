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
require_once('../library/departemen.php');
require_once('../include/errorhandler.php');
require_once('autotrans.payment.func.php');

$_SESSION["autotransstep"] = 1;

$departemen = "";
if (isset($_REQUEST["departemen"]))
    $departemen = $_REQUEST["departemen"];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
    <script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
    <link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah.js"></script>
    <script language="javascript" src="autotrans.payment3.js"></script>
</head>

<body>

<table border="0" width="100%" height="100%">
<tr><td align="center" valign="top" background="../images/bulu1.png" style="background-repeat:no-repeat">

    <table border="0" width="100%" align="center">
    <tr><td align="left" valign="top">

        <table border="0" width="95%" align="center">
        <tr>
            <td align="right">
                <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
                <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Batch Payment</font>
            </td>
        </tr>
        <tr>
            <td align="right">
                <a href="../penerimaan.php">
                    <font size="1" color="#000000"><b>Penerimaan</b></font></a>&nbsp>&nbsp
                <font size="1" color="#000000"><b>Batch Payment</b></font>
            </td>
        </tr>
        <tr>
            <td align="left">&nbsp;</td>
        </tr>
        </table><br />

        <form name="main" method="post" action="autotrans.payment.save.php" onsubmit="return validateSubmit()">
        <table width="100%" border="0">
        <tr>
            <td width="2%">&nbsp;</td>
            <td align="left" width="10%"><strong>Departemen&nbsp;</strong></td>
            <td width="*">
<?php          ShowSelectDept(); ?>
            <strong>Tahun Buku&nbsp;</strong>
<?php          ShowAccYear();    ?>
            </td>
        </tr>
        <tr>
            <td width="2%">&nbsp;</td>
            <td><strong>Nama&nbsp;</strong></td>
            <td>
                <input type="hidden" name="kelompok" id="kelompok">
                <input type="text" name="noid" id="noid" size="15" readonly style="background-color:#daefff; font-size: 14px;">
                <input type="text" name="nama" id="nama" size="30" readonly style="background-color:#daefff; font-size: 14px;">
                <input type="hidden" name="kelas" id="kelas">
                <input type="button" class="but" value="..." style="width: 40px; height: 23px;" onclick="SearchUser()">
                &nbsp;&nbsp;&nbsp;
                <i>Scan Barcode</i>
                <input name="txBarcode" id="txBarcode" type="text" style="width: 200px; font-size: 18px;"
                       onfocus="this.style.background = '#27d1e5'"
                       onblur="this.style.background = '#FFFFFF'"
                       onkeyup="return scanBarcode(event)">
                <br>
                <span id="spScanInfo" name="spScanInfo" style="color: red"></span>
            </td>
        </tr>
        <tr>
            <td width="2%">&nbsp;</td>
            <td align="left"><strong>Pengaturan</strong></td>
            <td width="*">
                <div id="divPaymentSelect">

                </div>
                <div id="divPaymentInfo" style="font-style: italic">

                </div>
            </td>
        </tr>
        <tr>
            <td width="2%">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td width="*">
                <div id="divPaymentList">

                </div>
            </td>
        </tr>
        </table>
        </form>

    </td></tr>
    </table>

</td></tr>
</table>

</body>

</html>
<?php
CloseDb();
?>
