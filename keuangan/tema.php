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
require_once('include/sessioninfo.php');
require_once('include/sessionchecker.php');
require_once('include/config.php');
require_once('include/theme.php');
require_once('include/db_functions.php');
if (getUserName()=="landlord" || getUserName()=="LANDLORD"){
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
$res=QueryDb("SELECT replid,theme FROM jbsuser.hakakses WHERE modul='KEUANGAN' AND login='".getIdUser()."'");
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
		$sql="UPDATE jbsuser.hakakses SET theme='$thm' WHERE replid='$replid'";
		//echo  $sql;
		//exit;
		$res=QueryDb($sql);
	if ($res){
		unset($_SESSION['theme']);
		$_SESSION['theme']=$thm;
		?>
		<script language="javascript" >
		//	alert ('Tema Anda sudah berubah\nPerubahan akan terlihat setelah Anda tekan F5');
			//top.location.reload();
			//document.location.href="theme_list.php";
			//document.location.reload();
			parent.topleft.get_fresh();
			parent.header.get_fresh();
			parent.topright.get_fresh();
			parent.midleft.get_fresh();
			parent.midright.get_fresh();
			parent.bottomleft.get_fresh();
			parent.footer.get_fresh();
			parent.bottomright.get_fresh();
			document.location.href="tema.php";
			//parent.location.reload();
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
		return "images/tumb/green.png";
	} elseif ($theme == 2) {
		return "images/tumb/ungu.png";
	} elseif ($theme == 3) {
		return "images/tumb/casual.png";
	} elseif ($theme == 4) {
		return "images/tumb/black.png";
	} elseif ($theme == 5) {
		return "images/tumb/vista.png";
	} elseif ($theme == 6) {
		return "images/tumb/coffee.png";
	} elseif ($theme == 7) {
		return "images/tumb/wood.png";
	} elseif ($theme == 8) {
		return "images/tumb/chocolate.png";
	} elseif ($theme == 9) {
		return "images/tumb/granite.png";
	} elseif ($theme == 10) {
		return "images/tumb/green.png";
	}
	 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/style.css">
<title>Untitled Document</title>
<script language="javascript" type="text/javascript" src="script/ajax.js"></script>
<script language="javascript" type="text/javascript" >
function show_theme(id,dir) {
	show_wait("active_theme");
	sendRequestText("getthemeimage.php", show_preview, "id="+id+"&dir="+dir);
	thm_fresh();
	//sendRequestText("updatetheme.php", show_preview, "id="+id);
	//newWindow('gambarlogin_add.php', 'TambahGambar','500','210','resizable=1,scrollbars=0,status=0,toolbar=0')
}
function chg_theme(id,dir) {
	document.location.href="tema.php?op=dfuh8347hw87ddu293&tema="+id;
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
			document.location.href="tema.php?5623bu9nfd98932jhkd";
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
.style4 {color: #808080}
-->
</style>
</head>
<body>
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/ico/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Tema</font><br />
            <a href="usermenu.php"><font size="1" color="#000000"><b>Pengaturan</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Tema</b></font></td>
  </tr>
</table>

<form action="theme_list.php" method="Get">
<table width="100%" border="0" cellspacing="0">
  <tr>
  	<td colspan="3"><span class="style4"><strong>Tema Yang Aktif Saat Ini :</strong></span></td>
  </tr>
  <tr>
  <tr>
  	<td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left" valign="bottom"><div id="active_theme" style="width:176px; height:132;"><img src="<?=GetThemeImage($theme)?>" width="176" height="132" align="left"/></div></td>
    <td width="78%" colspan="2" align="left" valign="middle"><span class="style2">Nama Tema : 
	<?php 
	switch($theme){
		case 1 :
			echo  " Green ";
			break;
		case 2 :
			echo  " Purple ";
			break;
		case 3 :
			echo  " Casual ";
			break;
		case 4 :
			echo  " Black ";
			break;
		case 5 :
			echo  " Vista ";
			break;
		case 6 :
			echo  " Coffee ";
			break;
		case 7 :
			echo  " Wood ";
			break;
		case 8 :
			echo  " Chocolate ";
			break;
		case 9 :
			echo  " Granite ";
			break;
		}
	?>
    </span><br />
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
 </table>
 <br />
 <table width="70%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 	<td colspan="5"><span class="style4"><strong>Pilihan Tema Yang Tersedia :</strong></span></td>
 </tr>
 <tr>
 	<td colspan="5">&nbsp;</td>
 </tr>
 <tr>
    <td width="20%"><div align="center"><img src="images/tumb/green.png" width="78" height="66" onClick="chg_theme(1,'<?=GetThemeImage(1)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="1" <?php if ($theme==1) echo  "checked"; ?>/>-->
    Green</div></td>
    <td width="20%"><div align="center"><img src="images/tumb/ungu.png" width="78" height="66" onClick="chg_theme(2,'<?=GetThemeImage(2)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="2" <?php if ($theme==2) echo  "checked"; ?>/>-->Purple</div></td>
    <td width="20%"><div align="center"><img src="images/tumb/casual.png" width="78" height="66" onClick="chg_theme(3,'<?=GetThemeImage(3)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="3" <?php if ($theme==3) echo  "checked"; ?>/>-->
    Casual</div></td>
    <td width="20%"><div align="center"><img src="images/tumb/black.png" width="78" height="66" onClick="chg_theme(4,'<?=GetThemeImage(4)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="4" <?php if ($theme==4) echo  "checked"; ?>/>-->
    Black</div></td>
    <td width="20%"><div align="center"><img src="images/tumb/vista.png" width="78" height="66" onClick="chg_theme(5,'<?=GetThemeImage(5)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="5" <?php if ($theme==5) echo  "checked"; ?>/>-->
    Vista</div></td>
  </tr>
  <tr>
  	<td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center"><img src="images/tumb/coffee.png" width="78" height="66" onClick="chg_theme(6,'<?=GetThemeImage(6)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="6" <?php if ($theme==6) echo  "checked"; ?>/>-->
    Coffee</div></td>
    <td><div align="center"><img src="images/tumb/wood.png" width="78" height="66" onClick="chg_theme(7,'<?=GetThemeImage(7)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="7" <?php if ($theme==7) echo  "checked"; ?>/>-->
    Wood</div></td>
    <td><div align="center"><img src="images/tumb/chocolate.png" width="78" height="66" onClick="chg_theme(8,'<?=GetThemeImage(8)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="8" <?php if ($theme==8) echo  "checked"; ?>/>-->
    Chocolate</div></td>
    <td><div align="center"><img src="images/tumb/granite.png" width="78" height="66" onClick="chg_theme(9,'<?=GetThemeImage(9)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
    <!--<input name="tema" id="tema" type="radio" value="9" <?php if ($theme==9) echo  "checked"; ?>/>-->
    Granite</div></td>
    <td>&nbsp;<!---<div align="center"><img src="../design/InfoGuru4_ORANGE.jpg" width="176" height="132" onClick="chg_theme(10,'<?=GetThemeImage(10)?>')" style="cursor:pointer;" title="Klik untuk ganti tema"/><br />
  <input name="tema" id="tema" type="radio" value="10" <?php if ($theme==10) echo  "checked"; ?>/>
    ???</div>--></td>
  </tr>
</table>

<input name="replid" type="hidden" value="<?=$replid?>"/>
</form>
</body>
</html>