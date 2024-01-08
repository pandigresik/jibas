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

$replid = $_REQUEST['replid'];

$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) {
	OpenDb(); 
	$sql = "SELECT * FROM jbsakad.semester WHERE semester = '".CQ($_REQUEST['semester'])."' AND departemen ='".$_REQUEST['departemen']."' AND replid <> '$replid' ";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
		CloseDb();
		$ERROR_MSG = "Semester {$_REQUEST['semester']} sudah digunakan!";
	} else {
		$sql = "UPDATE semester SET semester='".CQ($_REQUEST['semester'])."',keterangan='".CQ($_REQUEST['keterangan'])."' WHERE replid='$replid'";
		$result = QueryDb($sql);
		CloseDb();
	
		if ($result) { 
		?>
			<script language="javascript">
				opener.refresh();
				window.close();
			</script> 
<?php 	}
		exit();
	}
};

OpenDb();

$sql = "SELECT semester,departemen,keterangan,aktif FROM semester WHERE replid='$replid' ORDER BY semester";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$semester = $row[0];
$departemen = $row[1];
$keterangan = $row[2];
CloseDb();

if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
if (isset($_REQUEST['keterangan'])) 
	$keterangan = $_REQUEST['keterangan'];
	
//echo $sql;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Ubah Semester]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function validate() {
	return validateEmptyText('semester', 'Nama Semester') && 
		   validateMaxText('keterangan', 255, 'Keterangan');
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

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"  style="background-color:#dcdfc4"  onload="document.getElementById('semester').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Semester :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->

<form name="main" action="semester_edit.php" onSubmit="return validate()">
<input type="hidden" name="replid" id="replid" value="<?=$replid ?>" />
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="120"><strong>Departemen</strong></td>
	<td><input type="text" size="5" value="<?=$departemen ?>" readonly class="disabled"/></strong><input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
    </td>
</tr>
<tr>
    <td><strong>Semester</strong></td>
    <td>
    	<input type="text" name="semester" id="semester" size="30" maxlength="50" value="<?=$semester ?>" onFocus="showhint('Nama semester tidak boleh lebih dari 50 karakter!', this, event, '120px')"  onKeyPress="return focusNext('keterangan', event)" />
    </td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td>
    	<textarea name="keterangan" id="keterangan" rows="3" cols="45"  onKeyPress="return focusNext('Simpan', event)"><?=$keterangan ?></textarea>
    </td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but" />&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />
    </td>
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
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("semester");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
</script>