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
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['angkatan']))
	$angkatan = $_REQUEST['angkatan'];
if (isset($_REQUEST['id']))
	$flag = $_REQUEST['id'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Daftar Siswa]</title>
<link rel="stylesheet" type="text/css" href="style/style.css" />
<link href="script/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script src="script/SpryTabbedPanels.js" type="text/javascript"></script>
<script language = "javascript" type = "text/javascript" src="script/tables.js"></script>
<script src="script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="script/ajax.js" type="text/javascript"></script>
<script language="javascript">

function pilih(nis, nama) {	
	opener.acceptSiswa(nis, nama, <?=$flag?>);
	window.close();
}

</script>
</head>

<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" style="background-color:#dfdec9">
<!--<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />&nbsp;please wait...
</div>-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Siswa :.
    </div>
	</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
	<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
    <table border="0" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" width="100%" >
    <tr height="525">
    	<td width="100%" bgcolor="#FFFFFF" valign="top">
        
        <div id="TabbedPanels1" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab" tabindex="0"><font size="1">Pilih Siswa</font></li>
                <li class="TabbedPanelsTab" tabindex="0"><font size="1">Cari Siswa</font></li>
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
//var departemen = document.getElementById('departemen').value;	

sendRequestText("pilih_siswa.php", show_panel0, "departemen=<?=$departemen?>");
sendRequestText("cari_siswa.php", show_panel1, "departemen=-1");

function show_panel0(x) {
	document.getElementById("panel0").innerHTML = x;
	Tables('table', 1, 0);
	var spryselect1 = new Spry.Widget.ValidationSelect("depart");
	var spryselect2 = new Spry.Widget.ValidationSelect("angkatan");
	var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
	document.getElementById('depart').focus();
}
		
function show_panel1(x) {
	document.getElementById("panel1").innerHTML = x;
	//document.getElementById('nama').focus();
	var spryselect1 = new Spry.Widget.ValidationSelect("depart1");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
	
	var sprytextfield2 = new Spry.Widget.ValidationTextField("nis");
	document.getElementById('depart1').focus();
}

function show_panel2(x) {
	document.getElementById("panel1").innerHTML = x;	
	Tables('table1', 1, 0);	
}


function carilah(){
	var departemen = document.getElementById('depart1').value;
	var nis = document.getElementById('nis').value;
	var nama = document.getElementById('nama').value;
	
	if (nis == "" && nama == "") {
		alert ('NIS atau Nama Siswa tidak boleh kosong!');
		document.getElementById("nama").focus();	
		return false;
	}	
	
	sendRequestText("cari_siswa.php", show_panel2, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen);
}

function change_departemen(tipe){
	if (tipe == 0) {
		var departemen = document.getElementById('depart').value;
		sendRequestText("pilih_siswa.php", show_panel0, "departemen="+departemen);	
	} else {
		var departemen = document.getElementById('depart1').value;
		sendRequestText("cari_siswa.php", show_panel1, "departemen="+departemen);	
	}
}

function change(){
	var departemen = document.getElementById('depart').value;
	var angkatan = document.getElementById('angkatan').value;
	var tingkat = document.getElementById('tingkat').value;
	sendRequestText("pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat);	
}

function change_kelas(){
	var departemen = document.getElementById('depart').value;
	var angkatan = document.getElementById('angkatan').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;
	sendRequestText("pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat+"&kelas="+kelas);	
}

function cari(x) {
	document.getElementById("caritabel").innerHTML = x;		
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

function focusNext1(elemName, evt, st, no, aktif) {
  	evt = (evt) ? evt : event;	
    var charCode = (evt.charCode) ? evt.charCode :
  		((evt.which) ? evt.which : evt.keyCode);
   	if (charCode == 13) {	
		var point = parseInt(no);		
		var mundur = point-1;
		var maju = point+1;
		
		if (aktif == 1) {
			mod = point % 2;
		
				if (mod != 0 && point != 1) 
					document.getElementById(elemName+st+mundur).style.background = "#E7E7CF";
				else if (mod == 0 && point != 1)
					document.getElementById(elemName+st+mundur).style.background = "#FFFFFF";
			document.getElementById(st+elemName+maju).focus();
			document.getElementById(elemName+st+no).style.background = "#FFFF00";
			
		} else {
			document.getElementById(st+elemName+no).focus();
			document.getElementById(elemName+st+no).style.background = "#FFFF00";
			
		}
		
        return false;
   	} 
	return true;
}

function change_page(page, tipe) {
	if (tipe == "daftar") {
		var departemen = document.getElementById('depart').value;
		var angkatan = document.getElementById('angkatan').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat+"&kelas="+kelas+"&page="+page+"&hal="+page+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else {
		var departemen = document.getElementById('depart1').value;
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nis=document.getElementById("nis").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_siswa.php", show_panel2, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen+"&page1="+page+"&hal1="+page+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
		
	}
	//document.location.href="pegawai.php?bagian="+bagian+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal(tipe) {
	if (tipe == "daftar") 	{
		var departemen = document.getElementById('depart').value;
		var angkatan = document.getElementById('angkatan').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;
		var hal = document.getElementById("hal").value;
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat+"&kelas="+kelas+"&page="+hal+"&hal="+hal+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else { 
		var departemen = document.getElementById("depart1").value;
		var hal = document.getElementById("hal1").value;
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nis=document.getElementById("nis").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_siswa.php", show_panel2, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen+"&page1="+hal+"&hal1="+hal+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
	}
}

function change_baris(tipe) {
	if (tipe == "daftar") 	{
		var departemen = document.getElementById('depart').value;
		var angkatan = document.getElementById('angkatan').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat+"&kelas="+kelas+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else {
		var departemen = document.getElementById("depart1").value;
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nis=document.getElementById("nis").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_siswa.php", show_panel2, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
	}
	//document.location.href="pegawai.php?bagian="+bagian+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_urut(urut,urutan,tipe) {
	if (tipe == "daftar") 	{
		var departemen = document.getElementById('depart').value;
		var angkatan = document.getElementById('angkatan').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;
		var varbaris=document.getElementById("varbaris").value;
		var hal = document.getElementById("hal").value;
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat+"&kelas="+kelas+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris+"&page="+hal+"&hal="+hal);
	} else {
		var departemen=document.getElementById("depart1").value;
		var varbaris=document.getElementById("varbaris1").value;
		var hal = document.getElementById("hal1").value;
		var nis=document.getElementById("nis").value;
		var nama=document.getElementById("nama").value;	
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("cari_siswa.php", show_panel2, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris+"&page1="+hal+"&hal1="+hal);
	}	
	//document.location.href="pegawai.php?bagian="+bagian+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

</script>