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
require_once("../include/sessionchecker.php");
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

OpenDb();

$id = $_REQUEST['id'];

if (isset($_REQUEST['btSimpan'])) 
{
	$eselon = $_REQUEST['cbEselon'];
	$jabatan = $_REQUEST['txJabatan'];
	$singkatan = strtoupper((string) $_REQUEST['txSingkatan']);
	$satker = $_REQUEST['cbSatKer'];
	if ($eselon != "Eselon I")
		$sql = "UPDATE jabatan SET jabatan='$jabatan', singkatan='$singkatan', satker='$satker', eselon='$eselon' WHERE replid = $id";
	else
		$sql = "UPDATE jabatan SET jabatan='$jabatan', singkatan='$singkatan', satker=NULL, eselon='$eselon' WHERE replid = $id";
	QueryDb($sql);
	CloseDb();
	?>
	<script language="javascript">
		opener.RefreshPage(<?=$id?>);
		window.close();
    </script>    
    <?php
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ubah Jabatan</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function TambahJabatan(rootid) {
	var addr = "jabataninput.php";
    newWindow(addr, 'JabatanInput','400','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function CheckEselon() {
	var es = document.getElementById('cbEselon').value;
	document.getElementById('cbSatKer').disabled = (es == "Eselon I");
}
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff">

<?php
OpenDb();

$sql = "SELECT singkatan, jabatan, satker, eselon FROM jabatan WHERE replid=$id";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$sing = $row[0];
$jab  = $row[1];
$sat  = $row[2];
$eselon = $row[3];
?>
<form name="main" method="post">
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<table border="0" cellpadding="0" cellspacing="5" width="100%" id="table56">
<tr>
	<td class="header" colspan="2" align="center">Ubah Jabatan</td>
</tr>
<tr>
	<td align="right" width="120">Jabatan :</td>
    <td align="left"><input type="text" name="txJabatan" id="txJabatan" value="<?=$jab?>" size="50" maxlength="255" /></td>
</tr>
<tr>
	<td align="right">Singkatan :</td>
    <td align="left"><input type="text" name="txSingkatan" id="txSingkatan" value="<?=$sing?>" size="50" maxlength="255" /></td>
</tr>
<tr>
	<td align="right">Eselon :</td>
    <td align="left">
    <select name="cbEselon" id="cbEselon" onKeyPress="return focusNext('txSingkatan', event)" onChange="CheckEselon()">
<?php $sql = "SELECT eselon FROM eselon ORDER BY urutan";
	$result = QueryDb($sql);
	while ($row = mysqli_fetch_row($result)) { ?>    
    	<option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $eselon)?> ><?=$row[0]?></option>
<?php } ?>    
    </select>&nbsp;
    </td>
</tr>
<tr>
	<td align="right">Satuan Kerja :</td>
    <td align="left">
<?php 	$disabled = "";
	if ($eselon == "Eselon I")
		$disabled = "disabled"; ?>    
    <select name="cbSatKer" id="cbSatKer" <?=$disabled?>>
<?php 		 $sql = "SELECT satker, nama FROM satker";
		 $result = QueryDb($sql);
		 while ($row = mysqli_fetch_row($result)) { ?>
         	<option value="<?=$row[0]?>" <?=StringIsSelected($sat, $row[0])?> ><?=$row[1]?></option>

<?php 	 } ?>        
    </select>
    </td>
</tr>
<tr>
	<td colspan="2" align="center" bgcolor="#EAEAEA">
    <input type="submit" class="but" name="btSimpan" value="Simpan" />&nbsp;
    <input type="button" class="but" name="btClose" value="Tutup" onClick="window.close()" />
    </td>
</tr>
</table>
</form>
<?php
CloseDb();
?>
</body>
</html>