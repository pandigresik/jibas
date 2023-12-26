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
?>
<!DOCTYPE HTML PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>JIBAS Computer Based Exam - Hapus Informasi Login</title>

    <link href="images/jibas2015.ico" rel="shortcut icon" />
    <link href="script/jquery-ui/jquery-ui.min.css" type="text/css" media="screen" rel="stylesheet" />
    <link href="style/mainstyle.css" rel="stylesheet" />
    <link href="main.css" rel="stylesheet" />

    <script type="text/javascript" src="script/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="script/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="script/console.js"></script>
    <script type="text/javascript" src="script/tools.js"></script>
    <script type="text/javascript" src="script/jsonutil.js"></script>
    <script type="text/javascript" src="script/dialogbox.js"></script>

    <script type="text/javascript" src="clearconn.js?r=<?=filemtime('clearconn.js')?>"></script>

</head>

<body style="padding: 10px; margin: 15px">
<span style="font-size: 20px">Hapus Informasi Login</span><br><br><br>
<span style="font-style: italic; font-size: 12px; line-height: 20px;">
    Adakalanya informasi login masih tercatat di Server, meskipun aplikasi CBE Web Client telah tertutup atau koneksinya terputus.
    Sehingga ketika pengguna login kembali akan tertolak, karena masih ada informasi login sebelumnya di Server.<br><br>
    Gunakan menu ini untuk menghapus informasi login di Server tanpa bantuan Administrator JIBAS.<br><br>
    Silahkan isi login dan password untuk menghapus informasi login di Server.
    <br><br>
</span>
<table border="0" cellspacing="10" cellpadding="0">
<tr>
    <td align="right">Login: </td>
    <td style="padding-right:4px">
        <input type="text" name="txLogin" id="txLogin" value="" maxlength="30" class="inputbox">&nbsp;
    </td>
</tr>
<tr>
    <td align="right">Password: </td>
    <td style="padding-right:4px">
        <input type="password" name="txPassword" id="txPassword" value="" maxlength="30" class="inputbox">&nbsp;
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td style="padding-right:4px"  valign="top">
        <input type="button" value="Hapus" id="btHapus" name="btHapus" class="BtnPrimary" onclick="btHapus_click()">
        <input type="button" value="Tutup" id="btTutup" name="btTutup" class="BtnPrimary" onclick="btTutup_click()"><br>
        <span id="lbInfo" style="color: #FFFFFF; font-style: italic;">.</span>
    </td>
</tr>
</table>
</body>
</html>