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
require_once ("include/session.php");
require_once ("include/sessionchecker.php");
require_once ("include/cbe.version.php");
require_once ("../include/cbe.config.php");
require_once ("include/ujianchecker.php");
?>
<!DOCTYPE HTML PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <title>JIBAS Computer Based Exam - Web Client</title>
    <link href="images/jibas2015.ico" rel="shortcut icon" />
    <link href="script/jquery-ui/jquery-ui.min.css" type="text/css" media="screen" rel="stylesheet" />
    <link href="style/mainstyle.css" rel="stylesheet" />
    <link href="main.css" rel="stylesheet" />
    <link href="ujian.css" rel="stylesheet" />
    <script type="text/javascript" src="script/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="script/jquery.cookie.js"></script>
    <script type="text/javascript" src="script/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="script/jsonutil.js"></script>
    <script type="text/javascript" src="script/dateutil.js"></script>
    <script type="text/javascript" src="script/console.js"></script>
    <script type="text/javascript" src="script/tools.js"></script>
    <script type="text/javascript" src="script/dialogbox.js"></script>
    <script type="text/javascript" src="script/edate.js"></script>
    <script type="text/javascript" src="script/pingservice.js"></script>
    <script type="text/javascript" src="script/canvas2image.js"></script>
    <script type="text/javascript" src="ujian.js"></script>
    <script type="text/javascript" src="ujian.init3.js"></script>
    <script type="text/javascript" src="ujian.soal5.js"></script>
    <script type="text/javascript" src="ujian.canvas.js"></script>
    <script type="text/javascript" src="ujian.server2.js"></script>
</head>
<body style="padding: 0; margin: 0">
<div id="divBlock" class="divMainContainer"></div>

<div id="divMain" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
<table border="0" width="100%" style="height: 100%;" cellpadding="0" cellspacing="0">
<tr>
    <td style="height: 60px; background-color: #188361">
    <?php
        require_once("ujian.header.php");
    ?>
    </td>
</tr>
<tr>
    <td style="height: auto; background-color: #ffffff" align="left" valign="top">

        <table border="1" cellspacing="0" cellpadding="10" width="100%" style="height: 100%; border-color: #188361;">
        <tr>
            <td style="width: 65%; height: 100%" align="left" valign="top">
                <div id="divSoal" style="width: 100%; height: 100%; border: 0px solid #aaa; overflow: auto;">
                    &nbsp;
                </div>
            </td>
            <td style="width: 35%; height: 100%"  align="left" valign="top">

                <table border="0" cellpadding="0" cellspacing="0"  style="height: 100%; width: 100%">
                <tr style="height: 45%">
                    <td style="width: 98%;" align="left" valign="top">
                        <div id="divDaftar" style="width: 100%; height: 95%; border: 1px solid #aaa; overflow: auto; position: relative">
                            &nbsp;
                        </div>
                    </td>
                </tr>
                <tr style="height: 45%">
                    <td style="width: 100%;" align="left" valign="top">
                        <div id="divJawaban" style="width: 100%; height: 95%; border: 1px solid #aaa; overflow: auto;">
                            &nbsp;
                        </div>
                    </td>
                </tr>
                <tr style="height: 10%">
                    <td style="width: 100%;" align="left" valign="top">

                        <input type="button" class="BtnDefault"
                               style="background-color: #0a6aa1; color: #fff; font-size: 18px;
                               width: 120px; height: 40px;" value="Simpan"
                               name="btUjian"
                               onclick="ujian_simpanJawaban(0)">

                        <input type="button" class="BtnDefault"
                               style="background-color: #ffd700; color: #000; font-size: 18px;
                               width: 190px; height: 40px;" value="Simpan Ragu-Ragu"
                               name="btUjian"
                               onclick="ujian_simpanJawaban(1)">
                    </td>
                </tr>
                </table>

            </td>
        </tr>
        </table>

    </td>
</tr>
<tr>
    <td style="height: 20px; background-color: #dddddd">
        CBE Server: <?= $CBE_SERVER ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Versi: <?= $CBE_CFG_VERSION ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Build Date: <?= $CBE_CFG_BUILDDATE ?>
    </td>
</tr>
</table>

<div id='divDialog'></div>

<div id="dlgTimeLeftInfo" class="divTimeLeftInfo">
    <span id="txTextTimeLeftInfo" class="spTextTimeLeftInfo">
        TEXT
    </span>
    <span id="btCloseTimeLeftInfo" class="spCloseTimeLeftInfo">X</span>
</div>
<div id="dlgFinishUjianInfo" class="divFinishUjianInfo">
    <span id="txTextFinishUjianInfo" class="spTextFinishUjianInfo">
        UJIAN TELAH BERAKHIR
    </span>
    <span id="btCloseFinishUjianInfo" class="spCloseFinishUjianInfo">X</span>
</div>

</div> <!-- divMain -->
</body>
</html>
