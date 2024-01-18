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
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/getheader.php');
require_once('../include/sessioninfo.php');
require_once('../include/db_functions.php');

$login = SI_USER_ID();

if (isset($_REQUEST['simpan'])) {
	$nis=trim((string) $_REQUEST['nis']);
	OpenDb();
	$sql = "SELECT login FROM jbsuser.loginsiswa WHERE password=md5('".$_REQUEST['passlama']."') AND login='$nis'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) {
		CloseDb(); 
		$mysqli_ERROR_MSG = "Password lama anda tidak cocok!";
	} else {
		$sql = "UPDATE jbsuser.loginsiswa SET password=md5('".$_REQUEST['pass1']."') WHERE login=".trim((string) $_REQUEST['nis']);
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
$sql = "SELECT p.nis, p.nama FROM jbsakad.siswa p WHERE p.nis = '".$login."'";     
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$nis = $row[0];
$nama = $row[1];
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ganti Password Pengguna</title>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script src="../script/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function validasi() {
		var passlama = document.getElementById('passlama').value;
		var pass1 = document.getElementById('pass1').value;
		var pass2 = document.getElementById('pass2').value;
		if (passlama.length==0){
			alert('Anda harus mengisikan data untuk Password Lama!');
			document.getElementById('passlama').focus();
			return false;
		}
		if (pass1.length==0){
			alert('Silakan masukan password baru!');
			document.getElementById('pass1').focus();
			return false;
		}
		if (pass2.length==0){
			alert('Silakan masukan password baru (ulang)!');
			document.getElementById('pass2').focus();
			return false;
		}
		if (pass1 != pass2) {
			alert('Password yang anda masukkan tidak sama!');
			document.getElementById('pass2').focus();
			return false;
		} else {
			return true;
		}
	
}
</script>
</head>
<body onLoad="document.getElementById('passlama').focus();" style="background:url(../images/bgmain.jpg); margin-left:0px; margin-top:0px; margin-bottom:0px; margin-right:0px;">
<table border="0" width="100%" height="100%">
<tr>
	<td valign="middle" align="center" width="100%">
    <form name="main" method="post" onSubmit="return validasi();"> 
    <input type="hidden" name="login" id="login" value="<?=$login ?>" />
    <table border="0" background="images/bttable300.png">
    <tr>
        <td colspan="2" class="header">Ubah Password Pengguna</td>
    </tr>
    <tr>
        <td align="left">Nama:</td>
        <td align="left">
        <input type="text" name="nis" id="nis" size="12" readonly="readonly" value="<?=$nis ?>" style="background-color:#CCCCCC" />
        <input type="text" name="nama" id="nama" size="30" readonly="readonly" value="<?=$nama ?>" style="background-color:#CCCCCC" />        </td>
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
        <td colspan="2" align="left">
        	<div align="center">
        	  <input class="but" type="submit" value="Ganti" name="simpan">&nbsp;
        	  <input class="but" type="button" value="Tutup" onClick="window.close();">        
      	  </div></td>
        </tr>
    </table>
	  <script language='JavaScript'>
	    //Tables('table', 1, 0);
    </script>
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
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("passlama");
var sprytextfield2 = new Spry.Widget.ValidationTextField("pass1");
var sprytextfield3 = new Spry.Widget.ValidationTextField("pass2");
</script>