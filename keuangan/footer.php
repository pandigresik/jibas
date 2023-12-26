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
require_once('include/common.php');
//require_once('include/sessioninfo.php');
//require_once('include/sessionchecker.php');
require_once('include/config.php');
require_once('include/theme.php');
require_once('include/db_functions.php');
?>
<?php require_once("../akademik/include/sessioninfo.php") ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="../jibaskeu/script/clock.js"></script>
<script language="javascript">
function get_fresh(){
document.location.reload();
}
</script>
<title>Untitled Document</title>
<style type="text/css">
<!--
.style1 {
	font-size: 10px;
	font-weight: bold;
	font-family: Verdana;
	color: #000000;
}
-->
</style>
</head>


<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background:url(images/bgmain.jpg)" onload="startclock('clock')">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="25" valign="bottom">
    <td colspan="2" valign="bottom" background="<?=GetThemeDir() ?>bgmain_18a.jpg">	</td>
    <td background="<?=GetThemeDir() ?>bgmain_18a.jpg" valign="bottom">	</td>
    <td background="<?=GetThemeDir() ?>bgmain_18a.jpg" valign="bottom">	</td>
    </tr>
<tr height="42" valign="top">
    <td background="<?=GetThemeDir() ?>bgmain_22a.jpg" align="left"><span class="style1"><span class="style1">Selamat Datang 
      <?php
	if ($_SESSION['namakeuangan']=="landlord"){
		echo  "Administrator JIBAS [Keuangan]";
		} else {
		echo  $_SESSION['namakeuangan'];
		}
		?>
    </span><br />    </td>
    <td background="<?=GetThemeDir() ?>bgmain_22a.jpg" align="left"></td>
    <td background="<?=GetThemeDir() ?>bgmain_22a.jpg" rowspan="2" ><div align="right"><font color="black"><strong><div id="clock"></div></strong></font></div></td>
    <td rowspan="2" valign="top" background="<?=GetThemeDir() ?>bgmain_22a.jpg" >&nbsp;</td>
</tr><!---->
</table>
</body>
</html>