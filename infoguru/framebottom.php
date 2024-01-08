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
require_once("include/theme.php"); 
require_once("include/sessioninfo.php");
require_once("include/sessionchecker.php");

$nama = SI_USER_NAME();
if ($nama=="landlord")
	$nama = "Administrator JIBAS ['Infoguru']";
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="script/clock.js"></script>
<script type="text/javascript" language="text/javascript">
function get_fresh(){
	document.location.reload();
}
</script>
</head>

<body style="background-color:#000" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onload="startclock('clock')">
<table id="Table_01" width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<img src="<?=GetThemeDir()?>InfoGuru_16.png" width="20" height="22" alt=""></td>
		<td background="<?=GetThemeDir()?>InfoGuru_17.png" width="852" height="22" align='left' style='padding-top:1px'>
		<span style="font-family:Verdana; color:#666666; font-size:10px"><b>Selamat Datang <?=$nama?></b></span>
		</td>
		<td background="<?=GetThemeDir()?>InfoGuru_17.png" width="852" height="22" align='right' style='padding-top:1px'>
		<span style="font-family:Verdana; color:#FF6600; font-size:10px"><strong><div id="clock"></div></strong></span>
		</td>
		<td>
			<img src="<?=GetThemeDir()?>InfoGuru_18.png" width="17" height="22" alt=""></td>
	</tr>
</table>
</body>
</html>