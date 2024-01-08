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

$proses = "";
if (isset($_REQUEST['proses']))
	$proses = CQ($_REQUEST['proses']);

$kode = "";
if (isset($_REQUEST['kode']))
	$kode = CQ($_REQUEST['kode']);
	
$keterangan = "";
if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);	
$cek = 0;	
$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) {
	OpenDb();
	$sql = "SELECT * FROM prosespenerimaansiswa WHERE proses = '$proses' AND departemen = '".$departemen."'";
	$result = QueryDb($sql);
	
	$sql2="SELECT * from prosespenerimaansiswa WHERE kodeawalan='$kode'";
	$result2 = QueryDb($sql2);
		
	if (mysqli_num_rows($result) > 0) {
		CloseDb();
		$ERROR_MSG = "Nama proses ".$proses." sudah digunakan!";
	} else if (mysqli_num_rows($result2) > 0) {
		CloseDb();
		$ERROR_MSG = "Kode awalan ".$kode." sudah digunakan!";
		$cek = 1;
	} else {
		$sql = "INSERT INTO prosespenerimaansiswa SET proses='$proses', kodeawalan='$kode', departemen='$departemen', keterangan='$keterangan'";
		$result = QueryDb($sql);
		
		if ($result) { 
			$sql1 = "SELECT LAST_INSERT_ID(replid) FROM prosespenerimaansiswa ORDER BY replid DESC LIMIT 1";			
			$result1 = QueryDb($sql1);
			$row1 = mysqli_fetch_row($result1); 
			
			$sql2 = "UPDATE prosespenerimaansiswa SET aktif = 0 WHERE replid <> '".$row1[0]."' AND departemen = '".$departemen."'";			
			QueryDb($sql2);
			CloseDb();		
		?>
			<script language="javascript">
				opener.refresh();
				window.close();
			</script> 
<?php 	}
	}
}

switch ($cek) {
	case 0 : $input_awal = "onload=\"document.getElementById('proses').focus()\"";
		break;
	case 1 : $input_awal = "onload=\"document.getElementById('kode').focus()\"";
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Tambah Proses Penerimaan Siswa Baru]</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function validate() {
	return validateEmptyText('proses', 'Nama Proses') && 
		   validateEmptyText('kode', 'Kode Awalan') && 	
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
	var lain = new Array('proses','kode','keterangan');
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
    .: Tambah Proses Penerimaan Siswa Baru :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" >
    <!-- CONTENT GOES HERE //--->

<form name="main" onSubmit="return validate()">
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="120"><strong>Departemen</strong></td>
    <td><input type="text" name="departemen" size="10" value="<?=$departemen ?>" readonly class="disabled"/>
    	<input type="hidden" name="departemen" id="departemen" value ="<?=$departemen ?>" /></td>
</tr>
<tr>
	<td><strong>Nama Proses</strong></td>
	<td>
    	<input type="text" name="proses" id="proses" size="30" maxlength="100"  value="<?=$proses?>" onFocus="showhint('Nama proses tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('proses')"  onKeyPress="return focusNext('kode', event)" />
    </td>
</tr>
<tr>
	<td><strong>Kode Awalan</strong></td>
	<td>
    	<input type="text" name="kode" id="kode" size="10" maxlength="5" value="<?=$kode?>" onFocus="showhint('Kode awalan tidak boleh lebih dari 5 karakter!', this, event, '120px');panggil('kode')" onKeyPress="return focusNext('keterangan', event)" />
    </td>
</tr>
<tr>
	<td>
    </td>
    <td>*)untuk membangkitkan nomor pendaftaran
    </td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td>
    	<textarea name="keterangan" id="keterangan" rows="3" cols="45" onFocus="panggil('keterangan')" onKeyPress="return focusNext('Simpan', event)"><?=$keterangan ?></textarea>
    </td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but" onFocus="panggil('Simpan')"/>&nbsp;
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