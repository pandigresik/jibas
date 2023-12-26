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
session_name("jbsema");
session_start();

$SI_USER_LANDLORD = 0;
$SI_USER_MANAGER = 1;

function SI_USER_NAME()
{
	return $_SESSION['nama'];
}

function SI_USER_ID()
{
	return $_SESSION['login'];
}

function is_admin()
{
	if ($_SESSION['tingkat']=='0')
		return true;
	else
		return false;	
}

function SI_USER_ACCESS()
{
	return "ALL";
}



?>