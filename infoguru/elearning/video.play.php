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
$videoUrl = $_REQUEST["videoUrl"];
?>
<!DOCTYPE HTML PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>JIBAS InfoGuru</title>

    <link href="../images/jibas2015.ico" rel="shortcut icon" />
    <link href="../style/style.css" rel="stylesheet" />

</head>

<body style="padding: 0; margin: 0">
<table border="0" cellspacing="0" cellpadding="0" width="100%" style="background-color: #ececec;">
<tr><td align="center" valign="top">

    <table border="0" cellspacing="0" cellpadding="10" width="1100"  style="background-color: #fff;">
    <tr><td align="left" valign="top" width="1100">


        <table border="0" cellpadding="2" cellspacing="0" width="100%">
        <tr>
            <td colspan="4" align="center">
                <video id="video" onerror="failed(event)" controls="controls"
                       src="<?=$videoUrl?>"
                       style="height: 480px; width: 640px"></video>
            </td>
        </tr>
        </table>
        <br><br>

    </td></tr>
    </table>

    <br><br><br><br>

</td></tr>
</table>

</body>
</html>

