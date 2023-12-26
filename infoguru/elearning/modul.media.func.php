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
function SimpanKeterangan($idModul, $idMedia, $urutan, $keterangan)
{
    $sql = "SELECT COUNT(*)
              FROM jbsel.mediamodul
             WHERE idmodul = $idModul
               AND idmedia = $idMedia";
    $nData = FetchSingle($sql);

    if ($nData == 0)
    {
        $sql = "INSERT INTO jbsel.mediamodul
                   SET idmodul = $idModul, idmedia = $idMedia, urutan = $urutan, keterangan = '$keterangan', aktif = 1";
        QueryDb($sql);
    }
}

function EditKeterangan($idMediaModul, $urutan, $keterangan)
{
    $sql = "UPDATE jbsel.mediamodul
               SET urutan = $urutan, keterangan = '$keterangan'
             WHERE id = $idMediaModul";
    QueryDb($sql);
}

function ShowKeterangan($idMediaModul)
{
    $sql = "SELECT urutan, keterangan
              FROM jbsel.mediamodul
             WHERE id = $idMediaModul";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $urutan = $row[0];
        $keterangan = $row[1];
    }

    echo "<strong>Urutan</strong>: $urutan<br>";
    echo "<strong>Keterangan</strong>: $keterangan";
}

function ShowMediaModulAktif($no, $idMediaModul)
{
    $sql = "SELECT aktif
              FROM jbsel.mediamodul
             WHERE id = $idMediaModul";
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

        echo "<a style='cursor: pointer' onclick='setStatusMediaModulAktif($no, $idMediaModul, $newAktif)'><img src='$src' border='0' title='$title'></a>";
    }
}

function SetNewMediaModulAktif($idMediaModul, $newAktif)
{
    $sql = "UPDATE jbsel.mediamodul SET aktif = $newAktif WHERE id = $idMediaModul";
    QueryDb($sql);
}

function HapusMediaModul($idMediaModul)
{
    $sql = "DELETE FROM jbsel.mediamodul WHERE id = $idMediaModul";
    QueryDb($sql);
}
?>
