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
session_name("jbsinfosiswa");
session_start();

$SI_USER_LANDLORD = 0;
$SI_USER_MANAGER = 1;
$SI_USER_STAFF = 2;
$SI_USER_OSIS = 3;
$STAFF_DEPT = $_SESSION['departemen'];

function SI_USER_NAME() {
	return $_SESSION['nama'];
}

function SI_USER_NICKNAME() {
	return $_SESSION['panggilan'];
}

function SI_USER_ID() {
	return $_SESSION['login'];
}

function SI_USER_THEME() {
	return $_SESSION['theme'];
}


//function GetThemeDir() {
//	return $_SESSION['theme'];
//}
function SI_USER_LEVEL() {
	switch ($_SESSION['tingkat']){
	case 0:
		{
	global $SI_USER_LANDLORD;
	return $SI_USER_LANDLORD;
	break;
		}
		case 1:
		{
	global $SI_USER_MANAGER;
	return $SI_USER_MANAGER;
	break;
		}
		case 2:
		{
	global $SI_USER_STAFF;
	return $SI_USER_STAFF;
	break;
		}
		case 3:
		{
	global $SI_USER_OSIS;
	return $SI_USER_OSIS;
	break;
		}
	}
}
function SI_USER_ACCESS() {
	if ($_SESSION['tingkat']==2){
		return $_SESSION['departemen'];
	} else {
		return "ALL";
	}
}
?>