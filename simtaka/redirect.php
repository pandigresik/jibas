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
session_name("jbsperpus");
session_start();

header("Last-Modified: " .gmdate("D, d M Y H:i:s"). " GMT");
header("Cache-control: no-store, no-cache, must-revalidate");
header("Cache-control: post-check=0, pre-check=0", false);

require_once('inc/config.php');
require_once('inc/db_functions.php');

OpenDb();
   
$username = trim((string) $_POST['username']);
if ($username=="jibas") $username="landlord";
$password = trim((string) $_POST['password']);

$username = str_replace("'", "\'", $username);
$username = str_replace("--", " ", $username);
$login = $username;
$username = "'$username'";

$password = str_replace("'", "\'", $password);

$user_exists = false;
if ($login == "landlord")
{
	$sql_la = "SELECT password FROM $db_name_user.landlord";
	$result_la = QueryDb($sql_la) ;
	$row_la=@mysqli_fetch_array($result_la);
	if (md5($password)==$row_la['password'])
	{
		$_SESSION['login'] = "landlord";
		$_SESSION['tingkat'] = "0";
		$_SESSION['nama'] = "Administrator Jibas SimTaka";
		
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
              FROM $db_name_user.login l, $db_name_sdm.pegawai p 
             WHERE l.login=p.nip 
               AND l.login=$username";
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
				document.location.href = "../simtaka/";
			</script>
			<?php
		}
		else
		{
			$query = "SELECT login,password,nama 
                        FROM $db_name_user.login, $db_name_sdm.pegawai 
                       WHERE login = $username  
					     AND password = '".md5($password)."' 
					     AND nip=login";
			$result = QueryDb($query) or die(mysqli_error($mysqlconnection));
			$row = mysqli_fetch_array($result);
			$num = mysqli_num_rows($result);
			if ($num != 0)
			{
				$q = "SELECT aktif,tingkat,departemen,info1 
                        FROM $db_name_user.hakakses 
                       WHERE login = $username  
					     AND modul = 'SIMTAKA'";
				$res = QueryDb($q) or die(mysqli_error($mysqlconnection));
				$r = mysqli_fetch_array($res);
				if ($r['aktif']==0)
				{	?>
					<script language = "javascript" type = "text/javascript">
						alert("Status pengguna sedang tidak aktif!");
						document.location.href = "../simtaka/";
					</script>
				<?php 	
				}
				else
				{
					$_SESSION['login'] = $login;
					$_SESSION['tingkat'] = $r['tingkat'];
					$_SESSION['perpustakaan'] = $r['departemen'];
					$_SESSION['idperpustakaan'] = $r['info1'];
					$_SESSION['nama'] = $row['nama'];
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
{	?>
    <script language = "javascript" type = "text/javascript">
        alert("Username atau password tidak cocok!");
        document.location.href = "../simtaka/";
    </script>
	<?php
}
else
{
	if ($login == "landlord")
    	$query = "UPDATE $db_name_user.landlord 
                     SET lastlogin=NOW() 
                   WHERE password='".md5($password)."'";
    else 
		$query = "UPDATE $db_name_user.hakakses 
                     SET lastlogin=NOW() 
                   WHERE login = $username 
                     AND modul = 'SIMTAKA'";
	$result = QueryDb($query);
	
	if (isset($_SESSION['login']) && isset($_SESSION['tingkat']))
	{ 
	?>
    <script language = "javascript" type = "text/javascript">
        top.location.href = "../simtaka/";
    </script>
    <?php
	}
	exit();
}
?>