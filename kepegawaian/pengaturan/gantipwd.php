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
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/sessioninfo.php');
require_once('../include/theme.php');

if (isset($_REQUEST['simpan'])) {
	OpenDb();
	
	$sql_c = "SELECT password FROM pengguna WHERE login='".$_REQUEST['login']."'";
	$result_c = QueryDb($sql_c);
	$row_c = @mysqli_fetch_row($result_c);
	
	if ($row_c[0]!=md5((string) $_REQUEST['pwlama'])) { ?>
	    <script language="javascript">
	    	alert ('Password Lama Anda Salah !');
	    	document.location.href="gantipwd.php";
	    </script>
	<?php
	} else {
		$sql_u="UPDATE pengguna SET password='".md5((string) $_REQUEST['pwbaru'])."'";
		$result_u=QueryDb($sql_u);
		if ($result_u) { ?>
			<script language="javascript">
                alert ('Password Anda telah berubah !');
                document.location.href="gantipwd.php";
            </script>
<?php 	} else { ?>
		<script language="javascript">
			alert ('Password Anda Gagal diubah !');
			document.location.href="gantipwd.php";
		</script>
<?php 	}
	}
	CloseDb();
} else {
	$login = getUserId();
	$nama  = getUserName();
}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style<?=GetThemeDir2()?>.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function validate(){
var pwlama=document.getElementById("pwlama").value;
var pwbaru=document.getElementById("pwbaru").value;
var pwbaru1=document.getElementById("pwbaru1").value;
if (pwlama==""){
	alert ('Anda harus mengisikan data untuk Password Lama!');
	document.getElementById("pwlama").focus();
	return false;
}
if (pwbaru==""){
	alert ('Anda harus mengisikan data untuk Password Baru!');
	document.getElementById("pwbaru").focus();
	return false;
}
if (pwbaru1==""){
	alert ('Anda harus mengisikan data untuk Konfirmasi Password Baru!');
	document.getElementById("pwbaru1").focus();
	return false;
}
if (pwbaru1!=pwbaru){
	alert ('Password Baru harus sama dengan Konfirmasi Password Baru!');
	document.getElementById("pwbaru1").value="";
	document.getElementById("pwbaru1").focus();
	return false;
}
return true;
}

</script>
</head>

<body onLoad="document.getElementById('pwlama').focus()">
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
  <td width="100%" align="right" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
    <font style="background-color:#FFCC33; font-size:24px">&nbsp;&nbsp;</font>
    <font class="subtitle">Ganti Password</font><br />
    <a href="pengaturan.php">Pengaturan</a> &gt; Ganti Password Pengguna<br />
    </td>
    
</tr>
<tr><td align="center">
<form action="gantipwd.php" method="post" onSubmit="return validate()">
	<table border="0" cellspacing="0">
      <tr>
        <td height="30" colspan="2" align="center" class="header">Ganti Password</td>
        </tr>
      <tr>
        <td width="55" align="left" height="25"><strong>Login</strong></td>
        <td width="51" height="25"><input type="text" name="login" id="login" size="20" readonly="readonly" style="background-color:#EBEBEB" value="<?=$login?>" /></td>
      </tr>
      <tr>
        <td height="25" align="left"><strong>Nama</strong></td>
        <td height="25"><input type="text" name="nama" id="nama" size="35" readonly="readonly" style="background-color:#EBEBEB" value="<?=$nama?>" /></td>
      </tr>
      <tr>
        <td height="25" align="left"><strong>Password&nbsp;Lama</strong></td>
        <td height="25"><input type="password" name="pwlama" id="pwlama" value="" /></td>
      </tr>
      <tr>
        <td height="25" align="left"><strong>Password&nbsp;Baru</strong></td>
        <td height="25"><input type="password" name="pwbaru" id="pwbaru" value="" /></td>
      </tr>
      <tr>
        <td height="25" align="left"><strong>Password&nbsp;Baru&nbsp;<em>(ulangi)</em>&nbsp;</strong></td>
        <td height="25"><input type="password" name="pwbaru1" id="pwbaru1" value="" /></td>
      </tr>
      <tr>
        <td height="25" colspan="2" 	align="center" bgcolor="#EAEAEA"><label>
          <input type="submit" name="simpan" id="simpan" value="Ganti" class="but" />
        </label></td>
        </tr>
    </table>
</form>
	</td></tr>
<!-- END TABLE CENTER -->    
</table>

</body>
</html>
<?php CloseDb();?>