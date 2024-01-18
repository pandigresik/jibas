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

function RemoveChannel($idChannel)
{
    try
    {
        BeginTrans();

        $sql = "DELETE FROM jbsel.channelfollow WHERE idchannel = $idChannel";
        QueryDbEx2($sql);

        $sql = "DELETE FROM jbsel.channel WHERE id = $idChannel";
        QueryDbEx2($sql);

        CommitTrans();
        return GenericReturn::createJson(1, "OK", "");
    }
    catch(Exception)
    {
        RollbackTrans();
        return GenericReturn::createJson(-1, "ERROR", "");
    }
}

function ShowChannelAktif($no, $idChannel)
{
    $sql = "SELECT aktif
              FROM jbsel.channel
             WHERE id = $idChannel";
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

        echo "<a style='cursor: pointer' onclick='setStatusAktif($no, $idChannel, $newAktif)'><img src='$src' border='0' title='$title'></a>";
    }
}

function SetNewAktif($idChannel, $newAktif)
{
    $sql = "UPDATE jbsel.channel SET aktif = $newAktif WHERE id = $idChannel";
    QueryDb($sql);
}
?>