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
	
OpenDb();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Penempatan Calon Siswa</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language="javascript">

function change_dep() {		
	var departemen = document.getElementById("departemen").value;	
	parent.header.location.href = "penempatan_header.php?departemen="+departemen;	
	parent.footer.location.href = "blank_penempatan.php";	

}

function show_calon() {		
	var departemen = document.getElementById("departemen").value;
	var proses = document.getElementById("proses").value;
	var kelompok = document.getElementById("kelompok").value;
		
	if (proses.length == 0) {
		alert ('Tidak ada Proses Penerimaan yang aktif!');	
		document.getElementById("departemen").focus();
		return false;
	}
	if (kelompok == 0) {
		alert ('Belum ada Kelompok Calon Siswa pada Proses Penerimaan ini!');	
		document.getElementById("departemen").focus();
		return false;
	}	
	
	parent.footer.location.href="penempatan_footer.php?departemen="+departemen+"&proses="+proses;
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
      	if (elemName == 'tabel')
			show_calon();
		return false;
    }
    return true;
}

</script>
</head>
	
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
	<td rowspan="3" width="40%">
    <table width = "95%" border = "0">    
    <tr>
      	<td width = "40%"><strong>Departemen</strong>
      	<td width="60%">
       <select name="departemen" id="departemen" onChange="change_dep()" style="width:200px;" onKeyPress="return focusNext('tabel', event)">
	<?php $dep = getDepartemen(SI_USER_ACCESS());    
		foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
		<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
	<?php } ?>
		</select>
        
    	</td>
    </tr>
	<tr>
    	<td><strong>Proses Penerimaan</strong>
      	<td> 
        <?php 	
			OpenDb();
			$sql = "SELECT replid,proses FROM prosespenerimaansiswa WHERE departemen='$departemen' and aktif=1";  			$result = QueryDb($sql);
        	$row = @mysqli_fetch_array($result);
			$jumkel = 0;
			if ($row['replid'] <> "") {
				$sql1 = "SELECT * FROM kelompokcalonsiswa WHERE idproses='".$row['replid']."'";
				$result1 = QueryDb($sql1);
				$jumkel = mysqli_num_rows($result1);
			}
			  	
        ?>
     	<input type="text" size="30" class="disabled" value="<?=$row['proses']?>" readonly/>
     	<input type="hidden" id="proses" name="proses" value="<?=$row['replid']?>" />       
    	<input type="hidden" id="kelompok" name="kelompok" value="<?=$jumkel?>" />       
		</td>
    </tr>
	</table>
	</td>
	<td width="*" rowspan="2" valign="middle"><a href="#" onclick="show_calon()" >
    	<img src="../images/view.png" height="48" width="48" border="0" name="tabel" id="tabel" onmouseover="showhint('Klik untuk menampilkan daftar calon siswa !', this, event, '140px')"/></a></td>
    <td width="37%" align="right" valign="top"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Penempatan Calon Siswa</font>
    <br /><a href="../siswa_baru.php" target="content"><font size="1" color="#000000"><b>Penerimaan Siswa Baru</b></font></a>&nbsp>&nbsp
    <font size="1" color="#000000"><b>Penempatan Calon Siswa</b></font></td>  
</tr>
<tr>
	<td align="right" valign="top">
    </td>
</tr>

</table>
</body>
</html>
<script language="javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
</script>