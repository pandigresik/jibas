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
require_once('include/sessioninfo.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/common.php');
require_once('include/theme.php');
require_once('include/sessionchecker.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];
	
$bagian = "-1";
if (isset($_REQUEST['bagian']))
	$bagian = $_REQUEST['bagian'];	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Daftar Pegawai]</title>
<link rel="stylesheet" type="text/css" href="style/style.css" />
<link rel="stylesheet" type="text/css" href="script/tooltips.css" />
<link href="script/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="script/tables.js"></script>
<script language = "javascript" type = "text/javascript" src="script/tools.js"></script>
<script language="javascript" src="script/ajax.js"></script>
<script language="javascript" src="script/tooltips.js"></script>
<script src="script/SpryTabbedPanels.js" type="text/javascript"></script>
<script language="javascript">

function validate() {
	var nama = '' + document.getElementById('nama').value;
	var nip = '' + document.getElementById('nip').value;
	nama = trim(nama);
	nip = trim(nip);
	
	return (nama.length != 0) || (nip.length != 0);
}

function pilih(nip, nama) {
	//alert ('mau dipilih nih'+nip+' nama '+nama);
	opener.acceptPegawai(nip, nama, <?=$flag ?>);
	//opener.document.getElementById('urutan').focus();
	//opener.document.getElementById('kapasitas').focus();
	window.close();
}

</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dfdec9">
<!--<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />&nbsp;please wait...
</div>-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Pegawai :.
    </div>
	</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
    <table border="0" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" width="100%" >
    <tr height="500">
    	<td width="100%" bgcolor="#FFFFFF" valign="top">
        
        <div id="TabbedPanels1" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab" tabindex="0"><font size="1">Pilih Pegawai</font></li>
                <li class="TabbedPanelsTab" tabindex="0"><font size="1">Cari Pegawai</font></li>
            </ul>
            <div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent" id="panel0"></div>
                <div class="TabbedPanelsContent" id="panel1"></div>
            </div>
        </div>
        </td>
    </tr>
    </table>
     <!-- END OF CONTENT //--->
    </td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
   
</body>
</html>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//alert ('bagian <?=$bagian?>');
sendRequestText("daftar_pegawai.php", show_panel0, "bagian=<?=$bagian?>");
sendRequestText("cari_pegawai.php", show_panel1, "bagian=<?=$bagian?>");

function show_panel0(x) {
	document.getElementById("panel0").innerHTML = x;
	Tables('table', 1, 0);
	var spryselect1 = new Spry.Widget.ValidationSelect("bag");
	document.getElementById('bag').focus();
}

function show_panel1(x) {
	document.getElementById("panel1").innerHTML = x;
	var spryselect1 = new Spry.Widget.ValidationSelect("bag");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
	var sprytextfield2 = new Spry.Widget.ValidationTextField("nip");
	document.getElementById('nama').focus();
}

function show_panel2(x) {
	document.getElementById("panel1").innerHTML = x;	
	Tables('table1', 1, 0);	
}

function change_bagian() {	
	var bagian = document.getElementById('bag').value;
	sendRequestText("daftar_pegawai.php", show_panel0, "bagian="+bagian);
	sendRequestText("get_blank.php", cari, "");
}

function change_page(page, tipe) {
	if (tipe == "daftar") {
		var bagian=document.getElementById("bag").value;
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("daftar_pegawai.php", show_panel0, "bagian="+bagian+"&page="+page+"&hal="+page+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else {
		var bagian=document.getElementById("bag").value;
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nip=document.getElementById("nip").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_pegawai.php", show_panel2, "submit=1&nip="+nip+"&nama="+nama+"&bagian="+bagian+"&page1="+page+"&hal1="+page+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
		
	}
	//document.location.href="pegawai.php?bagian="+bagian+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal(tipe) {
	if (tipe == "daftar") 	{
		var bagian = document.getElementById("bag").value;
		var hal = document.getElementById("hal").value;
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("daftar_pegawai.php", show_panel0, "bagian="+bagian+"&page="+hal+"&hal="+hal+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else { 
		var bagian = document.getElementById("bag").value;
		var hal = document.getElementById("hal1").value;
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nip=document.getElementById("nip").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_pegawai.php", show_panel2, "submit=1&nip="+nip+"&nama="+nama+"&bagian="+bagian+"&page1="+hal+"&hal1="+hal+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
	}
}

function change_baris(tipe) {
	if (tipe == "daftar") 	{
		var bagian = document.getElementById("bag").value;
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("daftar_pegawai.php", show_panel0, "bagian="+bagian+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else {
		var bagian = document.getElementById("bag").value;
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nip=document.getElementById("nip").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_pegawai.php", show_panel2, "submit=1&nip="+nip+"&nama="+nama+"&bagian="+bagian+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
	}
	//document.location.href="pegawai.php?bagian="+bagian+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_urut(urut,urutan,tipe) {
	if (tipe == "daftar") 	{
		var bagian=document.getElementById("bag").value;
		var varbaris=document.getElementById("varbaris").value;
		var hal = document.getElementById("hal").value;
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("daftar_pegawai.php", show_panel0, "bagian="+bagian+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris+"&page="+hal+"&hal="+hal);
	} else {
		var bagian=document.getElementById("bag").value;
		var varbaris=document.getElementById("varbaris1").value;
		var hal = document.getElementById("hal1").value;
		var nip=document.getElementById("nip").value;
		var nama=document.getElementById("nama").value;	
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("cari_pegawai.php", show_panel2, "submit=1&nip="+nip+"&nama="+nama+"&bagian="+bagian+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris+"&page1="+hal+"&hal1="+hal);
	}	
	//document.location.href="pegawai.php?bagian="+bagian+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function carilah(){
	var nip = document.getElementById('nip').value;
	var nama = document.getElementById('nama').value;
	var bagian = document.getElementById('bag').value;
	
	if (nip == "" && nama == "") {
		alert ('NIP atau Nama Pegawai tidak boleh kosong!');
		document.getElementById("nama").focus();	
		return false;
	}
	
	sendRequestText("cari_pegawai.php", show_panel2, "submit=1&nip="+nip+"&nama="+nama+"&bagian="+bagian);
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