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
function ShowCbDepartemen($default)
{
    $userId = SI_USER_ID();

    $sql = "SELECT DISTINCT d.departemen
              FROM jbsakad.guru g, jbsakad.pelajaran p, jbsakad.departemen d
             WHERE g.idpelajaran = p.replid
               AND p.departemen = d.departemen
               AND p.aktif = 1
               AND g.aktif = 1
               AND d.aktif = 1
               AND g.nip = '$userId'
             ORDER BY urutan";
    $res = QueryDb($sql);

    $ret = "<select id='departemen' name='departemen' style='width: 200px;' onchange='changeDept()'>";
    while($row = mysqli_fetch_row($res))
    {
        $selected = $default == $row[0] ? "selected" : "";
        $ret .= "<option value='".$row[0]."' $selected>".$row[0]."</option>";
    }
    $ret .= "</select>";

    echo $ret;
}

function ShowCbPelajaran($dept, $default)
{
    if ($dept == "")
    {
        $ret = "<select id='pelajaran' name='pelajaran' style='width: 200px;' onchange='changePel()'>";
        $ret .= "</select>";
        echo $ret;
        return;
    }

    $userId = SI_USER_ID();

    $sql = "SELECT g.idpelajaran, p.nama
              FROM jbsakad.guru g, jbsakad.pelajaran p 
             WHERE g.idpelajaran = p.replid
               AND p.departemen = '$dept'
               AND g.nip = '$userId'
               AND p.aktif = 1
             ORDER BY p.nama";
    $res = QueryDb($sql);

    $ret = "<select id='pelajaran' name='pelajaran' style='width: 250px;' onchange='changePel()'>";
    while($row = mysqli_fetch_row($res))
    {
        $selected = $default == $row[0] ? "selected" : "";
        $ret .= "<option value='".$row[0]."' $selected>".$row[1]."</option>";
    }
    $ret .= "</select>";

    echo $ret;
}

function ShowCbChannel($idPel, $default)
{
    if ($idPel == "")
    {
        $ret = "<select id='channel' name='channel' style='width: 200px;' onchange='changeChannel()'>";
        $ret .= "</select>";
        echo $ret;
        return;
    }

    $userId = SI_USER_ID();

    $sql = "SELECT id, judul
              FROM jbsel.channel 
             WHERE idpelajaran = $idPel
               AND nip = '$userId'
               AND aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);

    $ret = "<select id='channel' name='channel' style='width: 250px;' onchange='changeChannel()'>";
    while($row = mysqli_fetch_row($res))
    {
        $selected = $default == $row[0] ? "selected" : "";
        $ret .= "<option value='".$row[0]."' $selected>".$row[1]."</option>";
    }
    $ret .= "</select>";

    echo $ret;
}
?>