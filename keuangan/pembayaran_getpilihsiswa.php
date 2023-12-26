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
	
$idangkatan = 0;
if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];

$idkelas = 0;
if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];

OpenDb(); 
?>
<table border="0" cellspacing="3" cellpadding="0" width="80%">
<tr>
    <td width="100">Departemen:</td>
    <td>
    <input type="text" name="departemen" size="15" id="departemen" value="<?=$departemen ?>" readonly="readonly" style="background-color:#CCCC00" />
    </td>
</tr>    
<tr>
    <td width="100">Angkatan:</td>
    <td>
    <select id="idangkatan" name="idangkatan" style="width:150px" onchange="change_ang()">
<?php      $sql = "SELECT replid, angkatan FROM jbsakad.angkatan WHERE departemen = '$departemen' ORDER BY replid";
        $result = QueryDb($sql);
        while($row = mysqli_fetch_row($result)) {
            if ($idangkatan == 0)
                $idangkatan = $row[0]; ?>
            <option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $idangkatan)?> > <?=$row[1]?></option>
<?php     } ?>
    </select>
    </td>
</tr>    
<tr>
    <td width="100">Kelas:</td>
    <td>
    <select id="idkelas" name="idkelas" style="width:150px" onchange="change_kel()">
<?php      $sql = "SELECT DISTINCT idkelas, kelas as namakelas FROM jbsakad.siswa, jbsakad.kelas, jbsakad.tingkat, jbsakad.tahunajaran  WHERE jbsakad.siswa.idkelas = jbsakad.kelas.replid AND idangkatan='$idangkatan' AND jbsakad.kelas.idtahunajaran = jbsakad.tahunajaran.replid AND jbsakad.kelas.idtingkat = jbsakad.tingkat.replid ORDER BY idkelas";
        $result = QueryDb($sql);
        while($row = mysqli_fetch_row($result)) {
            if ($idkelas == 0)
                $idkelas = $row[0];  ?>
            <option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $idkelas)?> > <?=$row[1]?></option>
<?php      } ?>
    </select>
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
$sql = "SELECT nis, nama FROM jbsakad.siswa WHERE idkelas = '$idkelas' ORDER BY nama";
$result = QueryDb($sql);
$no = 0;
while ($row = mysqli_fetch_array($result)) {
?>
<input type="hidden" name="isnew<?=$no?>" id="isnew<?=$no?>" value="<?=$isnew ?>" />
<tr height="25">
    <td align="center"><font size="1"><?=++$no ?></font></td>
    <td align="center"><font size="1">
    <a href="JavaScript:show_bayar('<?=$row['nis'] ?>')"><?=$row['nis'] ?></a></font></td>
    <td><font size="1"><?=$row['nama'] ?></font></td>
</tr>
<?php
}
?>
</table>
<?php  CloseDb() ?>