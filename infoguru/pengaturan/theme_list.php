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
require_once('../include/common.php');
require_once('../include/sessioninfo.php');
require_once('../sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once("../include/sessionchecker.php");

if (SI_USER_ID()=="landlord" || SI_USER_ID()=="LANDLORD"){
?>
<script language="javascript" >
	alert ('Maaf, Administrator tidak berhak mengganti tema');
	document.location.href="../blank.php";
</script>
<?php
}
OpenDb();
$theme="";
$replid="";
$res=QueryDb("SELECT replid,theme FROM jbsuser.hakakses WHERE modul='INFOGURU' AND login='".SI_USER_ID()."'");
$row=@mysqli_fetch_array($res);
$theme=$row['theme'];
$replid=$row['replid'];
CloseDb();
$op="";
if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];

if ($op=='dfuh8347hw87ddu293'){
	$thm=$_REQUEST['tema'];
	OpenDb();
		$sql="UPDATE jbsuser.hakakses SET theme=$thm WHERE replid=$replid";
		//echo $sql;
		//exit;
		$res=QueryDb($sql);
	if ($res){
		unset($_SESSION['theme']);
		$_SESSION['theme']=$thm;
		?>
		<script language="javascript" >
			//alert ('Tema Anda sudah berubah\nPerubahan akan terlihat setelah Anda tekan F5');
			//top.location.reload();
			//document.location.href="theme_list.php";
			
			parent.frameleft.get_fresh();
			parent.frameright.get_fresh();
			parent.framebottom.get_fresh();
			parent.frametop.get_fresh();
			document.location.href="theme_list.php";
		</script>
        <?php 
			}	
	CloseDb();
}
if (isset($_REQUEST['5623bu9nfd98932jhkd'])){
		//unset($_SESSION['theme']);
		//$_SESSION['theme']=$thm;
		?>
        <script language="javascript" >
			//alert ('Tema Anda sudah berubah\nPerubahan akan terlihat setelah Anda tekan F5');
			//top.location.reload();
			//document.location.href="theme_list.php";
			
			parent.frameleft.get_fresh();
			parent.frameright.get_fresh();
			parent.framebottom.get_fresh();
			parent.frametop.get_fresh();
			//document.location.href="../home.php";
		</script>
<?php
}
function GetThemeImage($theme) {
	// Change this variable with user's SESSION theme
	if ($theme == 1) {
		return "../design/InfoGuru4_RED.jpg";
	} elseif ($theme == 2) {
		return "../design/InfoGuru4_BLUE.jpg";
	} elseif ($theme == 3) {
		return "../design/InfoGuru4_BLACK.jpg";
	} elseif ($theme == 4) {
		return "../design/InfoGuru4_GREEN.jpg";
	} elseif ($theme == 5) {
		return "../design/InfoGuru4_LAVENDER.jpg";
	} elseif ($theme == 6) {
		return "../design/InfoGuru4_BLUE_SEA.jpg";
	} elseif ($theme == 7) {
		return "../design/InfoGuru4_GREEN_PADI.jpg";
	} elseif ($theme == 8) {
		return "../design/InfoGuru4_REDDOT.jpg";
	} elseif ($theme == 9) {
		return "../design/InfoGuru4_SUNSET.jpg";
	} elseif ($theme == 10) {
		return "../design/InfoGuru4_ORANGE.jpg";
	} elseif ($theme == 11) {
		return "../design/InfoGuru4_DIRT.jpg";
	} elseif ($theme == 12) {
		return "../design/InfoGuru4_PURPLE.jpg";
	}
	 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" type="text/javascript" src="../script/ajax.js"></script>
<script language="javascript" type="text/javascript" >
function show_theme(id,dir) {
	show_wait("active_theme");
	sendRequestText("getthemeimage.php", show_preview, "id="+id+"&dir="+dir);
	thm_fresh();
	//sendRequestText("updatetheme.php", show_preview, "id="+id);
	//newWindow('gambarlogin_add.php', 'TambahGambar','500','210','resizable=1,scrollbars=0,status=0,toolbar=0')
}
function chg_theme(id,dir) {
	document.location.href="theme_list.php?op=dfuh8347hw87ddu293&tema="+id;
	//sendRequestText("updatetheme.php", show_preview, "id="+id);
	//newWindow('gambarlogin_add.php', 'TambahGambar','500','210','resizable=1,scrollbars=0,status=0,toolbar=0')
}
function show_preview(x) {
	document.getElementById("active_theme").innerHTML = x;
}
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}
function thm_fresh() {
			//parent.framebottom.get_fresh();
			//parent.frametop.get_fresh();
			//parent.frameleft.get_fresh();
			//parent.frameright.get_fresh();
			document.location.href="theme_list.php?5623bu9nfd98932jhkd";
}
</script>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style2 {
	font-size: 14px;
	font-family: Calibri;
	font-weight: bold;
	color: #990000;
}
.style3 {color: #666666}
-->
</style>
</head>
<body>
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/ico/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<form action="theme_list.php" method="Get">
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td colspan="2" align="left" valign="bottom"><div id="active_theme" style="width:320px; height:240px;"><img src="<?=GetThemeImage($theme)?>" width="320" height="240" align="left"/></div></td>
    <td colspan="2" align="left" valign="middle"><span class="style2">Tema yang aktif &nbsp;&quot;
	<?php 
	switch($theme){
		case 1 :
			echo " Red ";
			break;
		case 2 :
			echo " Blue ";
			break;
		case 3 :
			echo " Black ";
			break;
		case 4 :
			echo " Green ";
			break;
		case 5 :
			echo " Lavender ";
			break;
		case 6 :
			echo " Sea ";
			break;
		case 7 :
			echo " Padi ";
			break;
		case 8 :
			echo " Red v2 ";
			break;
		case 9 :
			echo " Sunset ";
			break;
		case 10 :
			echo " Orange ";
			break;
		case 11 :
			echo " Dirt ";
			break;
		case 12 :
			echo " Purple ";
			break;			
	}
	?>&quot;</span><br />
        <span class="style3">Untuk mengganti tema, silakan klik gambar-gambar
         di bawah</span><!--
    , lalu klik Ganti Tema</span>
        <label>
      <input name="ganti" type="submit" class="but" id="ganti" value="Ganti Tema" />
      </label>    --></td>
  </tr>
  <tr>
    <td colspan="5" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td width="20%"><div align="center"><img src="../design/InfoGuru4_RED.jpg" width="176" height="132" onClick="chg_theme(1,'<?=GetThemeImage(1)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="1" <?php if ($theme==1) echo "checked"; ?>/>-->
    Red</div></td>
    <td width="20%"><div align="center"><img src="../design/InfoGuru4_BLUE.jpg" width="176" height="132" onClick="chg_theme(2,'<?=GetThemeImage(2)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="2" <?php if ($theme==2) echo "checked"; ?>/>-->Blue</div></td>
    <td width="20%"><div align="center"><img src="../design/InfoGuru4_BLACK.jpg" width="176" height="132" onClick="chg_theme(3,'<?=GetThemeImage(3)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="3" <?php if ($theme==3) echo "checked"; ?>/>-->
    Black</div></td>
    <td width="20%"><div align="center"><img src="../design/InfoGuru4_GREEN.jpg" width="176" height="132" onClick="chg_theme(4,'<?=GetThemeImage(4)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="4" <?php if ($theme==4) echo "checked"; ?>/>-->
    Green</div></td>
    <td width="20%"><div align="center"><img src="../design/InfoGuru4_LAVENDER.jpg" width="176" height="132" onClick="chg_theme(5,'<?=GetThemeImage(5)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="5" <?php if ($theme==5) echo "checked"; ?>/>-->
    Lavender</div></td>
  </tr>
  <tr>
    <td><div align="center"><img src="../design/InfoGuru4_BLUE_SEA.jpg" width="176" height="132" onClick="chg_theme(6,'<?=GetThemeImage(6)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="6" <?php if ($theme==6) echo "checked"; ?>/>-->
    Sea</div></td>
    <td><div align="center"><img src="../design/InfoGuru4_GREEN_PADI.jpg" width="176" height="132" onClick="chg_theme(7,'<?=GetThemeImage(7)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="7" <?php if ($theme==7) echo "checked"; ?>/>-->
    Padi</div></td>
    <td><div align="center"><img src="../design/InfoGuru4_REDDOT.jpg" width="176" height="132" onClick="chg_theme(8,'<?=GetThemeImage(8)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="8" <?php if ($theme==8) echo "checked"; ?>/>-->
    Red v2</div></td>
    <td><div align="center"><img src="../design/InfoGuru4_SUNSET.jpg" width="176" height="132" onClick="chg_theme(9,'<?=GetThemeImage(9)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="9" <?php if ($theme==9) echo "checked"; ?>/>-->
    Sunset</div></td>
    <td><div align="center"><img src="../design/InfoGuru4_ORANGE.jpg" width="176" height="132" onClick="chg_theme(10,'<?=GetThemeImage(10)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="10" <?php if ($theme==10) echo "checked"; ?>/>-->
    Orange</div></td>
  </tr>
   <tr>
    <td><div align="center"><img src="../design/InfoGuru4_DIRT.jpg" width="176" height="132" onClick="chg_theme(11,'<?=GetThemeImage(11)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="6" <?php if ($theme==6) echo "checked"; ?>/>-->
    Dirt</div></td>
    <td><div align="center"><img src="../design/InfoGuru4_PURPLE.jpg" width="176" height="132" onClick="chg_theme(12,'<?=GetThemeImage(12)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="6" <?php if ($theme==6) echo "checked"; ?>/>-->
    Purple</div></td>
    <td></td><td></td><td></td>
    </tr>
</table>
<input name="replid" type="hidden" value="<?=$replid?>"/>
</form>
</body>
</html>