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
require_once("../include/config.php");
require_once("../include/common.php");
require_once("../include/compatibility.php");
require_once("../include/db_functions.php");

OpenDb();

$ownerid = $_REQUEST['ownerid'];
$ownertype = $_REQUEST['ownertype'];

$sql = $ownertype == 'S' ?
       "SELECT foto, foto IS NULL AS isnull FROM jbsakad.siswa WHERE nis = '$ownerid'" :
       "SELECT foto, foto IS NULL AS isnull FROM jbssdm.pegawai WHERE nip = '".$ownerid."'";
$res = QueryDb($sql);

if (mysqli_num_rows($res) > 0)
{
    $row = mysqli_fetch_array($res);
    if ($row['isnull'] == 0)
    {
        echo $row['foto'];    
    }
    else
    {
        ShowNoUserImage();
    }
    
}
else
{
    ShowNoUserImage();
}

CloseDb();

function ShowNoUserImage()
{
    $filename = "../images/no_user.png";
    $contents = "";
    
    if ($handle = @fopen($filename, "r"))
    {
        $contents = @fread($handle, filesize($filename));
        @fclose($handle);
    }

    echo $contents;
}
?>