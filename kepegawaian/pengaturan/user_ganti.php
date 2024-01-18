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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/sessioninfo.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');

$login = getUserId();

if (isset($_REQUEST['simpan']))
{
	$nip = trim((string) $_REQUEST['nip']);
	$login = trim((string) $_REQUEST['login']);
	if ($login == 'landlord' || $login == 'LANDLORD')
	{
		OpenDb();
		
		$sql = "SELECT password FROM jbsuser.landlord WHERE password=md5('".$_REQUEST['passlama']."')";
		$result = QueryDb($sql);
		if (mysqli_num_rows($result) == 0)
		{
			CloseDb(); 
			$mysqli_ERROR_MSG = "Password lama Anda tidak cocok!";
		}
		else
		{
			$sql = "UPDATE jbsuser.landlord SET password=md5('".$_REQUEST['pass1']."')";
			$result = QueryDb($sql);
			CloseDb();
			$mysqli_ERROR_MSG = "Password Administrator telah berubah!";	
			$exit = 1;
		}	
	}
	else
	{
		OpenDb();
		$sql = "SELECT login FROM jbsuser.login WHERE password=md5('".$_REQUEST['passlama']."') AND login='$nip'";
		$result = QueryDb($sql);
		if (mysqli_num_rows($result) == 0)
		{
			CloseDb(); 
			$mysqli_ERROR_MSG = "Password lama Anda tidak cocok!";
		}
		else
		{
			$sql = "UPDATE jbsuser.login SET password=md5('".$_REQUEST['pass1']."') WHERE login='$nip'";
			$result = QueryDb($sql);
			CloseDb();
			$mysqli_ERROR_MSG = "Password Anda telah berubah!";	
			$exit = 1;
		}
	}
}

OpenDb();
if ($login=='landlord' || $login=='LANDLORD')
{
	$nip = "";
	$nama = "Administator";
	$title = "Administrator";
}
else
{
	$sql = "SELECT p.nip, p.nama FROM jbssdm.pegawai p WHERE p.nip = '".$login."'"; 
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$nip = $row[0];
	$nama = $row[1];
	$title = "Pengguna";
}
CloseDb();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian [Ganti Password <?=$title?>]</title>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function validasi() {
	var passlama = document.getElementById('passlama').value;
	var pass1 = document.getElementById('pass1').value;	
	var pass2 = document.getElementById('pass2').value;
	
	if (passlama.length == 0) {
		alert("Password Lama tidak boleh kosong");
		document.getElementById('passlama').focus();
		return false;
	}
	
	if (pass1.length == 0) {
		alert("Password tidak boleh kosong");
		document.getElementById('pass1').focus();
		return false;
	}
	
	if (pass2.length == 0) {
		alert("Konfirmasi tidak boleh kosong");
		document.getElementById('pass2').focus();
		return false;
	}
	
	if (pass1 != pass2) {
		alert('Password yang anda masukkan tidak sama!');
		document.getElementById('pass1').focus();
		return false;
	}
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
	var lain = new Array('passlama','pass1','pass2');
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
<body onLoad="document.getElementById('passlama').focus();" style="background-color:#dcdfc4; margin-left:0px; margin-top:0px; margin-bottom:0px; margin-right:0px;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
    <div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Password <?=$title?> :.
    </div>
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //---> 
    <form name="main" method="post" onSubmit="return validasi();"> 
    <input type="hidden" name="login" id="login" value="<?=$login ?>" />
    <table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
    <!-- TABLE CONTENT -->
    <tr>
        <td width="110"><strong>Nama</strong></td>
        <td>
        	<?php if ($login!='landlord' && $login!='LANDLORD'){ ?>
            	<input type="text" name="nip" id="nip" size="10" readonly="readonly" value="<?=$nip ?>" class="disabled" />
        	<?php } ?>
            	<input type="text" name="nama" id="nama" size="30" readonly="readonly" value="<?=$nama ?>" class="disabled" />        
        </td>
    </tr>
    <tr>
    	<td><strong>Password Lama</strong></td>
        <td><input type="password" name="passlama" id="passlama" size="20" onFocus="panggil('passlama')" onKeyPress="return focusNext('pass1', event)"/></td>
    </tr>
    <tr>
    	<td><strong>Password</strong></td>
        <td><input type="password" name="pass1" id="pass1" size="20" onFocus="panggil('pass1')" onKeyPress="return focusNext('pass2', event)"/></td>
    </tr>
    <tr>
    	<td><strong>Konfirmasi</strong></td>
        <td><input type="password" name="pass2" id="pass2" size="20" onFocus="panggil('pass2')" onKeyPress="return focusNext('simpan', event)"/></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
       		<input class="but" type="submit" value="Simpan" name="simpan" id="simpan">&nbsp;
        	<input class="but" type="button" value="Tutup" onClick="window.close();">        
      	<td>
    </tr>
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
<?php if (strlen((string) $mysqli_ERROR_MSG) > 0) { ?>
<script language="javascript">
    alert('<?=$mysqli_ERROR_MSG ?>');
</script>
<?php } ?>
</body>
</html>
<?php if ($exit) { ?>
<script language="javascript">
    window.close();
</script>
<?php } ?>