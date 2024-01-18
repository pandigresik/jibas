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
require_once('../../include/errorhandler.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/common.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('../../include/theme.php');
require_once('../../include/sessionchecker.php'); 

if (isset($_REQUEST['iddir']))
	$iddir = $_REQUEST['iddir'];
	
OpenDb();

$sql = "SELECT dirfullpath FROM jbsvcr.dirshare WHERE idroot = 0";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$rootname = $row[0];

$sql = "SELECT dirfullpath FROM jbsvcr.dirshare WHERE replid = '".$iddir."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$dfullpath = $row[0];
$fullpath = str_replace($rootname, "", (string) $dfullpath);

CloseDb();

$cek = 0;
$ERROR_MSG = "";
$FileShareDir = "$FILESHARE_UPLOAD_DIR/fileshare/";
if (isset($_REQUEST['Simpan']))
{
	$rootfolder_db = trim((string) $_REQUEST['fullpath']);
	$dir_db = $rootfolder_db . $_REQUEST['folder'] . "/";
	$dir_real = str_replace($rootname, $FileShareDir, $dir_db);
		
	if (!file_exists($dir_real))
	{
		mkdir($dir_real, 0750, true);
		
		$fhtaccess = "$dir_real/.htaccess";
		$fhtaccess = str_replace("//", "/", $fhtaccess);
		if ($fp = @fopen($fhtaccess, "w"))
		{
			@fwrite($fp, "Options -Indexes\r\n");
			@fclose($fp);
		}
	}
	
	OpenDb();
	$sql="INSERT INTO jbsvcr.dirshare SET idroot=$iddir, dirname='".$_REQUEST['folder']."', dirfullpath='$dir_db', idguru='".SI_USER_ID()."'";
	QueryDb($sql);
	CloseDb();
	?>
	<script language="javascript" >
		opener.get_fresh();
		window.close();
	</script>
	<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../style/style.css">
<link rel="stylesheet" type="text/css" href="../../style/tooltips.css">
<link rel="stylesheet" type="text/css" href="../../script/SpryAssets/SpryValidationTextField.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOGURU [Tambah Folder]</title>
<script language = "javascript" type = "text/javascript" src="../../script/tooltips.js"></script>
<script language="javascript" src="../../script/tables.js"></script>
<script language="javascript" src="../../script/tools.js"></script>
<script language="javascript" src="../../script/validasi.js"></script>
<script language="javascript" src="../../script/SpryAssets/SpryValidationTextField.js"></script>
<script language="javascript" >
function validate(){
var folder=document.getElementById('folder').value;
if (folder.length==0){
	alert ('Anda harus mengisikan nama untuk Folder yang akan dibuat!');
	document.getElementById('folder').focus();	
	return false;
	}
	return true
	
}

function kopikecopy(){
	var x=document.getElementById('folder').value;
	document.getElementById('copydir').value=x;
}

function acceptPegawai(nip, nama) {
	document.getElementById('folder').value=nip;
}
</script>
<style type="text/css">
<!--
.style1 {color: #0000FF}
-->
</style>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF" onLoad="document.getElementById('folder').focus();">
<form name="main" onSubmit="return validate()" method="POST" enctype="multipart/form-data">
<table border="0" width="95%" cellspacing="0" align="center">
<!-- TABLE CONTENT -->
<tr height="25">
<td colspan="2" class="header"><div align="center">Buat Folder Baru</div></td>
</tr>
<tr>
  <td width="110" height="25" align="right" bgcolor="#CCCCCC"><div align="left"><strong>Tujuan&nbsp;:&nbsp;</strong></div></td>
  <td width="846" height="25" align="left" bgcolor="#CCCCCC">&nbsp;<strong><?="(root)/".$fullpath?><input name="copydir" id="copydir" type="text" readonly style="border:none; background-color:#CCCCCC;" /></strong>
  <input name="fullpath" id="fullpath" type="hidden" readonly value="<?=$dfullpath?>" /><input name="idroot" id="idroot" type="hidden" readonly value="<?=$iddir?>" /></td>
</tr>
<tr>
  <td align="right"><div align="left"><strong>Nama&nbsp;Folder&nbsp;:&nbsp;</strong></div></td>
  <td height="25" align="left">&nbsp;<input onKeyUp="kopikecopy()" name="folder" id="folder" type="text" <?php if (SI_USER_ID()=="LANDLORD" || SI_USER_ID()=="landlord"){ ?> readonly onClick="caripegawai()" <?php } ?>/>&nbsp;<?php if (SI_USER_ID()=="LANDLORD" || SI_USER_ID()=="landlord"){ ?><img src="../../images/ico/cari.png" border="0" onClick="caripegawai()"/><?php } ?> </td>
</tr>
<?php if (SI_USER_ID()=="LANDLORD" || SI_USER_ID()=="landlord"){ ?>
<tr>
  <td height="30" colspan="2" align="center" bgcolor="#CCCCCC"> 
  <div align="center" class="style1">* Untuk menambah folder Guru, lebih baik gunakan NIP supaya lebih mudah diakses</div>
  </td>
</tr>
<?php } ?>
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
	var sprytextfield1 = new Spry.Widget.ValidationTextField("folder");
</script>