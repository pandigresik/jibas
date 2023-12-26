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
<!DOCTYPE HTML PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>Hasil Ujian</title>

    <link href="images/jibas2015.ico" rel="shortcut icon" />
    <link href="script/jquery-ui/jquery-ui.min.css" type="text/css" media="screen" rel="stylesheet" />
    <link href="style/mainstyle.css" rel="stylesheet" />
    <link href="main.css" rel="stylesheet" />

    <script type="text/javascript" src="script/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="script/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="script/dialogbox.js"></script>
    <script type="text/javascript" src="script/stringutil.js"></script>
    <script type="text/javascript" src="script/jsonutil.js"></script>
    <script type="text/javascript" src="script/console.js"></script>

    <script type="text/javascript" src="hasilujian4.js"></script>
</head>

<body style="padding: 10px; margin: 10px">
<table border="0" width="100%">
<tr>
<td align="center" valign="top">
<?php
require_once ("hasilujian.content.php");
?>
</td>
</tr>
</table>

<div id='divDialogSoal'></div>
</body>
</html>
