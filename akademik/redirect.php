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
session_name("jbsakad");
session_start();

header("Last-Modified: " .gmdate("D, d M Y H:i:s"). " GMT");
header("Cache-control: no-store, no-cache, must-revalidate");
header("Cache-control: post-check=0, pre-check=0", false);

require_once('include/config.php');
require_once('include/db_functions.php');

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
if ($login == "landlord")
{
	$sql_la = "SELECT password FROM jbsuser.landlord";
	$result_la = QueryDb($sql_la);
	$row_la = @mysqli_fetch_array($result_la);
	if (md5($password) == $row_la['password'])
	{
		$_SESSION['login'] = "landlord";
		$_SESSION['namasimaka'] = "landlord";
		$_SESSION['tingkatsimaka'] = "0";
		$_SESSION['departemensimaka'] = "ALL";
		$_SESSION['temasimaka'] = 1;
		$user_exists = true;
	}
	else
	{
		$user_exists = false;
	}
}
else
{
	$sql = "SELECT p.aktif 
              FROM jbsuser.login l, jbssdm.pegawai p 
             WHERE l.login = p.nip 
               AND l.login = $username";
	$result = QueryDb($sql);
	$row = mysqli_fetch_array($result);
	$jum = mysqli_num_rows($result);
	if ($jum > 0)
	{
		if ($row['aktif'] == 0)
		{
			?>
			<script language = "javascript" type = "text/javascript">
				alert("Status pengguna sedang tidak aktif!");
				document.location.href = "../akademik/";
			</script>
			<?php
		}
		else
		{
			$query = "SELECT login,password 
                        FROM jbsuser.login 
                       WHERE login = $username
					     AND password= '" . md5($password) . "'";
			$result = QueryDb($query) or die(mysqli_error($mysqlconnection));
			$row = mysqli_fetch_array($result);
			$num = mysqli_num_rows($result);
			if($num != 0)
			{
				$query2 = "SELECT h.departemen as departemen, h.tingkat as tingkat, p.nama as nama, h.theme as tema 
                             FROM jbsuser.hakakses h, jbssdm.pegawai p 
                            WHERE h.login = $username 
                              AND p.nip=h.login 
                              AND h.modul='SIMAKA' 
                              AND p.aktif=1";
				$result2 = QueryDb($query2) or die(mysqli_error($mysqlconnection));
				$result4 = QueryDb($query2) or die(mysqli_error($mysqlconnection));
				$row2 = mysqli_fetch_array($result2);
				$num2 = mysqli_num_rows($result2);
						
				if ($num2 > 0)
				{
					$i = 0;
					while ($row4 = mysqli_fetch_array($result4))
					{
						$dep[$i] = $row4['departemen'];
						$i++;
					}
					
					$_SESSION['login'] = $login;
					$_SESSION['namasimaka'] = $row2['nama'];
					$_SESSION['tingkatsimaka'] = $row2['tingkat'];
					$_SESSION['temasimaka'] = $row2['tema'];
					if ($row2['tingkat'] == 2)
						$_SESSION['departemensimaka'] = $dep;
					else
						$_SESSION['departemensimaka'] = "ALL";
					
					$user_exists = true;
				}
			}
		} 
	}
	else
	{
		$user_exists = false;
	}		
}

if (!$user_exists)
{
	?>
    <script language = "javascript" type = "text/javascript">
        alert("Username atau password tidak cocok!");
        document.location.href = "../akademik/";
    </script>
    <?php
}
else
{
	if ($login == "landlord")
    	$query = "UPDATE jbsuser.landlord SET lastlogin=NOW() WHERE password='".md5($password)."'";
    else
		$query = "UPDATE jbsuser.hakakses SET lastlogin=NOW() WHERE login = $username AND modul = 'SIMAKA'";
	$result = queryDb($query);
	?>
    <script language = "javascript" type = "text/javascript">
        top.location.href = "../akademik/";
    </script>
    <?php
	exit();
}
?>