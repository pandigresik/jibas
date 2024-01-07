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
    global $idtambahan, $kolom, $op;

    $idtambahan = 0;
    if (isset($_REQUEST['idtambahan'])) {
        $idtambahan = $_REQUEST['idtambahan'];
    }

    $op = "";
    if (isset($_REQUEST['op'])) {
        $op = $_REQUEST['op'];
    }

    $sql = "SELECT kolom FROM jbsakad.tambahandata WHERE replid = '".$idtambahan."'";
    $kolom = FetchSingle($sql);
}

function ChangeAktif(): void
{
    $newaktif = $_REQUEST['newaktif'];
    $idpilihan = $_REQUEST['idpilihan'];

    $sql = "UPDATE pilihandata 
               SET aktif = '$newaktif' 
             WHERE replid = '".$idpilihan."'";
    QueryDb($sql);
}

function HapusData(): void
{
    $idpilihan = $_REQUEST['idpilihan'];

    $sql = "DELETE FROM pilihandata 
             WHERE replid = '".$idpilihan."'";
    QueryDb($sql);
}

function ShowDataPilihan($replid): void
{

}

function ShowLinkPilihan($replid): void
{
    echo "<a onclick='aturPilihan($replid)'>atur pilihan</a>";
}
?>
