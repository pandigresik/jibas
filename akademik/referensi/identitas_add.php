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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');
$departemen = $_REQUEST['departemen'];
$title = "Sekolah";
if ($departemen=='yayasan')
	$title = "";
if (isset($_REQUEST['Simpan'])) {
	OpenDb();
		$sql1="SELECT * FROM jbsumum.identitas WHERE departemen='$departemen'";
		$result1=QueryDb($sql1);
		$row1 = mysqli_fetch_array($result1);
		$nama = CQ($_REQUEST['nama']);
		$situs = CQ($_REQUEST['situs']);
		$email = CQ($_REQUEST['email']);
		$alamat1 = CQ($_REQUEST['alamat1']);
		$alamat2 = CQ($_REQUEST['alamat2']);
		$tlp1 = CQ($_REQUEST['tlp1']);
		$tlp2 = CQ($_REQUEST['tlp2']);
		$tlp3 = CQ($_REQUEST['tlp3']);
		$tlp4 = CQ($_REQUEST['tlp4']);
		$fax1 = CQ($_REQUEST['fax1']);
		$fax2 = CQ($_REQUEST['fax2']);
		if (mysqli_num_rows($result1) > 0) {
			$sql = "UPDATE jbsumum.identitas SET nama='$nama', situs='$situs', email='$email', alamat1='$alamat1', alamat2='$alamat2', telp1='$tlp1', telp2='$tlp2', telp3='$tlp3', telp4='$tlp4', fax1='$fax1', fax2='$fax2' WHERE departemen = '".$departemen."'";
		} else {
			$sql = "INSERT INTO jbsumum.identitas SET nama='$nama', situs='$situs', email='$email', alamat1='$alamat1', alamat2='$alamat2', telp1='$tlp1', telp2='$tlp2', telp3='$tlp3', telp4='$tlp4', fax1='$fax1', fax2='$fax2', departemen='$departemen'";
		}
		//echo $sql; exit;
		$result = QueryDb($sql);
		CloseDb();			
		if ($result) { 	
		?>
			<script language="javascript">				
				opener.getfresh();
				window.close();
			</script>
<?php 	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Input Identitas Sekolah]</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function validate() {
	return validateEmptyText('nama', 'Nama <?=$title?>') &&
			cekEmail();
}

function cekEmail() {
	if (!validateEmail("email") ) { 
		alert( "Email yang Anda masukkan bukan merupakan alamat email!" );
		document.getElementById('email')focus();
		return false;	
	}	
	return true;
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
	var lain = new Array('nama','alamat1','alamat2','tlp1','tlp2','tlp3','tlp4','fax1','fax2','situs','email');
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"  style="background-color:#dcdfc4" onLoad="document.getElementById('nama').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Identitas Sekolah :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
<form name="main" onSubmit="return validate()" method="post">
<input type="hidden" name="departemen" id="departemen" size="80" maxlength="250" value="<?=$departemen ?>"/>
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="120"><strong>Nama</strong></td>
    <td><input type="text" name="nama" id="nama" size="80" maxlength="250" value="<?=$nama ?>" onKeyPress="return focusNext('alamat1', event)" onFocus="panggil('nama')"/></td>
</tr>
<tr>
	<td colspan="2">
    <table border="0" width="100%" align="center">
    <tr><td width="50%">
	<fieldset><legend><b>Lokasi 1</b></legend>
    <table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
    <tr>	
        <td valign="top">Alamat</td>
        <td>
            <textarea name="alamat1" id="alamat1" rows="3" style="width:190px" onKeyPress="return focusNext('tlp1', event)" onFocus="panggil('alamat1')" ><?=$alamat1 ?></textarea>
        </td>
    </tr>    
    <tr>
        <td>No Telp1</td>
        <td><input type="text" name="tlp1" id="tlp1" size="30" maxlength="50" value="<?=$tlp1 ?>" onKeyPress="return focusNext('tlp2', event)" onFocus="panggil('tlp1')"/>
        </td>
    </tr>
    <tr>
        <td>No Telp2</td>
        <td><input type="text" name="tlp2" id="tlp2" size="30" maxlength="50" value="<?=$tlp2 ?>"onKeyPress="return focusNext('fax1', event)" onFocus="panggil('tlp2')"/>
        </td>
    </tr>
    <tr>
        <td>No Fax</td>
        <td><input type="text" name="fax1" id="fax1" size="30" maxlength="50" value="<?=$fax1 ?>"onKeyPress="return focusNext('alamat2', event)" onFocus="panggil('fax1')"/>
        </td>
    </tr>
   	</table>
	</fieldset>
	</td><td>
    <fieldset><legend><b>Lokasi 2</b></legend>
    <table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
    <tr>	
        <td valign="top">Alamat</td>
        <td>
            <textarea name="alamat2" id="alamat2" rows="3" style="width:190px" onKeyPress="return focusNext('tlp3', event)" onFocus="panggil('alamat2')"><?=$alamat3 ?></textarea>
        </td>
    </tr>   
    <tr>
        <td>No Telp1</td>
        <td><input type="text" name="tlp3" id="tlp3" size="30" maxlength="50" value="<?=$tlp3 ?>" onKeyPress="return focusNext('tlp4', event)" onFocus="panggil('tlp3')"/>
        </td>
    </tr>
    <tr>
        <td>No Telp2</td>
        <td><input type="text" name="tlp4" id="tlp4" size="30" maxlength="50" value="<?=$tlp4 ?>" onKeyPress="return focusNext('fax2', event)" onFocus="panggil('tlp4')"/>
        </td>
    </tr>
     <tr>
        <td>No Fax</td>
        <td><input type="text" name="fax2" id="fax2" size="30" maxlength="50" value="<?=$fax2 ?>" onKeyPress="return focusNext('situs', event)" onFocus="panggil('fax2')"/>
        </td>
    </tr>
   	</table>
	</fieldset>
    </td>
    </tr>    
    </table>
    </td>
</tr>
<tr>
	<td>Website</td>
	<td><input type="text" name="situs" id="situs" size="80" maxlength="100" value="<?=$situs ?>" onKeyPress="return focusNext('email', event)" onFocus="panggil('situs')"/>
    </td>
</tr>
<tr>
	<td><strong>Email</strong></td>
	<td><input type="text" name="email" id="email" size="80" maxlength="100" value="<?=$email?>" onKeyPress="return focusNext('Simpan', event)"/>
    </td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but" onFocus="panggil('Simpan')" />&nbsp;    
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />
    </td>
</tr>
<!-- END OF TABLE CONTENT -->
</table>
</form>
</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>

<!-- Tamplikan error jika ada -->
<?php if (strlen((string) $ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>
</body>
</html>