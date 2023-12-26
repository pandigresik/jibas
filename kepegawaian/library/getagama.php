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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

$agama_kiriman=$_REQUEST['agama'];
?>
<select name="cbAgama" id="cbAgama" onKeyPress="return focusNext('cbNikah', event)">
<?php // Olah untuk combo agama
	OpenDb();
	$sql_agama="SELECT replid,agama,urutan FROM jbsumum.agama ORDER BY urutan";
	$result_agama=QueryDB($sql_agama);
	while ($row_agama = mysqli_fetch_array($result_agama))
	{ ?>
		<option value="<?=$row_agama['agama']?>"<?=StringIsSelected($row_agama['agama'],$agama_kiriman)?>><?=$row_agama['agama']?></option>
<?php  } 
	CloseDb();
	?>
</select>	
<img src="../images/ico/tambah.png" onclick="tambah_agama();"/>