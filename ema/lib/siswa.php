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
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['id']))
	$flag = $_REQUEST['id'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Daftar Siswa]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<link rel="stylesheet" type="text/css" href="../script/tooltips.css" />
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript">

function pilih(nis, nama) {	
	opener.acceptPegawai(nis, nama, <?=$flag?>);
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
//var departemen = document.getElementById('departemen').value;	

function show_tab_pilih(){
	//alert ('pilih');
	sendRequestText("pilih_siswa.php", show_panel, "departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&tingkat=<?=$tingkat?>");
}
function show_tab_cari(){
	//alert ('cari');
	sendRequestText("cari_siswa.php", show_panel, "departemen=-1");
}
function show_panel(x) {
	document.getElementById("panel").innerHTML = x;
	//Tables('table', 1, 0);
	//var spryselect1 = new Spry.Widget.ValidationSelect("depart");
	//var spryselect2 = new Spry.Widget.ValidationSelect("tahunajaran");
	//var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
	//var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
	//document.getElementById('depart').focus();
}
		
function show_panel1(x) {
	document.getElementById("panel1").innerHTML = x;
	document.getElementById('nama').focus();
	//var spryselect1 = new Spry.Widget.ValidationSelect("depart1");
	//var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
	//var sprytextfield2 = new Spry.Widget.ValidationTextField("nis");
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
	
	sendRequestText("cari_siswa.php", show_panel, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen);
}

function change_departemen(tipe){
	if (tipe == 0) {
		var departemen = document.getElementById('depart').value;
		sendRequestText("pilih_siswa.php", show_panel, "departemen="+departemen);	
	} else {
		var departemen = document.getElementById('depart1').value;
		sendRequestText("cari_siswa.php", show_panel, "departemen="+departemen);	
	}
}

function change(){
	var departemen = document.getElementById('depart').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	sendRequestText("pilih_siswa.php", show_panel, "departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat);	
}

function change_kelas(){
	var departemen = document.getElementById('depart').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;
	sendRequestText("pilih_siswa.php", show_panel, "departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas);	
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

function change_page(page, tipe) {
	if (tipe == "daftar") {
		var departemen = document.getElementById('depart').value;
		var tahunajaran = document.getElementById('tahunajaran').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("pilih_siswa.php", show_panel, "departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&page="+page+"&hal="+page+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else {
		var departemen = document.getElementById('depart1').value;
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nis=document.getElementById("nis").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_siswa.php", show_panel, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen+"&page1="+page+"&hal1="+page+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
		
	}
	//document.location.href="pegawai.php?bagian="+bagian+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal(tipe) {
	if (tipe == "daftar") 	{
		var departemen = document.getElementById('depart').value;
		var tahunajaran = document.getElementById('tahunajaran').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;
		var hal = document.getElementById("hal").value;
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("pilih_siswa.php", show_panel, "departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&page="+hal+"&hal="+hal+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else { 
		var departemen = document.getElementById("depart1").value;
		var hal = document.getElementById("hal1").value;
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nis=document.getElementById("nis").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_siswa.php", show_panel, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen+"&page1="+hal+"&hal1="+hal+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
	}
}

function change_baris(tipe) {
	if (tipe == "daftar") 	{
		var departemen = document.getElementById('depart').value;
		var tahunajaran = document.getElementById('tahunajaran').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("pilih_siswa.php", show_panel, "departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else {
		var departemen = document.getElementById("depart1").value;
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nis=document.getElementById("nis").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_siswa.php", show_panel, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
	}
	//document.location.href="pegawai.php?bagian="+bagian+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_urut(urut,urutan,tipe) {
	if (tipe == "daftar") 	{
		var departemen = document.getElementById('depart').value;
		var tahunajaran = document.getElementById('tahunajaran').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;
		var varbaris=document.getElementById("varbaris").value;
		var hal = document.getElementById("hal").value;
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("pilih_siswa.php", show_panel, "departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris+"&page="+hal+"&hal="+hal);
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
		
		sendRequestText("cari_siswa.php", show_panel, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris+"&page1="+hal+"&hal1="+hal);
	}	
	//document.location.href="pegawai.php?bagian="+bagian+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

</script>
</head>

<body style="background-color:#FFFFFF">
	<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
    <table cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" width="100%">
    <tr height="525">
    	<td width="100%" bgcolor="#FFFFFF" valign="top">
        <div id="tabs">
            <ul>
				<li><a href="#panel" onclick="show_tab_pilih()">Pilih Siswa</a></li>
                <li><a href="#panel" onclick="show_tab_cari()">Cari Siswa</a></li>
            </ul>
            <div id="panel">
                <script language="javascript">
					//alert ('Asup');
					sendRequestText("pilih_siswa.php", show_panel, "departemen=<?=$departemen?>&tahunajaran=<?=$tahunajaran?>&tingkat=<?=$tingkat?>");
					//sendRequestText("cari_siswa.php", show_panel, "departemen=-1");
				</script>
            </div>
        </div>
        </td>
    </tr>
    </table>
   
</body>
</html>