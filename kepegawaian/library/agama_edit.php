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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');

$replid=$_REQUEST['replid'];
OpenDb();
$sql="SELECT * from jbsumum.agama where replid = $replid";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$agama = $row['agama'];
if (isset($_REQUEST['agama']))
	$agama = CQ($_REQUEST['agama']);
$urutan = $row['urutan'];
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$cek = 0;
$ERROR_MSG = "";
if (isset($_POST['simpan'])) {
	OpenDb();
	$sql_cek="SELECT * from jbsumum.agama where agama='$agama' AND replid <> $replid";
	$hasil=QueryDb($sql_cek);
	
	$sql1 = "SELECT * FROM jbsumum.agama WHERE urutan = '$urutan' AND replid <> $replid";
	$result1 = QueryDb($sql1);
	
	if (mysqli_num_rows($hasil)>0){
		CloseDb();
		$ERROR_MSG = "Agama $agama sudah digunakan!";
	} else if (mysqli_num_rows($result1) > 0) {		
		CloseDb();
		$ERROR_MSG = "Urutan $urutan sudah digunakan!";	
		$cek = 1;
    } else{
		$sql = "UPDATE jbsumum.agama SET agama='$agama',urutan='$urutan' WHERE replid = '".$replid."'";
		$result = QueryDb($sql);
		if ($result) { ?>
		<script language="javascript">
            opener.refresh('<?=$_REQUEST['agama']?>');
            window.close();
        </script>
<?php 
		}
	}
}

CloseDb();

switch ($cek) {
	case 0 : $input_awal = "onload=\"document.getElementById('agama').focus()\"";
		break;
	case 1 : $input_awal = "onload=\"document.getElementById('urutan').focus()\"";
		break;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function validate() {
	return validateEmptyText('agama', 'Nama Agama') && 
		   validateEmptyText('urutan', 'Urutan Agama') &&
		   validateNumber('urutan', 'Urutan Agama') &&
		   validateMaxText('agama', 20, 'Nama Agama');
}

function focusNext(elemName, evt) {
	var aktif = 1;
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
	var lain = new Array('agama','urutan');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

</script>
<title>JIBAS SIMAKA [Ubah Nama Agama]</title>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" <?=$input_awal?>>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Agama :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->

  	<form name="main" method="post" onSubmit="return validate();">
    <input type="hidden" name="replid" id="replid" value="<?=$replid?>">    
    <table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
	<!-- TABLE CONTENT -->
    <tr>
        <td width="35%"><strong>Nama Agama</strong></td>
        <td>
        <input type="text" name="agama" id="agama" maxlength="20" size="30" value="<?=$agama?>" onFocus="panggil('agama')" onKeyPress="return focusNext('urutan', event)">
        
        </td>
    </tr>
   <tr>
        <td><strong>Urutan</strong></td>
        <td>
        <input type="text" name="urutan" id="urutan" maxlength="1" size="2" value="<?=$urutan?>" onFocus="showhint('Urutan tampil Agama!', this, event, '120px');panggil('urutan')" onKeyPress="return focusNext('Simpan', event)">
        </td>
    </tr>    
    <tr>
        <td align="center" colspan="2">
        	<input class="but" type="submit" value="Simpan" id="Simpan" name="simpan" onFocus="panggil('Simpan')">
            <input class="but" type="button" value="Tutup" onClick="window.close();">
        </td>
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
<!-- Tamplikan error jika ada -->
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

</body>
</html>