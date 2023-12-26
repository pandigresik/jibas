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
	if (getUserTheme()==0){
		$theme=1;
	} else {
		$theme=getUserTheme();
	}

	if ($theme == 1) {
		return "theme/1/";
	} elseif ($theme == 2) {
		return "theme/2/";
	} elseif ($theme == 3) {
		return "theme/3/";
	} elseif ($theme == 4) {
		return "theme/4/";
	} 

}


function GetThemeDir2() {
	if (getUserTheme()==0){
		$theme=1;
	} else {
		$theme=getUserTheme();
	}

	if ($theme == 1) {
		return "1";
	} elseif ($theme == 2) {
		return "2";
	} elseif ($theme == 3) {
		return "3";
	} elseif ($theme == 4) {
		return "4";
	}
}

function GetThemeNums() {
		return "4";
}

function GetThemeDir3($thm) {
	if ($thm == 1) {
		return "theme/1/";
	} elseif ($thm == 2) {
		return "theme/2/";
	} elseif ($thm == 3) {
		return "theme/3/";
	} elseif ($thm == 4) {
		return "theme/4/";
	}
}

function GetThemeColor() {
	if (getUserTheme()==0){
		$thm=1;
	} else {
		$thm=getUserTheme();
	}

	if ($thm == 1) {
		return "#7c7f4f";
	} elseif ($thm == 2) {
		return "#544909";
	} elseif ($thm == 3) {
		return "#5a5a58";
	} elseif ($thm == 4) {
		return "#5a5a58";
	}
}
?>