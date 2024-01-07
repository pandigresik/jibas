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
function ReadParams(): void
{
    global $op, $from, $backurl, $departemen, $title;

    $departemen = "";
    if (isset($_REQUEST['departemen'])) {
        $departemen = $_REQUEST['departemen'];
    }

    $op = "";
    if (isset($_REQUEST['op'])) {
        $op = $_REQUEST['op'];
    }

    $from = "Kesiswaan";
    if (isset($_REQUEST['from'])) {
        $from = $_REQUEST['from'];
    }

    $backurl = "../siswa.php";
    if ($from == "Penerimaan Siswa Baru") {
        $backurl = "../siswa_baru.php";
    }

    $title = "Kolom Tambahan Data Siswa";
    if ($from == "Penerimaan Siswa Baru") {
        $title = "Kolom Tambahan Data Calon Siswa";
    }
}

function ChangeAktif(): void
{
    $newaktif = $_REQUEST['newaktif'];
    $replid = $_REQUEST['replid'];

    $sql = "UPDATE tambahandata 
               SET aktif = '$newaktif' 
             WHERE replid = '".$replid."'";
    QueryDb($sql);
}

function HapusData(): void
{
    $replid = $_REQUEST['replid'];

    $sql = "DELETE FROM tambahandata 
             WHERE replid = '".$replid."'";
    QueryDb($sql);
}

function ShowDataPilihan($idtambahan): void
{
    $list = GetDataPilihan($idtambahan);
    echo "<span id='pilihan-$idtambahan'><i>$list</i></span>";
}

function GetDataPilihan($idtambahan): string
{
    $list = "";

    $sql = "SELECT pilihan 
              FROM jbsakad.pilihandata
             WHERE idtambahan = '$idtambahan'
               AND aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        if ($list != "") {
            $list .= ", ";
        }
        $list .= $row[0];
    }

    return $list;
}

function ShowLinkPilihan($idtambahan): void
{
    echo "<a onclick='aturPilihan($idtambahan)' onmouseover='' style='color: blue; cursor: pointer; font-weight: normal;'>";
    echo "<img src='../images/ico/ubah.png'>atur pilihan</a>";
}
?>
