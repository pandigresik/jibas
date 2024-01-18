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

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['replid']))
	$replid = $_REQUEST['replid'];
if (isset($_REQUEST['id']))
	$id = $_REQUEST['id'];

if (isset($_REQUEST['simpan'])) {
	OpenDb();
	$sql_cek = "SELECT * FROM jbsakad.kelompokcalonsiswa WHERE kelompok = '".CQ($_REQUEST['kelompok'])."' AND idproses='$id' AND replid <> '$replid'";
	$result_cek = QueryDb($sql_cek);
	
	if (mysqli_num_rows($result_cek) > 0) {
		$ERROR_MSG = "Nama ".$_REQUEST['kelompok']." sudah digunakan!";
		CloseDb();
	} else {
		$sql = "UPDATE jbsakad.kelompokcalonsiswa SET kelompok='".CQ($_REQUEST['kelompok'])."', idproses='$id', kapasitas='".$_REQUEST['kapasitas']."', keterangan='".CQ($_REQUEST['keterangan'])."' WHERE replid='$replid'";
		$result = QueryDb($sql);
		
		if ($result) { ?>
			<script language="javascript">
				opener.refresh('<?=$replid?>');
				window.close();
			</script> 
<?php 	}
		
	}
}

OpenDb();
$sql_tampil = "SELECT p.departemen, p.proses, k.kelompok, k.kapasitas, k.keterangan, k.idproses FROM kelompokcalonsiswa k, prosespenerimaansiswa p WHERE k.replid='$replid' AND k.idproses = p.replid";
$result_tampil = QueryDb($sql_tampil);
$row_tampil = mysqli_fetch_array($result_tampil);
$departemen = $row_tampil['departemen'];
$proses = $row_tampil['proses'];
$kelompok = $row_tampil['kelompok'];
$kapasitas = $row_tampil['kapasitas'];
$keterangan = $row_tampil['keterangan'];
$id = $row_tampil['idproses'];
if (isset($_REQUEST['kelompok']))	
	$kelompok=$_REQUEST['kelompok'];
if (isset($_REQUEST['kapasitas']))	
	$kapasitas=$_REQUEST['kapasitas'];
if (isset($_REQUEST['keterangan']))	
	$keterangan=$_REQUEST['keterangan'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Ubah Kelompok Calon Siswa]</title>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function validate() {
	return validateEmptyText('kelompok', 'Nama Kelompok') && 
		   validateEmptyText('kapasitas', 'Kapasitas') && 	
		   validateNumber('kapasitas', 'Kapasitas') &&
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

function panggil(elem){
	var lain = new Array('kelompok','kapasitas','keterangan');
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

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('kelompok').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Kelompok Calon Siswa :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->

<form name="main" onSubmit="return validate()">
<input type="hidden" name="replid" id="replid" value="<?=$replid ?>" />
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="120"><strong>Departemen</strong></td>
	<td><input type="text" name="departemen" id="departemen" size="10" value="<?=$departemen ?>" readonly class="disabled"/><input type="hidden" name="departemen" id="departemen" value ="<?=$departemen ?>" />
    
    </td>
</tr>
<tr>
	<td><strong>Penerimaan</strong></td>
	<td><input type="text" name="proses" id="proses" size="30" value="<?=$proses ?>" readonly class="disabled"/>
    	<input type="hidden" name="id" id="id" value="<?=$id ?>" />
    </td>
</tr>
<tr>
    <td><strong>Kelompok</strong></td>
    <td>
    	<input type="text" name="kelompok" id="kelompok" size="30" maxlength="100" value="<?=$kelompok ?>" onFocus="showhint('Nama kelompok tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('kelompok')"  onKeyPress="return focusNext('kapasitas', event)"  />
    </td>
</tr>
<tr>
	<td><strong>Kapasitas</strong></td>
	<td>
    	<input type="text" name="kapasitas" id="kapasitas" size="3" maxlength="4" value="<?=$kapasitas ?>" onFocus="showhint('Kapasitas tidak boleh lebih dari 4 karakter!', this, event, '120px');panggil('kapasitas')"  onKeyPress="return focusNext('keterangan', event)"  />
    </td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td>
    	<textarea name="keterangan" id="keterangan" rows="3" cols="45" onKeyPress="return focusNext('simpan', event)" onFocus="panggil('keterangan')"><?=$keterangan ?></textarea>
    </td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="submit" name="simpan" id="simpan" value="Simpan" class="but" onFocus="panggil('simpan')"/>&nbsp;
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
<?php if (strlen((string) $ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

</body>
</html>