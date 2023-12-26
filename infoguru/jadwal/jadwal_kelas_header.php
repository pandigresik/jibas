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
require_once("../include/sessionchecker.php");

if (isset($_REQUEST['info_jadwal']))
	$info_jadwal=$_REQUEST['info_jadwal'];
if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];
if (isset($_REQUEST['tingkat']))
	$tingkat=$_REQUEST['tingkat'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran=$_REQUEST['tahunajaran'];
if (isset($_REQUEST['kelas']))
	$kelas=$_REQUEST['kelas'];
	
if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];


if ($op=="dw8dxn8w9ms8zs22"){
	OpenDb();
	$sql_update_aktif = "UPDATE jbsakad.infojadwal SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql_update_aktif);
	CloseDb();
} else if ($op=="fcjfootkpsmfkgjdmv"){
	OpenDb();
	$sql_update_terlihat = "UPDATE jbsakad.infojadwal SET terlihat = '".$_REQUEST['newvis']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql_update_terlihat);
	CloseDb();
} else if ($op=="xm8r389xemx23xb2378e23"){
	OpenDb();
	$sql_delete = "DELETE FROM jbsakad.infojadwal WHERE replid = '".$_REQUEST['info_jadwal']."'";
	$result=QueryDb($sql_delete);
	if ($result){
	?>
	<script type="text/javascript" language="javascript">
		document.location.href="jadwal_kelas_header.php";
		parent.footer.location.href="blank_jadwalkelas.php";
	</script>
	<?php
		
	}

}
OpenDb();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Jadwal Kelas</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh(){
	document.location.href="jadwal_kelas_header.php";
}

function change_departemen(){
	var info_jadwal = document.getElementById('info_jadwal').value;
	var departemen = document.getElementById('departemen').value;
	
	document.location.href="jadwal_kelas_header.php?info_jadwal="+info_jadwal+"&departemen="+departemen;
	parent.footer.location.href="blank_jadwalkelas.php";
}

function change_ajaran() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var info_jadwal = document.getElementById("info_jadwal").value;
	
	document.location.href = "jadwal_kelas_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&info_jadwal="+info_jadwal;
	parent.footer.location.href="blank_jadwalkelas.php";
}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var info_jadwal = document.getElementById("info_jadwal").value;
	var tingkat = document.getElementById("tingkat").value;
		
	document.location.href = "jadwal_kelas_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&info_jadwal="+info_jadwal+"&tingkat="+tingkat;
	parent.footer.location.href="blank_jadwalkelas.php";
}

function change(jadwal, ajaran, dep){	
	var info_jadwal = document.getElementById('info_jadwal').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;		
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	
	
	if (jadwal == 0) {
		document.location.href="jadwal_kelas_header.php?info_jadwal="+info_jadwal+"&tingkat="+tingkat+"&kelas="+kelas+"&departemen="+departemen+"&tahunajaran="+tahunajaran;	
	} else { 		
		document.location.href="jadwal_kelas_header.php?info_jadwal="+jadwal+"&departemen="+dep+"&tahunajaran="+ajaran;	
	}
	parent.footer.location.href="blank_jadwalkelas.php";
}

/*function change(){
	var info_jadwal = document.getElementById('info_jadwal').value;
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;	
	
	document.location.href="jadwal_kelas_header.php?info_jadwal="+info_jadwal+"&departemen="+departemen+"&tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&kelas="+kelas;	
	parent.footer.location.href="blank_jadwalkelas.php";
}*/

function tampil(){
	var info_jadwal = document.getElementById('info_jadwal').value;
	var tingkat = document.getElementById('tingkat').value;	
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var departemen = document.getElementById('departemen').value;
	
	if (info_jadwal==""){
		alert ('Info Jadwal tidak boleh kosong !');	
		document.getElementById('info_jadwal').focus();
		return false;		
	} else if (tingkat==""){
		alert ('Tingkat tidak boleh kosong !');
		document.getElementById('tingkat').focus();
		return false;
	} else if (tahunajaran==""){
		alert ('Tahun ajaran tidak boleh kosong !');
		document.getElementById('tahunajaran').focus();
		return false;
	} else if (kelas==""){
		alert ('Kelas tidak boleh kosong !');
		document.getElementById('kelas').focus();
		return false;
	} else {	
		parent.footer.location.href="jadwal_kelas_footer.php?info="+info_jadwal+"&kelas="+kelas+"&departemen="+departemen;		
	}
}	

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<form action="jadwal_kelas_footer.php" method="post" id="inputForm" name="inputForm" >
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
    <td width="48%">
	<table width = "100%" border = "0" cellpadding="0" cellspacing="0" >
    <tr>
        <td width="22%"><strong>Departemen</strong></td>
        <td width="29%">
        	<select name="departemen" id="departemen" onChange="change_departemen()"  style="width:120px" onkeypress="return focusNext('tahunajaran', event)">
        <?php 
            $dep = getDepartemen(SI_USER_ACCESS());    
            foreach($dep as $value) {
                if ($departemen == "")
                    $departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
        <?php }	?>
            </select></td>
        <td width="14%"><strong>Tingkat</strong></td>
        <td width="*">
            <select name="tingkat" id="tingkat" onChange="change_tingkat()"  style="width:100px" onkeypress="return focusNext('kelas', event)">
        <?php
            $sql_tingkat="SELECT * FROM jbsakad.tingkat WHERE departemen='$departemen' AND aktif = 1 ORDER BY urutan";
            $result_tingkat=QueryDb($sql_tingkat);
            while ($row_result_tingkat=@mysqli_fetch_array($result_tingkat)){
                if ($tingkat=="")
                    $tingkat=$row_result_tingkat['replid'];
        ?> 
            <option value="<?=$row_result_tingkat['replid']?>" <?=StringIsSelected($row_result_tingkat['replid'], $tingkat)?>><?=$row_result_tingkat['tingkat']?></option>
        <?php 	} ?> 
            </select></td>       
    </tr>	
    <tr>
        <td><strong>Tahun Ajaran</strong></td>
        <td><select name="tahunajaran" id="tahunajaran" onChange="change_ajaran()"  style="width:120px" onkeypress="return focusNext('tingkat', event)">
        <?php
            $sql_tahunajaran="SELECT * FROM jbsakad.tahunajaran WHERE departemen='$departemen' AND aktif=1 ORDER BY replid DESC";
            $result_tahunajaran=QueryDb($sql_tahunajaran);
            while ($row_result_tahunajaran=@mysqli_fetch_array($result_tahunajaran)){
                if ($tahunajaran=="")
                    $tahunajaran=$row_result_tahunajaran['replid'];
        ?> 
            <option value="<?=$row_result_tahunajaran['replid']?>" <?=StringIsSelected($row_result_tahunajaran['replid'], $tahunajaran)?>><?=$row_result_tahunajaran['tahunajaran']?></option>
        <?php }	?> 
            </select></td>
        <td><strong>Kelas</strong></td>
        <td><select name="kelas" id="kelas" onChange="change(0)"  style="width:100px" onkeypress="return focusNext('info_jadwal', event)">
        <?php
            $sql_kelas="SELECT * FROM jbsakad.kelas WHERE idtahunajaran='$tahunajaran' AND idtingkat='$tingkat' ORDER BY kelas";
            $result_kelas=QueryDb($sql_kelas);
            while ($row_result_kelas=@mysqli_fetch_array($result_kelas)){
            if ($kelas=="")
                $kelas=$row_result_kelas['replid'];
        ?> 
            <option value="<?=$row_result_kelas['replid']?>" <?=StringIsSelected($row_result_kelas['replid'], $kelas)?>><?=$row_result_kelas['kelas']?></option>
        <?php 	} ?> 
            </select></td>        
    </tr>
    <tr>
        <td><strong>Info Jadwal</strong></td>
        <td colspan="3"><select name="info_jadwal" id="info_jadwal" onChange="change(0)" style="width:285px">
        <?php 	OpenDb();
            $sql_info_jadwal="SELECT * FROM jbsakad.infojadwal WHERE idtahunajaran = '$tahunajaran' ORDER BY aktif DESC";
            $result_info_jadwal=QueryDb($sql_info_jadwal);
            while ($row_info_jadwal=@mysqli_fetch_array($result_info_jadwal)){
                if ($info_jadwal=="")
                    $info_jadwal=$row_info_jadwal['replid'];
                if ($row_info_jadwal['aktif']) 
                    $ada = '(A)';
                else 
                    $ada = '';			 
        ?> 
            <option value="<?=$row_info_jadwal['replid']?>" <?=StringIsSelected($row_info_jadwal['replid'],$info_jadwal)?>>
            <?=$row_info_jadwal['deskripsi'].' '.$ada?></option>
        <?php  } ?> 
            </select>
              	            
        </td>
    </tr>   
    </table>
    </td>
    <td valign="middle" rowspan="2" width="*" >
            <a href="#" onClick="tampil()"><img src="../images/ico/view.png" height="48" width="48" border="0" name="tabel" id="tabel"  onmouseover="showhint('Klik untuk menampilkan jadwal kelas!', this, event, '80px')"/></a>        </td>
<td valign="top" align="right" width="50%">
           <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Jadwal Berdasarkan Kelas</font><br />
           <a href="../jadwal.php" target="framecenter">
            <font size="1" color="#000000"><b>Jadwal</b></font></a>&nbsp>&nbsp 
            <font size="1" color="#000000"><b>Jadwal Berdasarkan Kelas</b></font><a> 	
        </td>
        </table>
</form>
</body>
</html>
<script language="javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
var spryselect2 = new Spry.Widget.ValidationSelect("tahunajaran");
var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
var spryselect5 = new Spry.Widget.ValidationSelect("info_jadwal");
</script>