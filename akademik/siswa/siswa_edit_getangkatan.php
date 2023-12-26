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
$departemen=$_POST['departemen'];
?>
<select name="angkatan" id="angkatan" class="ukuran" style="width:195px;">          
<?php // Olah untuk combo angkatan
	OpenDb();
	$sql_angkatan="SELECT * FROM jbsakad.angkatan WHERE aktif=1 AND departemen='$departemen' ORDER BY replid DESC";
	$result_angkatan=QueryDB($sql_angkatan);
	while ($row_angkatan = mysqli_fetch_array($result_angkatan)) {
	if ($angkatan == "")
		$angkatan = $row_angkatan['replid'];
?>
<option value="<?=$row_angkatan['replid']?>"<?=IntIsSelected($row_angkatan['replid'], $angkatan)?>>
<?=$row_angkatan['angkatan']?></option>
<?php  } 	CloseDb();
// Akhir Olah Data angkatan
?>
</select>