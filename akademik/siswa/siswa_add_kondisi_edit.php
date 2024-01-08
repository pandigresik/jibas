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

$cek = 0;
$ERROR_MSG="";
if (isset($_POST['simpan'])) {
	OpenDb();
	$sql_cek="SELECT * FROM jbsakad.kondisisiswa where kondisi='".CQ($_REQUEST['kondisi'])."' AND replid <>'".$_REQUEST['orig_kondisi']."'";
	$hasil = QueryDb($sql_cek);
	
	$sql1 = "SELECT * FROM jbsakad.kondisisiswa WHERE urutan = '$urutan' AND replid <> '".$_REQUEST['orig_kondisi']."'";
	$result1 = QueryDb($sql1);
	
	if (mysqli_num_rows($hasil) > 0){
		CloseDb();
		$ERROR_MSG = "Kondisi {$_REQUEST['kondisi']} sudah digunakan!";	
    } else if (mysqli_num_rows($result1) > 0) {		
		CloseDb();
		$ERROR_MSG = "Urutan {$_REQUEST['urutan']} sudah digunakan!";
		$cek = 1;		
	} else {
		$sql = "UPDATE jbsakad.kondisisiswa SET kondisi='".CQ($_POST['kondisi'])."',urutan='".$_POST['urutan']."' WHERE replid='".$_REQUEST['orig_kondisi']."'";
		$result = QueryDb($sql);
	
	if ($result) { ?>
		<script language="javascript">
            opener.refresh('<?=$_REQUEST['kondisi']?>');
            window.close();
        </script>
<?php 		}	
	}
}
CloseDb();

switch ($cek) {
	case 0 : $input_awal = "onload=\"document.getElementById('kondisi').focus()\"";
		break;
	case 1 : $input_awal = "onload=\"document.getElementById('urutan').focus()\"";
		break;
}

$replid = $_REQUEST['replid'];
$kondisi = $_GET['kondisi'];
if (isset($_POST['kondisi']))
	$kondisi = $_POST['kondisi'];
$urutan = $_GET['urutan'];
if (isset($_POST['urutan']))
	$urutan = $_POST['urutan'];
	
if (isset($_POST['orig_kondisi']))
	$orig_kondisi = $_POST['orig_kondisi'];
if (isset($_POST['orig_urutan']))
	$orig_urutan = $_POST['orig_urutan'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<title>JIBAS SIMAKA [Ubah Nama Kondisi]</title>
<script language="javascript">
function cek() {
	var kondisi = document.main.kondisi.value;
	var urutan = document.main.urutan.value;
	
	if (kondisi.length == 0) {
		alert('Anda belum memasukkan Nama kondisi');
		document.getElementById('kondisi').focus();
		return false;
	}
	if (kondisi.length > 100) {
		alert('Nama Kondisi tidak boleh lebih dari 100 karakter');
		document.getElementById('kondisi').focus();
		return false;
	}
	if (urutan.length == 0) {
		alert('Anda belum memasukkan Urutan Kondisi' );
		document.getElementById('urutan').focus();
		return false;
	}
	if (isNaN(urutan)){
		alert("Urutan Kondisi harus berupa bilangan");
		document.getElementById('urutan').focus();				
		return false;
	}
	return true;
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
	var lain = new Array('kondisi','urutan');
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
    .: Ubah Kondisi Siswa :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //---><?php
?>
    <form name="main" method="post" onSubmit="return cek();">    
    <table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
	<!-- TABLE CONTENT -->
    <tr>
        <td width="35%"><strong>Nama Kondisi</strong></td>
        <td>
        <input type="text" name="kondisi" id="kondisi" maxlength="100" size="30" value="<?=$kondisi?>" onFocus="panggil('kondisi')" onKeyPress="return focusNext('urutan', event)">
        <input type="hidden" name="orig_kondisi" value="<?=$replid?>">
        </td>
    </tr>
   <tr>
        <td><strong>Urutan</strong></td>
        <td>
        <input type="text" name="urutan" id="urutan" maxlength="2" size="2" value="<?=$urutan?>" onFocus="showhint('Urutan tampil Kondisi!', this, event, '120px');panggil('urutan')" onKeyPress="return focusNext('Simpan', event)">
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