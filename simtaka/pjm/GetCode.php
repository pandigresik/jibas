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
require_once("../inc/db_functions.php");
require_once('../inc/sessioninfo.php');
require_once("../inc/common.php");
OpenDb();
$perpustakaan=SI_USER_IDPERPUS();
if ($perpustakaan!='ALL')
	$filter=" AND perpustakaan=".$perpustakaan;
if(isset($_REQUEST['q'])) {
  $search=$_REQUEST['q'];
	//echo("Serch=".$search);
	//exit;
	$sql = "SELECT kodepustaka FROM daftarpustaka WHERE kodepustaka LIKE '%$search%' AND status=1 $filter";	
	//echo($sql);exit;
	$result = QueryDb($sql) or die (mysqli_error ());
	while($row = mysqli_fetch_array($result)){
		echo $row['kodepustaka']."\n";
	}
}
?>