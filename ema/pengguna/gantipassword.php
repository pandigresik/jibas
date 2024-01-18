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
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');
require_once('../inc/sessioninfo.php');
//if (is_admin()){
	//header("location:pengguna.php");
	//exit();
//}
if (isset($_REQUEST['gntpass'])){
	if (SI_USER_ID()=='landlord' || SI_USER_ID()=='LANDLORD'){
		OpenDb();
		$sql = "SELECT password FROM jbsuser.landlord WHERE password='".md5((string) $_REQUEST['passlama'])."'";
		$result = QueryDb($sql);
		if (mysqli_num_rows($result) == 0) {
			$err = "Password Lama Anda Salah!";
		} else {
			$sql = "UPDATE jbsuser.landlord SET password='".md5((string) $_REQUEST['password'])."'";
			$result = QueryDb($sql);
			CloseDb();
			?>
			<script language="javascript">
				alert ('Password Administrator berhasil diubah!');
			</script>
			<?php
		}	
	} else {
		OpenDb();
		$sql = "SELECT password FROM $db_name_user.login WHERE login='".SI_USER_ID()."'";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_array($result);
		if (md5((string) $_REQUEST['passlama'])!=$row['password']){
			$err = "Password Lama Anda Salah!";
		} else {
			$sql = "UPDATE $db_name_user.login SET password='".md5((string) $_REQUEST['password'])."' WHERE login='".SI_USER_ID()."'";
			$res = QueryDb($sql);
			if ($res){
			?>
			<script language="javascript">
				alert ('Password Anda berhasil diubah!');
			</script>
			<?php
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function validate(){
	var a = document.getElementById('passlama').value;
	var b = document.getElementById('password').value;
	var c = document.getElementById('password2').value; 
	if (a.length==0){
		alert ('Password lama harus diisi!');
		document.getElementById('passlama').focus();
		return false;
	}
	if (b.length==0){
		alert ('Password baru harus diisi!');
		document.getElementById('password').focus();
		return false;
	}
	if (c.length==0){
		alert ('Konfirmasi Password baru harus diisi!');
		document.getElementById('password2').focus();
		return false;
	}
	if (c!=b){
		alert ('Password dan Konfirmasi Password harus sama!');
		document.getElementById('password2').value="";;
		document.getElementById('password2').focus();
		return false;
	}
	return true;
}
</script>
</head>

<body>
<div align="right">
<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
    <span class="news_title2">Ganti Password </span>
</div>
<br />
<form action="gantipassword.php" onSubmit="return validate()">
<table width="300" border=1 class="tab"  align="center">
  <tr>
    <td height="25" colspan="2" align="center" class="header">Ganti Password</td>
  </tr>
  <tr>
    <td width="51%" class="news_content1"><strong>Password Lama</strong></td>
    <td width="49%" height="25"><input name="passlama" type="password" class="inputtxt" id="passlama" /></td>
  </tr>
  <tr>
    <td class="news_content1"><strong>Password Baru</strong></td>
    <td height="25"><input name="password" type="password" class="inputtxt" id="password" /></td>
  </tr>
  <tr>
    <td class="news_content1"><strong>Password Baru (ulangi)</strong></td>
    <td height="25"><input name="password2" type="password" class="inputtxt" id="password2" /></td>
  </tr>
  <?php if ($err!="") { ?>
  <tr>
    <td height="25" colspan="2" align="center" class="err"><?=$err?></td>
  </tr>
  <?php } ?>
  <tr>
    <td height="40" colspan="2" align="center" bgcolor="#D2D2D2" class="news_content1"><input name="gntpass" type="submit" class="cmbfrm2" id="gntpass" value="Simpan" /></td>
  </tr>
</table>
</form>
</body>
</html>