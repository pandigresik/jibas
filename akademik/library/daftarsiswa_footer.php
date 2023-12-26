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
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$tahunajaran ="";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
$kelas = "";
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daftar Pegawai</title>
<script language="javascript" src="../script/string.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript">

</script>
</head>

<body background="">
<form name="main" enctype="multipart/form-data">
<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
<table border="0" cellpadding="0" cellspacing="5" width="100%" align="center">
<tr><td align="left">
<!-- BOF CONTENT -->

<?php 	OpenDb();
	$sql = "SELECT s.nis, s.nama, k.kelas FROM siswa s, kelas k, tingkat t WHERE t.departemen = '$departemen' AND s.idkelas = k.replid AND k.idtingkat = t.replid AND t.replid = $tingkat AND k.idtahunajaran = $tahunajaran AND s.aktif=1 AND s.idkelas = $kelas ORDER BY nama"; 
	$result = QueryDb($sql);
	$jum = mysqli_num_rows($result);
		if ($jum > 0) {
?>
<table width="100%" id="table" class="tab" align="center" cellpadding="2" cellspacing="0">
<tr height="30">
		<td class="header" width="7%" align="center">No</td>
    <td class="header" width="15%" align="center">N I S</td>
    <td class="header" align="center">Nama</td>
    <!--<td class="header" width="10%" align="center">Kelas</td>-->
    <td class="header" width="10%" align="center">&nbsp;</td>
</tr>
<?php
	$cnt = 1;	
	while($row = mysqli_fetch_row($result)) { 
?>
<tr>
	<td align="center" onclick="parent.pilih('<?=$row[0]?>', '<?=$row[1]?>')" style="cursor:pointer"><?=$cnt ?></td>
    <td align="center" onclick="parent.pilih('<?=$row[0]?>', '<?=$row[1]?>')"  style="cursor:pointer"><?=$row[0] ?></td>
    <td onclick="parent.pilih('<?=$row[0]?>', '<?=$row[1]?>')"  style="cursor:pointer"><?=$row[1] ?></td>
    <!--<td align="center"><?=$row[2] ?></td>-->
    <td align="center" onclick="parent.pilih('<?=$row[0]?>', '<?=$row[1]?>')"  style="cursor:pointer">
    <input type="button" name="pilih" class="but" id="pilih" value="Pilih" onclick="parent.pilih('<?=$row[0]?>', '<?=$row[1]?>')" />
    </td>
</tr>
<?php $cnt++; 
	} 
?> 
</table>
<script language="javascript">
	Tables('table', 1, 0);
</script>
<!-- EOF CONTENT -->

<?php 
} else { 
	echo "<strong><font color='red'>Tidak ditemukan adanya data</strong></td></tr>";
}		
?>
</td></tr>
<tr height="26">
	<td colspan="5" align="center" bgcolor="">
    <input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="parent.tutup()" /></td>
</tr>


</table>
</form>

</body>
</html>