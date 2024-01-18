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

$rootid = $_REQUEST['rootid'];

$title = "Tambah Jabatan";
$label = "Jabatan";
if ($rootid != 0) 
{
	$title = "Tambah Sub Jabatan";
	$label = "Sub Jabatan";
}

if (isset($_REQUEST['btSimpan'])) 
{
	$eselon = $_REQUEST['cbEselon'];
	$jabatan = $_REQUEST['txJabatan'];
	$singkatan = strtoupper((string) $_REQUEST['txSingkatan']);
	$satker = $_REQUEST['cbSatKer'];
	$sql = "INSERT INTO jabatan SET rootid=$rootid, eselon='$eselon', jabatan='$jabatan', singkatan='$singkatan', satker='$satker'";
	QueryDb($sql);
	CloseDb();
	?>
	<script language="javascript">
		opener.RefreshPage(<?=$rootid?>);
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
<title>Tambah Jabatan</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function TambahJabatan(rootid) {
	var addr = "jabataninput.php";
    newWindow(addr, 'JabatanInput','400','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff">

<?php
OpenDb();
?>
<form name="main" method="post">
<input type="hidden" name="rootid" id="rootid" value="<?=$rootid?>" />
<table border="0" cellpadding="0" cellspacing="5" width="100%" id="table56">
<tr>
	<td class="header" colspan="2" align="center"><?=$title?></td>
</tr>
<?php 
if ($rootid != 0) { 
	$sql = "SELECT singkatan FROM jabatan WHERE replid = $rootid";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$jab = $row[0]; ?>
<tr>
	<td align="right" width="120">Jabatan :</td>
    <td align="left"><input type="text" name="txParentJabatan" id="txParentJabatan" style="background-color:#CCCCCC" readonly size="30" maxlength="255" value="<?=$jab?>" /></td>
</tr>
<?php } ?>
<tr>
	<td align="right" width="120"><?=$label?> :</td>
    <td align="left"><input type="text" name="txJabatan" id="txJabatan" size="50" maxlength="255" /></td>
</tr>
<tr>
	<td align="right">Singkatan :</td>
    <td align="left"><input type="text" name="txSingkatan" id="txSingkatan" size="50" maxlength="255" /></td>
</tr>
<tr>
	<td align="right">Eselon :</td>
    <td align="left">
    <select name="cbEselon" id="cbEselon" onKeyPress="return focusNext('txSingkatan', event)">
<?php $sql = "SELECT eselon FROM eselon WHERE urutan >= 1 ORDER BY urutan";
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
    <select name="cbSatKer" id="cbSatKer">
<?php 		 $sql = "SELECT satker, nama FROM satker";
		 $result = QueryDb($sql);
		 while ($row = mysqli_fetch_row($result)) { ?>
         	<option value="<?=$row[0]?>"><?=$row[1]?></option>

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