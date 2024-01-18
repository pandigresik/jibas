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
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

OpenDb();

$rootid = $_REQUEST['rootid'];
$tingkat = $_REQUEST['tingkat'];
$jenis = $_REQUEST['jenis'];

if (isset($_REQUEST['btSimpan'])) 
{
	$diklat = $_REQUEST['txDiklat'];
	$sql = "INSERT INTO diklat SET rootid=$rootid, tingkat=$tingkat, diklat='$diklat', jenis='$jenis'";
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
<title>Tambah Diklat</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff">

<?php
OpenDb();
?>
<form name="main" method="post">
<input type="hidden" name="rootid" id="rootid" value="<?=$rootid?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>" />
<input type="hidden" name="jenis" id="jenis" value="<?=$jenis?>" />
<table border="0" cellpadding="0" cellspacing="5" width="100%" id="table56">
<tr>
	<td class="header" colspan="2" align="center">Tambah Diklat</td>
</tr>
<?php 
if ($rootid != 0) { 
	$sql = "SELECT diklat FROM diklat WHERE replid = $rootid";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$diklat = $row[0];
?>
<tr>
	<td align="right" width="120">Diklat :</td>
    <td align="left"><input type="text" name="txParentDiklat" id="txParentDiklat" readonly size="30" maxlength="255" value="<?=$diklat?>" /></td>
</tr>
<?php } ?>
<tr>
	<td align="right" width="120">Sub Diklat :</td>
    <td align="left"><input type="text" name="txDiklat" id="txDiklat" size="30" maxlength="255" /></td>
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