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
function GetVendorName($vendorId)
{
    $vendorName = "";

    $sql = "SELECT nama FROM jbsfina.vendor WHERE vendorid = '".$vendorId."'";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $vendorName = $row[0];
    }

    return $vendorName;
}

function ShowSelectPetugas($vendorId)
{
    $sb = new StringBuilder();
    $sb->AppendLine("<select id='petugas' name='petugas' style='width: 300px'>");

    $sql = "SELECT u.userid, u.nama
              FROM jbsfina.userpos u
             WHERE u.aktif = 1
               AND NOT u.userid IN (SELECT vu.userid FROM jbsfina.vendoruser vu WHERE vu.vendorid = '$vendorId')
             ORDER BY u.nama";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $sb->AppendLine("<option value='".$row[0]."'>".$row[1]."</option>");
    }
    $sb->AppendLine("</select>");

    echo $sb->ToString();
}

function createJsonReturn($status, $message)
{
    $ret = [$status, $message];
    return json_encode($ret, JSON_THROW_ON_ERROR);
}

function TambahVendorUser()
{
    $vendorId = $_REQUEST["vendorid"];
    $userId = $_REQUEST["userid"];
    $tingkat = $_REQUEST["tingkat"];

    $sql = "SELECT COUNT(replid) 
              FROM jbsfina.vendoruser
             WHERE vendorid = '$vendorId'
               AND userid = '".$userId."'";
    $nData = FetchSingle($sql);
    if ($nData != 0)
        return createJsonReturn(-1, "User id $userId sudah terdaftar sebagai petugas");

    $sql = "INSERT INTO jbsfina.vendoruser
               SET vendorid = '$vendorId', userid = '$userId', tingkat = $tingkat";
    QueryDb($sql);

    return createJsonReturn(1, "OK");
}
?>