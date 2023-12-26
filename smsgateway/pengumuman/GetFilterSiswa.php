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
require_once('../include/common.php');

OpenDb();

$dep = $_REQUEST['dep'];
$tkt = $_REQUEST['tkt'];
$thn = $_REQUEST['thn'];
$kls = $_REQUEST['kls'];
?>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
  <td style="padding-right:4px">Departemen</td>
  <td class="td">
    <select id="CmbDepSis" name="CmbDepSis" class="Cmb" onchange="ChgCmbDepSis()">
<?php  $sql = "SELECT departemen
		      FROM $db_name_akad.departemen
			 WHERE aktif=1
			 ORDER BY urutan";
    $res = QueryDb($sql);
    while ($row = @mysqli_fetch_row($res))
	{
      if ($dep == "")
		$dep=$row[0]; ?>
      <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$dep)?>><?=$row[0]?></option>
<?php  } ?>
    </select>
  </td>
  <td class="td">Tingkat</td>
  <td class="td">
    <select id="CmbTktSis" name="CmbTktSis" class="Cmb" onchange="ChgCmbTktThnSis()">
<?php  $sql = "SELECT replid, tingkat
		      FROM $db_name_akad.tingkat
			 WHERE aktif=1
			   AND departemen='$dep'";
    $res = QueryDb($sql);
    while ($row = @mysqli_fetch_row($res))
	{
      if ($tkt == "")
		$tkt = $row[0]; ?>
      <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$tkt)?>><?=$row[1]?></option>
<?php  } ?>
    </select>
  </td>
</tr>
<tr>
  <td style="padding-right:4px">Tahun Ajaran</td>
  <td class="td">
    <select id="CmbThnSis" name="CmbThnSis" class="Cmb" onchange="ChgCmbTktThnSis()">
<?php  $sql = "SELECT replid, tahunajaran
			  FROM $db_name_akad.tahunajaran
			 WHERE aktif=1
			   AND departemen='$dep'";
    $res = QueryDb($sql);
    while ($row = @mysqli_fetch_row($res))
	{
      if ($thn == "")
		$thn = $row[0];	?>
      <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$thn)?>><?=$row[1]?></option>
<?php  } ?>
    </select>
  </td>
  <td class="td">Kelas</td>
  <td class="td">
    <select id="CmbKlsSis" name="CmbKlsSis" class="Cmb" onchange="ChgCmbKlsSis()">
<?php  $sql = "SELECT replid, kelas
		      FROM $db_name_akad.kelas
			 WHERE aktif=1
			   AND idtahunajaran='$thn'
			   AND idtingkat='$tkt'";
    $res = QueryDb($sql);
    while ($row = @mysqli_fetch_row($res))
	{
	  if ($kls == "")
		$kls = $row[0]; ?>
      <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$kls)?>><?=$row[1]?></option>
<?php  } ?>
    </select>
  </td>
</tr>
</table>
<?php
CloseDb();
?>