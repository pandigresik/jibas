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
require_once('../include/theme.php'); 
require_once('../cek.php');
require_once('../library/dpupdate.php');

if (isset($_REQUEST['kode']))
	$kode = CQ($_REQUEST['kode']);

if (isset($_REQUEST['nama']))
	$nama = CQ($_REQUEST['nama']);

$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) 
{
	OpenDb();
	
	$sql = "SELECT replid FROM dasarpenilaian WHERE dasarpenilaian = '".$kode."'";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) 
	{		
		CloseDb();
		$ERROR_MSG = "Kode $kode sudah digunakan!";	
		$cek = 0;	
	} 
	else 
	{
		$sql = "INSERT INTO dasarpenilaian SET dasarpenilaian = '$kode', keterangan = '".$nama."'";
		$result = QueryDb($sql);
		CloseDb();
		if ($result) 
		{ 	?>
			<script language="javascript">				
				opener.refresh();
				window.close();
			</script> 
<?php 	}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Tambah Aspek Penilaian]</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function tutup() {
	document.getElementById('urutan').focus();
}

function validate() {
	return validateEmptyText('kode', 'Kode Aspek Penilaian') && 
		   validateEmptyText('nama', 'Nama Aspek Penilaian');
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'nip')
			caripegawai();
		return false;
    } 
    return true;
}

function panggil(elem){
	var lain = new Array('kode','nama');
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"  style="background-color:#dcdfc4" <?=$input_awal?>>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
    <div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Tambah Aspek Penilaian :.
    </div>
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<form name="main" onSubmit="return validate()">
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="60"><strong>Kode</strong></td>
	<td>
    	<input type="text" name="kode" id="kode" size="10" maxlength="10" value="<?=$kode?>" onFocus="showhint('Kode aspek penilaian tidak boleh lebih dari 10 karakter!', this, event, '120px'); panggil('kode')" onKeyPress="return focusNext('nama', event)"/></td>
</tr>
<tr>
	<td><strong>Aspek</strong></td>
	<td>
    	<input type="text" name="nama" id="nama" size="50" maxlength="50" value="<?=$nama?>" onFocus="showhint('Nama aspek penilaian tidak boleh lebih dari 50 karakter!', this, event, '120px'); panggil('nama')" onKeyPress="return focusNext('Simpan', event)"/></td>
</tr>
<tr>
	<td colspan="2" height="25" width="100%" align="left" valign="top" style="border-width:1px; border-style:dashed; border-color:#03F; background-color:#CFF">
	   	<strong>Anda hanya perlu mengisikan kode dan aspek secara umum tanpa harus menambahkan pelajaran, tahun ajaran atau semester.<br/>
				<font color="#FF0000">Contoh yang salah : PRAK-IGG (Praktek Bahasa Inggris), PKON-MAT (Pemahaman Konsep Matematika) </font><br />
				<font color="Blue">Contoh yang benar : PRAK (Praktek), PKON (Pemahaman Konsep)</font></strong></td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but" onFocus="panggil('Simpan')"/>&nbsp;
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