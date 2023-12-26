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
require_once('daftartagihan.func.php');

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Rekening Bank</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="daftartagihan.js?r=<?=filemtime('daftartagihan.js')?>"></script>
</head>

<body >
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<tr>
    <td align="center" valign="top" background="../images/bulu1.png" style="background-repeat:no-repeat">

        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="height: 1200px"  align="left">
        <tr>
            <td align="left" valign="top"  style="width: 400px;" rowspan="2">

                <span style="font-size: 16px; font-weight: bold">Daftar Tagihan</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a><br>
                <table id="tabSelection" border="0" cellspacing="0" cellpadding="2" width="100%">
                <tr>
                    <td width="25%">Departemen:</td>
                    <td width="75%">
<?php               $departemen = "";
                    ShowSelectDepartemen(); ?>
                    </td>
                </tr>
                <tr>
                    <td width="25%">Bulan:</td>
                    <td width="75%">
<?php               $bulan = date('n');
                    $tahun = date('Y');
                    ShowSelectBulan();
                    ShowSelectTahun(); ?>
                    <input type="button" class="but" style="width: 40px; height: 25px" value="lihat" onclick="changeTagihanSel()">
                    </td>
                </tr>
                <tr id="trTagihanSet">
                    <td colspan="2">
                    <div id="dvTagihanSet" style="overflow: auto; width: 400px; height: auto; border: 1px solid #ddd;">
<?php               ShowTagihanSet()  ?>
                    </div>
                    </td>
                </tr>
                <tr id="trTagihanInfo" style="display: none">
                    <td colspan="2" style="height: auto; background-color: #ffffff">
                        &larr;&nbsp;&nbsp;<a href="#" onclick="showTagihanSet()" style="font-weight: normal; text-decoration: underline; color: #0000ff">kembali</a><br><br>
                        <div id="dvTagihanInfo" style="overflow: auto; width: 400px; height: auto;  border: 1px solid #ddd;">

                        </div>
                    </td>
                </tr>
                </table>

            </td>
            <td align="left" valign="top" width="*" style="height: 60px;">

                <table border="0" width="95%" align="center">
                <tr>
                    <td align="right">
                        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;
                        </font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Daftar Tagihan</font>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <a href="onlinepay.php">
                            <font size="1" color="#000000"><b>OnlinePay</b></font>
                        </a>&nbsp>&nbsp
                        <font size="1" color="#000000"><b>Daftar Tagihan</b></font>
                    </td>
                </tr>
                </table>


            </td>
        </tr>
        <tr>
            <td width="*" align="left" valign="top">

                <div id="dvTagihanData" style="background-color: #FFFFFF; width: 100%; height: auto; overflow: auto; padding: 10px;">

                </div>
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