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
require_once("../include/version.config.php");
require_once("../include/cbe.config.php");

// 2020-06-18
$checkCurl = function_exists('curl_exec');
?>
<!DOCTYPE HTML PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <title>JIBAS Computer Based Exam - Web Client</title>
    <link href="images/jibas2015.ico" rel="shortcut icon" />
    <link href="script/jquery-ui/jquery-ui.min.css" type="text/css" media="screen" rel="stylesheet" />
    <link rel="stylesheet" href="../script/bgstretcher.css" />
    <link href="style/mainstyle.css" rel="stylesheet" />
    <script type="text/javascript" src="script/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="script/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../script/bgstretcher.js"></script>
    <script type="text/javascript" src="script/console.js"></script>
    <script type="text/javascript" src="script/jsonutil.js"></script>
    <script type="text/javascript" src="script/dialogbox.js"></script>
    <script type="text/javascript" src="script/tools.js"></script>
    <script type="text/javascript" src="login.js"></script>
    <style type="text/css">
        #Main {
            position:absolute;
            z-index:1;
            top:50px;
            left:10px;
        }
        #Footer {
            position:fixed;
            bottom:20px;
            right:20px;
        }
        #Partner {
            position:fixed;
            bottom:20px;
            left:20px;
        }
    </style>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onresize="login_centerMain()" style="padding:0px; margin:0px; ">
<div style="position:relative; z-index:2;">

<div id="Main">

    <table border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td rowspan="5" valign="top"><img src="../images/imfront_cbe.png"></td>
        <td valign="bottom" align="left" style="height: 73px">
            <font style="font-family:helvetica; font-size:16px; color:#fff; font-weight:bold;">
                COMPUTER BASED EXAM - <font style="color:#ffbe1b">WEB CLIENT</font>
                <br>

            </font>
        </td>
    </tr>
    <tr>
        <td valign="top" align="left">

        <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="padding-right:4px">
                <input type="text" name="txLogin" id="txLogin" <?php if ($checkCurl === false) echo 'disabled' ?> value="" maxlength="30" class="inputbox"
                       onfocus="setFocus('#txLogin','Login')" onblur="setBlur('#txLogin','Login')">&nbsp;
            </td>
            <td style="padding-right:4px">
                <input type="password" name="txPassword" id="txPassword" <?php if ($checkCurl === false) echo 'disabled' ?> value="" maxlength="30" class="inputbox"
                       onfocus="setFocus('#txPassword','Password')" onblur="setBlur('#txPassword','Password')">&nbsp;
            </td>
            <td style="padding-right:4px"  valign="top">
                <input type="button" value="Login" id="btLogin" name="btLogin" <?php if ($checkCurl === false) echo 'disabled' ?> class="BtnPrimary" onclick="btLogin_click()">
            </td>
            <td style="padding-right:4px"  valign="top">
                <a title="Kembali ke Menu Utama" href="../" style="color:#2fcced; font-weight:bold; font-family:Arial; font-size:12px; text-decoration:underline">Menu Utama</a><br>
<?php           if ($checkCurl === true)  { ?>
                <a title="Hapus Informasi Login" onclick="showClearConn()" style="color:#eee; font-weight:normal; font-family:Arial; font-size:12px; text-decoration:underline">Hapus Informasi Login</a><br>
<?php           } ?>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <span id="lbInfo" style="color: #FFFFFF; font-style: italic;">.</span>
            </td>
        </tr>
        </table>

        </td>
    </tr>
    <tr>
        <td valign="top" align="left" style="height: 25px;" >
            <span style="font-size: 11px; color: #fff">
            CBE Server: <?= $CBE_SERVER ?>
<?php
            if ($checkCurl === false)
            {
                echo "<br><span style='color: yellow; background-color: red;'>Mohon aktifkan dahulu fungsi curl di konfigurasi php </span>";
            }
?>                
            </span>
        </td>
    </tr>
    <tr>
        <td valign="top" align="left" style="height: 75px;" >
            <br>
            <table border="0" cellspacing="0" cellpadding="2">
            <tr>
                <td width="30" align="center">
                    <img src="images/jibas44.png" height="26">
                </td>
                <td>
                    <a style="color: #fff" target="_blank"
                       href="http://www.jibas.net/content/cbe/cbe.php">
                        Tentang JIBAS Computer Based Exam
                    </a>
                </td>
            </tr>
            <tr>
                <td width="30" align="center">
                    <img src="images/smartphone32.png">
                </td>
                <td>
                    <a style="color: #fff"  target="_blank"
                       href="https://play.google.com/store/apps/details?id=net.jibas.cbe.android&hl=en">
                       JIBAS Computer Based Exam - Android Client
                    </a>
                </td>
            </tr>
            </table>

        </td>
    </tr>

    </table>

</div> <!-- Main -->

<div id="Partner">
    <?php
    $_REQUEST = [];
    $_REQUEST['relpath'] = "..";
    include('../partner.php');
    ?>
</div>

<div id="Footer">
    <?php include('../footer.php'); ?>
</div>

<div id='divDialog'></div>

</div>
</body>
</html>