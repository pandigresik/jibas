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
require_once("../inc/session.checker.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../scr/tools.js"></script>
<script language="javascript">
function OpenConf()
{
	newWindow('konfigurasi.php', 'Konfigurasi','500','300','resizable=0,scrollbars=0,status=0,toolbar=0')
}

function OpenConfTime()
{
	newWindow('waktu.pinjam.config.php', 'KonfigurasiWaktuPinjam','500','300','resizable=0,scrollbars=0,status=0,toolbar=0')
}

function ChgPass()
{
	newWindow('user_ganti.php', 'Konfigurasi','348','225','resizable=0,scrollbars=0,status=0,toolbar=0')
}
</script>
<style type="text/css">
<!--
.style2 {
	color: #A9A9A9;
	font-size: 18px;
}
.style4 {color: #A9A9A9; font-size: 14px; }
-->
</style>
</head>

<body>
<div id="title" align="center">
	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
	<font style="font-size:18px; color:#999999">Pengaturan</font><br />
</div>
<br>

<table width="0%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		
	<table border='0' align='center' cellpadding='4' cellspacing='0'>
	<tr>
		<td align='center' valign='middle' width='80'>
			<img src='../img/members.png' height='60'>
		</td>
		<td align='left' valign='middle' width='120'>
			<a href="anggota.php">
				Daftar Anggota<br>Non Sekolah
			</a>
		</td>
		<td width='10'>&nbsp;</td>
		
		<td align='center' valign='middle' width='80'>
			<img src='../img/users.png' height='60'>
		</td>
		<td align='left' valign='middle' width='120'>
			<a href="pengguna.php">
				Hak Akses Pengguna
			</a>
		</td>
		<td width='10'>&nbsp;</td>
		
		<td align='center' valign='middle' width='80'>
			<img src='../img/key.png' height='60'>
		</td>
		<td align='left' valign='middle' width='120'>
			<a href="javascript:ChgPass()">
				Ganti Password
			</a>
		</td>
	</tr>
	<tr height='25'>
		<td colspan='8'>&nbsp;</td>
	</tr>
	<tr>
		<td align='center' valign='middle' width='80'>
			<img src='../img/configuration.png' height='60'>
		</td>
		<td align='left' valign='middle' width='120'>
			<a href="javascript:OpenConf()">
				Konfigurasi<br>Maksimal
				Jumlah Peminjaman
			</a>
		</td>
		<td width='10'>&nbsp;</td>
		
		<td align='center' valign='middle' width='80'>
			<img src='../img/configuration.png' height='60'>
		</td>
		<td align='left' valign='middle' width='120'>
			<a href="javascript:OpenConfTime()">
				Konfigurasi<br>
				Waktu Peminjaman
			</a>
		</td>
		<td width='10'>&nbsp;</td>
		
		<td align='center' valign='middle' width='80'>
			<img src='../img/document.png' height='60'>
		</td>
		<td align='left' valign='middle' width='120'>
			<a href="Header.php">
				Konfigurasi<br>
				Header Dokumen
			</a>
		</td>
	</tr>
	<tr height='25'>
		<td colspan='8'>&nbsp;</td>
	</tr>
	<tr>
		<td align='center' valign='middle' width='80'>
			<img src='../img/warning.png' height='60'>
		</td>
		<td align='left' valign='middle' width='120'>
			<a href="queryerror.php">
				Query Error Log
			</a>
		</td>
		<td width='10'>&nbsp;</td>
		
		<td colspan='5'>&nbsp;</td>
	</tr>
	</table>
	</td>
</tr>
</table>



<!-- End ImageReady Slices -->
</body>
</html>