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
/*require_once('config.php');
require_once('db_functions.php');
require_once('common.php');
require_once('sessioninfo.php');
*/
function GetThemeDir() {
OpenDb();
$sql_tema="Select theme from jbsuser.hakakses where login='".SI_USER_ID()."' AND modul='SIMAKA'";
$hasil=QueryDb($sql_tema);
$row_tema=mysqli_fetch_array($hasil);
$row_tema2=mysqli_num_rows($hasil);

if ($row_tema2==0){
	$theme=3;
	} else {
	$theme=$row_tema['theme'];
	}
	//$theme=SI_USER_THEME();
	if ($theme == 1) {
		return "theme/green/";
	} elseif ($theme == 2) {
		return "theme/pink/";
	} elseif ($theme == 3) {
		return "theme/casual/";
	} elseif ($theme == 4) {
		return "theme/apple/";
	} elseif ($theme == 5) {
		return "theme/vista/";
	} elseif ($theme == 6) {
		return "theme/kopi/";
	} elseif ($theme == 7) {
		return "theme/wood/";
	} elseif ($theme == 8) {
		return "theme/gold/";
	} elseif ($theme == 9) {
		return "theme/granite/";
	}

}
?>