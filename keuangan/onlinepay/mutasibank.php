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
require_once('mutasibank.func.php');

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Mutasi Bank</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="../script/themes/ui-lightness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/ui/jquery-ui.custom.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah.js"></script>
    <script language="javascript" src="../script/stringutil.js"></script>
    <script language="javascript" src="../script/dateutil.js"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="onlinepay.util.js?r=<?=filemtime('onlinepay.util.js')?>"></script>
    <script language="javascript" src="mutasibank.js?r=<?=filemtime('mutasibank.js')?>"></script>
    <script language="javascript" src="mutasibank.riwayat.js?r=<?=filemtime('mutasibank.riwayat.js')?>"></script>
    <script language="javascript" src="mutasibank.deposit.js?r=<?=filemtime('mutasibank.deposit.js')?>"></script>
    <script language="javascript" src="mutasibank.simpan.js?r=<?=filemtime('mutasibank.simpan.js')?>"></script>
    <script language="javascript" src="mutasibank.ambil.js?r=<?=filemtime('mutasibank.ambil.js')?>"></script>
</head>

<body >
<table border="0" width="100%" height="100%">
<tr>
    <td align="center" valign="top" background="../images/bulu1.png" style="background-repeat:no-repeat">

        <table border="0" width="100%" align="center">
        <tr>
            <td align="left" valign="top">

            <table border="0"width="95%" align="center">
            <tr>
                <td align="right">
                    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;
                    </font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Mutasi Bank</font>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <a href="onlinepay.php">
                        <font size="1" color="#000000"><b>OnlinePay</b></font>
                    </a>&nbsp>&nbsp
                    <font size="1" color="#000000"><b>Mutasi Bank</b></font>
                </td>
            </tr>
            <tr>
                <td align="left">&nbsp;</td>
            </tr>
            </table>

            </td>
        </tr>
        </table>

        <table border="0" width="100%" cellspacing="0" cellpadding="10" align="left">
        <tr>
            <td align="left" valign="top" width="380" style="border-right: 1px solid;">

                <table id="tabSelection" border="0" cellspacing="0" cellpadding="5" width="100%">
                <tr>
                    <td width="25%"><strong>Departemen:</strong></td>
                    <td width="75%">
<?php               $departemen = "";
                    ShowSelectDepartemen(); ?>
                    </td>
                </tr>
                </table>
                <br>
                <div id="dvBankSaldo">
<?php               ShowBankSaldo(); ?>
                </div>
            </td>
            <td align="left" valign="top" width="*">
                <div id="dvContent"></div>
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