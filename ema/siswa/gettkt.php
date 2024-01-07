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
require_once("../inc/config.php");
require_once("../inc/common.php");
require_once("../inc/db_functions.php");
require_once('siswa.class.php');
OpenDb();

$SP = new CSiswa();
$SP -> dep = $_REQUEST['dep'];
$SP->GetTkt();
/*
OpenDb();
$sql = "SELECT * FROM tingkat WHERE departemen='".$_REQUEST['dep']."' AND aktif = 1 ORDER BY tingkat";
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
if ($num==0){
	echo "<option value=''>Tidak ada Data</option>";	
} else {
	while ($row = @mysqli_fetch_array($result)){
		if ($tkt == "")
			$tkt = $row['replid'];
		echo "<option value='".$row['replid']."' ".StringIsSelected($tkt,$row['replid']).">".$row['tingkat']."</option>";
	}
}
*/
?>