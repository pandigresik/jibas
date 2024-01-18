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
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$nis = "";
if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];
	
$nama = "";
if (isset($_REQUEST['nama']))
	$nama = $_REQUEST['nama'];
	
?>
<table border="0" cellspacing="3" cellpadding="0" width="100%">
<tr>
    <td width="18%">Dept:</td>
    <td>
    <input type="text" name="departemen2" size="15" id="departemen2" value="<?=$departemen ?>" readonly="readonly" style="background-color:#CCCC00" />
    </td>
</tr>    
<tr>
    <td>NIS:</td>
    <td>
    <input type="text" id="nis" name="nis" size="15">
    </td>
</tr>    
<tr>
    <td>Nama:</td>
    <td>
    <input type="text" id="nama" name="nama" size="15">&nbsp;<input class="but" type="button" name="cari" id="cari" value="Cari" onclick="do_carisiswa()">
    </td>
</tr>    
</table>

<table border="0" id="table" class="tab" cellpadding="2" cellspacing="0" width="100%">
<tr height="20">
    <td class="header" width="5%" align="center">No</td>
    <td class="header" width="30%" align="center">NIS</td>
    <td class="header">Nama</td>
</tr>
<?php

$nis = trim((string) $nis);
$nama = trim((string) $nama);
	
if ((strlen($nis) > 0) && (strlen($nama) > 0))
	$sql = "SELECT s.nis as nis, s.nama as nama FROM jbsakad.siswa s,jbsakad.kelas k, jbsakad.tahunajaran t, jbsakad.tingkat ti WHERE s.nis LIKE '%$nis%' AND s.nama LIKE '%$nama%' AND ti.departemen='$departemen' AND t.departemen='$departemen' AND k.idtingkat=ti.replid AND k.idtahunajaran=t.replid AND k.replid=s.idkelas ORDER BY s.nama";
else if (strlen($nis) > 0)
	$sql = "SELECT s.nis as nis, s.nama as nama FROM jbsakad.siswa s,jbsakad.kelas k, jbsakad.tahunajaran t, jbsakad.tingkat ti WHERE s.nis LIKE '%$nis%' AND ti.departemen='$departemen' AND t.departemen='$departemen' AND k.idtingkat=ti.replid AND k.idtahunajaran=t.replid AND k.replid=s.idkelas ORDER BY s.nama";
else if (strlen($nama) > 0)
	$sql = "SELECT s.nis as nis, s.nama as nama FROM jbsakad.siswa s,jbsakad.kelas k, jbsakad.tahunajaran t, jbsakad.tingkat ti WHERE s.nama LIKE '%$nama%' AND ti.departemen='$departemen' AND t.departemen='$departemen' AND k.idtingkat=ti.replid AND k.idtahunajaran=t.replid AND k.replid=s.idkelas ORDER BY s.nama";
else
	exit();
	
OpenDb();	
$result = QueryDb($sql);
$no = 0;
while ($row = mysqli_fetch_array($result)) {
?>
<input type="hidden" name="isnew<?=$no?>" id="isnew<?=$no?>" value="<?=$isnew ?>" />
<tr height="25">
    <td align="center"><font size="1"><?=++$no ?></font></td>
    <td align="center"><font size="1">
    <a href="JavaScript:show_bayar('<?=$row['nis']?>')"><?=$row['nis'] ?></a></font></td>
    <td><font size="1"><?=$row['nama'] ?></font></td>
</tr>
<?php
}
?>
</table>
<?php  CloseDb() ?>