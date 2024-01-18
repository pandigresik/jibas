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
require_once('inc/config.php');
require_once('inc/db_functions.php');
require_once('inc/sessioninfo.php');
require_once('inc/sessionchecker.php');

OpenDb();
$sql = "SELECT YEAR(NOW()),MONTH(NOW()),DAY(NOW())";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$y = $row[0];
$m = $row[1];
$d = $row[2]; 
CloseDb();

$month = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/style.css">
<title>Jibas Ema</title>
<script type="text/javascript">
function logout()
{
	if (confirm('Anda yakin akan keluar dari JIBAS EMA?'))
		top.location.href = "logout.php";
}
</script>
<style>
.mainMenu
{
	color: white;
	font-family: Verdana;
	font-size: 12px;
	font-weight: normal;
}

.mainMenu:hover
{
	color: #80ff00;
	font-family: Verdana;
	font-size: 12px;
	font-weight: normal;
}
</style>
</head>
<body style="margin-top:0px;margin-left:0px;margin-right:0px;margin-bottom:0px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td width="80%" style="background-image:url(img/top_header_new_01.png); background-repeat:repeat-x">
		<img src="img/top_header_new.png" width="396" height="50" />
	</td>
    <td width="20%" style="background-image:url(img/top_header_new_01.png); background-repeat:repeat-x">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="18%" align="right">
				<div style="padding-right:5px"><span class="style1"><?=$d?></span></div>
			</td>
            <td width="82%" valign="middle">
				<span class="style2"><?=$month[$m]?></span><br /><span class="style4"><?=$y?></span>
			</td>
        </tr>
      	</table>
	</td>
</tr>
<tr height='30'>
    <td style="background-image:url(img/bluefireback1.gif); background-repeat:repeat-x">
		
		&nbsp;&nbsp;
		<a href='akademik.php' class='mainMenu' target='content'>AKADEMIK</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='kepegawaian.php' class='mainMenu' target='content'>KEPEGAWAIAN</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='keuangan.php' class='mainMenu' target='content'>KEUANGAN</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='perpustakaan.php' class='mainMenu' target='content'>PERPUSTAKAAN</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='kritik/kritik.php' class='mainMenu' target='content'>KRITIK &amp; SARAN</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='konfigurasi.php' class='mainMenu' target='content'>KONFIGURASI</a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='#' onclick='logout()' style='background-color: maroon' class='mainMenu'>&nbsp;LOGOUT&nbsp;</a>

	</td>
    <td style="background-image:url(img/bluefireback1.gif); background-repeat:repeat-x">
    </td>
  </tr>
</table>
</body>
</html>