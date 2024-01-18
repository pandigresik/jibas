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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cari Pegawai]</title>
<script language="javascript" src="../script/string.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript">
/*function validate() {
	var nama = '' + document.getElementById('nama').value;
	var nip = '' + document.getElementById('nip').value;
	nama = trim(nama);
	nip = trim(nip);
	
	return (nama.length != 0) || (nip.length != 0);
}*/

function pilih(nip, nama) {
	opener.acceptPegawai(nip, nama);
	window.close();
}
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onload="document.getElementById('NIP').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" height="500" valign="top">
    <!-- CONTENT GOES HERE //---> 
	

	<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center" >
	<tr height="25">
		<td class="header" colspan="2" align="center">Cari Pegawai</td>
	</tr>
	<form name="main">
	<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
	<tr>
    	<td>
		<strong>NIP</strong> <input type="text" name="nip" id="nip" value="<?=$_REQUEST['nip'] ?>" size="20" />&nbsp;
        <strong>Nama</strong> <input type="text" name="nama" id="nama" value="<?=$_REQUEST['nama'] ?>" size="25" />&nbsp;
     	<input type="submit" class="but" name="Submit" id="Submit" value="Cari" />        
       	</td>
    </tr>
	</form>	
	<tr>
		<td align="center" >
	<br />
<?php
OpenDb();
if (isset($_REQUEST['Submit'])) { 

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
} else {
	$sql = "SELECT nip, nama FROM jbssdm.pegawai ORDER BY nama"; 
}
$result = QueryDb($sql);	
CloseDb();
$jum = mysqli_num_rows($result);

if ($jum > 0) {
?>
<table width="100%" id="table" class="tab" align="center" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
<tr height="30">
	<td class="header" width="7%" align="center">No</td>
    <td class="header" width="15%" align="center">N I P</td>
    <td class="header" align="center">Nama</td>
    <td class="header" width="10%">&nbsp;</td>
</tr>
<?php

$cnt = 0;
while($row = mysqli_fetch_row($result)) { ?>
<tr>
	<td align="center" onclick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" style="cursor:pointer" title="Klik untuk memilih guru"><?=++$cnt ?></td>
    <td align="center" onclick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" style="cursor:pointer" title="Klik untuk memilih guru"><?=$row[0] ?></td>
    <td onclick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" style="cursor:pointer" title="Klik untuk memilih guru"><?=$row[1] ?></td>
    <td align="center" onclick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" style="cursor:pointer" title="Klik untuk memilih guru">
    <input type="button" name="pilih" class="but" id="pilih" value="Pilih" onclick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" />
    </td>
</tr>


<?php 
	} ?>
	</table>
<script language="javascript">
	Tables('table', 1, 0);
</script>
<?php } else { ?>
	<strong><font color="red">Tidak ditemukan adanya data</font></strong><br /><br />    
<?php } ?>
</td></tr>
<tr height="35">
	<td colspan="4" align="center">
    <input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="window.close()" /></td>
</tr>
</table>

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


</body>
</html>