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
require_once('autotrans.setting.func.php');

if (getLevel() == 2)
{ ?>
    <script language="javascript">
        alert('Maaf, anda tidak berhak mengakses halaman ini!');
        document.location.href = "../penerimaan.php";
    </script>
    <?php 	exit();
} // end if

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
    <script language="javascript" src="autotrans.setting.js"></script>
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
                <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pengaturan Batch Payment</font>
            </td>
        </tr>
        <tr>
            <td align="right">
                <a href="../penerimaan.php">
                    <font size="1" color="#000000"><b>Penerimaan</b></font></a>&nbsp>&nbsp
                <font size="1" color="#000000"><b>Pengaturan Batch Payment</b></font>
            </td>
        </tr>
        <tr>
            <td align="left">&nbsp;</td>
        </tr>
        </table><br />

        <table border="0" cellpadding="2" cellspacing="5" align="left">
        <tr>
            <td width="100">&nbsp;</td>
            <td><strong>Departemen</strong></td>
            <td>
<?php              $departemen = "";
                ShowSelectDepartemen() ?>
            </td>
            <td width="100">&nbsp;</td>
            <td>
                <a onclick="tambahAutoTrans()" style="cursor: pointer"><img src="../images/ico/tambah.png">&nbsp;tambah</a>
            </td>
            <td>
                <a onclick="refreshPage()" style="cursor: pointer"><img src="../images/ico/refresh.png">&nbsp;muat ulang</a>
            </td>
        </tr>
        </table>

        <br><br><br><br>
        <div id="divDaftar">
<?php   ShowDaftar($departemen) ?>
        </div>


    </td></tr>
    </table>

</td></tr>
</table>

</body>

</html>
<?php
CloseDb();
?>
