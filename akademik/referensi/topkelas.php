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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];

OpenDb();



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kelas</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
function change_departemen() {
	var departemen = document.getElementById("departemen").value;
	parent.topkelas.location.href = "topkelas.php?departemen="+departemen;
	parent.bottomkelas.location.href = "blank_kelas.php";
}

function change() {
	var departemen = document.getElementById("departemen").value;
	var tingkat = document.getElementById("tingkat").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	parent.topkelas.location.href = "topkelas.php?departemen="+departemen+"&tingkat="+tingkat+"&tahunajaran="+tahunajaran;
	parent.bottomkelas.location.href = "blank_kelas.php";
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel') {
			show_kelas();
			panggil('tabel');
		} 
		return false;
    } 
    return true;
}

function panggil(elem){
	var lain = new Array('departemen','tahunajaran','tingkat');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

function show_kelas() {
	var departemen = document.getElementById("departemen").value;
	var tingkat = document.getElementById("tingkat").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	if (departemen==""){
	alert ('Departemen tidak boleh kosong !');
	return false;
	}
	if (tingkat==""){
	alert ('Tingkat tidak boleh kosong !');
	return false;
	}
	if (tahunajaran==""){
	alert ('Tahun ajaran tidak boleh kosong !');
	return false;
	}
	
	parent.bottomkelas.location.href="bottomkelas.php?departemen="+departemen+"&tingkat="+tingkat+"&tahunajaran="+tahunajaran;
}

</script>
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<table border="0" width="100%">
<!-- TABLE TITLE -->
<tr>
    <td rowspan="3" width="53%">
	<table width = "100%" border = "0">
    <tr>
    	<td align="center" rowspan="2"><strong>Departemen</strong><br /><br />
    	<select name="departemen" id="departemen" style="width:130px;" onChange="change_departemen()" onKeyPress="return focusNext('tahunajaran', event)" onfocus="panggil('departemen')">
       	<?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
				if ($departemen == "")
					$departemen = $value; ?>
        <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> ><?=$value ?></option>
        <?php } ?>
        </select>
   		</td>
    	<td align="center" rowspan="2"><strong>Tahun Ajaran</strong> <br /><br />
    	<select name="tahunajaran" id="tahunajaran" style="width:130px;"  onchange="change()" onKeyPress="return focusNext('tingkat', event)" onfocus="panggil('tahunajaran')">
   		<?php 	OpenDb();
			$sql_tahunajaran = "SELECT * FROM tahunajaran where departemen='$departemen' ORDER BY aktif DESC, tglmulai DESC";
			$result_tahunajaran = QueryDb($sql_tahunajaran);
			CloseDb();
			while ($row_tahunajaran = @mysqli_fetch_array($result_tahunajaran)) {
				if ($tahunajaran == "")
					$tahunajaran = $row_tahunajaran['replid'];
				$ada = "";
				if ($row_tahunajaran['aktif'])
					$ada = "(Aktif)";	
		?>
        <option value="<?=urlencode((string) $row_tahunajaran['replid'])?>" <?=IntIsSelected($row_tahunajaran['replid'], $tahunajaran)?> >
		<?=$row_tahunajaran['tahunajaran']." ".$ada?></option>
        <?php  } ?>
      	</select>
    	</td>
        <td align="center" rowspan="2"><strong>Tingkat</strong><br /><br />
   		<select name="tingkat" id="tingkat" style="width:130px;" onchange="change()" onKeyPress="return focusNext('tabel', event)" onfocus="panggil('tingkat')">
	    <?php OpenDb();
			$sql_tingkat = "SELECT * FROM tingkat where departemen='$departemen' AND aktif=1 ORDER BY urutan";
			$result_tingkat = QueryDb($sql_tingkat);
			CloseDb();
	
			while ($row_tingkat = @mysqli_fetch_array($result_tingkat)) {
				if ($tingkat == "")
					$tingkat = $row_tingkat['replid'];
		?>
	   	<option value="<?=urlencode((string) $row_tingkat['replid'])?>" <?=IntIsSelected($row_tingkat['replid'], $tingkat)?> >
	    <?=$row_tingkat['tingkat']?></option>
        <?php 	}   ?>
		</select>
    	</td>
	</tr>
	</table>
	</td>   
    <td rowspan="3" align="left" valign="middle" width="*">&nbsp;
    <a href="#" onclick="show_kelas()"><img src="../images/view.png" height="48" width="48" border="0" name="tabel" id="tabel" onmouseover="showhint('Klik untuk menampilkan kelas!', this, event, '120px')"/></a></td>
   	<td align="right" valign="top">
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Kelas</font><br />
    <a href="../referensi.php" target="content">
        <font size="1" color="#000000"><b>Referensi</b></font></a>&nbsp>&nbsp	 	
        <font size="1" color="#000000"><b>Kelas</b></font>
    </td>
</tr>
</table>
</body>
</html>