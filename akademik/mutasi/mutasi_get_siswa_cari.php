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
require_once('../include/config.php');
require_once('../include/db_functions.php');
//require_once('../library/departemen.php');
?>
<table width="100%" id="table" class="tab" align="center" cellpadding="2" cellspacing="0">
<tr height="30">
	<td class="header" width="7%" align="center" height="30">No</td>
    <td class="header" width="15%" align="center" height="30">N I S</td>
    <td class="header" height="30">Nama</td>
	<td class="header" height="30">Kelas</td>
</tr>
<?php

OpenDb();
$nama = $_REQUEST['nama'];
$nis = $_REQUEST['nis'];
$departemen = $_REQUEST['departemen'];

if ((strlen((string) $nama) > 0) && (strlen((string) $nip) > 0))
	$sql = "SELECT s.nis, s.nama, k.kelas FROM jbsakad.siswa s,jbsakad.kelas k ,jbsakad.tingkat ti,jbsakad.tahunajaran ta WHERE s.nama LIKE '%$nama%' AND s.nis LIKE '%$nis%' AND k.replid=s.idkelas AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ta.departemen='$departemen' AND ti.departemen='$departemen' ORDER BY s.nama"; 
else if (strlen((string) $nama) > 0)
	$sql = "SELECT s.nis, s.nama, k.kelas FROM jbsakad.siswa s,jbsakad.kelas k ,jbsakad.tingkat ti,jbsakad.tahunajaran ta  WHERE s.nama LIKE '%$nama%' AND k.replid=s.idkelas AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ta.departemen='$departemen' AND ti.departemen='$departemen' ORDER BY s.nama"; 
else if (strlen((string) $nis) > 0)
	$sql = "SELECT s.nis, s.nama, k.kelas FROM jbsakad.siswa s,jbsakad.kelas k ,jbsakad.tingkat ti,jbsakad.tahunajaran ta  WHERE k.replid=s.idkelas AND s.nis LIKE '%$nis%' AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ta.departemen='$departemen' AND ti.departemen='$departemen' ORDER BY s.nama"; 
else if ((strlen((string) $nama) == 0) || (strlen((string) $nis) == 0))
	$sql = "SELECT s.nis, s.nama, k.kelas FROM jbsakad.siswa s,jbsakad.kelas k,jbsakad.tingkat ti,jbsakad.tahunajaran ta WHERE k.replid=s.idkelas AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ta.departemen='$departemen' AND ti.departemen='$departemen' ORDER BY s.nama"; 
$result = QueryDb($sql);
$cnt = 1;
	
while($row = mysqli_fetch_row($result)) { 
if ($cnt%2==0)
	$bg="bgcolor='#e7e7cf'";
if ($cnt%2==1)
	$bg="bgcolor='#ffffff'";
?>
<tr>
	<td align="center" height="25" <?=$bg?> onClick="ambilcari('<?=$row[0]?>')" style="cursor:pointer"><?=$cnt ?></td>
    <td align="center" height="25" <?=$bg?> onClick="ambilcari('<?=$row[0]?>')" style="cursor:pointer"><?=$row[0] ?></td>
    <td height="25" <?=$bg?> onClick="ambilcari('<?=$row[0]?>')" style="cursor:pointer"><?=$row[1] ?></td>
	<td height="25" <?=$bg?> onClick="ambilcari('<?=$row[0]?>')" style="cursor:pointer"><?=$row[2] ?></td>
</tr>
<?php
$cnt++;
}
CloseDb();	
if (mysqli_num_rows($result) == 0) { ?>
<tr height="26"><td colspan="4" align="center"><em>Tidak ditemukan data</em></td></tr>
<?php } ?>

</table>