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
$sql = "SELECT * FROM jbsfina.groupbarang WHERE replid='".$_REQUEST['idgroup']."'";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$groupname = stripslashes((string) $row['namagroup']);
if (isset($_REQUEST['groupname']))
	$groupname = addslashes(trim((string) $_REQUEST['groupname']));
//$groupname = addslashes(trim($groupname));
$keterangan = stripslashes((string) $row['keterangan']);
if (isset($_REQUEST['keterangan']))
	$keterangan = addslashes(trim((string) $_REQUEST['keterangan']));	

//$keterangan = addslashes(trim($keterangan));
if (isset($_REQUEST['Simpan'])){
	$sql = "SELECT * FROM jbsfina.groupbarang WHERE namagroup='$groupname' AND replid<>'".$_REQUEST['idgroup']."'";
	if (@mysqli_num_rows(QueryDb($sql))>0){
		?>
        <script language="javascript">
			alert ('Group <?=$_REQUEST['groupname']?> sudah digunakan!');
        </script>
        <?php
	} else {
		QueryDb("UPDATE jbsfina.groupbarang SET namagroup='$groupname', keterangan='$keterangan' WHERE replid='".$_REQUEST['idgroup']."'");
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
	var namagroup = document.getElementById('groupname').value;
	if (namagroup.length==0){
		alert ('Anda harus mengisikan Nama Group!'); 
		document.getElementById('groupname').focus();
		return false;
	}
	return true;
}
</script>
</head>
<body onLoad="document.getElementById('groupname').focus()">
<fieldset style="border:#336699 1px solid; background-color:#eaf4ff" >
<legend style="background-color:#336699; color:#FFFFFF; font-size:12px; font-weight:bold; padding:5px; ">&nbsp;Tambah&nbsp;Group&nbsp;</legend>
<form action="EditGroup.php" onSubmit="return validate()" method="post">
<input type="hidden" name="idgroup" id="idgroup" value="<?=$_REQUEST['idgroup']?>" />
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>Nama Group</td>
    <td><input name="groupname" id="groupname" type="text" maxlength="45" style="width:100%" value="<?=stripslashes($groupname)?>" /></td>
  </tr>
  <tr>
    <td>Keterangan</td>
    <td><textarea name="keterangan" id="keterangan" style="width:100%" rows="5"><?=stripslashes($keterangan)?></textarea></td>
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