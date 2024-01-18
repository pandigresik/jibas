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
require_once('include/config.php');
require_once('include/db_functions.php');

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-control: no-store, no-cache, must-revalidate");
header("Cache-control: post-check=0, pre-check=0", false);

session_name("jbsinfoguru");
session_start();

OpenDb();

$username = trim((string) $_REQUEST['username']);
if ($username == "jibas") $username = "landlord";
$password = trim((string) $_REQUEST['password']);

$username = str_replace("'", "\'", $username);
$username = str_replace("--", " ", $username);
$login = $username;
$username = "'$username'";

$password = str_replace("'", "\'", $password);

$user_exists = false;
if ($login == "landlord")
{
	$sql_la = "SELECT password FROM jbsuser.landlord";
	$result_la = QueryDb($sql_la) or die(mysqli_error($mysqlconnection));
	$result_hasilla = mysqli_fetch_array($result_la);
	
	if (md5($password) == $result_hasilla['password'])
	{
		$_SESSION['login'] = "landlord";
		$_SESSION['nama'] = "Administrator JIBAS";
		$_SESSION['tingkat'] = 0;
		$_SESSION['departemen'] = "ALL";
		$_SESSION['bagian'] = "Admin";
		$_SESSION['panggilan'] = "Admin";
		$_SESSION['theme'] = 1;
		
		$user_exists = true;
	} 
} 
else 
{
	$query = "SELECT login, password 
				FROM jbsuser.login 
			   WHERE login = $username
			     AND password='" . md5($password) . "'";
	$result = QueryDb($query) or die(mysqli_error($mysqlconnection));
	$row = mysqli_fetch_array($result);
	$num = mysqli_num_rows($result);
	if($num != 0) 
	{
		$query2 = "SELECT h.departemen as departemen, h.tingkat as tingkat, p.nama as nama, 
						  p.panggilan as panggilan, h.theme as tema, p.bagian 
					 FROM jbsuser.hakakses h, jbssdm.pegawai p 
					WHERE h.login = $username
					  AND p.nip = h.login
					  AND h.modul = 'INFOGURU'";
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
			$_SESSION['bagian'] = $row2['bagian'];
			if ($row2['tingkat'] == 2)
			{
				$_SESSION['departemen'] = $row2['departemen'];
			} 
			else 
			{
				$_SESSION['departemen'] = "ALL";
			}
			$user_exists = true;
		} 
		else 
		{
			$user_exists = false;
		}
	}
}

if (!$user_exists) 
{
?>
    <script language = "javascript" type = "text/javascript">
        alert("Username atau password tidak cocok!");
        document.location.href = "../infoguru";
    </script>
<?php
}
else
{
	if ($login == "landlord")
	{
		$query = "UPDATE jbsuser.landlord SET lastlogin=NOW() WHERE password='".md5($password)."'";
		QueryDb($query);
    } 
	else 
	{
		$query_root = "SELECT replid, dirfullpath FROM jbsvcr.dirshare WHERE idroot=0";
		$result_root = QueryDb($query_root);
		$row_root = @mysqli_fetch_array($result_root);
		$newdir = $FILESHARE_UPLOAD_DIR . "/fileshare/" . $_SESSION['login'];
	
		if (!@file_exists($newdir) && !@is_dir($newdir))
		{
			@mkdir($newdir, 0750, true);
			
			$fhtaccess = "$newdir/.htaccess";
			$fhtaccess = str_replace("//", "/", $fhtaccess);
			if ($fp = @fopen($fhtaccess, "w"))
			{
				@fwrite($fp, "Options -Indexes\r\n");
				@fclose($fp);
			}
		}
			
		$query = "SELECT * FROM jbsvcr.dirshare 
				   WHERE idroot = '".$row_root['replid']."' AND idguru='".$_SESSION['login']."' AND dirname='".$_SESSION['login']."'";
		if (@mysqli_num_rows(QueryDb($query)) == 0)
		{
			$dirfullpath = $row_root['dirfullpath'] . $_SESSION['login'] . "/";
			
			$query_dir = "INSERT INTO jbsvcr.dirshare SET idroot='".$row_root['replid']."', 
							dirname='".$_SESSION['login']."', dirfullpath='$dirfullpath', idguru='".$_SESSION['login']."'";
			QueryDb($query_dir);
		}
		
		$query = "UPDATE jbsuser.hakakses SET lastlogin=NOW() WHERE login=$username AND modul='INFOGURU'";
		QueryDb($query);
	}
	
    if (isset($_SESSION['nama']))
	{ 
	?>
    <script language = "javascript" type = "text/javascript">
		top.location.href = "../infoguru";
    </script>
    <?php
	}
	exit();
}
?>