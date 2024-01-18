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
require_once("include/sessioninfo.php");
require_once("include/db_functions.php");
require_once("include/config.php");
require_once("include/theme.php");

$menu = "";
if (isset($_REQUEST['menu']))
	$menu = $_REQUEST['menu'];
	
$content = "";
if (isset($_REQUEST['content']))
	$content = $_REQUEST['content'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" language="JavaScript1.2" src="design/dhtml/stmenu.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="script/ajax.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="script/tools.js"></script>
<script type="text/javascript" language="JavaScript1.2">
function get_fresh()
{
	document.location.reload();
}

function chating_euy()
{
	newWindow('buletin/chat/chat.php','ChattingYuk',626,565,'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function home()
{
	document.location.reload();
	//parent.framecenter.location.href="home.php";
}

function akademik()
{
	sendRequestText("get_content.php", show_content, "menu=akademik");
	//parent.framecenter.location.href="home.php";
}

function kepegawaian()
{
	sendRequestText("get_content.php", show_content, "menu=kepegawaian");
	//parent.framecenter.location.href = "home.php";
}

function elearning()
{
    sendRequestText("get_content.php", show_content, "menu=elearning");
    //parent.framecenter.location.href = "home.php";
}

function buletin()
{
	sendRequestText("get_content.php", show_content, "menu=buletin");
	//parent.framecenter.location.href="home.php";
}

function pengaturan()
{
	sendRequestText("get_content.php", show_content, "menu=pengaturan");
	//parent.framecenter.location.href="home.php";
}

function logout()
{
	if (confirm('Anda yakin akan keluar dari InfoGuru ?'))
		document.location.href="logout.php";
}

function show_content(x)
{
	document.getElementById("vscroll0").innerHTML = x;
}

function show_wait(areaId)
{
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}

function ganti()
{
	var login=document.getElementById('login').value;
	var addr="pengaturan/ganti_password2.php";
	newWindow(addr,'GantiPasswordUser','419','200','resizeable=0,scrollbars=0,status=0,toolbar=0');
}
</script>
<style>
.iconTitle{
	font-family:Verdana;
	font-size:12px;
	font-weight:bold;
	color:#fff
}
</style>
</head>
<body style="background-color:#6a6a6a" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onload="buletin()">

<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="images/ico/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<form name="top">
<input type="hidden" value="<?=$theme?>" id="theme" name="theme">
<input type="hidden" id="login" value="<?=trim((string) SI_USER_NAME())?>">
</form>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="20">
		<img src="<?=GetThemeDir()?>InfoGuru_01.png" width="20" height="15" alt="">
	</td>
	<td height="15" colspan="2" background="<?=GetThemeDir()?>InfoGuru_02.png">
		<span style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">
	    <a href="javascript:buletin();" style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">Buletin</a>&nbsp;
<?php 	if (SI_USER_GROUP() == "Akademik" || SI_USER_GROUP() == "Admin") { ?>
            &nbsp;|&nbsp;
            <a href="javascript:akademik();" style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">Akademik</a>&nbsp;
<?php 		} ?>
<?php 		if (SI_USER_LEVEL() != 0) { ?>
            &nbsp;|&nbsp;
			<a href="javascript:kepegawaian();" style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">Kepegawaian</a>&nbsp;|&nbsp;
            <a href="javascript:elearning();" style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">School Tube</a>
<?php 	} ?>
        &nbsp;|&nbsp;
	    <a href="javascript:pengaturan();" style="font-family:Verdana; color:#FFFFFF; font-size:10px; font-weight:bold; text-decoration:none">Pengaturan</a>&nbsp;
        &nbsp;|&nbsp;
	    <a href="javascript:logout()" style="font-family:Verdana; color:#00FF00; font-size:10px; font-weight:bold; text-decoration:none">Logout</a>&nbsp;
		</span>
	</td>
	<td width="136">
		<img src="<?=GetThemeDir()?>InfoGuru_03.png" width="136" height="15" alt="">
	</td>
	<td width="17">
		<img src="<?=GetThemeDir()?>InfoGuru_04.png" width="17" height="15" alt="">
	</td>
</tr>
<tr>
	<td>
		<img src="<?=GetThemeDir()?>InfoGuru_05.png" width="20" height="64" alt=""></td>
	<td background="<?=GetThemeDir()?>InfoGuru_06.png" width="100%" height="64">
    <div id="vscroll0" align="left">    </div>	</td>
	<td background="<?=GetThemeDir()?>InfoGuru_06.png"></td>
	<td>
		<img src="<?=GetThemeDir()?>InfoGuru_07.png" width="136" height="64" alt=""></td>
	<td>
		<img src="<?=GetThemeDir()?>InfoGuru_08.png" width="17" height="64" alt=""></td>
</tr>
<tr>
	<td>
		<img src="<?=GetThemeDir()?>InfoGuru_09.png" width="20" height="8" alt=""></td>
	<td height="8" colspan="2" background="<?=GetThemeDir()?>InfoGuru_10.png">	</td>
	<td>
		<img src="<?=GetThemeDir()?>InfoGuru_11.png" width="136" height="8" alt=""></td>
	<td>
		<img src="<?=GetThemeDir()?>InfoGuru_12.png" width="17" height="8" alt=""></td>
</tr>
</table>
</body>
</html>