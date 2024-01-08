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
require_once("../include/sessionchecker.php");
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

$replid = $_REQUEST['replid'];
if (isset($_REQUEST['satker']))
	$satker = $_REQUEST['satker'];
if (isset($_REQUEST['nama']))
	$nama = $_REQUEST['nama'];
$cek = 0;
$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) {
	OpenDb();
	$sql = "SELECT * FROM satker WHERE satker = '".$_REQUEST['satker']."' AND replid <> $replid";
	$result = QueryDb($sql);
	
	$sql1 = "SELECT * FROM satker WHERE nama = '".$_REQUEST['nama']."' AND replid <> $replid";
	$result1 = QueryDb($sql1);
	
	
	if (mysqli_num_rows($result) > 0) {
		CloseDb();
		$ERROR_MSG = "Satuan Kerja {$_REQUEST['satker']} sudah digunakan!";
		$cek = 0;	
	} else if (mysqli_num_rows($result1) > 0) {
		CloseDb();
		$ERROR_MSG = "Nama {$_REQUEST['nama']} sudah digunakan!";
		$cek = 1;
	} else {
		$sql = "UPDATE satker SET satker='".$_REQUEST['satker']."',nama='".$_REQUEST['nama']."' WHERE replid=$replid";
		$result = QueryDb($sql);
		CloseDb();
	
		if ($result) { ?>
			<script language="javascript">
				opener.refresh();
				window.close();
			</script> 
<?php 	}
		exit();
	}
};


switch ($cek) {
	case 0 : $input_awal = "onload=\"document.getElementById('satker').focus()\"";
		break;
	case 1 : $input_awal = "onload=\"document.getElementById('nama').focus()\"";
		break;
}

OpenDb();
$sql = "SELECT satker,nama FROM satker WHERE replid = $replid"; 

$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$satker = $row[0];
$nama = $row[1];


CloseDb();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style<?=GetThemeDir2()?>.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function tutup() {
	document.getElementById('urutan').focus();
}

function validate() {
	return validateEmptyText('satker', 'Satuan Kerja') && 
		   validateEmptyText('nama', 'Nama Satuan Kerja');
}


</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF" <?=$input_awal?>>
<form name="main" onSubmit="return validate()">
<input type="hidden" name="replid" id="replid" value="<?= $replid ?>" />


<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr height="25">
<td class="header" colspan="2" align="center">Ubah Satuan Kerja</td>
</tr>
<tr>
	<td width="84"><strong>Satuan&nbsp;Kerja</strong></td>
	<td width="1090">
   	  <input type="text" name="satker" id="satker" size="20" maxlength="50" value="<?= $satker ?>" onFocus="showhint('Satuan Kerja tidak boleh lebih dari 50 karakter!', this, event, '120px');" />    </td>
</tr>
<tr>
	<td><strong>Nama</strong></td>
	<td>
    	<input type="text" name="nama" id="nama" size="30" maxlength="50" value="<?= $nama ?>" onFocus="showhint('Nama Satuan Kerja tidak boleh lebih dari 50 karakter!', this, event, '120px');" />    </td>
</tr>

<tr>
	<td colspan="2" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but"/>&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />    </td>
</tr>
<!-- END OF TABLE CONTENT -->
</table>
</form>
<!-- Tamplikan error jika ada -->
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

</body>
</html>