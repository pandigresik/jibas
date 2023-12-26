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
require_once ("include/config.php");
require_once ("include/db_functions.php");
require_once ("library/genericreturn.php");
require_once ("index.func.php");

OpenDb();

$nTotal = CountTotalVideo();
?>
<!DOCTYPE HTML PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>JIBAS School Tube</title>

    <link href="images/jibas2015.ico" rel="shortcut icon" />
    <link href="script/jquery-ui/jquery-ui.min.css" type="text/css" media="screen" rel="stylesheet" />
    <link href="style/mainstyle.css" rel="stylesheet" />
    <link href="index.css" rel="stylesheet" />

    <script type="text/javascript" src="script/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="script/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="script/console.js?r=<?=filemtime('script/console.js')?>"></script>
    <script type="text/javascript" src="script/tools.js?r=<?=filemtime('script/tools.js')?>"></script>
    <script type="text/javascript" src="script/jsonutil.js?r=<?=filemtime('script/jsonutil.js')?>"></script>
    <script type="text/javascript" src="script/dateutil.js?r=<?=filemtime('script/dateutil.js')?>"></script>
    <script type="text/javascript" src="script/dialogbox.js?r=<?=filemtime('script/dialogbox.js')?>"></script>
    <script type="text/javascript" src="script/stringutil.js?r=<?=filemtime('script/stringutil.js')?>"></script>
    <script type="text/javascript" src="script/validatorx.js?r=<?=filemtime('script/validatorx.js')?>"></script>

    <script type="text/javascript" src="setting.js?r=<?=filemtime('setting.js')?>"></script>
    <script type="text/javascript" src="index.js?r=<?=filemtime('index.js')?>"></script>
    <script type="text/javascript" src="search.js?r=<?=filemtime('search.js')?>"></script>
    <script type="text/javascript" src="search.video.js?r=<?=filemtime('search.video.js')?>"></script>
    <script type="text/javascript" src="search.channelmodul.js?r=<?=filemtime('search.channelmodul.js')?>"></script>
    <script type="text/javascript" src="channel.js?r=<?=filemtime('channel.js')?>"></script>
    <script type="text/javascript" src="modul.js?r=<?=filemtime('modul.js')?>"></script>
    <script type="text/javascript" src="browse.js?r=<?=filemtime('browse.js')?>"></script>
    <script type="text/javascript" src="home.js?r=<?=filemtime('home.js')?>"></script>
    <script type="text/javascript" src="following.js?r=<?=filemtime('following.js')?>"></script>
    <script type="text/javascript" src="fav.js?r=<?=filemtime('fav.js')?>"></script>

</head>

<body style="padding: 0; margin: 0">
<table border="0" width="100%" style="height: 100%;" cellpadding="0" cellspacing="0">
<tr style="height: 100%">
    <td style="background-color: #3088a3; width: 190px;" valign="top">

        <div id="divMenu">

<?php
    if (!isset($_SESSION["IsLogin"]))
    {
        ShowDefaultMenu();
    }
    else
    {
        ShowLoginMenu();
    }
?>
        </div>

    </td>
    <td style="background-color: #ffffff; width: auto;">

        <table border="0" cellspacing="0" cellpadding="10" width="100%" style="height: 100%">
        <tr id="trHeader">
            <td style="height: 30px; background-color: #25697d">

                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="60%" align="left" valign="middle">
                        <span style="color: white">
                            Search:
                            <select id="searchBy" style="font-size: 12px; height: 27px; width: 120px;">
                                <option value="0">Video</option>
                                <option value="1">Modul</option>
                                <option value="2">Channel</option>
                            </select>
<?php                       ShowCbSearchDepartemen() ?>
                            <input id="searchKey" type="text" style="font-size: 12px; height: 24px; width: 250px;">
                            <span style="cursor: pointer" onclick="ix_clearSearch()"><img src="images/clear.png" style="height: 20px" title="clear"></span>
                            <span id="searchInfo" style="color: yellow"></span>
                        </span>
                    </td>
                    <td width="*" align="right">
                        <img src="images/bannerschooltube.png" height="28">
                    </td>

                </tr>

                </table>

            </td>
        </tr>
        <tr id="trMain">
            <td style="height: auto; background-color: #ffffff" align="left" valign="top">
                <div id="divContent" style="height: 100%; width: 100%; overflow: auto;">
<?php           require_once ("home.php") ?>
                </div>
            </td>
        </tr>
        <tr id="trFooter">
            <td style="height: 10px; background-color: #dddddd" align="right">

                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="50%" align="left">
                        Total <?= $nTotal ?> Video
                    </td>
                    <td width="50%" align="right">
                        <a style="color: blue; font-weight: normal" onclick="ix_loginAdmin()">
                            Login Administrator JIBAS
                        </a>
                    </td>
                </tr>
                </table>

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
<?php
CloseDb();
?>