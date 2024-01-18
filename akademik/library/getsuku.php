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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

$suku_kiriman=$_REQUEST['suku'];

?>
<select name="suku" id="suku" class="ukuran" onKeyPress="return focusNext('status', event)">
	<option value="">[Pilih Suku]</option>  
    <?php // Olah untuk combo suku
	OpenDb();
	$sql_suku="SELECT suku,urutan,replid FROM jbsumum.suku ORDER BY urutan";
	$result_suku=QueryDB($sql_suku);
	while ($row_suku = mysqli_fetch_array($result_suku)) {
	//	if($suku_kiriman=="")
	//	$suku_kiriman=$row_suku['suku'];
		
		
	?>
	<option value="<?=$row_suku['suku']?>" <?=StringIsSelected($row_suku['suku'], $suku_kiriman)?> ><?=$row_suku['suku']?></option>
<?php
    } 
	CloseDb();
	// Akhir Olah Data suku
	?>
    </select>
	
    <img src="../images/ico/tambah.png" onclick="tambah_suku();" onMouseOver="showhint('Tambah Suku!', this, event, '50px')" />