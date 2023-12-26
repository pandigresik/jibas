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
require_once("include/config.php");
require_once("include/db_functions.php");
require_once("include/common.php");

$op="";
if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];
	
$tema="";
if (isset($_REQUEST['tema']))
	$tema=$_REQUEST['tema'];

$nama = getUserName();

if ($op=="bdy73y76d8838g")
{
	unset($_SESSION['tema']);
	$_SESSION['tema']=$tema;
	
	OpenDb();
	QueryDb("UPDATE pengguna SET tema=$tema WHERE login='".getUserId()."'");
	CloseDb();
	?>
    <script language="javascript">
    	parent.frameatas.location.href="frameatas.php";
		parent.framekiri.location.href="framekiri.php";
		parent.framekanan.location.href="framekanan.php";
		//parent.framebawah.location.href="framebawah.php";
    </script>
	<?php
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<script language="javascript" src="script/clock.js"></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=GetThemeColor()?>" onLoad="startclock('clock')">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr height="32">
	<td width="10" bgcolor="<?=GetThemeColor()?>">&nbsp;</td>
	<td background="<?=GetThemeDir()?>bgmain2_18.jpg" width="20">&nbsp;</td>
	<td colspan="2" background="<?=GetThemeDir()?>bgmain2_19.jpg" width="*">
    
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
	    <td width="35%" align="left" valign="top"><font face="Verdana" size="1" color="#333333">Selamat Datang <?=$nama?></font></td>
	    <td width="30%" align="center" valign="top"><font face="Verdana" size="1" color="#333333"><strong>&nbsp;</strong></font></td>
	    <td width="35%" align="right" valign="top"><font face="Verdana" size="1" color="#333333"><div id="clock"></div></font></td>
	</tr>
	
	</table>


    </td>
	<td background="<?=GetThemeDir()?>bgmain2_20.jpg" width="17">&nbsp;</td>	
    <td width="13" bgcolor="<?=GetThemeColor()?>">&nbsp;</td>	
</tr>
</table>
</body>
</html>