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
require_once('../sessionchecker.php');

if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];
if (isset($_REQUEST['tingkat']))
	$tingkat=$_REQUEST['tingkat'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran=$_REQUEST['tahunajaran'];
if (isset($_REQUEST['kelas']))
	$kelas=$_REQUEST['kelas'];
	
OpenDb();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Input Presensi Harian</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh(){
	document.location.href="input_presensi_header.php";
	//parent.contentblank.location.href = "presensi_blank.php";
}

function change_departemen(){
	var departemen = document.getElementById('departemen').value;
	
	document.location.href="input_presensi_header.php?departemen="+departemen;
	//parent.contentblank.location.href = "presensi_blank.php";
	parent.footer.location.href = "blank_presensi.php?tipe='harian'";
	//parent.menu.location.href="../blank_white.php";
	//parent.isi.location.href="../blank_white.php";
}

function change(){
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;	
	var semester = document.getElementById('semester').value;
		
	document.location.href="input_presensi_header.php?departemen="+departemen+"&tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&kelas="+kelas+"&semester="+semester;
	//parent.input_presensi_footer.location.href ="presensi_blank.php";
	//parent.contentblank.location.href = "presensi_blank.php";
	parent.footer.location.href = "blank_presensi.php?tipe='harian'";
	
}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
		
	document.location.href = "input_presensi_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat;
	//parent.input_presensi_footer.location.href ="presensi_blank.php";
	//parent.contentblank.location.href = "presensi_blank.php";
	parent.footer.location.href = "blank_presensi.php?tipe='harian'";
	
}

function tampil(){
	var tingkat = document.getElementById('tingkat').value;	
	var kelas = document.getElementById('kelas').value;
	var semester = document.getElementById('semester').value;
	
	if (semester==""){
		alert ('Semester tidak boleh kosong!');
		return false;
	} else if (tingkat==""){
		alert ('Tingkat tidak boleh kosong!');
		document.getElemetnById('tingkat').focus();
		return false;
	} else if (kelas==""){
		alert ('Kelas tidak boleh kosong!');
		document.getElemetnById('kelas').focus();
		return false;
	} else {	
		parent.footer.location.href = "input_presensi_footer.php?semester="+semester+"&kelas="+kelas;
	}
}	

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel') 
			tampil();
        return false;
    }
    return true;
}
</script>
</head>

<body leftmargin="0" topmargin="0" onload="document.getElementById('departemen').focus()">
<form method="post" id="inputForm" name="inputForm" >
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
    <td width="57%">
	<table width = "100%" border = "0" height="100%" cellpadding="1" cellspacing="1" >
    <tr>
        <td width="18%"><strong>Departemen</strong></td>
        <td width="32%">
            <select name="departemen" id="departemen" onChange="change_departemen()" style="width:150px;" onkeypress="return focusNext('tingkat', event)">
        <?php 
            $dep = getDepartemen(SI_USER_ACCESS());    
            foreach($dep as $value) {
                if ($departemen == "")
                    $departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
        <?php }	?>
            </select></td>
      	<td width="20%"><strong>Tahun Ajaran</strong></td>
        <td>
        <?php  OpenDb();
            $sql = "SELECT replid,tahunajaran FROM tahunajaran WHERE departemen='$departemen' AND aktif=1 ORDER BY replid DESC";
            $result = QueryDb($sql);
            CloseDb();
            $row = @mysqli_fetch_array($result);	
            $tahunajaran = $row['replid'];				
        ?>
        <input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$row['replid']?>">        <input type="text" name="tahun" id="tahun" readonly class="disabled" style="width:140px" value="<?=$row['tahunajaran']?>" /></td> 
   	</tr>
    <tr>
        <td><strong>Tingkat</strong></td>
        <td>
            <select name="tingkat" id="tingkat" onChange="change()" style="width:150px;" onkeypress="return focusNext('kelas', event)">
        <?php
            OpenDb();
			$sql="SELECT * FROM tingkat WHERE departemen='$departemen' AND aktif = 1 ORDER BY urutan";
            $result=QueryDb($sql);
            while ($row=@mysqli_fetch_array($result)){
                if ($tingkat=="")
                    $tingkat=$row['replid'];
        ?> 
            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $tingkat)?>><?=$row['tingkat']?></option>
        <?php 	} ?> 
            </select></td>
       	<td><strong>Semester </strong></td>
        <td>
         <?php OpenDb();
            $sql = "SELECT replid,semester FROM semester where departemen='$departemen' AND aktif = 1 ORDER BY replid DESC";
            $result = QueryDb($sql);
            CloseDb();
            $row = @mysqli_fetch_array($result);			
        ?>
            <input type="text" name="sem" id="sem" class="disabled" style="width:140px" readonly value="<?=$row['semester']?>" />
            <input type="hidden" name="semester" id="semester" value="<?=$row['replid']?>">      	</td>
    </tr>
    <tr>	
        <td><strong>Kelas</strong></td>
        <td><select name="kelas" id="kelas" onChange="change()" style="width:150px;" onkeypress="return focusNext('tabel', event)">
        <?php
            OpenDb();
            $sql="SELECT * FROM jbsakad.kelas WHERE idtahunajaran='$tahunajaran' AND idtingkat='$tingkat' AND aktif = 1 ORDER BY kelas";
            $result=QueryDb($sql);
            CloseDb();
            while ($row=@mysqli_fetch_array($result)){
            if ($kelas=="")
                $kelas=$row['replid'];
        ?> 
            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $kelas)?>><?=$row['kelas']?></option>
        <?php 	} ?> 
            </select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
    </tr>
    </table>
    </td>
    <td valign="middle" rowspan="3" width="5%" align="left">
    	<a href="#" onClick="tampil()"><img src="../images/ico/view.png" height="48" border="0" name="tabel" id="tabel"  onmouseover="showhint('Klik untuk menampilkan presensi harian!', this, event, '100px')"/>        </a></td>
    <td valign="top" align="right" width="38%">
     	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Presensi Harian</font><br />
    	<a href="../presensi.php" target="framecenter">
      	<font size="1" color="#000000"><b>Presensi</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Presensi Harian</b></font>		
  	</td>
</tr>	
</table>
</form>
</body>
</html>
<?php
CloseDb();
?>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect2 = new Spry.Widget.ValidationSelect("kelas");
	var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
	 
</script>