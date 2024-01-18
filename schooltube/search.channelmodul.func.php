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
function DisplayModulSearchList($idList, $page)
{
    global $G_ROW_PER_PAGE;

    $startIndex = $page * $G_ROW_PER_PAGE;
    $stopIndex = (($page + 1) * $G_ROW_PER_PAGE) - 1;

    $idArr = explode(",", (string) $idList);
    if ($stopIndex < 0) $stopIndex = count($idArr);

    for($i = 0; $i < count($idArr); $i++)
    {
        if ($i < $startIndex)
            continue;

        if ($i > $stopIndex)
            continue;

        $idModul = $idArr[$i];

        $sql = "SELECT COUNT(*)
                  FROM jbsel.mediamodul
                 WHERE idmodul = $idModul";
        $nMedia = FetchSingle($sql);

        $sql = "SELECT m.judul AS modul, c.judul as channel, m.deskripsi, pl.nama as pelajaran, g.nama as guru, m.nfollower
                  FROM jbsel.modul m, jbsel.channel c, jbsakad.pelajaran pl, jbssdm.pegawai g
                 WHERE m.idchannel = c.id
                   AND c.idpelajaran = pl.replid
                   AND c.nip = g.nip 
                   AND m.id = $idModul";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_array($res))
        {
            echo "<tr style='cursor: pointer; line-height: 18px;' onclick='sr_showModulView($idModul)'>";
            echo "<td align='left' valign='top' width='500px' style='line-height: 20px;'>";
            echo "<span style='color: blue'>".$row['pelajaran'] | $row['channel'] | $row['guru']."</span><br>";
            echo "<span style='font-family: Arial; font-weight: bold; font-size: 14px'>".$row['modul']."</span><br>";
            echo "<span style='font-family: 'Times New Roman'; font-size: 12px;'>".$row['deskripsi']."</span><br><br>";
            echo "</td>";
            echo "<td align='left' valign='top' width='100px'>";
            echo "<span style='margin-left: 10px; line-height: 18px; color: #666;'>$nMedia Video</span><br>";
            echo "<span style='margin-left: 10px; line-height: 18px; color: #666;'>{$row['nfollower'] }Follower</span><br>";
            echo "</td>";
            echo "</tr>";
        }
    }
}

function DisplayChannelSearchList($idList, $page)
{
    global $G_ROW_PER_PAGE;

    $startIndex = $page * $G_ROW_PER_PAGE;
    $stopIndex = (($page + 1) * $G_ROW_PER_PAGE) - 1;

    $idArr = explode(",", (string) $idList);
    for($i = 0; $i < count($idArr); $i++)
    {
        if ($i < $startIndex)
            continue;

        if ($i > $stopIndex)
            continue;

        $idChannel = $idArr[$i];

        $sql = "SELECT COUNT(*)
                  FROM jbsel.media
                 WHERE idchannel = $idChannel";
        $nMedia = FetchSingle($sql);

        $sql = "SELECT c.judul as channel, g.nama as guru, pl.nama as pelajaran, c.deskripsi, c.nfollower
                  FROM jbsel.channel c, jbssdm.pegawai g, jbsakad.pelajaran pl
                 WHERE c.idpelajaran = pl.replid
                   AND c.nip = g.nip
                   AND c.id = $idChannel";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_array($res))
        {
            echo "<tr style='cursor: pointer; line-height: 18px;' onclick='sr_showChannelView($idChannel)'>";
            echo "<td align='left' valign='top' width='500px' style='line-height: 20px;'>";
            echo "<span style='color: blue'>".$row['pelajaran'] | $row['guru']."</span><br>";
            echo "<span style='font-family: Arial; font-weight: bold; font-size: 14px'>".$row['channel']."</span><br>";
            echo "<span style='font-family: 'Times New Roman'; font-size: 12px;'>".$row['deskripsi']."</span><br><br>";
            echo "</td>";
            echo "<td align='left' valign='top' width='100px'>";
            echo "<span style='margin-left: 10px; line-height: 18px; color: #666;'>$nMedia Video</span><br>";
            echo "<span style='margin-left: 10px; line-height: 18px; color: #666;'>{$row['nfollower'] }Follower</span><br>";
            echo "</td>";
            echo "</tr>";
        }

    }
}
?>