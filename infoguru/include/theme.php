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
require_once('sessioninfo.php');
function GetThemeDir() {
	// Change this variable with user's SESSION theme
	//$theme = SI_USER_THEME();
	$theme = 2;
	if ($theme == 1) {
		return "images/theme/red/";
	} elseif ($theme == 2) {
		return "images/theme/blue/";
	} elseif ($theme == 3) {
		return "images/theme/black/";
	} elseif ($theme == 4) {
		return "images/theme/green/";
	} elseif ($theme == 5) {
		return "images/theme/lavender/";
	} elseif ($theme == 6) {
		return "images/theme/blue_sea/";
	} elseif ($theme == 7) {
		return "images/theme/greenpadi/";
	} elseif ($theme == 8) {
		return "images/theme/reddot/";
	} elseif ($theme == 9) {
		return "images/theme/sunset/";
	} elseif ($theme == 10) {
		return "images/theme/orange/";
	} elseif ($theme == 11) {
		return "images/theme/dirt/";
	} elseif ($theme == 12) {
		return "images/theme/purple/";
	}
	
}

function GetThemeDir2() 
{
	return "";	
}
?>