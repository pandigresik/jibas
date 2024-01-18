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

header("Last-Modified: " .gmdate("D, d M Y H:i:s"). " GMT");
header("Cache-control: no-store, no-cache, must-revalidate");
header("Cache-control: post-check=0, pre-check=0", false);

require_once('inc/config.php');
require_once('inc/db_functions.php');

OpenDb();

$username = trim((string) $_POST['username']);
if ($username == "jibas") $username = "landlord";
$password = trim((string) $_POST['password']);

$username = str_replace("'", "\'", $username);
$username = str_replace("--", " ", $username);
$login = $username;
$username = "'$username'";

$password = str_replace("'", "\'", $password);

$user_exists = false;
if (isset($_SESSION["login"]))
	$user_exists = $_SESSION["login"];
	
if(!isset($_SESSION["login"])) 
{
	if ($login == "landlord")
	{
		$sql_la = "SELECT password FROM $db_name_user.landlord";
		$result_la = QueryDb($sql_la) ;//or die(mysqli_error($mysqlconnection));
		$row_la=@mysqli_fetch_array($result_la);
		if (md5($password)==$row_la['password'])
		{
			$_SESSION['login'] = "landlord";
            $_SESSION['tingkat'] = "0";
			$_SESSION['nama'] = "Administrator Jibas EMA";
            $user_exists = true;
		} 
		else 
		{
			$user_exists = false;
		}
	} 
	else 
	{
		$query = "SELECT login,password 
                    FROM jbsuser.login 
                   WHERE login = $username
                     AND password = '".md5($password)."'";
		$result = QueryDb($query) or die(mysqli_error($mysqlconnection));
		$row = mysqli_fetch_array($result);
		$num = mysqli_num_rows($result);
		if($num != 0) 
		{
			$query2 = "SELECT h.departemen as departemen, h.tingkat as tingkat, p.nama as nama, 
							  p.panggilan as panggilan, h.theme as tema 
						 FROM jbsuser.hakakses h, jbssdm.pegawai p 
						WHERE h.login = $username 
						  AND p.nip=h.login 
						  AND h.modul='EMA'";
			$result2 = QueryDb($query2) or die(mysqli_error($mysqlconnection));
			$row2 = mysqli_fetch_array($result2);
			$num2 = mysqli_num_rows($result2);
			if($num2 != 0) 
			{
				$_SESSION['login'] = $login;
				$_SESSION['nama'] = $row2['nama'];
				$_SESSION['tingkat'] = 2;
				$_SESSION['panggilan'] = $row2['panggilan'];
				$_SESSION['theme'] = $row2['tema'];
				if ($row2['tingkat']==2)
					$_SESSION['departemen'] = $row2['departemen'];
				else 
					$_SESSION['departemen'] = "ALL";
				$user_exists = true;
			} 
			else 
			{
				$user_exists = false;
			}
		}
	}
}

if(!$user_exists) 
{	?>
    <script language = "javascript" type = "text/javascript">
        alert("Username atau password tidak cocok!");
        document.location.href = "../ema";
    </script>
 <?php
}
else
{
	if ($login == "landlord")
    	$query = "UPDATE $db_name_user.landlord SET lastlogin='".date("Y-m-d H:I:s")."' WHERE password='".md5($password)."'";
    else 
		$query = "UPDATE $db_name_user.hakakses SET lastlogin='".date("Y-m-d H:I:s")."' WHERE login=$username AND modul = 'EMA'";
	$result = queryDb($query);
	
	if ((isset($_SESSION['login'])) && (isset($_SESSION['tingkat'])))
	{ ?>
    <script language = "javascript" type = "text/javascript">
        top.location.href = "../ema";
    </script>
<?php }
	exit();
}
?>