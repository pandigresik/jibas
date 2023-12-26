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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
$dep_asal=$_REQUEST['dep_asal'];
$sekolah = $_REQUEST['sekolah'];
?>
	<select name="sekolah" id="sekolah" onKeyPress="return focusNext('ketsekolah', event)" style="width:150px;">
     <option value="">[Pilih Asal Sekolah]</option>
     <?php // Olah untuk combo sekolah
	OpenDb();
	$sql_sekolah="SELECT sekolah FROM jbsakad.asalsekolah WHERE departemen='$dep_asal' ORDER BY sekolah";
	$result_sekolah=QueryDB($sql_sekolah);
	while ($row_sekolah = mysqli_fetch_array($result_sekolah)) {
	if ($sekolah=="")
		$sekolah=$row_sekolah['sekolah'];
	?>
       <option value="<?=$row_sekolah['sekolah']?>" <?=StringIsSelected($row_sekolah['sekolah'],$sekolah)?>>
        <?=$row_sekolah['sekolah']?>
        </option>
      <?php
    } 
	CloseDb();
	// Akhir Olah Data sekolah
	?>
    </select>