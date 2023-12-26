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

if (isset($_REQUEST['idtahunajaran'])) 
	$idtahunajaran = $_REQUEST['idtahunajaran'];
	
if (isset($_REQUEST['idtingkat']))
	$idtingkat = $_REQUEST['idtingkat'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pindah Kelas</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function change_departemen() {
	var departemen = document.getElementById("departemen").value;
	
	parent.siswa_pindah_header.location.href = "siswa_pindah_header.php?departemen="+departemen;
	parent.siswa_pindah_footer.location.href = "blank_pindah.php";
}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	
	parent.siswa_pindah_header.location.href = "siswa_pindah_header.php?departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran;
	parent.siswa_pindah_footer.location.href = "blank_pindah.php";
}

function cari_siswa() {
	var departemen = document.getElementById("departemen").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;	
	var kelas = document.getElementById("kelas").value;	
	
	if (idtahunajaran==""){
		alert ('Tahun Ajaran tidak boleh kosong!');
		document.getElementById("departemen").focus();
		return false;
	}
	if (idtingkat==""){
		alert ('Tingkat tidak boleh kosong');
		document.getElementById("tingkat").focus();
		return false;
	}	
	if (kelas == 0) {
		alert ('Belum ada Kelas yang aktif pada Tingkat ini!');	
		document.getElementById("departemen").focus();
		return false;
	}
	
	parent.siswa_pindah_footer.location.href = "siswa_pindah_footer.php?departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran;	
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel') {
			cari_siswa();
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
		<select name="departemen" id="departemen" onchange="change_departemen()" style="width:200px;" onKeyPress="return focusNext('idtingkat', event)" >
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
      <!--<div id="tahunajaranInfo">-->
      	<?php 
			OpenDb();
			$sql_tahunajaran = "SELECT replid,tahunajaran FROM tahunajaran where departemen='$departemen' AND aktif = 1 ";
			$result_tahunajaran = QueryDb($sql_tahunajaran);
			CloseDb();
			$row_tahunajaran = mysqli_fetch_array($result_tahunajaran);
			$idtahunajaran = $row_tahunajaran['replid'];
			
			//if($row_tahunajaran = mysqli_fetch_array($result_tahunajaran)) {
			
		?>
  			<input type="text" name="tahunajaran" id="tahunajaran" size="30" readonly="readonly" value="<?=$row_tahunajaran['tahunajaran']?>" class="disabled">
  			<input type="hidden" name="idtahunajaran" id="idtahunajaran" value="<?=$row_tahunajaran['replid']?>">
      	<!--</div>-->      </td>
	<tr>
    	<td align="left" width = "13%"><strong>Tingkat</strong>
      	<td>
        <!--<div id="tingkatInfo">-->
        <select name="idtingkat" id="idtingkat" onchange="change_tingkat()" style="width:200px;" onKeyPress="return focusNext('tabel', event)" >
		<?php 	OpenDb(); 
			$sql_tingkat = "SELECT replid,tingkat FROM tingkat where departemen='$departemen' AND aktif = 1 ORDER BY urutan";
			$result_tingkat = QueryDb($sql_tingkat);
			
			while ($row_tingkat = mysqli_fetch_array($result_tingkat)) {
			if ($idtingkat == "") 
				$idtingkat = $row_tingkat['replid'];	
		?>
  		<option value="<?=$row_tingkat['replid']?>" <?=IntIsSelected($row_tingkat['replid'], $idtingkat)?>>
		<?=$row_tingkat['tingkat']?></option>
  		<?php
  			} //while
			CloseDb();
		?>
		</select>
	<?php 	$total = 0;
		if ($idtingkat <> "" && $idtahunajaran <> ""){
			OpenDb();
        	$sql_kelas="SELECT k.replid,k.kelas FROM jbsakad.kelas k WHERE k.idtingkat='$idtingkat' AND k.idtahunajaran='$idtahunajaran' AND k.aktif=1 ORDER BY k.kelas";
			
        	$result_kelas=QueryDb($sql_kelas);
			$total = mysqli_num_rows($result_kelas);
		}
	?>
        <input type="hidden" name="kelas" id="kelas" value="<?=$total?>" />        
        </td>  
  	</tr>
    </table>   	</td>
  	<td valign="middle" width="0"><a href="#" onclick="cari_siswa()" ><img src="../images/view.png" height="48" border="0" name="tabel" id="tabel" onmouseover="showhint('Klik untuk menampilkan daftar siswa yang akan pindah kelas!', this, event, '200px')"/></a></td>
  	<td colspan = "2" align="right" valign="top"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pindah Kelas</font><br />
    <a href="../siswa.php" target="content">
      <font size="1" color="#000000"><b>Kesiswaan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Pindah Kelas</b></font>
    </td>     
</tr>
</table>
	
</body>
</html>
<script language="javascript">
	var spryselect11 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect12 = new Spry.Widget.ValidationSelect("idtingkat");
</script>