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
function ShowCbKategori($idDefault)
{
    global $idChannel;

    $sql = "SELECT nip, idpelajaran 
              FROM jbsel.channel
             WHERE id = $idChannel";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return;

    $row = mysqli_fetch_row($res);
    $nip = $row[0];
    $idPel = $row[1];

    $sql = "SELECT id, kategori
              FROM jbscbe.kategori
             WHERE nip = '$nip'
               AND idpelajaran = '$idPel'
               AND rootid = 0";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        $select  = "<select id='kategori' name='kategori' style='font-size:14px; height: 25px;'>";
        $select .= "<option value'0'>(tidak ada kategori)</option>";
        $select .= "</select>";
        echo $select;
        return;
    }

    $select  = "<select id='kategori' name='kategori' style='font-size:14px; height: 25px; width: 450px;'>";
    $select .= "<option value'0'>(tidak ada kategori)</option>";

    while($row = mysqli_fetch_row($res))
    {
        $idKate = $row[0];
        $kate = $row[1];

        $selected = (int) $idKate == (int) $idDefault ? "selected" : "";

        $select .= "<option value='$idKate' $selected>$kate</option>";

        SubCbKategori($idKate, $select, 1, $idDefault);
    }

    $select .= "</select>";

    echo $select;
}

function SubCbKategori($rootId, &$select, $level, $idDefault)
{
    $sql = "SELECT id, kategori
              FROM jbscbe.kategori
             WHERE rootid = $rootId";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return;

    $space = "";
    for($i = 1; $i <= $level; $i++)
    {
        $space .= "--";
    }

    while($row = mysqli_fetch_row($res))
    {
        $idKate = $row[0];
        $kate = $row[1];

        $selected = (int) $idKate == (int) $idDefault ? "selected" : "";

        $select .= "<option value='$idKate' $selected>$space $kate</option>";

        SubCbKategori($idKate, $select, $level + 1, $idDefault);
    }
}
?>