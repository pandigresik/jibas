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
function ShowFollowModul($sortBy)
{
    $userId = $_SESSION["UserId"];
    $userCol = $_SESSION["UserCol"];

    $idList = "";
    $sql = "SELECT DISTINCT mf.idmodul
              FROM jbsel.modulfollow mf, jbsel.modul m
             WHERE mf.idmodul = m.id
               AND m.aktif = 1
               AND $userCol = '".$userId."'";

    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        if ($idList != "") $idList .= ",";
        $idList .= $row[0];
    }

    if ($idList == "")
    {
        echo "<br><br><i>Belum ada modul yang diikuti</i>";
        return;
    }

    $sql = "SELECT m.id, m.judul AS modul, c.judul as channel, m.deskripsi, pl.nama as pelajaran, g.nama as guru, c.nfollower
              FROM jbsel.modul m, jbsel.channel c, jbsakad.pelajaran pl, jbssdm.pegawai g, jbsel.modulfollow mf
             WHERE m.idchannel = c.id
               AND c.idpelajaran = pl.replid
               AND c.nip = g.nip 
               AND m.id = mf.idmodul
               AND m.id IN ($idList)";

    if ($sortBy == 1)
        $sql .= " ORDER BY mf.timestamp DESC";
    else if ($sortBy == 2)
        $sql .= " ORDER BY mf.timestamp ASC";
    else if ($sortBy == 3)
        $sql .= " ORDER BY m.judul ASC";
    else
        $sql .= " ORDER BY m.judul DESC";

    $res = QueryDb($sql);
    echo "<table border='0' cellpadding='5' cellspacing='0' width='600px'>";
    while ($row = mysqli_fetch_array($res))
    {
        $idModul = $row['id'];

        $sql = "SELECT COUNT(*)
                  FROM jbsel.mediamodul
                 WHERE idmodul = $idModul";
        $nMedia = FetchSingle($sql);

        echo "<tr style='cursor: pointer; line-height: 18px;' onclick='sr_showModulView($idModul)'>";
        echo "<td align='left' valign='top' width='500px' style='line-height: 20px;'>";
        echo "<span style='color: blue'>".$row['pelajaran'] | $row['channel'] | $row['guru']."</span><br>";
        echo "<span style='font-family: Arial; font-weight: bold; font-size: 14px'>".$row['modul']."</span><br>";
        echo "<span style='font-family: 'Times New Roman'; font-size: 12px;'>".$row['deskripsi']."</span><br><br>";
        echo "</td>";
        echo "<td align='left' valign='top' width='100px'>";
        echo "<span style='margin-left: 10px; line-height: 18px; color: #666;'>$nMedia Video</span><br>";
        echo "<span style='margin-left: 10px; line-height: 18px; color: #666;'>{$row['nfollower']} Follower</span><br>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function ShowFollowChannel($sortBy)
{
    $userId = $_SESSION["UserId"];
    $userCol = $_SESSION["UserCol"];

    $idList = "";
    $sql = "SELECT DISTINCT cf.idchannel
              FROM jbsel.channelfollow cf, jbsel.channel c
             WHERE cf.idchannel = c.id
               AND c.aktif = 1
               AND cf.$userCol = '".$userId."'";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        if ($idList != "") $idList .= ",";
        $idList .= $row[0];
    }

    if ($idList == "")
    {
        echo "<br><br><i>Belum ada channel yang diikuti</i>";
        return;
    }

    $sql = "SELECT c.id, c.judul as channel, g.nama as guru, pl.nama as pelajaran, c.deskripsi, c.nfollower
              FROM jbsel.channel c, jbssdm.pegawai g, jbsakad.pelajaran pl, jbsel.channelfollow cf
             WHERE c.idpelajaran = pl.replid
               AND c.nip = g.nip
               AND c.id = cf.idchannel 
               AND c.id IN ($idList)";

    if ($sortBy == 1)
        $sql .= " ORDER BY cf.timestamp DESC";
    else if ($sortBy == 2)
        $sql .= " ORDER BY cf.timestamp ASC";
    else if ($sortBy == 3)
        $sql .= " ORDER BY c.judul ASC";
    else
        $sql .= " ORDER BY c.judul DESC";

    $res = QueryDb($sql);
    echo "<table border='0' cellpadding='5' cellspacing='0' width='600px'>";
    while ($row = mysqli_fetch_array($res))
    {
        $idChannel = $row['id'];

        $sql = "SELECT COUNT(*)
                  FROM jbsel.media
                 WHERE idchannel = $idChannel";
        $nMedia = FetchSingle($sql);

        echo "<tr style='cursor: pointer; line-height: 18px;' onclick='sr_showChannelView($idChannel)'>";
        echo "<td align='left' valign='top' width='500px' style='line-height: 20px;'>";
        echo "<span style='color: blue'>".$row['pelajaran'] | $row['guru']."</span><br>";
        echo "<span style='font-family: Arial; font-weight: bold; font-size: 14px'>".$row['channel']."</span><br>";
        echo "<span style='font-family: 'Times New Roman'; font-size: 12px;'>".$row['deskripsi']."</span><br><br>";
        echo "</td>";
        echo "<td align='left' valign='top' width='100px'>";
        echo "<span style='margin-left: 10px; line-height: 18px; color: #666;'>$nMedia Video</span><br>";
        echo "<span style='margin-left: 10px; line-height: 18px; color: #666;'>{$row['nfollower']} Follower</span><br>";
        echo "</td>";

        echo "</tr>";
    }
    echo "</table>";
}
?>
