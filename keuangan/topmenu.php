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
require_once('include/config.php');
require_once('include/theme.php');
require_once('include/db_functions.php');

OpenDb();
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op=="a5t2vb7ys763yhuy7s")
{
	$sql="UPDATE jbsuser.hakakses SET theme='".$_REQUEST['tema']."' WHERE login='".getIdUser()."' AND modul='KEUANGAN'";
	$result=QueryDb($sql);
	session_name("jbsfina");
	session_start();
	unset($_SESSION['temakeuangan']);
	$_SESSION['temakeuangan']=$_REQUEST['tema'];
?>
<script type="text/javascript">
	parent.topleft.location.reload();	
	document.location.href="topmenu.php";
	parent.topright.location.reload();
	parent.midleft.location.reload();
	parent.midright.location.reload();
	parent.bottomleft.location.reload();
	parent.footer.location.reload();
	parent.bottomright.location.reload();
</script>
<?php
}
CloseDb();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" language="JavaScript1.2" src="script/dhtml/stmenu.js"></script>
<script type="text/javascript">

function get_fresh(){
document.location.reload();
}

function chg_thm(theme){
	document.location.href="topmenu.php?op=a5t2vb7ys763yhuy7s&tema="+theme;
}
function logout() {
    if (confirm("Anda yakin akan menutup Aplikasi Manajemen Keuangan ini?")){
		top.location.href="../akademik/logout.php";
	} 
	
	return false;
}
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_nbGroup(event, grpName) { //v6.0
  var i,img,nbArr,args=MM_nbGroup.arguments;
  if (event == "init" && args.length > 2) {
    if ((img = MM_findObj(args[2])) != null && !img.MM_init) {
      img.MM_init = true; img.MM_up = args[3]; img.MM_dn = img.src;
      if ((nbArr = document[grpName]) == null) nbArr = document[grpName] = new Array();
      nbArr[nbArr.length] = img;
      for (i=4; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
        if (!img.MM_up) img.MM_up = img.src;
        img.src = img.MM_dn = args[i+1];
        nbArr[nbArr.length] = img;
    } }
  } else if (event == "over") {
    document.MM_nbOver = nbArr = new Array();
    for (i=1; i < args.length-1; i+=3) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = (img.MM_dn && args[i+2]) ? args[i+2] : ((args[i+1])? args[i+1] : img.MM_up);
      nbArr[nbArr.length] = img;
    }
  } else if (event == "out" ) {
    for (i=0; i < document.MM_nbOver.length; i++) {
      img = document.MM_nbOver[i]; img.src = (img.MM_dn) ? img.MM_dn : img.MM_up; }
  } else if (event == "down") {
    nbArr = document[grpName];
    if (nbArr)
      for (i=0; i < nbArr.length; i++) { img=nbArr[i]; img.src = img.MM_up; img.MM_dn = 0; }
    document[grpName] = nbArr = new Array();
    for (i=2; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = img.MM_dn = (args[i+1])? args[i+1] : img.MM_up;
      nbArr[nbArr.length] = img;
  } }
}
//-->

</script>
<script language="javascript">
function show_info(){
	document.getElementById('menu').style.display='none';
	document.getElementById('tentang').style.display='';
	parent.content.location.href="jibasinfo.php";
}
function hide_info(){
	document.getElementById('menu').style.display='';
	document.getElementById('tentang').style.display='none';
	parent.content.location.href="penerimaan.php";
}
</script>
<style type="text/css">
<!--
.style9 {color: #FFFFFF; font-weight: bold; font-family: Verdana; font-size: 12px; }
.style10 {
	color: #000000;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="background:url(images/bgmain.jpg)" >
<input type="hidden" id="current" name="current" value="<?=$theme?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="38">
    <td width="35" align="right" background="<?=GetThemeDir() ?>bgmain_03a.jpg">
    	<a href="../akademik/logout.php" class="style1" onClick="return logout()" ><img src="images/ico/exit.gif" width="20" height="20" border="0" /></a>
    </td>
    <td width="109" align="left" background="<?=GetThemeDir() ?>bgmain_03a.jpg">
    	&nbsp;<a href="../akademik/logout.php" class="style1 style9" onClick="return logout()" style="color:#FFFF00;" >Keluar</a>&nbsp;
    </td>
    <td width="706" align="left" background="<?=GetThemeDir() ?>bgmain_03a.jpg">
	<?php  if (isset($_SESSION['namasimaka']))
		{ ?>
            <img src="images/ico/home.png" width="20" height="20" border="0" />
            <a href="../akademik/index2.php" title="Akademik" target="_top" class="style9" style="color:#FFFF00;">Akademik</a>&nbsp;&nbsp;
	<?php  } 
		else 
		{ ?>
            <a href="#" title="Akademik" target="_self" class="style9" onClick="alert ('Maaf Anda Tidak Berhak mengakses Halaman Akademik');" style="color:#FFFF00;">
            <img src="images/ico/home.png" width="20" height="20" border="0" />Akademik</a>&nbsp;&nbsp;
	<?php  } ?>
		<span class="style9" style="text-decoration:none; cursor:pointer" onClick="hide_info()">
        <img src="images/ico/keuangan.png" width="20" height="20" border="0" />Keuangan</span>&nbsp;&nbsp;&nbsp;&nbsp;
    	<img src="images/A.gif" width="20" height="20" />&nbsp;<span>
        <a class="style9" style="text-decoration:none;color:#FFFF00;" href="javascript:show_info()" >Tentang JIBAS</a></span>
		
    </td>
    <td width="70" align="right" background="<?=GetThemeDir() ?>bgmain_03a.jpg"></td>
    <td width="331" align="right" background="<?=GetThemeDir() ?>bgmain_03a.jpg"></td>
</tr>
<tr height="60">
    <td colspan="5" background="<?=GetThemeDir() ?>bgtable.jpg">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
    	<td width="90%">
       	<!-- Begin Content ================================================================================================-->
		<table width="200" border="0" cellspacing="0" cellpadding="0" id="tentang" style="display:none">
          <tr>
            <td align="center"><a href="jibasinfo.php" target="content"><img src="images/jibas_info.png" border="0" /></a><br />
              <span class="style10">Tentang JIBAS</span></td>
            <td align="center"><a href="jibascontact.php" target="content"><img src="images/jibas_contact.png" border="0" /></a><br />
              <span class="style10">Hubungi Kami</span></td>
          </tr>
        </table>
        <table border="0" cellpadding="3" cellspacing="0" align="left" id="menu"><tr>
	<td align="center"><a href="penerimaan.php" target="content" style="color:#FFFFFF; text-decoration:none">
    <img src="images/down.png" height="40" border="0" /><br />
    <span class="style10">Penerimaan</span></a></td>
    <td width="5">&nbsp;</td>
    <td align="center"><a href="pengeluaran.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/bt_up.png" height="40" border="0" /><br />
      <span class="style10">Pengeluaran</span></a></td>
    <td width="5">&nbsp;</td>
    <td align="center"><a href="jurnalumum.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/configuration_settings.png" height="40" border="0" /><br />
      <span class="style10">Jurnal Umum</span></a></td>
    <td width="5">&nbsp;</td>
    <td align="center"><a href="lapkeuangan.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/coffeecup_red.png" height="40" border="0" /><br />
      <span class="style10">Laporan Keuangan</span></a></td>
    <td width="5">&nbsp;</td>
    <td align="center"><a href="inventori/Inventory.Main.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/inventory.png" height="40" border="0" /><br />
        <span class="style10">Inventory</span></a></td>
    <td width="5">&nbsp;</td>
	<td align="center"><a href="usermenu.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/settings.png" height="40" border="0" /><br />
        <span class="style10">Pengaturan</span></a></td>
    </tr></table>
		<!-- End Content ==================================================================================================-->        </td>
        <td width="10%"><img src="<?=GetThemeDir() ?>jibaslogo.jpg" border="0" /><br /></td>
    </tr>
    </table>    </td>
</tr>
<tr height="26">
    <td colspan="5" background="<?=GetThemeDir() ?>bgmain_11a.jpg"></td>
</tr>
</table>
</body>
</html>