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
$idtahunbuku = $_REQUEST['idtahunbuku'];
$departemen = $_REQUEST['departemen'];
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link href="script/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language = "javascript" type = "text/javascript" src="script/tables.js"></script>
<script src="script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="script/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="script/ajax.js" type="text/javascript"></script>
<script language="javascript">


function pilih(id) {	
	parent.content.location.href = "lapbayarsiswa_all_content.php?nis="+id+"&idtahunbuku=<?=$idtahunbuku?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>";
}


</script>
</head>

<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF">
<input type="hidden" id="idtahunbuku" value="<?=$idtahunbuku ?>" />
<input type="hidden" id="tanggal1" value="<?=$tanggal1 ?>" />
<input type="hidden" id="tanggal2" value="<?=$tanggal2 ?>" />
<table border="0" width="100%" align="center" cellspacing="2" cellpadding="2">
<tr><td align="left">
 	<table border="0" cellpadding="2" bgcolor="#FFFFFF" cellspacing="0" width="100%" >
    <tr height="500">
    	<td width="100%" valign="top" bgcolor="#FFFFFF">
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
</tr>
</table>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
sendRequestText("library/pilih_siswa.php", show_panel0, "departemen=<?=$departemen?>");
sendRequestText("library/cari_siswa.php", show_panel1, "departemen=<?=$departemen?>");

function show_panel0(x) {
	document.getElementById("panel0").innerHTML = x;
	Tables('table', 1, 0);
	
	var spryselect1 = new Spry.Widget.ValidationSelect("depart");
	var spryselect2 = new Spry.Widget.ValidationSelect("angkatan");
	document.getElementById('angkatan').focus();
	var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect4 = new Spry.Widget.ValidationSelect("kelas");		
}
		
function show_panel1(x) {
	document.getElementById("panel1").innerHTML = x;
	
	var spryselect1 = new Spry.Widget.ValidationSelect("depart1");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
	var sprytextfield2 = new Spry.Widget.ValidationTextField("nis");
	document.getElementById('nama').focus();
}

function show_panel2(x) {
	document.getElementById("panel1").innerHTML = x;	
	Tables('table1', 1, 0);
		
}

function carilah(){
	var nis = document.getElementById('nis').value;
	var nama = document.getElementById('nama').value;
	var departemen = document.getElementById('depart1').value;
	
	if (nis == "" && nama == "") {
		alert ('NIS atau Nama Siswa tidak boleh kosong!');
		document.getElementById("nama").focus();	
		return false;
	}	
	sendRequestText("library/cari_siswa.php", show_panel2, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen);
	parent.content.location.href="blank_lapbayarsiswa_all.php";
}

function change_departemen(tipe){	
	if (tipe == 0) {
		var departemen = document.getElementById('depart').value;
		sendRequestText("library/pilih_siswa.php", show_panel0, "departemen="+departemen);	
	} else if (tipe == 1) {
		var departemen = document.getElementById('depart1').value;		
		sendRequestText("library/cari_siswa.php", show_panel1, "departemen="+departemen);	
	}
	parent.content.location.href="blank_lapbayarsiswa_all.php"; 
}

function change(){
	var departemen = document.getElementById('depart').value;
	var angkatan = document.getElementById('angkatan').value;
	var tingkat = document.getElementById('tingkat').value;
	sendRequestText("library/pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat);	
	parent.content.location.href="blank_lapbayarsiswa_all.php";
}

function change_kelas(){
	var departemen = document.getElementById('depart').value;
	var angkatan = document.getElementById('angkatan').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;
	sendRequestText("library/pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat+"&kelas="+kelas);
	parent.content.location.href="blank_lapbayarsiswa_all.php";
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

function change_urut(urut,urutan,tipe) {
	if (tipe == "daftar") 	{
		var departemen = document.getElementById('depart').value;
		var angkatan = document.getElementById('angkatan').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("library/pilih_siswa.php", show_panel0, "departemen="+departemen+"&angkatan="+angkatan+"&tingkat="+tingkat+"&kelas="+kelas+"&urut="+urut+"&urutan="+urutan);
	} else if (tipe == "cari") {
		var departemen=document.getElementById("depart1").value;
		var nis=document.getElementById("nis").value;
		var nama=document.getElementById("nama").value;	
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("library/cari_siswa.php", show_panel2, "submit=1&nis="+nis+"&nama="+nama+"&departemen="+departemen+"&urut1="+urut+"&urutan1="+urutan);
	}
}		
</script>
</body>
</html>