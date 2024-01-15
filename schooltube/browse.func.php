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
function ShowCbDepartemen()
{
    global $selDept;

    $sql = "SELECT departemen 
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";

    echo "<select id='cbDepartemen' style='height: 25px; width: 220px' onchange='bw_changeDept()'>";
    $res = QueryDb($sql);
    while ($row = mysqli_fetch_row($res))
    {
        if ($selDept == "") $selDept = $row[0];
        echo "<option value='".$row[0]."'>".$row[0]."</option>";
    }
    echo "</select>";
}

function ShowCbPelajaran($dept)
{
    $sql = "SELECT DISTINCT pel.replid, pel.nama
              FROM jbsel.channel c, jbsakad.pelajaran pel
             WHERE c.idpelajaran = pel.replid
               AND pel.departemen = '$dept'
               AND c.aktif = 1
               AND pel.aktif = 1
             ORDER BY pel.nama";
    $res = QueryDb($sql);

    echo "<select id='cbPelajaran' style='height: 25px; width: 300px' onchange='bw_changePel()'>";
    if (mysqli_num_rows($res) == 0)
    {
        echo "<option value='0'>(belum ada channel pelajaran)</option>";
    }
    else
    {
        while ($row = mysqli_fetch_row($res))
        {
            echo "<option value='".$row[0]."'>".$row[1]."</option>";
        }
    }
    echo "</select>";
}

function BrowseChannel($idPelajaran)
{
    $sql = "SELECT c.id, c.judul as channel, g.nama as guru, pl.nama as pelajaran, c.deskripsi, c.nfollower
              FROM jbsel.channel c, jbssdm.pegawai g, jbsakad.pelajaran pl
             WHERE c.idpelajaran = pl.replid
               AND c.nip = g.nip
               AND c.idpelajaran = $idPelajaran
               AND c.aktif = 1
             ORDER BY c.urutan";
    $res = QueryDb($sql);

    echo "<table border='0' width='900' cellpadding='2' cellspacing='0'>";
    while ($row = mysqli_fetch_array($res))
    {
        $idChannel = $row['id'];

        $sql = "SELECT COUNT(*)
                  FROM jbsel.media
                 WHERE idchannel = $idChannel";
        $nMedia = FetchSingle($sql);

        echo "<tr style='cursor: pointer; line-height: 18px;' onclick='bw_showChannelView($idChannel)'>";
        echo "<td align='left' valign='top' width='500px' style='line-height: 20px;'>";
        echo "<span style='color: blue'>".$row['pelajaran'] | $row['guru']."</span><br>";
        echo "<span style='font-family: Arial; font-weight: bold; font-size: 14px'>".$row['channel']."</span><br>";
        echo "<span style='font-family: 'Times New Roman'; font-size: 12px;'>".$row['deskripsi']."</span><br><br>";
        echo "</td>";
        echo "<td align='left' valign='top' width='100px'><br>";
        echo "<span style='margin-left: 10px; line-height: 18px; color: #666;'>$nMedia Video</span><br>";
        echo "<span style='margin-left: 10px; line-height: 18px; color: #666;'>{$row['nfollower']} Follower</span><br>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
