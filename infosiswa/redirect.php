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

header("Last-Modified: " .gmdate("D, d M Y H:i:s"). " GMT");
header("Cache-control: no-store, no-cache, must-revalidate");
header("Cache-control: post-check=0, pre-check=0", false);

OpenDb();

session_name("jbsinfosiswa");
session_start();
    
$username = trim((string) $_POST['username']);
if ($username == "jibas") $username = "landlord";
$password = trim((string) $_POST['password']);

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
	$row_la = @mysqli_fetch_row($result_la);
	if (md5($password)==$row_la[0])
	{
		$_SESSION['login'] = "landlord";
		$_SESSION['nama'] = "landlord";
		$_SESSION['panggilan'] = "Admin";
		$_SESSION['theme'] = 1;
		$user_exists = true;
	}
} 
elseif ($username=="'adminsiswa'")
{
	$sql_la = "SELECT password FROM jbsuser.adminsiswa";
	$result_la = QueryDb($sql_la) or die(mysqli_error($mysqlconnection));
	$row_la = @mysqli_fetch_row($result_la);
	if (md5($password)==$row_la[0])
	{
		$_SESSION['login'] = "adminsiswa";
		$_SESSION['nama'] = "adminsiswa";
		$_SESSION['panggilan'] = "Admin Siswa";
		$_SESSION['theme'] = 1;
		$user_exists = true;
	}
} 
else 
{
	$query = "SELECT p.nis as nis,p.nama as nama, p.panggilan as panggilan 
                FROM jbsakad.siswa p 
               WHERE p.nis = $username 
                 AND p.pinsiswa='$password'";
	$result = QueryDb($query) or die(mysqli_error($mysqlconnection));
	$row = mysqli_fetch_array($result);
	$num = mysqli_num_rows($result);
	if($num != 0) 
	{
		$_SESSION['login'] = $login;
		$_SESSION['nama'] = $row['nama'];
		$_SESSION['panggilan'] = $row['panggilan'];
		$user_exists = true;
	}
}

if (!$user_exists) 
{
    ?>
    <script language = "javascript" type = "text/javascript">
        alert("Username atau password tidak cocok!");
        document.location.href = "../infosiswa/";
    </script>
    <?php
}
else
{
	if ($login == "landlord")
	{
		$query = "UPDATE jbsuser.landlord SET lastlogin=NOW() WHERE password='".md5($password)."'";
    } 
	elseif ($username=="'adminsiswa'")
	{
		$query = "UPDATE jbsuser.adminsiswa SET lastlogin=NOW() WHERE password='".md5($password)."'";
    } 
	else 
	{
		$sql = "SELECT * FROM jbsuser.hakakses WHERE modul='INFOSISWA' AND login=$username";
		$result = QueryDb($sql);
		$num=@mysqli_num_rows($result);
		$query = "UPDATE jbsuser.hakaksesinfosiswa SET lastlogin=NOW() WHERE nis=$username";
	}
	$result = QueryDb($query);
	?>
    <script language = "javascript" type = "text/javascript">
        top.location.href = "../infosiswa/";
    </script>
    <?php
	exit();
	}
?>