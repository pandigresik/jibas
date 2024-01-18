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
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/sessioninfo.php');
require_once('include/db_functions.php');
require_once('library/departemen.php');

$login = getIdUser();

if (isset($_REQUEST['simpan'])) {
	$nip=trim((string) $_REQUEST['nip']);
	OpenDb();
	$sql = "SELECT login FROM jbsuser.login WHERE password='".md5((string) $_REQUEST['passlama'])."' AND login='$nip'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) {
		CloseDb(); 
		$mysqli_ERROR_MSG = "Password lama anda tidak cocok!";
	} else {
		$sql = "UPDATE jbsuser.login SET password='".md5((string) $_REQUEST['pass1'])."' WHERE login='".trim((string) $_REQUEST['nip'])."'";
		$result = QueryDb($sql);
		CloseDb();
	
		if ($result) { ?>
			<script language="javascript">
				alert("Password anda telah berubah");
				window.close();
			</script> 
<?php 	}	
		exit();
	}
}

OpenDb();
$sql = "SELECT p.nip, p.nama FROM jbssdm.pegawai p WHERE p.nip = '".$login."'";     
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$nip = $row[0];
$nama = $row[1];
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ganti Password Pengguna</title>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function validasi() {
	if (validateEmptyText('passlama', 'Password Lama') {
		var pass1 = document.getElementById('pass1').value;
		var pass2 = document.getElementById('pass2').value;

		if (pass1 != pass2) {
			alert('Password yang anda masukkan tidak sama!');
			return false;
		} else {
			return true;
		}
	} else {
		return false;
	}
}
</script>
</head>

<body>
<table border="0" width="100%" height="100%">
<tr>
	<td valign="middle" align="center" width="100%">
    
    <br />
    <form name="main" method="post" onSubmit="return validasi();"> 
    <input type="hidden" name="id" id="id" value="<?=$id ?>" />
    <table border="0" background="images/bttable300.png">
    <tr>
        <td colspan="2" class="header" height="30">Ubah Password Pengguna</td>
    </tr>
    <tr>
        <td align="left">Nama:</td>
        <td align="left">
        <input type="text" name="nip" id="nip" size="12" readonly="readonly" value="<?=$nip ?>" style="background-color:#CCCCCC" />
        <input type="text" name="nama" id="nama" size="30" readonly="readonly" value="<?=$nama ?>" style="background-color:#CCCCCC" /> 
        </td>
    </tr>
    <tr>
    	<td align="left">Password Lama:</td>
        <td align="left"><input type="password" name="passlama" id="passlama" size="20" /></td>
    </tr>
    <tr>
    	<td align="left">Password:</td>
        <td align="left"><input type="password" name="pass1" id="pass1" size="20" /></td>
    </tr>
    <tr>
    	<td align="left">Ulangi Password:</td>
        <td align="left"><input type="password" name="pass2" id="pass2" size="20" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
        	<input class="but" type="submit" value="Ganti" name="simpan">
            <input class="but" type="button" value="Tutup" onClick="window.close();">
        </td>
    </tr>
    </table>
    </form>
	</td>
</tr>
</table>
<?php if (strlen((string) $mysqli_ERROR_MSG) > 0) { ?>
<script language="javascript">
    alert('<?=$mysqli_ERROR_MSG ?>');
</script>
<?php } ?>
</body>
</html>