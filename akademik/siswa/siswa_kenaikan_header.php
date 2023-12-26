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

OpenDb();
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['tahunajaran'])) 
	$tahunajaran = $_REQUEST['tahunajaran'];
	
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kenaikan Kelas</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function change_departemen() {
	var departemen = document.getElementById("departemen").value;
	
	parent.siswa_kenaikan_header.location.href = "siswa_kenaikan_header.php?departemen="+departemen;
	parent.siswa_kenaikan_footer.location.href = "blank_kenaikan_all.php";
}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var tingkat = document.getElementById("tingkat").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	
	parent.siswa_kenaikan_header.location.href = "siswa_kenaikan_header.php?departemen="+departemen+"&tingkat="+tingkat+"&tahunajaran="+tahunajaran;
	parent.siswa_kenaikan_footer.location.href = "blank_kenaikan_all.php";
}

function tampil() {
	var departemen = document.getElementById("departemen").value;
	var tingkat = document.getElementById("tingkat").value;
	var tahunajaran = document.getElementById("tahunajaran").value;	
	var kelas = document.getElementById("kelas").value;	
	
	if (tahunajaran==""){
		alert ('Tahun Ajaran tidak boleh kosong!');
		document.getElementById("tahunajaran").focus();
		return false;
	}
	if (tingkat==""){
		alert ('Tingkat tidak boleh kosong!');
		document.getElementById("tingkat").focus();
		return false;
	}	
	if (kelas == 0) {
		alert ('Belum ada Kelas yang aktif pada Tingkat ini!');	
		document.getElementById("departemen").focus();
		return false;
	}
	
	parent.siswa_kenaikan_footer.location.href = "siswa_kenaikan_footer.php?departemen="+departemen+"&tingkat="+tingkat+"&tahunajaran="+tahunajaran;	
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel') {
			tampil();
		} 
		return false;
    } 
    return true;
}

</script>
</head>
	
<body leftmargin="0" topmargin="0" onload="document.getElementById('departemen').focus()">
<table border="0" width="100%" cellpadding="0" cellspacing="0"  >
<!-- TABLE TITLE -->
<tr>
	<td rowspan="2" width="36%">
	<table width = "100%" border = "0">
    <tr>
      	<td width = "30%"><strong>Departemen</strong>
      	<td width = "*">
		<select name="departemen" id="departemen" onchange="change_departemen()" style="width:200px;" onKeyPress="return focusNext('tahunajaran', event)" >
        <?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
			if ($departemen == "")
				$departemen = $value; ?>
          	<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> ><?=$value ?> 
            </option>
      	<?php } ?>
      	</select>    	</td>
    </tr>
    <tr>
		<td><strong>Tahun Ajaran</strong></td>
        <td>
    	<select name="tahunajaran" id="tahunajaran" style="width:200px;"  onchange="change_tingkat()" onKeyPress="return focusNext('tingkat', event)">
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
   	</tr>
	<tr>
    	<td><strong>Tingkat</strong>
      	<td>
        <select name="tingkat" id="tingkat" onchange="change_tingkat()" style="width:200px;" onKeyPress="return focusNext('tabel', event)" >
		<?php 	OpenDb(); 
			$sql_tingkat = "SELECT replid,tingkat FROM tingkat where departemen='$departemen' AND aktif = 1 ORDER BY urutan";
			$result_tingkat = QueryDb($sql_tingkat);
			
			while ($row_tingkat = mysqli_fetch_array($result_tingkat)) {
			if ($tingkat == "") 
				$tingkat = $row_tingkat['replid'];	
		?>
  		<option value="<?=$row_tingkat['replid']?>" <?=IntIsSelected($row_tingkat['replid'], $tingkat)?>>
		<?=$row_tingkat['tingkat']?></option>
  		<?php
  			} //while
			CloseDb();
		?>
		</select>
    <?php 	$total = 0;
		if ($tingkat <> "" && $tahunajaran <> ""){
			OpenDb();
        	$sql_kelas="SELECT k.replid,k.kelas FROM jbsakad.kelas k WHERE k.idtingkat='$tingkat' AND k.idtahunajaran='$tahunajaran' AND k.aktif=1 ORDER BY k.kelas";
			
        	$result_kelas=QueryDb($sql_kelas);
			$total = mysqli_num_rows($result_kelas);
		}
	?>
        <input type="hidden" name="kelas" id="kelas" value="<?=$total?>" />
		</td>  
  	</tr>
    </table>   	</td>
  	<td valign="middle"><a href="#" onclick="tampil()" ><img src="../images/view.png" height="48" border="0" name="tabel" id="tabel" onmouseover="showhint('Klik untuk menampilkan daftar siswa yang akan naik kelas!', this, event, '200px')"/></a></td>
  	<td colspan = "2" align="right" valign="top"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Kenaikan Kelas</font><br />
    <a href="../kelulusan.php" target="content">
      <font size="1" color="#000000"><b>Kenaikan & Kelulusan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Kenaikan Kelas</b></font>
    </td>     
</tr>
</table>
	
</body>
</html>
<script language="javascript">
	var spryselect11 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect12 = new Spry.Widget.ValidationSelect("tahunajaran");
	var spryselect12 = new Spry.Widget.ValidationSelect("tingkat");
</script>