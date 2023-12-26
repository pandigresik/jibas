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
function ShowModulAktif($no, $idModul)
{
    $sql = "SELECT aktif
              FROM jbsel.modul
             WHERE id = $idModul";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $aktif = (int) $row[0];

        if ($aktif == 1)
        {
            $newAktif = 0;
            $title = "Aktif";
            $src = "../images/ico/aktif.png";
        }
        else
        {
            $newAktif = 1;
            $title = "Non Aktif";
            $src = "../images/ico/nonaktif.png";
        }

        echo "<a style='cursor: pointer' onclick='setStatusAktif($no, $idModul, $newAktif)'><img src='$src' border='0' title='$title'></a>";
    }
}

function SetNewAktif($idModul, $newAktif)
{
    $sql = "UPDATE jbsel.modul SET aktif = $newAktif WHERE id = $idModul";
    QueryDb($sql);
}

function RemoveModul($idModul)
{
    $sql = "DELETE FROM jbsel.modulfollow WHERE idmodul = $idModul";
    QueryDb($sql);

    $sql = "DELETE FROM jbsel.mediamodul WHERE idmodul = $idModul";
    QueryDb($sql);

    $sql = "DELETE FROM jbsel.modul WHERE id = $idModul";
    QueryDb($sql);
}
?>