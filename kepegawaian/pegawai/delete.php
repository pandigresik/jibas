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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../include/sessioninfo.php");
require_once('../include/theme.php');

$id = $_REQUEST['id'];
$tabel = $_REQUEST['tabel'];
$judul = $_REQUEST['judul'];

if (isset($_REQUEST['Hapus'])) {
	OpenDb();
	
	$user = getUserId();
	$alasan = $_REQUEST['alasan'];
	
	
	$sql = "INSERT INTO dataaction SET tabel='$tabel', id=$id, op='D', user='$user', alasan='$alasan'";
	QueryDb($sql);
	
	$sql = "DELETE FROM $tabel WHERE replid=$id";	
	QueryDb($sql);
	
	CloseDb(); ?>
    <script language="javascript">
		opener.Refresh();
		window.close();
    </script>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hapus Riwayat <?=$judul?></title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/rupiah.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function validate() {
	return validateEmptyText('alasan', 'Alasan Penghapusan Data <?=$judul?> Pegawai') && 
		   confirm("Apakah anda yakin akan menghapus data ini?");
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
</script>
</head>

<body>
<form name="main" method="post" onSubmit="return validate()">
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<input type="hidden" name="tabel" id="tabel" value="<?=$tabel?>" />
<input type="hidden" name="judul" id="judul" value="<?=$judul?>" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr height="30">
	<td width="100%" class="header" align="center">Hapus Riwayat <?=$judul?></td>
</tr>
<tr>
	<td width="100%" align="center">
    
    <table border="0" cellpadding="0" cellspacing="5" width="100%">
    <tr>
    	<td align="right" valign="top"><strong>Alasan Penghapusan :</strong></td>
	    <td align="left" valign="top">
        <textarea id="alasan" name="alasan" rows="2" cols="50"><?=$alasan?></textarea>
        </td>
    </tr>
    <tr>
    	<td align="right" valign="top">&nbsp;</td>
	    <td align="left" valign="top">
        <input type="submit" value="Hapus" name="Hapus" style="color:#FF0000" class="but" />
        <input type="button" value="Tutup" onClick="window.close()" class="but" />
        </td> 
    </tr>
    </table>
    
    </td>
</tr>
</table>
</form>

</body>
</html>