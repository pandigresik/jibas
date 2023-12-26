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
$tahun = date('Y');
if (isset($_REQUEST['tahun']))
	$tahun = $_REQUEST['tahun'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daftar Mutasi Siswa</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript">
function change_dep() {
	var departemen = document.getElementById("departemen").value;
	
	parent.daftar_mutasi_siswa_header.location.href = "daftar_mutasi_siswa_header.php?departemen="+departemen;
	parent.daftar_mutasi_siswa_footer.location.href = "mutasi_blank.php";
}

function change_tahun() {
	var departemen = document.getElementById("departemen").value;	
	var tahun = document.getElementById("tahun").value;
	
	parent.daftar_mutasi_siswa_header.location.href = "daftar_mutasi_siswa_header.php?departemen="+departemen+"&tahun="+tahun;
	parent.daftar_mutasi_siswa_footer.location.href = "mutasi_blank.php";
}

function tampil() {
	var departemen = document.getElementById("departemen").value;	
	var tahun = document.getElementById("tahun").value;
	
	if (tahun==""){
		alert ('Belum ada siswa yang dimutasi pada departemen ini!');
		document.getElementById("departemen").focus();
		return false;
	}
	parent.daftar_mutasi_siswa_footer.location.href = "daftar_mutasi_siswa_footer.php?departemen="+departemen+"&tahun="+tahun;
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
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<table border="0" width="100%" cellpadding="0" cellspacing="0"  >
<!-- TABLE TITLE -->
<tr>
	<td rowspan="2" width="36%">
	<table width = "100%" border = "0">
    <tr>
    	<td align="left" width = "30%"><strong>Departemen</strong>
    	<td width = "*">
		<select name="departemen" id="departemen"  onchange="change_dep()" style="width:225px" onKeyPress="return focusNext('tahun', event)">
        <?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
    		if ($departemen == "")
        		$departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
              <?=$value ?> 
              </option>
	  	<?php } ?>
        </select>
   		</td>
  	</tr>
	<tr>
    	<td align="left"><strong>Tahun Mutasi</strong>
		<td>
        <select name="tahun" id="tahun" onchange="change_tahun()"  style="width:225px" onKeyPress="return focusNext('tabel', event)">
    <?php  OpenDb();
	  	$sql_tahun="SELECT YEAR(tglmutasi) AS tahun FROM jbsakad.mutasisiswa WHERE departemen='$departemen' GROUP BY tahun ORDER BY tahun DESC";
	  	$result_tahun = QueryDb($sql_tahun);
		while ($row_tahun=mysqli_fetch_array($result_tahun)){
	?>
       		<option value="<?=$row_tahun['tahun']?>" <?=IntIsSelected($row_tahun['tahun'],$tahun)?>><?=$row_tahun['tahun']?></option>
                          
	<?php 	}  CloseDb();
	?>
    	</select>        
        </td>  
	</tr>
	</table>
    </td>
  	<td valign="middle"><a href="#" onClick="tampil()" ><img src="../images/view.png" height="48" border="0" name="tabel" id="tabel" onMouseOver="showhint('Klik untuk menampilkan daftar siswa yang dimutasi!', this, event, '200px')"/></a></td>
  	<td colspan="2" align="right" valign="top">
	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Daftar Mutasi Siswa</font><br />
        <a href="../mutasi.php" target="content"> 
        <font size="1" color="#000000"><b>Mutasi</b></font></a>&nbsp>&nbsp 
        <font size="1" color="#000000"><b>Daftar Mutasi Siswa</b></font>
   	</td>
</tr>
</table>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect2 = new Spry.Widget.ValidationSelect("tahun");
</script>