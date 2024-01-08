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
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMTAKA[Daftar Anggota]</title>
<link rel="stylesheet" type="text/css" href="../sty/style.css" />
<script language = "javascript" type = "text/javascript" src="../scr/tables.js"></script>
<script language = "javascript" type = "text/javascript" src="../scr/tools.js"></script>
<script language="javascript" src="../scr/ajax.js"></script>
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
	//opener.acceptPegawai(nip, nama);
	//opener.document.getElementById('urutan').focus();
	//opener.document.getElementById('kapasitas').focus();
	window.close();
}

</script>
</script>
	<link type="text/css" href="../scr/jquery3/themes/default/ui.all.css" rel="stylesheet" />
	<script type="text/javascript" src="../scr/jquery3/jquery-1.2.6.js"></script>
	<script type="text/javascript" src="../scr/jquery3/ui/ui.core.js"></script>
	<script type="text/javascript" src="../scr/jquery3/ui/ui.tabs.js"></script>
	<link type="text/css" href="../scr/jquery3/demos.css" rel="stylesheet" />
	<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
</script>
<script type="text/javascript">
//var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//alert ('bagian <?//=$bagian?>');
function view_tab_daftar(){
	sendRequestText("daftar_anggota.php", show_panel, "");
}
function view_tab_cari(){
	sendRequestText("cari_anggota.php", show_panel, "");
}
function show_panel(x) {
	document.getElementById("panel").innerHTML = x;
	//Tables('table', 1, 0);
	//var spryselect1 = new Spry.Widget.ValidationSelect("bag");
	//document.getElementById('bag').focus();
}

function show_panel1(x) {
	document.getElementById("panel1").innerHTML = x;
	/*var spryselect1 = new Spry.Widget.ValidationSelect("bag");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
	var sprytextfield2 = new Spry.Widget.ValidationTextField("nip");*/
	document.getElementById('nama').focus();
}

function show_panel2(x) {
	document.getElementById("panel1").innerHTML = x;	
	Tables('table1', 1, 0);	
}


function change_page(page, tipe) {
	if (tipe == "daftar") {
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("daftar_anggota.php", show_panel, "page="+page+"&hal="+page+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else {
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nip=document.getElementById("nip").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_anggota.php", show_panel, "submit=1&nip="+nip+"&nama="+nama+"&bagian="+bagian+"&page1="+page+"&hal1="+page+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
		
	}
	//document.location.href="pegawai.php?bagian="+bagian+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal(tipe) {
	if (tipe == "daftar") 	{
		var hal = document.getElementById("hal").value;
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("daftar_anggota.php", show_panel, "page="+hal+"&hal="+hal+"&urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else { 
		var hal = document.getElementById("hal1").value;
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nip=document.getElementById("nip").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_anggota.php", show_panel, "submit=1&nip="+nip+"&nama="+nama+"&page1="+hal+"&hal1="+hal+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
	}
}

function change_baris(tipe) {
	if (tipe == "daftar") 	{
		var varbaris=document.getElementById("varbaris").value;
		var urut=document.getElementById("urut").value;
		var urutan=document.getElementById("urutan").value;
		
		sendRequestText("daftar_anggota.php", show_panel, "urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris);
	} else {
		var varbaris=document.getElementById("varbaris1").value;
		var urut=document.getElementById("urut1").value;
		var urutan=document.getElementById("urutan1").value;
		var nip=document.getElementById("nip").value;
		var nama=document.getElementById("nama").value;	
		
		sendRequestText("cari_anggota.php", show_panel, "submit=1&nip="+nip+"&nama="+nama+bagian+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris);
	}
	//document.location.href="pegawai.php?bagian="+bagian+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_urut(urut,urutan,tipe) {
	if (tipe == "daftar") 	{
		var varbaris=document.getElementById("varbaris").value;
		var hal = document.getElementById("hal").value;
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("daftar_anggota.php", show_panel, "urut="+urut+"&urutan="+urutan+"&varbaris="+varbaris+"&page="+hal+"&hal="+hal);
	} else {
		var varbaris=document.getElementById("varbaris1").value;
		var hal = document.getElementById("hal1").value;
		var nip=document.getElementById("nip").value;
		var nama=document.getElementById("nama").value;	
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("cari_anggota.php", show_panel, "submit=1&nip="+nip+"&nama="+nama+"&urut1="+urut+"&urutan1="+urutan+"&varbaris1="+varbaris+"&page1="+hal+"&hal1="+hal);
	}	
	//document.location.href="pegawai.php?bagian="+bagian+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function carilah(){
	var nip = document.getElementById('nip').value;
	var nama = document.getElementById('nama').value;
	
	if (nip == "" && nama == "") {
		alert ('No Registrasi atau Nama Anggota tidak boleh kosong!');
		document.getElementById("nama").focus();	
		return false;
	}
	
	sendRequestText("cari_anggota.php", show_panel, "submit=1&nip="+nip+"&nama="+nama);
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
    <table border="0" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" width="100%" >
    <tr height="525">
    	<td width="100%" bgcolor="#FFFFFF" valign="top">
        <div id="tabs">
            <ul>
                <li><a href="#panel" onclick="view_tab_daftar()">Daftar Anggota</a></li>
                <li><a href="#panel" onclick="view_tab_cari()">Cari Anggota</a></li>
            </ul>
            <div id="panel">
                <script language="javascript">
					//alert ('Asup');
					sendRequestText("daftar_anggota.php", show_panel, "");
				</script>
            </div>
        </div>
        </td>
    </tr>
    </table>
</body>
</html>