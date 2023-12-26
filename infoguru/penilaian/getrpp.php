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
require_once("../include/sessionchecker.php");

$idrpp=$_REQUEST['rpp'];
OpenDb();
?>
<select name="idrpp" id="idrpp" style="width:170px;" onkeypress="return focusNext('deskripsi', event)">
<?php 
	$sql_rpp="SELECT * FROM rpp WHERE idtingkat='".$_REQUEST['tingkat']."' AND idsemester='".$_REQUEST['semester']."' AND idpelajaran='".$_REQUEST['pelajaran']."' AND aktif=1 ORDER BY rpp";
	$result_rpp=QueryDb($sql_rpp);
	while ($row_rpp=@mysqli_fetch_array($result_rpp)){
		//if($idrpp=="")
		//	$idrpp=$row_rpp['replid'];
?>
		<option value="<?=$row_rpp['replid'] ?>" <?=IntIsSelected($row_rpp['replid'], $idrpp) ?> ><?=$row_rpp['rpp'] ?>
		</option>
<?php } ?>

	</select>
	<img src="../images/ico/tambah.png" onClick="get_rpp('<?=$_REQUEST['tingkat']?>','<?=$_REQUEST['pelajaran']?>','<?=$_REQUEST['semester']?>')" onMouseOver="showhint('Tambah RPP!', this, event, '80px')">