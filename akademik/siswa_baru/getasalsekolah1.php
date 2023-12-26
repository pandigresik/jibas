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

if ($_REQUEST['departemen'] == "SD") {
	$urutan = 2;
} else {
	OpenDb();
	$query = "SELECT urutan FROM jbsakad.departemen WHERE departemen = '".$_REQUEST['departemen']."'";	
	$hasil = QueryDb($query);	
	CloseDb();
	$row = mysqli_fetch_array($hasil);
	$urutan = $row['urutan'];
}


OpenDb();
$sql_departemen="SELECT departemen,urutan FROM jbsakad.departemen WHERE urutan < '$urutan' ORDER BY urutan DESC LIMIT 1";			
$result_departemen=QueryDB($sql_departemen);
$row_departemen = @mysqli_fetch_array($result_departemen);
$dep_asal = $row_departemen['departemen'];
?>
<input type="hidden" name="dep_asal" id="dep_asal" value="<?=$dep_asal?>" />