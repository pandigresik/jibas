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
require_once(__DIR__ . '/../cek.php');
?>
<html>
<head>
<title>Impor Nilai Pelajaran</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script language = "javascript" type = "text/javascript" src="../script/jquery-1.9.1.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript">
<?php require_once("impnilai.js.php"); ?>
</script>
</head>
<body leftmargin="0" topmargin="0" onLoad="document.getElementById('departemen').focus()">

<table border="0" width="100%" cellpadding="10" cellspacing="0">
<tr>
<td width="64%">
    <br>&nbsp;&nbsp;&nbsp;&nbsp;
    <strong>File Form Nilai (*.xlsx): </strong>
    <input type="file" name="fexcel" id="fexcel" style="width: 500px;">
    <input type="button" name="btProses" id="btProses" value="Proses" class="but" style="height: 24px; width: 100px;" onclick="uploadFile()">
</td>
<td align="left" valign="middle" width="*" rowspan="3">
    &nbsp;
</td>
<td valign="top" width="40%" align="right">
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Impor Nilai</font><br />
    <a href="../exim.php" target="content">
        <font size="1" color="#000000"><b>Ekspor &amp; Impor</b></font>
    </a>&nbsp>&nbsp
    <font size="1" color="#000000"><b>Impor Nilai</b></font></td>
</td>
</tr>
<tr>
    <td colspan="3">
        <div id="divReader" style="width: 1000px; height: 500px; overflow: auto; border-width: 1px; border-style: solid; border-color: #bbb; padding: 10px;">

        </div>
    </td>
</tr>
</table>
<br>

</body>
</html>