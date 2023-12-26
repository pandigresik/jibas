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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once("../include/theme.php");
$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];
//if (isset($_REQUEST['departemen']))
//	$departemen = $_REQUEST['departemen'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<link rel="stylesheet" type="text/css" href="../script/tooltips.css" />
<link href="../script/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/newwindow.js"></script>
<script src="../script/SpryTabbedPanels.js" type="text/javascript"></script>
<script language="javascript">

function validate() {
	var nama = '' + document.getElementById('nama').value;
	var nis = '' + document.getElementById('nis').value;
	nama = trim(nama);
	nis = trim(nis);
	
	return (nama.length != 0) || (nis.length != 0);
}

function pilih(nis, nama) {
	//alert ('mau dipilih nih'+nip+' nama '+nama);
		opener.acceptPegawai(nis, nama, <?=$flag ?>);
	window.close();
}
</script>
<script language="javascript" src="../script/cal2.js"></script>
<script language="javascript" src="../script/cal_conf2.js"></script>
<script language='JavaScript'>
	 Tables('table', 1, 0);
</script>
</head>

<body leftmargin="0" topmargin="0" style="background-color:#dcdfc4">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />&nbsp;please wait...
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->

    <table border="0" cellpadding="20" bgcolor="#FFFFFF" cellspacing="0" width="100%" >
    <tr>
    	<td width="100%" bgcolor="white" valign="top">
		
       

        </td>
    </tr>
    <tr height="600">
    	<td width="100%" bgcolor="#FFFFFF" valign="top">
        
        <div id="TabbedPanels1" class="TabbedPanels">
		<ul class="TabbedPanelsTabGroup">
		    <li class="TabbedPanelsTab" tabindex="0"><font size="1">Cari Siswa</font></li>
		    <li class="TabbedPanelsTab" tabindex="0"><font size="1">Pilih Siswa</font></li>
		</ul>
		<div class="TabbedPanelsContentGroup">
	    	<div class="TabbedPanelsContent" id="panel0">

            </div>
		    <div class="TabbedPanelsContent" id="panel1">
            
           
		</div>
		</div>
        </td>
    </tr>
    </table>
     <!-- END OF CONTENT //--->
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
   
</body>
</html>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//var departemen = document.getElementById('departemen').value;	
sendRequestText("mutasi_cari_siswa.php", show_panel0, "");//"departemen="+departemen);
sendRequestText("mutasi_pilih_siswa.php", show_panel1, "");
function departemen() {
	var departemen=document.getElementById('departemen').value;
	alert ('Dep='+departemen);
	sendRequestText("mutasi_pilih_siswa.php", show_panel1, "departemen="+departemen);
}
function kelas() {
	var departemen=document.getElementById('departemen').value;
	var kelas=document.getElementById('kelas').value;
	alert ('Departemen='+departemen+',kelas='+kelas);
	sendRequestText("mutasi_pilih_siswa.php", show_panel1, "departemen="+departemen+"&kelas="+kelas);
}
function tampil() {
	var nis=document.getElementById('nis').value;
	var nama=document.getElementById('nama').value;
	alert ('Nis='+nis+',Nama='+nama);
	sendRequestText("mutasi_cari_siswa.php", show_panel0, "nis="+nis+"&nama="+nama+"&Submit=Submit");
}
Tables('table', 1, 0);


function wait_panel0() {
	show_wait("panel0");
}

function wait_panel1() {
	show_wait("panel1");
}


function show_panel0(x) {
	document.getElementById("panel0").innerHTML = x;
	document.getElementById('nama').focus();
}
		
function show_panel1(x) {
	document.getElementById("panel1").innerHTML = x;
	//var departemen=document.getElementById('departemen').value;
	//var departemen=document.getElementById('departemen').value;
	//alert ('Dep='+departemen);
	//var kelas=document.getElementById('kelas').value;
	//alert ('Departemen='+departemen+',kelas='+kelas);
	//sendRequestText("mutasi_pilih_siswa.php", show_panel1, "kelas="+kelas);

}

</script>