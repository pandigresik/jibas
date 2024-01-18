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
require_once('../include/config.php');
require_once('../include/db_functions.php');
OpenDb();
$idgroup = $_REQUEST['idgroup'];
if (isset($_REQUEST['Simpan'])){
	$sql = "SELECT * FROM jbsfina.kelompokbarang WHERE kelompok='".addslashes(trim((string) $_REQUEST['kelompokname']))."' AND idgroup='".$_REQUEST['idgroup']."'";
	if (@mysqli_num_rows(QueryDb($sql))>0){
		?>
        <script language="javascript">
			alert ('Kelompok <?=$_REQUEST['kelompokname']?> sudah digunakan!');
        </script>
        <?php
	} else {
		QueryDb("INSERT INTO jbsfina.kelompokbarang SET kelompok='".addslashes(trim((string) $_REQUEST['kelompokname']))."', keterangan='".addslashes(trim((string) $_REQUEST['keterangan']))."',idgroup='".$_REQUEST['idgroup']."'");
		?>
        <script language="javascript">
			parent.opener.GetFresh();
			window.close();
        </script>
        <?php
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../style/style.css" />
<title>Tambah Group Barang</title>
<script language="javascript">
function validate(){
	var namakelompok = document.getElementById('kelompokname').value;
	if (namakelompok.length==0){
		alert ('Anda harus mengisikan Nama Kelompok!'); 
		document.getElementById('kelompokname').focus();
		return false;
	}
	return true;
}
</script>
</head>
<body onLoad="document.getElementById('kelompokname').focus()">
<fieldset style="border:#336699 1px solid; background-color:#eaf4ff" >
<legend style="background-color:#336699; color:#FFFFFF; font-size:12px; font-weight:bold; padding:5px; ">&nbsp;Tambah&nbsp;Kelompok&nbsp;</legend>
<form action="AddKelompok.php" onSubmit="return validate()" method="post">
<input type="hidden" name="idgroup" id="idgroup" value="<?=$_REQUEST['idgroup']?>" />
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>Nama Kelompok</td>
    <td><input name="kelompokname" id="kelompokname" type="text" maxlength="45" style="width:100%" value="<?=stripslashes((string) $_REQUEST['kelompokname'])?>" /></td>
  </tr>
  <tr>
    <td>Keterangan</td>
    <td><textarea name="keterangan" id="keterangan" style="width:100%" rows="5"><?=stripslashes((string) $_REQUEST['keterangan'])?></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input class="but" type="submit" name="Simpan" value="Simpan" />&nbsp;&nbsp;<input type="button" value="Batal" onClick="window.close()" class="but" /></td>
  </tr>
</table>
</form>
</fieldset>
</body>
<?php
CloseDb();
?>
</html>