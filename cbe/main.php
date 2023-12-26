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
require_once ("include/mainchecker.php");
require_once ("include/cbe.version.php");
require_once ("../include/cbe.config.php");
require_once ("main.func.php");
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

    <script type="text/javascript" src="script/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="script/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="script/console.js"></script>
    <script type="text/javascript" src="script/tools.js"></script>
    <script type="text/javascript" src="script/pingservice.js"></script>
    <script type="text/javascript" src="script/jsonutil.js"></script>
    <script type="text/javascript" src="script/dateutil.js"></script>
    <script type="text/javascript" src="script/dialogbox.js"></script>
    <script type="text/javascript" src="script/stringutil.js"></script>

    <script type="text/javascript" src="main.js?r=<?=filemtime('main.js')?>"></script>
    <script type="text/javascript" src="main.menu4.js?r=<?=filemtime('main.menu4.js')?>"></script>
    <script type="text/javascript" src="welcome.js?r=<?=filemtime('welcome.js')?>"></script>
    <script type="text/javascript" src="ujiankhusus2.js?r=<?=filemtime('ujiankhusus2.js')?>"></script>
    <script type="text/javascript" src="ujianumum2.js?r=<?=filemtime('ujianumum2.js')?>"></script>
    <script type="text/javascript" src="ujianumumsiswa2.js?r=<?=filemtime('ujianumumsiswa2.js')?>"></script>
    <script type="text/javascript" src="ujianremed2.js?r=<?=filemtime('ujianremed2.js')?>"></script>
    <script type="text/javascript" src="hasilujian4.js?r=<?=filemtime('hasilujian4.js')?>"></script>
    <script type="text/javascript" src="jadwal2.js?r=<?=filemtime('jadwal2.js')?>"></script>
    <script type="text/javascript" src="rekap2.js?r=<?=filemtime('rekap2.js')?>"></script>
    <script type="text/javascript" src="banksoal3.js?r=<?=filemtime('banksoal3.js')?>"></script>
    <script type="text/javascript" src="carisoal.js?r=<?=filemtime('carisoal.js')?>"></script>
    <script type="text/javascript" src="userlist2.js?r=<?=filemtime('userlist2.js')?>"></script>
    <script type="text/javascript" src="resutil.js?r=<?=filemtime('resutil.js')?>"></script>
    <script type="text/javascript" src="testconn.js?r=<?=filemtime('testconn.js')?>"></script>
    <script type="text/javascript" src="useroffline.js?r=<?=filemtime('useroffline.js')?>"></script>

</head>

<body style="padding: 0; margin: 0">
<table border="0" width="100%" style="height: 100%;" cellpadding="0" cellspacing="0">
<tr style="height: 100%">
    <td style="background-color: #188361; width: 220px;" valign="top">
    <?php include("main.menu.php") ?>
    </td>
    <td style="background-color: #ffffff; width: auto;">

        <table border="0" cellspacing="0" cellpadding="10" width="100%" style="height: 100%">
        <tr id="trHeader">
            <td style="height: 30px; background-color: #0a6148">

                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="60%" align="left">
                        &nbsp;&nbsp;
                        <span style="font-family: 'Segoe Ui', Arial; font-size: 18px; color: #fff">
                        <?= $_SESSION["Departemen"] ?>
                        </span>
                    </td>
                    <td width="*" align="right">
                        <img src="images/bannercbe.png" height="28">
                    </td>
                </tr>

                </table>

            </td>
        </tr>
        <tr id="trMain">
            <td style="height: auto; background-color: #ffffff" align="left" valign="top">
                <div id="divContent" style="height: 100%; width: 100%; overflow: auto;">
                <?php
                    showContentPage();
                ?>
                </div>
            </td>
        </tr>
        <tr id="trFooter">
            <td style="height: 10px; background-color: #dddddd">
                CBE Server: <?= $CBE_SERVER ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Versi: <?= $CBE_CFG_VERSION ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Build Date: <?= $CBE_CFG_BUILDDATE ?>
            </td>
        </tr>
        </table>

    </td>
</tr>
</table>

<div id='divDialog'></div>
<div id='divDialogSoal'></div>

</body>
</html>