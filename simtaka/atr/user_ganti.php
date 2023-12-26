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
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/sessioninfo.php');
require_once('../inc/db_functions.php');
require_once('../inc/theme.php');

$login = SI_USER_ID();

if (isset($_REQUEST['simpan'])) {
	$nip=trim($_REQUEST['nip']);
	$login=trim($_REQUEST['login']);
	if ($login=='landlord' || $login=='LANDLORD'){
		OpenDb();
		$sql = "SELECT password FROM jbsuser.landlord WHERE password=md5('".$_REQUEST['passlama']."')";
		$result = QueryDb($sql);
		if (mysqli_num_rows($result) == 0) {
			CloseDb(); 
			$mysqli_ERROR_MSG = "Password lama Anda tidak cocok!";
		} else {
			$sql = "UPDATE jbsuser.landlord SET password=md5('".$_REQUEST['pass1']."')";
			$result = QueryDb($sql);
			CloseDb();
			$mysqli_ERROR_MSG = "Password Administrator telah berubah!";	
			$exit = 1;
		}	
	} else {
		OpenDb();
		$sql = "SELECT login FROM jbsuser.login WHERE password=md5('".$_REQUEST['passlama']."') AND login='$nip'";
		$result = QueryDb($sql);
		if (mysqli_num_rows($result) == 0) {
			CloseDb(); 
			$mysqli_ERROR_MSG = "Password lama Anda tidak cocok!";
		} else {
			$sql = "UPDATE jbsuser.login SET password=md5('".$_REQUEST['pass1']."') WHERE login='$nip'";
			$result = QueryDb($sql);
			CloseDb();
			$mysqli_ERROR_MSG = "Password Anda telah berubah!";	
			$exit = 1;
		}
	}
	
}

OpenDb();
if ($login=='landlord' || $login=='LANDLORD'){
	$nip = "";
	$nama = "Administator";
	$title = "Administrator";
} else {
	$sql = "SELECT p.nip, p.nama FROM jbssdm.pegawai p WHERE p.nip = '$login'"; 
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
<link rel="stylesheet" type="text/css" href="../sty/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMTAKA [Ganti Password <?=$title?>]</title>
<script language="javascript" src="../scr/validasi.js"></script>
<script language="javascript" src="../scr/tables.js"></script>
<script language="javascript" src="../scr/tools.js"></script>
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
<body onLoad="document.getElementById('passlama').focus();" style="background:url(../images/bgmain.jpg); margin-left:0px; margin-top:0px; margin-bottom:0px; margin-right:0px;">
    <form name="main" method="post" onSubmit="return validasi();"> 
    <input type="hidden" name="login" id="login" value="<?=$login ?>" />
    <table border="1" width="95%" cellpadding="0" cellspacing="0" align="center" class="tab">
    <!-- TABLE CONTENT -->
    <tr height="25">
    	<td class="header" colspan="2" align="center">Ubah Password <?=$title?></td>
    </tr>
    <tr>
        <td width="110" style="padding:2px"><strong>Nama</strong></td>
        <td style="padding:2px">
        	<table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
               	  <?php if ($login!='landlord' && $login!='LANDLORD'){ ?>
                        <input type="text" name="nip" id="nip" size="10" readonly="readonly" value="<?=$nip ?>" class="inptxt" />
                    <?php } ?>
                </td>
                <td><input type="text" name="nama" id="nama" size="20" readonly="readonly" value="<?=$nama ?>" class="inptxt" /></td>
              </tr>
            </table>       
        </td>
    </tr>
    <tr>
    	<td style="padding:2px"><strong>Password Lama</strong></td>
        <td style="padding:2px"><input name="passlama" type="password" class="inptxt" id="passlama" onFocus="panggil('passlama')" onKeyPress="return focusNext('pass1', event)" size="20"/></td>
    </tr>
    <tr>
    	<td style="padding:2px"><strong>Password</strong></td>
        <td style="padding:2px"><input name="pass1" type="password" class="inptxt" id="pass1" onFocus="panggil('pass1')" onKeyPress="return focusNext('pass2', event)" size="20"/></td>
    </tr>
    <tr>
    	<td style="padding:2px"><strong>Konfirmasi</strong></td>
        <td style="padding:2px"><input name="pass2" type="password" class="inptxt" id="pass2" onFocus="panggil('pass2')" onKeyPress="return focusNext('simpan', event)" size="20"/></td>
    </tr>
    <tr>
        <td height="50" colspan="2" align="center" style="padding:2px">
      		<input class="btnfrm" type="submit" value="Simpan" name="simpan" id="simpan">
      &nbsp;
        	<input class="btnfrm" type="button" value="Tutup" onClick="window.close();">        
      	</td>
    </tr>
    </table>
    </form>
<?php if (strlen($mysqli_ERROR_MSG) > 0) { ?>
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