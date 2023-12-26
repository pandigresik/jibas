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
require_once('config.php');

session_name("_JIBAS_KEPEGAWAIAN__");
session_start();

$SI_USER_LANDLORD = 0;
$SI_USER_MANAGER = 1;
$SI_USER_STAFF = 2;

function getUserName()
{
	return $_SESSION['namasimpeg'];
}

function getUserTheme()
{
	return $_SESSION['temasimpeg'];
}

function getLevel()
{
	return $_SESSION['tingkatsimpeg'];
}

function getAccess()
{
	return $_SESSION['tingkatsimpeg'];
}

function getUserId()
{
	return $_SESSION['login'];
}

function SI_USER_LEVEL()
{
	switch ($_SESSION['tingkatsimpeg'])
	{
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
	}
}
?>