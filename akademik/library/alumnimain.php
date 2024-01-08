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
//include('../cek.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('departemen.php');

?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link href="../script/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/ajax.js"></script>
<script src="../script/SpryTabbedPanels.js" type="text/javascript"></script>
<script language="javascript">
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}
locnm=location.href;
pos=locnm.indexOf("indexb.htm");
locnm1=locnm.substring(0,pos);
function ByeWin() {

windowIMA=opener.ref_del_agama();
}
 
</script>
</head>
<body bgcolor="#FFFFFF" onUnload="ByeWin()">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />&nbsp;please wait...
</div>

<table width="100%" border="0">
  <tr>
    <td align="center"><strong>DAFTAR SISWA</strong></td>
  </tr>
  <tr>
    <td>
    <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor="#000000">

    <tr >
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
		</div>
        </td>
    </tr>
	
    </table>
    </td>
  </tr>
   <tr valign="top">
    <td align="center"><input type="button" value="Tutup" onclick="window.close();" class="but"></td>
  </tr>
</table>
</body>
</html>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");

sendRequestText("cari_alumni.php", show_panel0, "");
sendRequestText("pilih_alumni.php", show_panel1, "");

function wait_panel0() {
	show_wait("panel0");
}

function wait_panel1() {
	show_wait("panel1");
}

		
function show_panel0(x) {
	document.getElementById("panel0").innerHTML = x;
}
		
function show_panel1(x) {
	document.getElementById("panel1").innerHTML = x;
}

function cari(){
	var departemen=document.getElementById("departemen").value;
	var nis=document.getElementById("nis").value;
	var nama=document.getElementById("nama").value;
	var cari;
	if (nama.length==0 && nis.length==0){
		alert ('Anda harus mengisikan data untuk NIS dan/atau Nama !');
		document.getElementById("nis").focus();
		cari=0;
	}
	if (nama.length>0 && nama.length<3){
		alert ('Nama tidak boleh kurang dari 3 karakter !');
		document.getElementById("nama").focus();
		cari=0;
	}
	if (nis.length>0 && nis.length<3){
		alert ('NIS tidak boleh kurang dari 3 karakter !');
		document.getElementById("nis").focus();
		cari=0;
	}		
	//alert ('Dep='+departemen+'NIS='+nis+'Nama='+nama);
	if (cari!=0){
	show_wait("tabel_cari");
	
	sendRequestText("get_alumni_cari.php", show_tabelcari, "departemen="+departemen+"&nis="+nis+"&nama="+nama);
		
	//parent.mutasi_content.location.href="daftar_mutasi_siswa_footer.php?from_left=1&departemen="+departemen;
	}
}

function show_tabelcari(x) {
	document.getElementById("tabel_cari").innerHTML = x;
}
function show_blank_cari(x) {
	document.getElementById("tabel_cari").innerHTML = x;
}
function show_blank_pilih(x) {
	document.getElementById("tabel_pilih").innerHTML = x;
}
function departemen2() {
	var departemen2=document.getElementById("departemen2").value;
	show_wait("kelas_Info");
	show_wait("tabel_pilih");	
	sendRequestText("get_kelas_pilih.php", show_kelas, "departemen="+departemen2);
	//parent.mutasi_content.location.href="daftar_mutasi_siswa_footer.php?from_left=1&departemen="+departemen2;
}
function departemen() {
	//var departemen2=document.getElementById("departemen2").value;	
	sendRequestText("get_blank.php", show_blank_cari, "");
}

function show_kelas(x) {
	document.getElementById("kelas_Info").innerHTML = x;
	var kelas=document.getElementById("kelas").value;
	
	sendRequestText("get_alumni_pilih.php", show_tabelpilih, "kelas="+kelas);
	
}
function kelas() {
	//document.getElementById("kelas_Info").innerHTML = x;
	var departemen2=document.getElementById("departemen2").value;
	var kelas=document.getElementById("kelas").value;
	show_wait("tabel_pilih");

	
	sendRequestText("get_alumni_pilih.php", show_tabelpilih, "kelas="+kelas+"&alumni=1");

	//parent.mutasi_content.location.href="daftar_mutasi_siswa_footer.php?from_left=1&departemen="+departemen2;
	
}
function timeoutcari(){
	setTimeout("cari()",1);
}
function timeoutkelas() {
	setTimeout("kelas()",1);
}
function show_tabelpilih(x) {
	document.getElementById("tabel_pilih").innerHTML = x;
}

function ambilpilih(nis,nama) {
	var flag=0;
	//alert ('NIS='+nis);
	opener.acceptSiswa(nis, nama, flag);
	window.close();
	//document.getElementById("tabel_pilih").innerHTML = x;
	//parent.mutasi_content.location.href="siswa_mutasi.php?asal=pilih&tampil=tampil&nis="+nis;
}

</script>