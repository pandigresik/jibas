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
$menu="";
if (isset($_REQUEST['menu']))
	$menu=$_REQUEST['menu'];
$content="";
if (isset($_REQUEST['content']))
	$content=$_REQUEST['content'];	
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
function get_fresh(){
	document.location.reload();
}
function chating_euy(){
	newWindow('buletin/chat/chat.php','ChattingYuk',626,565,'resizable=0,scrollbars=0,status=0,toolbar=0');
}
function home(){
	document.location.reload();
	parent.framecenter.location.href="home.php";
}
function akademik(){
	sendRequestText("get_content.php", show_content, "menu=akademik");
	parent.framecenter.location.href="home.php";
}
function buletin(){
	sendRequestText("get_content.php", show_content, "menu=buletin");
	parent.framecenter.location.href="home.php";
}
function pengaturan(){
	sendRequestText("get_content.php", show_content, "menu=pengaturan");
	parent.framecenter.location.href="home.php";
}
function dotnet(){
	sendRequestText("get_content.php", show_content, "menu=dotnet");
	parent.framecenter.location.href="home.php";
}
function logout() {
    if (confirm("Anda yakin akan menutup Aplikasi Manajemen Keuangan ini?"))
		document.location.href="logout.php";
}
function show_content(x) {
	document.getElementById("vscroll0").innerHTML = x;
}
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}
function ganti() {
	var login=document.getElementById('login').value;
	var addr="pengaturan/ganti_password2.php";
	if (login=="LANDLORD" || login=="landlord"){
		alert ('Maaf, Administrator tidak dapat mengganti password !');
		parent.framecenter.location.href="center.php";
	} else {
		newWindow(addr,'GantiPasswordUser','419','200','resizeable=0,scrollbars=0,status=0,toolbar=0');
	}
}
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
function BlinkText(Current){
	if (Current=='')
		Current=0;
	Current = parseInt(Current);
	var Txt = "DEMO Version";

	if (Current==(Txt.length+10)){
		Current=0;
		document.getElementById('TxtDemo').innerHTML = '';
	}
	var	x   = Txt.charAt(Current);
	Current = parseInt(Current);
	setTimeout("BlinkText2('"+x+"','"+Current+"')",100);
}
function BlinkText2(x,Current){
	var y = document.getElementById('TxtDemo').innerHTML;
	document.getElementById('TxtDemo').innerHTML = y+x;
	Current = parseInt(Current);
	BlinkText(Current+1);
}
</script>
<style type="text/css">
<!--
.style3 {font-size: 10px; font-weight:bold; line-height:10px; }
.style7 {font-size: 13px; font-weight: normal; line-height: 10px; font-family: Arial; color:#333333 }
.style9 {color: #FFFFFF; font-weight: bold; font-family: Verdana; font-size: 12px; text-decoration:none }
.style10 {
	color: #FFFFFF;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}
-->
</style>
</head>
<body style="background-color:#6a6a6a" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" >
<table id="Table_01" width="100%" border="0" cellpadding="0" cellspacing="0">	
    <tr>
		<td>
			<img src="images/Keuangan2_01.png" width="25" height="13" alt=""></td>
		<td width="50%" height="13" valign="bottom" background="images/Keuangan2_02.png">
        <!--a class="style9" style="color:#FFFFFF; cursor:pointer" onClick="show_info()" >Tentang JIBAS</a-->		</td>
		<td width="50%" align="right" valign="bottom" background="images/Keuangan2_02.png">
          
        </td>
		<td background="images/Keuangan2_03.png" width="94" height="13">
   		</td>
		<td>
			<img src="images/Keuangan2_04.png" width="10" height="13" alt=""></td>
		<td>
			<img src="images/Keuangan2_05.png" width="17" height="13" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/Keuangan2_06.png" width="25" height="61" alt=""></td>
		<td width="100%" height="46" colspan="2" background="images/Keuangan2_07.png">
        
        <table width="200" border="0" cellspacing="0" cellpadding="0" id="tentang" style="display:none">
          <tr>
            <td align="center"><a href="jibasinfo.php" target="content"><img src="images/jibas_info.png" border="0" /></a><br />
              <span class="style10">Tentang JIBAS</span></td>
            <td align="center"><a href="jibascontact.php" target="content"><img src="images/jibas_contact.png" border="0" /></a><br />
              <span class="style10">Hubungi Kami</span></td>
          </tr>
        </table>
        <table border="0" cellpadding="3" cellspacing="0" align="left" id="menu">
        <tr>
        <td align="center"><a href="referensi.php" target="content" style="color:#FFFFFF; text-decoration:none">
    <img src="images/referensi.png" height="40" border="0" /><br />
    <span class="style10">Referensi</span></a></td>
    <td width="5">&nbsp;</td>
        <td align="center"><a href="penerimaan.php" target="content" style="color:#FFFFFF; text-decoration:none">
    <img src="images/down.png" height="40" border="0" /><br />
    <span class="style10">Penerimaan</span></a></td>
    <td width="5">&nbsp;</td>
    <td align="center"><a href="pengeluaran.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/bt_up.png" height="40" border="0" /><br />
      <span class="style10">Pengeluaran</span></a></td>
    <td width="5">&nbsp;</td>
	<td align="center"><a href="tabungan/tabungan.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/tabungan.png" height="40" border="0" /><br />
      <span class="style10">Tabungan Siswa</span></a></td>
    <td width="5">&nbsp;</td>
    <td align="center"><a href="tabunganp/tabungan.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/tabunganp.png" height="40" border="0" /><br />
            <span class="style10">Tabungan Pegawai</span></a></td>
    <td width="5">&nbsp;</td>
    <td align="center"><a href="schoolpay/schoolpay.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/schoolpay.png" height="40" border="0" /><br />
            <span class="style10">SchoolPay</span></a></td>
    <td width="5">&nbsp;</td>
    <td align="center"><a href="onlinepay/onlinepay.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/jspayicon.png" height="40" border="0" /><br />
            <span class="style10">OnlinePay</span></a></td>
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
    <td align="center"><a href="usermenu.php" target="content" style="color:#FFFFFF; text-decoration:none"><img src="images/Settings.png" height="40" border="0" /><br />
        <span class="style10">Pengaturan</span></a></td>
    <td width="5">&nbsp;</td>
    <td align="center"><a href="javascript:logout()" style="color:#FFFFFF; text-decoration:none"><img src="images/logout.png" width="46" height="40" border="0" /><br />
        <span class="style10">Keluar</span></a></td> 
        </tr>
        </table>
		
        </td>
		<td>
			<img src="images/Keuangan2_08.png" width="94" height="61" alt=""></td>
		<td>
			<img src="images/Keuangan2_09.png" width="10" height="61" alt=""></td>
		<td>
			<img src="images/Keuangan2_10.png" width="17" height="61" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/Keuangan2_11.png" width="25" height="13" alt=""></td>
		<td width="100%" height="13" colspan="2" background="images/Keuangan2_12.png">		</td>
		<td>
			<img src="images/Keuangan2_13.png" width="94" height="13" alt=""></td>
		<td>
			<img src="images/Keuangan2_14.png" width="10" height="13" alt=""></td>
		<td>
			<img src="images/Keuangan2_15.png" width="17" height="13" alt=""></td>
	</tr>
</table>


</body>
</html>