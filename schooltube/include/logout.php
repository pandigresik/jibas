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
require_once("config.php");
require_once("session.php");
require_once("db_functions.php");

// DELETE webuserinfo
$userid = $_SESSION["UserId"];
$sessionid = $_SESSION["SessionId"];

OpenDb();

$sql = "DELETE FROM jbscbe.webuserinfo WHERE userid = '$userid' AND sessionid = '".$sessionid."'";
QueryDb($sql);

$sql = "DELETE FROM jbscbe.webuserintent WHERE userid = '".$userid."'";
QueryDb($sql);

CloseDb();

// DELETE ALL SESSION
foreach($_SESSION as $k => $v)
{
    unset($_SESSION[$k]);
}

// DELETE ALL COOKIES
foreach($_COOKIE as $k => $v)
{
    setcookie($k, "", ['expires' => time() - 3600, 'path' => "/"]);
}

if (file_exists("login.php"))
    $addr = "index.php";
elseif (file_exists("../login.php"))
    $addr = "../index.php";
elseif(file_exists("../../login.php"))
    $addr = "../../login.php";
else
    $addr = "../../../login.php";
?>
<script language="javascript">
    if (self != self.top)
    {
        top.window.location.href='<?=$addr ?>';
    }
    else if (self.name != "")
    {
        window.close();
        opener.top.window.location.href='<?=$addr ?>';
    }
    else
    {
        window.location.href='<?=$addr ?>';
    }
</script>