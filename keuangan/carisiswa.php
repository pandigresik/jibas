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
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cari Siswa</title>
<script language="javascript" src="script/string.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript">
function validate() {
	var nama = '' + document.getElementById('nama').value;
	var nis = '' + document.getElementById('nis').value;
	nama = trim(nama);
	nis = trim(nis);
	
	return (nama.length != 0) || (nis.length != 0);
}

function pilih(nis, nama) {
	opener.acceptCari(nis, nama, <?=$flag?>);
	window.close();
}

function change_dep() {
	var nama = '' + document.getElementById('nama').value;
	var nis = '' + document.getElementById('nis').value;
	var dep = '' + document.getElementById('departemen').value;
	nama = escape(nama);
	nis = escape(nis);
	
	document.location.href = 'carisiswa.php?departemen='+departemen+"&nis="+nis+"&nama="+nama;
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr><td align="left">
<!-- BOF CONTENT -->

<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center" background="images/bttablelong.png">
<tr><td>
<form name="main" onsubmit="return validate()">
<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
<div align="right">
<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Cari Siswa</font><br />
</div>
<!--Departemen:--> 
<select name="departemen" id="departemen" onchange="change_dep()" style="visibility:hidden;">
<?php
OpenDb();
$dep = getDepartemen(getAccess());
foreach($dep as $value) {
?>
	<option value="<?=$value ?>" <?=StringIsSelected($departemen, $value)?> ><?=$value ?></option>
<?php
}
CloseDb();
?>
</select><br />
Nama: <input type="text" name="nama" id="nama" value="<?=$_REQUEST['nama'] ?>" size="15" />&nbsp;&nbsp;
NIS: <input type="text" name="nis" id="nis" value="<?=$_REQUEST['nis'] ?>" size="15" />&nbsp;
<input type="submit" class="but" name="Submit" id="Submit" value="Cari" />&nbsp;
<input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="window.close()" />
</form>
</td></tr>

<tr><td>
<br />
<table width="100%" id="table" class="tab" align="center" cellpadding="2" cellspacing="0">
<tr height="30">
	<td class="header" width="7%" align="center">No</td>
    <td class="header" width="15%" align="center">N I S</td>
    <td class="header" >Nama</td>
    <td class="header" width="10%">&nbsp;</td>
</tr>
<?php if (isset($_REQUEST['Submit'])) { 
OpenDb();
$nama = $_REQUEST['nama'];
$nis = $_REQUEST['nis'];

if ((strlen((string) $nama) > 0) && (strlen((string) $nis) > 0))
	$sql = "SELECT nis, nama FROM jbsakad.siswa WHERE nama LIKE '%$nama%' AND nis LIKE '%$nis%' AND aktif = 1 ORDER BY nama"; 
else if (strlen((string) $nama) > 0)
	$sql = "SELECT nis, nama FROM jbsakad.siswa WHERE nama LIKE '%$nama%' AND aktif = 1 ORDER BY nama"; 
else if (strlen((string) $nis) > 0)
	$sql = "SELECT nis, nama FROM jbsakad.siswa WHERE nis LIKE '%$nis%' AND aktif = 1 ORDER BY nama"; 
$result = QueryDb($sql);
$cnt = 0;
while($row = mysqli_fetch_row($result)) { ?>
<tr>
	<td align="center"><?=++$cnt ?></td>
    <td align="center"><?=$row[0] ?></td>
    <td><?=$row[1] ?></td>
    <td align="center">
    <input type="button" name="pilih" class="but" id="pilih" value="Pilih" onclick="pilih('<?=$row[0]?>')" />
    </td>
</tr>
<?php
}
CloseDb();
?>
<?php if ($cnt == 0) { ?>
<tr height="26"><td colspan="4" align="center"><em>Tidak ditemukan data</em></td></tr>
<?php } ?>
<tr height="26">
	<td colspan="4" align="center" bgcolor="#999900"><input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="window.close()" /></td>
</tr>
</table>
</td></tr>
<?php } ?>
</table>

<!-- EOF CONTENT -->
</td></tr>
</table>
<script language="javascript">
	Tables('table', 1, 0);
	document.getElementById('nama').focus();
</script>
</body>
</html>