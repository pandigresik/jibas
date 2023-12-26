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
require_once("include/sessionchecker.php");
require_once("include/theme.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="style/style.css" />
<link rel="stylesheet" href="menu/style<?=GetThemeDir2()?>.css" type="text/css" media="screen" />
<script type='text/javascript' src='menu/jquery-1.2.6.min.js'></script>
<script type='text/javascript' src='menu/kwicks.js'></script>
<script type='text/javascript' src='menu/custom.js'></script>
<script type="text/javascript" src="frameatas.js"></script>
</head>
<body bgcolor="<?=GetThemeColor()?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="MM_preloadImages('test/Shutdown_over<?=GetThemeDir2()?>.jpg')">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr height="61">
	<td width="10" bgcolor="<?=GetThemeColor()?>">&nbsp;</td>
	<td width="20" background="<?=GetThemeDir()?>bgmain2_02.jpg">&nbsp;</td>
	<td width="*"  background="<?=GetThemeDir()?>bgmain2_03.jpg" valign="bottom">
	  <table border="0" cellpadding="2" cellspacing="0">
	  <tr>
		<td width="80" align="center" valign="top">
			<a href="referensi/referensi.php" target="content">
			<img src="images/referensi.png" border="0" height="35"><br>
			Referensi</a>
		</td>
		<td width="80" align="center" valign="top">
			<a href="pegawai/pegawai.php" target="content">
			<img src="images/pegawai2.png" border="0" height="35"><br>
			Kepegawaian</a>
		</td>
		<td width="80" align="center" valign="top">
			<a href="presensi/presensi.php" target="content">
			<img src="images/presensi.png" border="0" height="35"><br>
			Presensi</a>
		</td>
		<td width="80" align="center" valign="top">
			<a href="pengaturan/pengaturan.php" target="content">
			<img src="images/pengaturan.png" border="0" height="35"><br>
			Pengaturan</a>
		</td>
		<td width="80" align="center" valign="top">
			<a href="#" onclick="logout()" style="color:red">
			<img src="images/logout.png" border="0" height="35"><br>
			Keluar</a>
		</td>
	  </tr>
	  
	  </table>	
	  
      
	  
	  
    </td>
	<td width="296" background="<?=GetThemeDir()?>bgmain2_04.jpg">&nbsp;</td>
	<td width="17" background="<?=GetThemeDir()?>bgmain2_05.jpg">&nbsp;</td>
	<td width="13" bgcolor="<?=GetThemeColor()?>">&nbsp;</td>	
</tr>
<tr height="13">
	<td width="10" bgcolor="<?=GetThemeColor()?>">&nbsp;</td>
	<td background="<?=GetThemeDir()?>bgmain2_08.jpg" width="20">&nbsp;</td>
	<td colspan="2" background="<?=GetThemeDir()?>bgmain2_09.jpg" width="*">&nbsp;</td>
	<td background="<?=GetThemeDir()?>bgmain2_10.jpg" width="17">&nbsp;</td>
    <td width="13" bgcolor="<?=GetThemeColor()?>">&nbsp;</td>
</tr>
</table>
</body>
</html>