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

session_name("JBSSMS");
session_start();

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
	$result_la = QueryDb($sql_la);
	$row_la = @mysqli_fetch_array($result_la);
	if (md5($password)==$row_la['password'])
	{
		$_SESSION['login'] = "landlord";
		$_SESSION['tingkat'] = "0";
		$_SESSION['nama'] = "Administrator Jibas SMS Gateway";
		$user_exists = true;
	} 
	else 
	{
		$user_exists = false;
	}
} 
else 
{
	$sql = "SELECT l.password,h.tingkat 
              FROM $db_name_user.login l,$db_name_user.hakakses h 
             WHERE l.login=h.login 
               AND l.login=$username 
               AND h.modul='SMSG'";
	$result = QueryDb($sql);
	
	$jum = mysqli_num_rows($result);
	if ($jum < 1){
		?>
		<script language = "javascript" type = "text/javascript">
			alert("Username tidak terdaftar!");
			document.location.href = "../smsgateway";
		</script>
		<?php 
		$user_exists = false;
	} else {
		$row = mysqli_fetch_row($result);
		$level = $row[1];
		if ($row[0]!=md5($password)){
			?>
			<script language = "javascript" type = "text/javascript">
				alert("Password Anda salah!");
				document.location.href = "../smsgateway";
			</script>
			<?php 
			$user_exists = false;
		} else {
			$sql = "SELECT p.aktif,p.nama 
                      FROM $db_name_user.login l, $db_name_sdm.pegawai p 
                     WHERE l.login=p.nip AND l.login=$username ";
			$result = QueryDb($sql);
			$row = mysqli_fetch_row($result);
			if ($row[0]=='0'){
				?>
				<script language = "javascript" type = "text/javascript">
					alert("Pengguna sedang tidak aktif!");
					document.location.href = "../smsgateway";
				</script>
				<?php 
				$user_exists = false;
			} else {
				$_SESSION['login'] = $login;
				$_SESSION['tingkat'] = $level;
				$_SESSION['nama'] = "$row[1]";
				$user_exists = true;
			}
		}
	}		
}

if(!$user_exists) 
{
	?>
    <script language = "javascript" type = "text/javascript">
        alert("Username atau password tidak cocok!");
        document.location.href = "../smsgateway";
    </script>
    <?php
}
else
{
	if ($login=="landlord")
		$query = "UPDATE $db_name_user.landlord SET lastlogin=now() WHERE password='".md5($password)."'";
    else
		$query = "UPDATE $db_name_user.hakakses SET lastlogin=now() WHERE login=$username AND modul='SMSG'";
		
	$result = queryDb($query);
	
	if (isset($_SESSION['login']) && isset($_SESSION['tingkat']))
	{ 
	?>
    <script language = "javascript" type = "text/javascript">
        document.location.href = "../smsgateway";
    </script>
    <?php
	}
	exit();
}
?>