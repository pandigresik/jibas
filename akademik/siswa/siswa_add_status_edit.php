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
require_once('../include/theme.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

//$orig_status=$_REQUEST['orig_status']
$cek = 0;
if (isset($_POST['simpan'])) {
	OpenDb();
	$sql_cek = "SELECT * FROM jbsakad.statussiswa where status='".$_REQUEST['status']."' AND replid <> '".$_REQUEST['orig_status']."'";
	$hasil=QueryDb($sql_cek);
	
	$sql1 = "SELECT * FROM jbsakad.statussiswa WHERE urutan = '".$_REQUEST['urutan']."' AND replid <> '".$_REQUEST['orig_status']."'";
	$result1 = QueryDb($sql1);
	
	if (mysqli_num_rows($hasil) > 0){
		CloseDb();
		$ERROR_MSG = "Status {$_REQUEST['status']} sudah digunakan!";
	} else if (mysqli_num_rows($result1) > 0) {		
		CloseDb();
		$ERROR_MSG = "Urutan {$_REQUEST['urutan']} sudah digunakan!";
		$cek = 1;		
	} else {
		$sql = "UPDATE jbsakad.statussiswa SET status='".CQ($_POST['status'])."',urutan='".$_POST['urutan']."' WHERE replid = '".$_REQUEST['orig_status']."'";
		$result = QueryDb($sql);
	
		if ($result) { ?>
		<script language="javascript">
            opener.refresh('<?=$_REQUEST['status']?>');
            window.close();
        </script>
<?php 	}	
	}
}
CloseDb();

switch ($cek) {
	case 0 : $input_awal = "onload=\"document.getElementById('status').focus()\"";
		break;
	case 1 : $input_awal = "onload=\"document.getElementById('urutan').focus()\"";
		break;
}

$replid = $_REQUEST['replid'];
$status = $_GET['status'];
if (isset($_POST['status']))
	$status = $_POST['status'];
$urutan = $_GET['urutan'];
if (isset($_POST['urutan']))
	$urutan = $_POST['urutan'];

if (isset($_POST['orig_status']))
	$orig_status = $_POST['orig_status'];
if (isset($_POST['orig_urutan']))
	$orig_urutan = $_POST['orig_urutan'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="JavaScript" src="../script/tooltips.js"></script>
<title>JIBAS SIMAKA [Ubah Nama Status]</title>
<script language="javascript">
function cek() {
	var status = document.main.status.value;
	var urutan = document.main.urutan.value;
	if (status.length == 0) {
		alert('Anda belum memasukkan Nama Status');
		document.getElementById('status').focus();				
		return false;
	}
	if (status.length > 100) {
		alert('Nama Status tidak boleh lebih dari 100 karakter');
		document.getElementById('status').focus();				
		return false;
	}
	if (urutan.length == 0) {
		alert('Anda belum memasukkan Urutan Status');
		document.getElementById('urutan').focus();				
		return false;
	}
	if (isNaN(urutan)){
		alert("Urutan Status harus berupa bilangan");
		document.getElementById('urutan').focus();				
		return false;
	}
	
	return true;
	document.location.href="siswa_add_status_edit.php?status"+status;
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

function panggil(elem){
	var lain = new Array('status','urutan');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" <?=$input_awal?>>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Status Siswa :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->

    
    <form name="main" method="post" onSubmit="return cek();">    
    <table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
	<!-- TABLE CONTENT -->
    <tr>
        <td width="35%"><strong>Nama Status</strong></td>
        <td>
        <input type="text" name="status" id="status" maxlength="100" size="30" value="<?=$status?>" onFocus="panggil('status')" onKeyPress="return focusNext('urutan', event)">
        <input type="hidden" name="orig_status" value="<?=$replid?>">
        </td>
    </tr>
   <tr>
        <td><strong>Urutan</strong></td>
        <td>
        <input type="text" name="urutan" id="urutan" maxlength="2" size="2" value="<?=$urutan?>" onFocus="showhint('Urutan tampil Status!', this, event, '120px');panggil('urutan')" onKeyPress="return focusNext('Simpan', event)">
        <input type="hidden" name="orig_urutan" value="<?=$urutan?>">
        </td>
    </tr>   
    <tr>        
        <td align="center" colspan="2">
        <input class="but" type="submit" value="Simpan" name="simpan" id="Simpan" onFocus="panggil('Simpan')">
        <input class="but" type="button" value="Tutup" onClick="window.close();"> 
        </td>
    </tr>
    </table>
    </form>
<!-- END OF CONTENT //--->
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
<!-- Tamplikan error jika ada -->
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>
</body>
</html>