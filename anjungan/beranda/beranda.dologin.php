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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

$ERRMSG = "";

$op = $_REQUEST['op'];
$password = trim((string) $_REQUEST['password']);

if (strlen($password) == 0)
{
    include("beranda.login.php");
    exit();
}

OpenDb();
$sql = "SELECT COUNT(replid)
          FROM jbsuser.landlord
         WHERE password = md5('$password')";
$ndata = (int)FetchSingle($sql);

if ($ndata == 0)
{
    $ERRMSG = "Password salah!";
    include("beranda.login.php");
    exit();
}

require_once("beranda.session.php");
$_SESSION['isadminlogin'] = true;

if ($op == "edit")
    require_once("beranda.editor.php");
else
    require_once("beranda.changebg.php");
?>