<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 2.6.0 (January 14, 2012)
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
require_once("../include/sessionchecker.php");

if (isset($_REQUEST['kategori']))
	$kategori = $_REQUEST['kategori'];

if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);	

$cek = 0;
$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) {
	OpenDb();
	$sql = "SELECT * FROM jbsvcr.catatankategori WHERE kategori = '".$kategori."'";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {		
		CloseDb();
		$ERROR_MSG = "Kategori $kategori sudah digunakan!";		
	} else {
		$sql = "INSERT INTO jbsvcr.catatankategori SET kategori='$kategori',keterangan='$keterangan'";
		$result = QueryDb($sql);
		if ($result) { ?>
			<script language="javascript">				
				opener.refresh();
				window.close();
			</script> 
<?php 	}
	}
	CloseDb();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOGURU [Tambah Kategori Catatan Siswa]</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script src="../script/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function validate() {
	return validateEmptyText('kategori', 'Nama Kategori') &&
		   validateMaxText('kategori', 255, 'Kategori') && 
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

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="document.getElementById('kategori').focus();">
<form name="main" id="main" action="catatankategori_add.php" onSubmit="return validate()">
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr height="25">
<td colspan="2"><div align="left"><strong>Tambah Catatan Kategori</strong></td>
</tr>
<tr>
	<td width="120"><strong>Kategori</strong></td>
	<td>
    	<input type="text" name="kategori" id="kategori" size="40" maxlength="100" style="height: 25px; font-size: 12px;" value="<?=$kategori ?>" onKeyPress="return focusNext('keterangan', event)" />    </td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td>
    	<textarea name="keterangan" id="keterangan" rows="3" cols="45" onKeyPress="return focusNext('Simpan', event)"><?=$keterangan ?></textarea>    </td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but" />&nbsp;
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

<!-- Pilih inputan pertama -->
</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("kategori");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
</script>