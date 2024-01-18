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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cari Pegawai</title>
<script language="javascript" src="../script/string.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript">
function validate() {
	var nama = '' + document.getElementById('nama').value;
	var nip = '' + document.getElementById('nip').value;
	nama = trim(nama);
	nip = trim(nip);
	
	return (nama.length != 0) || (nip.length != 0);
}

function pilih(nip, nama) {
	parent.opener.acceptPegawai(nip, nama);
	window.close();
}
</script>
</head>

<body>

<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center" >
<tr><td>
<form name="main" onsubmit="return validate()">
<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
<font size="2"><strong>Cari Pegawai</strong></font><br />
Nama: <input type="text" name="nama" id="nama" value="<?=$_REQUEST['nama'] ?>" size="17" />&nbsp;&nbsp;
NIP: <input type="text" name="nip" id="nip" value="<?=$_REQUEST['nip'] ?>" size="17" />&nbsp;
<input type="submit" class="but" name="Submit" id="Submit" value="Cari" />&nbsp;
<input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="window.close()" />
</form>
</td></tr>

<tr><td>
<br />
<table width="100%" id="table" class="tab" align="center" cellpadding="2" cellspacing="0" bordercolor="#000000">
<tr height="30">
	<td class="header" width="7%" align="center">No</td>
    <td class="header" width="15%" align="center">N I P</td>
    <td class="header" >Nama</td>
    <td class="header" width="10%">&nbsp;</td>
</tr>
<?php
if (isset($_REQUEST['Submit'])) { 
OpenDb();
$nama = $_REQUEST['nama'];
$nip = $_REQUEST['nip'];

if ((strlen((string) $nama) > 0) && (strlen((string) $nip) > 0))
	$sql = "SELECT nip, nama FROM jbssdm.pegawai WHERE nama LIKE '%$nama%' AND nip LIKE '%$nip%' ORDER BY nama"; 
else if (strlen((string) $nama) > 0)
	$sql = "SELECT nip, nama FROM jbssdm.pegawai WHERE nama LIKE '%$nama%' ORDER BY nama"; 
else if (strlen((string) $nip) > 0)
	$sql = "SELECT nip, nama FROM jbssdm.pegawai WHERE nip LIKE '%$nip%' ORDER BY nama"; 
else if ((strlen((string) $nama) == 0) || (strlen((string) $nip) == 0))
	$sql = "SELECT nip, nama FROM jbssdm.pegawai ORDER BY nama"; 
$result = QueryDb($sql);
$cnt = 0;
while($row = mysqli_fetch_row($result)) { ?>
<tr>
	<td align="center"><?=++$cnt ?></td>
    <td align="center"><?=$row[0] ?></td>
    <td><?=$row[1] ?></td>
    <td align="center">
    <input type="button" name="pilih" class="but" id="pilih" value="Pilih" onclick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" />
    </td>
</tr>
<?php
}
CloseDb();
} else { 
	OpenDb();
$sql = "SELECT nip, nama FROM jbssdm.pegawai ORDER BY nama"; 
$result = QueryDb($sql);
$cnt = 0;
while($row = mysqli_fetch_row($result)) { ?>
<tr>
	<td align="center"><?=++$cnt ?></td>
    <td align="center"><?=$row[0] ?></td>
    <td><?=$row[1] ?></td>
    <td align="center">
    <input type="button" name="pilih" class="but" id="pilih" value="Pilih" onclick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" />
    </td>
</tr>
<?php 
}
}
CloseDb();	
if ($cnt == 0) { ?>
<tr height="26"><td colspan="4" align="center"><em>Tidak ditemukan data</em></td></tr>
<?php } ?>
<tr height="26">
	<td colspan="4" align="center" bgcolor="#999900"><input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="window.close()" /></td>
</tr>
</table>
</td></tr>

</table>

<script language="javascript">
	Tables('table', 1, 0);
	document.getElementById('nama').focus();
</script>
</body>
</html>