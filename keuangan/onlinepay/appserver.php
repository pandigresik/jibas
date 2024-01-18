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
require_once('appserver.config.php');

OpenDb();

$dept = $_REQUEST["dept"] ?? "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Alamat aplikasi JIBAS Sinkronisasi Jendela Sekolah</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
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
                        </font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">JIBAS Sinkronisasi Jendela Sekolah</font>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <a href="onlinepay.php">
                            <font size="1" color="#000000"><b>OnlinePay</b></font>
                        </a>&nbsp>&nbsp
                        <font size="1" color="#000000"><b>JIBAS Sinkronisasi Jendela Sekolah</b></font>
                    </td>
                </tr>
                <tr>
                    <td align="left">&nbsp;</td>
                </tr>
                </table>
                <br />

            </td>
        </tr>
        </table>
        <br>

        <table border="0" width="100%" align="left">
        <tr>
            <td align="left" valign="top" width="10%">
                &nbsp;
            </td>
            <td align="left" valign="top" width="*">
                <span style="font-family: Verdana, serif; font-size: 18px; font-weight: bold">JIBAS Sinkronisasi Jendela Sekolah</span><br>
                <span style="font-style: italic; font-size: 12px">
                    Alamat IP yang digunakan oleh aplikasi JIBAS Sinkronisasi Jendela Sekolah:
                </span>
                <br><br><br>
<?php           $disabled = getLevel() == 2 ? "disabled" : ""; ?>
                Alamat IP: <input type="text" id="ipaddr" <?=$disabled?> name="ipaddr" class="inputbox" style="width: 200px; font-size: 18px;" value="<?=$JS_SERVER_ADDR?>">
                <span style="font-size: 16px; font-weight: bold">:8105</span>&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a>
                <br><br>
                <div>
<?php           if (getLevel() != 2) { ?>
                <input type="button" id="simpan" class="but" style="height: 28px; width: 80px" value=" Simpan " onclick="saveJsServerAddr()">&nbsp;&nbsp;
                <span id="status"></span>
<?php           } ?>
                </div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" width="10%">
                &nbsp;
            </td>
            <td align="left" valign="top" width="*">
                <br><br><br>
                <fieldset style="width: 800px; border-color: #2326b3; border-width: 1px; font-size: 12px; background-color: #ffffdd">
                    <legend style="background-color: #2326b3; color: white; font-weight: bold; font-size: 14px;">&nbsp;&nbsp;INFORMASI&nbsp;&nbsp;</legend>
                    <ol>
                        <li>Aplikasi JIBAS Sinkronisasi Jendela Sekolah digunakan untuk melakukan sinkronisasi data supaya muncul di aplikasi Android Jendela Sekolah</li>
                        <li>Pastikan konfigurasi Alamat IP telah di aplikasi JIBAS Sinkronisasi Jendela Sekolah di menu Konfigurasi > Aplikasi. Gunakan IP Address bukan localhost.</li>
                        <li>Atur juga Firewall di server yang digunakan supaya port 8105 dapat di akses</li>
                        <li>Gambar berikut adalah contoh pengaturan dan informasi nya</li>
                    </ol>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" width="10%">
                &nbsp;
            </td>
            <td align="left" valign="top" width="*">
                <br><br>
                <img src="appserver.jpg" style="border: 1px solid #666; padding: 10px; width: 800px;">
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