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
require_once('../include/sessioninfo.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../include/theme.php');
require_once('../sessionchecker.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];

$departemen = "-1";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$pelajaran = "-1";
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Daftar Guru]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<link rel="stylesheet" type="text/css" href="../script/tooltips.css" />
<link href="../script/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<script src="../script/SpryTabbedPanels.js" type="text/javascript"></script>
<script language="javascript">

function pilih(nip, nama, dep, pel) {
	//alert ('nip='+nip+',nama='+nama+',dep='+dep+',pel='+pel);
	opener.acceptPegawai(nip, nama, <?=$flag ?>, dep, pel);
	window.close();
}

</script>
<link type="text/css" href="../script/jquery3/themes/default/ui.all.css" rel="stylesheet" />
<script type="text/javascript" src="../script/jquery3/jquery-1.2.6.js"></script>
<script type="text/javascript" src="../script/jquery3/ui/ui.core.js"></script>
<script type="text/javascript" src="../script/jquery3/ui/ui.tabs.js"></script>
<link type="text/css" href="../script/jquery3/demos.css" rel="stylesheet" />
<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
</script>
<script type="text/javascript">
//var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
function view_tab_daftar(){
	sendRequestText("daftar_guru.php", show_panel, "departemen=<?=$departemen?>&pelajaran=<?=$pelajaran?>");
}
function view_tab_cari(){
	sendRequestText("cari_guru.php", show_panel, "");
}
function show_panel(x) {
	document.getElementById("panel").innerHTML = x;
	//Tables('table', 1, 0);
	//var spryselect1 = new Spry.Widget.ValidationSelect("depart");
	//var spryselect2 = new Spry.Widget.ValidationSelect("pelajaran");
}
		
function show_panel1(x) {
	document.getElementById("panel1").innerHTML = x;
	document.getElementById('nama').focus();
	var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
	var sprytextfield2 = new Spry.Widget.ValidationTextField("nip");
}

function show_panel2(x) {
	document.getElementById("panel1").innerHTML = x;	
	Tables('table1', 1, 0);	
}


function get_guru(){	
	var pelajaran = document.getElementById('pelajaran').value;
	var departemen = document.getElementById('depart').value;
	
	sendRequestText("daftar_guru.php", show_panel, "pelajaran="+pelajaran+"&departemen="+departemen);
}

function change_departemen() {	
	var departemen = document.getElementById('depart').value;
	//var pelajaran = document.getElementById('pelajaran').value;	
	sendRequestText("daftar_guru.php", show_panel, "departemen="+departemen);
}

function carilah(){
	var nip = document.getElementById('nip').value;
	var nama = document.getElementById('nama').value;
	
	if (nip == "" && nama == "") {
		alert ('NIP atau Nama Guru tidak boleh kosong!');
		document.getElementById("nama").focus();	
		return false;
	}
	
	sendRequestText("cari_guru.php", show_panel, "submit=1&nip="+nip+"&nama="+nama);
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    } else {		
		sendRequestText("get_blank.php", cari, "");
	}
    return true;
}

function cari(x) {
	document.getElementById("caritabel").innerHTML = x;		
}


</script>
</head>

<body style="background-color:#FFFFFF">
<!--
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />&nbsp;please wait...
</div>-->
<!--<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
     CONTENT GOES HERE //--->
	<!--<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />-->
    <table border="0" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" width="100%" >
    <tr height="500">
    	<td width="100%" bgcolor="#FFFFFF" valign="top">
        <div id="tabs">
            <ul>
                <li><a href="#panel" onclick="view_tab_daftar()">Daftar Pegawai</a></li>
                <li><a href="#panel" onclick="view_tab_cari()">Cari Pegawai</a></li>
            </ul>
            <div id="panel">
                <script language="javascript">
					//alert ('Asup');
					sendRequestText("daftar_guru.php", show_panel, "departemen=<?=$departemen?>&pelajaran=<?=$pelajaran?>");
				</script>
            </div>
        </div>
        <!--
        <div id="TabbedPanels1" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab" tabindex="0"><font size="1">Pilih Guru</font></li>
                <li class="TabbedPanelsTab" tabindex="0"><font size="1">Cari Guru</font></li>
            </ul>
            <div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent" id="panel0"></div>
                <div class="TabbedPanelsContent" id="panel1"></div>
            </div>
        </div>
        -->
        </td>
    </tr>
    </table>
     <!-- END OF CONTENT 
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>//--->
   
</body>
</html>