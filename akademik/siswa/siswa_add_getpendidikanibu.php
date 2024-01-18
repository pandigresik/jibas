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

$pendidikan_kiriman = $_REQUEST['pendidikan'];
// Olah untuk combo sekolah
?>
	<select name="pendidikanibu" id="Infopendidikanibu" class="ukuran"  onKeyPress="return focusNext('Infopekerjaanayah', event)" onfocus="panggil('Infopendidikanibu')" style="width:140px">
	<option value="">[Pilih Pendidikan]</option>
<?php
	OpenDb();
	$sql="SELECT pendidikan FROM jbsumum.tingkatpendidikan ORDER BY pendidikan";
	$result=QueryDB($sql);
	CloseDb();
	while ($row = mysqli_fetch_array($result)) {
	//if ($pendidikan_kiriman=="")
	//	$pendidikan_kiriman=$row['pendidikan'];
		?>
     	 <option value="<?=$row['pendidikan']?>"<?=StringIsSelected($row['pendidikan'],$pendidikan_kiriman)?> >
		 <?=$row['pendidikan']?></option>
<?php } ?> 
	</select>