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
require_once('pgserver.config.php');
require_once('pgschoolid.config.php');
require_once('pgservice.config.php');

OpenDb();

$dept = $_REQUEST["dept"] ?? "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Kode Sekolah dan Biaya Layanan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="kodesekolah.js?r=<?=filemtime('kodesekolah.js')?>"></script>
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
                </font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Kode Sekolah dan Biaya Layanan</font>
            </td>
        </tr>
        <tr>
            <td align="right">
                <a href="onlinepay.php">
                    <font size="1" color="#000000"><b>OnlinePay</b></font>
                </a>&nbsp>&nbsp
                <font size="1" color="#000000"><b>Kode Sekolah dan Biaya Layanan</b></font>
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
            <span style="font-family: Verdana, serif; font-size: 18px; font-weight: bold">Kode Sekolah &amp; Biaya Layanan</span>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="#" onclick="location.reload();" style="font-weight: normal; color: blue; text-decoration: underline">muat ulang</a><br>
            <span style="font-style: italic; font-size: 12px">
                Kode Sekolah dan Basis Data yang digunakan sekolah di JIBAS Jendela Sekolah
            </span>
            <br><br><br>

            <fieldset style="width: 800px; border: 1px solid #999;">
                <legend style="font-size: 14px; font-weight: bold">Kode Sekolah &amp; Database Id</legend>
                <br>
                    Kode Sekolah &amp; Database Id yang digunakan di aplikasi JIBAS Sinkronisasi Jendela Sekolah<br><br>
<?php               $disabled = getLevel() == 2 ? "disabled" : ""; ?>
                    <table border="0" cellpadding="2" cellspacing="0">
                    <tr>
                        <td width="100" align="right">Kode Sekolah</td>
                        <td width="450" align="left">&nbsp;&nbsp;
                            <input type="text" id="schoolid" name="schoolid" class="inputbox" <?=$disabled?> maxlength="5" style="width: 100px; font-size: 16px;" value="<?= $PG_SCHOOL_ID ?>">
                            &nbsp;&nbsp;
                            <a href="#" style="font-weight: normal; color: #0000ff" onclick="lihatContoh()">lihat contoh</a>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">Database Id</td>
                        <td align="left">&nbsp;&nbsp;
                            <input type="text" id="dbid" name="dbid" <?=$disabled?> class="inputbox" maxlength="5" style="width: 100px; font-size: 16px;" value="<?= $PG_DATABASE_ID ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            &nbsp;&nbsp;
<?php                       if (getLevel() != 2) { ?>
                            <input type="button" id="btschoolid" class="but" style="height: 30px; width: 80px" value=" Simpan " onclick="saveSchoolId()">&nbsp;&nbsp;
                            <span id="statusschoolid" style="color: #0000ff"></span>
<?php                       } ?>
                        </td>
                    </tr>
                    </table>
            </fieldset>
            <br><br>
            <fieldset style="width: 800px; border: 1px solid #999;">
                <legend style="font-size: 14px;  font-weight: bold;">Biaya Layanan</legend>
                <br>
                <div>
                    Biaya Layanan yang diterapkan dalam setiap transaksi melalui JIBAS Payment Gateway<br><br>
                    <input type="text" id="servicefee" name="servicefee" disabled="disabled" class="inputbox" maxlength="5" style="width: 100px; font-size: 16px;" value="<?= $PG_SERVICE_FEE ?>">
<?php               if (getLevel() != 2) { ?>
                    <input type="button" id="btrefresh" class="but" style="height: 30px; width: 100px" value=" muat ulang " onclick="refreshServiceFee()">&nbsp;&nbsp;
                    <span id="statusservicefee" style="color: #0000ff"></span>
<?php               } ?>
                </div>
                <br>
            </fieldset>
            <br><br>
            <fieldset style="width: 800px; border-color: #2326b3; border-width: 1px; font-size: 12px; background-color: #ffffdd">
                <legend style="background-color: #2326b3; color: white; font-weight: bold; font-size: 14px;">&nbsp;&nbsp;INFORMASI&nbsp;&nbsp;</legend>
                <ul>
                    <li>Kode Sekolah diperoleh setelah sekolah melakukan registrasi penggunaan JIBAS Jendela Sekolah</li>
                </ul>
            </fieldset>

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