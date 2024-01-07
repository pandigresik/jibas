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
function GetVideoLikedIdList($sortBy, &$count)
{
    if (!isset($_SESSION["IsLogin"]))
        return "";

    $userId = $_SESSION["UserId"];
    $userCol = $_SESSION["UserCol"];

    $sql = "SELECT DISTINCT ml.idmedia
              FROM jbsel.medialike ml, jbsel.media m
             WHERE ml.idmedia = m.id
               AND m.aktif = 1
               AND ml.$userCol = '".$userId."'";

    if ($sortBy == 1)
        $sql .= " ORDER BY ml.timestamp DESC";
    else if ($sortBy == 2)
        $sql .= " ORDER BY ml.timestamp ASC";
    else if ($sortBy == 3)
        $sql .= " ORDER BY m.judul ASC";
    else
        $sql .= " ORDER BY m.judul DESC";

    $res = QueryDb($sql);

    $count = 0;

    $idList = "";
    while($row = mysqli_fetch_row($res))
    {
        if ($idList != "") $idList .= ",";
        $idList .= $row[0];

        $count += 1;
    }

    return $idList;
}
?>

