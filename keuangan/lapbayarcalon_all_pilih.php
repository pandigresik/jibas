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
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="script/ajax.js" type="text/javascript"></script>
<script language="javascript">

function pilih(id) {	
	parent.content.location.href = "lapbayarcalon_all_content.php?replid="+id+"&idtahunbuku=<?=$idtahunbuku?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>";	
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
            	<li class="TabbedPanelsTab" tabindex="0"><font size="1">Pilih Calon Siswa</font></li>
            	<li class="TabbedPanelsTab" tabindex="0"><font size="1">Cari Calon Siswa</font></li>
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

var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
sendRequestText("library/pilih_calonsiswa.php", show_panel0, "departemen=<?=$departemen?>");
sendRequestText("library/cari_calonsiswa.php", show_panel1, "departemen=<?=$departemen?>");

function show_panel0(x) {
	document.getElementById("panel0").innerHTML = x;
	Tables('table', 1, 0);	
	var spryselect1 = new Spry.Widget.ValidationSelect("depart2");
	var spryselect2 = new Spry.Widget.ValidationSelect("proses");
	var spryselect3 = new Spry.Widget.ValidationSelect("kelompok");		
}
		
function show_panel1(x) {
	document.getElementById("panel1").innerHTML = x;
	document.getElementById('nama').focus();
	var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
	var spryselect1 = new Spry.Widget.ValidationSelect("depart3");
	var sprytextfield2 = new Spry.Widget.ValidationTextField("no");
}

function show_panel2(x) {
	document.getElementById("panel1").innerHTML = x;	
	Tables('table1', 1, 0);	
}

function carilah1(){
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	var departemen = document.getElementById('depart3').value;
	
	if (no == "" && nama == "") {
		alert ('No Pendaftaran atau Nama Calon tidak boleh kosong!');
		document.getElementById("nama").focus();	
		return false;
	}	
	sendRequestText("library/cari_calonsiswa.php", show_panel2, "submit=1&no="+no+"&nama="+nama+"&departemen="+departemen);
}


function change_departemen(tipe){	
	if (tipe == 1) {
		var departemen = document.getElementById('depart1').value;		
		sendRequestText("library/cari_siswa.php", show_panel1, "departemen="+departemen);	
	} else if (tipe == 2) {		
		var departemen = document.getElementById('depart2').value;			
		sendRequestText("library/pilih_calonsiswa.php", show_panel0, "departemen="+departemen);	
	}
	parent.content.location.href="blank_lapbayarcalon_all.php";
}

function change_proses(){
	var departemen = document.getElementById('depart2').value;
	var proses = document.getElementById('proses').value;
	sendRequestText("library/pilih_calonsiswa.php", show_panel0, "departemen="+departemen+"&proses="+proses);	
	parent.content.location.href="blank_lapbayarcalon_all.php";
}

function change_kelompok(){
	var departemen = document.getElementById('depart2').value;
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	sendRequestText("library/pilih_calonsiswa.php", show_panel0, "departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok);	
	parent.content.location.href="blank_lapbayarcalon_all.php";
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

function change_urut(urut,urutan,tipe) {
	 if (tipe == "daftarcalon") {		
		var departemen = document.getElementById('depart2').value;
		var proses = document.getElementById('proses').value;
		var kelompok = document.getElementById('kelompok').value;
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		
		sendRequestText("library/pilih_calonsiswa.php", show_panel0, "departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok+"&urut2="+urut+"&urutan2="+urutan);
		
	} else {		
		var departemen = document.getElementById('depart3').value;
		var no = document.getElementById('no').value;
		var nama = document.getElementById('nama').value;
		
		if (urutan =="ASC"){
			urutan="DESC"
		} else {
			urutan="ASC"
		}
		sendRequestText("library/cari_calonsiswa.php", show_panel2, "submit=1&no="+no+"&nama="+nama+"&departemen="+departemen+"&urut3="+urut+"&urutan3="+urutan);
	
	}
}		
</script>
</body>
</html>