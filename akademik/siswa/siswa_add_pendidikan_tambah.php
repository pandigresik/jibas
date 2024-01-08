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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
require_once('../cek.php');

$pendidikan = CQ($_REQUEST['pendidikan']);
$ERROR_MSG = "";
if (isset($_REQUEST['simpan'])) {
	OpenDb();
	
	$sql_cek="SELECT * FROM jbsumum.tingkatpendidikan where pendidikan='$pendidikan'";
	$hasil=QueryDb($sql_cek);
	if (mysqli_fetch_array($hasil)){
		CloseDb();
		$ERROR_MSG = "Pendidikan $pendidikan sudah digunakan!";
	} else {
		$sql = "INSERT INTO jbsumum.tingkatpendidikan SET pendidikan='$pendidikan'";
		$result = QueryDb($sql);	
		if ($result) { 
	?>
		<script language="javascript">
			opener.document.location.href="siswa_add_pendidikan.php?pendidikan=<?=$pendidikan?>";
			window.close();
		</script> 
<?php 	
		}
	}
}
CloseDb(); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<title>JIBAS SIMAKA [Tambah Tingkat Pendidikan]</title>
<script language="javascript">
function cek() {	
	var pendidikan = document.getElementById('pendidikan').value();
	if (pendidikan.length == 0) {
		alert('Anda belum memasukkan nama pendidikan');
		return false;
	}
	if (pendidikan.length > 20) {
		alert('Nama pendidikan tidak boleh lebih dari 20 karakter');
		return false;
	}
	return true;
	//document.location.href="siswa_add_pendidikan_tambah.php?pendidikan="+pendidikan;
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
	var lain = new Array('pendidikan');
	
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

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('pendidikan').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Tambah Tingkat Pendidikan :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->

   <form  name="main" method="post" onSubmit="return cek();">    
   <table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
	<!-- TABLE CONTENT -->
    <tr>
        <td><strong>Pendidikan</strong></td>
        <td><input name="pendidikan" id="pendidikan" maxlength="20" size="30" value="<?=$pendidikan?>" onKeyPress="return focusNext('simpan', event)" onFocus="panggil('pendidikan')"></td>
    </tr>   
    <tr>
        <td align="center" colspan="2">
        	<input class="but" type="submit" value="Simpan" name="simpan" id="simpan" onfocus = "panggil('simpan')">
            <input class="but" type="button" value="Tutup" onClick="window.close();">
        </td>
    </tr>
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
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>
</body>
</html>