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
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];
	
$kode = "";
if (isset($_REQUEST['kode']))
	$kode = CQ($_REQUEST['kode']);
$materi = "";
if (isset($_REQUEST['materi']))
	$materi = CQ($_REQUEST['materi']);	
$deskripsi = "";
if (isset($_REQUEST['deskripsi']))
	$deskripsi = CQ($_REQUEST['deskripsi']);	

$ERROR_MSG = "";

if (isset($_REQUEST['Simpan'])) {
	OpenDb();
	$sql = "SELECT * FROM rpp WHERE koderpp = '".$kode."'";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
		CloseDb();
		$ERROR_MSG = "Kode pembelajaran $kode sudah digunakan!";
	} else {
		$sql = "INSERT INTO rpp SET idtingkat = '$tingkat', idsemester = '$semester', idpelajaran = '$pelajaran', koderpp = '$kode', rpp = '$materi', deskripsi = '".$deskripsi."'";
		$result = QueryDb($sql);
		if ($result) { 
			$sql1 = "SELECT replid FROM rpp ORDER BY replid DESC LIMIT 1";
			$result1 = QueryDb($sql1);
			$row1 = mysqli_fetch_row($result1);
			CloseDb();
		?>
			<script language="javascript">
				opener.refresh(<?=$row1[0]?>);				
				//opener.location.href = "rpp_footer.php?semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&urut=<?=$urut?>&urutan=<?=$urutan?>";
				window.close();
			</script> 
<?php 	}
	}
}

OpenDb();
$sql = "SELECT semester, tingkat, nama, s.departemen FROM semester s,  tingkat t, pelajaran p WHERE s.replid = '$semester' AND t.replid = '$tingkat' AND p.replid = '$pelajaran' AND s.departemen = p.departemen ";

$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$departemen = $row["departemen"];
$namasemester = $row["semester"];
$namatingkat = $row["tingkat"];
$namapel = $row["nama"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Tambah Rencana Program Pembelajaran]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script src="../script/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script language="javascript">
//textarea
tinyMCE.init({
	mode : "textareas",
	theme : "simple",
});

function validate() {
	return 	validateEmptyText('kode', 'Kode pembelajaran') && 
			validateEmptyText('materi', 'Materi pembelajaran') && 
		   	validateMaxText('materi', 255, 'Materi pembelajaran');
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
	var lain = new Array('kode','materi','deskripsi');
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('kode').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Tambah Rencana Program Pembelajaran :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<form name="main" method="post" onSubmit="return validate()">
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="12%"><strong>Departemen</strong></td>
	<td width="12%">
    	<input type="text" name="departemen1" id="departemen1" size="10" readonly value="<?=$departemen ?>" class="disabled" />
    	<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />    
    <td width="12%"><strong>Tingkat</strong></td>
    <td>
        <input type="text" name="tingkat1" id="tingkat1" size="10" readonly value="<?=$namatingkat ?>" class="disabled" />
        <input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>"/>
        
        	</td>
    
    <td><strong>&nbsp;Semester&nbsp;</strong></td>
    <td>
    	<input type="text" name="semester1" id="semester1" size="21" readonly value="<?=$namasemester ?>" class="disabled" />
        <input type="hidden" name="semester" id="semester" value="<?=$semester?>"/>
    </td>
</tr>
<tr>
	<td><strong>Kode RPP</strong></td>
	<td>
    	<input type="text" name="kode" id="kode" size="10" maxlength="20" value="<?=$kode?>" onFocus="showhint('Kode pembelajaran tidak boleh lebih dari 20 karakter!', this, event, '120px');panggil('kode')"  onKeyPress="return focusNext('materi', event)"/>
    <td><strong>Pelajaran</strong></td>
    <td colspan="3">
         <input type="text" name="pelajaran1" id="pelajaran1" size="48" readonly value="<?=$namapel ?>" class="disabled" />
        <input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran?>"/>    </td>
</tr>
<tr>
	<td valign="top"><strong>Materi</strong></td>
	<td colspan="5"><input type="text" name="materi" id="materi" size="75" maxlength="225" value="<?=$materi?>" onFocus="panggil('materi')"  onKeyPress="return focusNext('deskripsi', event)"/>    </td>
</tr>
<tr>
	<td colspan = "6" height="200" valign="top">
	<fieldset><legend><b>Deskripsi Program Pembelajaran</b></legend>
    <br />
    <textarea name="deskripsi" id="deskripsi" rows="20" onFocus="panggil('deskripsi')" style="width:100%"><?=$deskripsi?></textarea>
    </fieldset></tr>

<tr>
	<td colspan="6" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but" onFocus="panggil('Simpan')" />&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />    </td>
</tr>
<!-- END OF TABLE CONTENT -->
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