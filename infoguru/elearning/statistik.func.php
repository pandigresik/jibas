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

    $sql = "SELECT d.departemen
              FROM jbsakad.departemen d
             WHERE d.aktif = 1
             ORDER BY d.urutan";
    $res = QueryDb($sql);

    $ret = "<select id='departemen' name='departemen' style='width: 200px;' onchange='changeDept()'>";
    while($row = mysqli_fetch_row($res))
    {
        if ($selDept == "")
            $selDept = $row[0];
        $ret .= "<option value='".$row[0]."'>".$row[0]."</option>";
    }
    $ret .= "</select>";

    echo $ret;
}

function ShowDaftarStatistik($dept)
{
    $pelList = [];

    $sql = "SELECT c.idpelajaran, p.nama
              FROM jbsel.channel c, jbsakad.pelajaran p 
             WHERE c.idpelajaran = p.replid
               AND c.aktif = 1
               AND p.aktif = 1
               AND p.departemen = '$dept'
             ORDER BY p.nama";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $pelList[] = [$row[0], $row[1]];
    }

    $nPel = count($pelList);
    if ($nPel == 0)
        return;

    $no = 0;
    for($i = 0; $i < $nPel; $i++)
    {
        $no += 1;

        $idPelajaran = $pelList[$i][0];
        $pelajaran = $pelList[$i][1];

        $sql = "SELECT c.id, c.nip, p.nama
                  FROM jbsel.channel c, jbssdm.pegawai p 
                 WHERE c.nip = p.nip
                   AND c.idpelajaran = $idPelajaran";
        $res = QueryDb($sql);
        $nGuru = mysqli_num_rows($res) + 1;

        $first = true;
        while($row = mysqli_fetch_row($res))
        {
            $idChannel = $row[0];
            $nip = $row[1];
            $guru = $row[2];

            if ($first)
            {
                echo "<tr style='height: 28px'>";
                echo "<td rowspan='$nGuru' align='center' valign='top' style='background-color: #f6f6f6'>$no</td>";
                echo "<td colspan='7' align='left' style='background-color: #d8eff6'><b>$pelajaran</b></td>";
                echo "</tr>";

                $first = false;
            }

            echo "<tr style='height: 28px'>";
            echo "<td align='left'>$guru - $nip</td>";
            echo "<td align='center' style='font-size: 14px'>" . CountChannel($idChannel) . "</td>";
            echo "<td align='center' style='font-size: 14px'>" . CountModul($idChannel) . "</td>";
            echo "<td align='center' style='font-size: 14px'>" . CountVideo($idChannel) . "</td>";
            echo "<td align='center' style='font-size: 14px'>" . CountFollower($idChannel) . "</td>";
            echo "<td align='center' style='font-size: 14px'>" . CountView($idChannel) . "</td>";
            echo "<td align='center' style='font-size: 14px'>" . CountLike($idChannel) . "</td>";
            echo "</tr>";
        }
    }
}

function CountChannel($idChannel)
{
    $sql = "SELECT COUNT(id)
              FROM jbsel.channel 
             WHERE id = $idChannel
               AND aktif = 1";
    return FetchSingle($sql);
}

function CountModul($idChannel)
{
    $sql = "SELECT COUNT(m.id)
              FROM jbsel.channel c, jbsel.modul m 
             WHERE m.idchannel = c.id
               AND c.aktif = 1
               AND m.aktif = 1
               AND c.id = $idChannel";
    return FetchSingle($sql);
}

function CountVideo($idChannel)
{
    $sql = "SELECT COUNT(id)
              FROM jbsel.media
             WHERE idchannel = $idChannel
               AND aktif = 1";
    return FetchSingle($sql);
}

function CountFollower($idChannel)
{
    $sql = "SELECT SUM(nfollower)
              FROM jbsel.channel
             WHERE id = $idChannel
               AND aktif = 1";
    return FetchSingle($sql);
}

function CountView($idChannel)
{
    $sql = "SELECT SUM(m.nview)
              FROM jbsel.channel c, jbsel.media m
             WHERE c.id = m.idchannel
               AND c.id = $idChannel
               AND c.aktif = 1
               AND m.aktif = 1";
    return FetchSingle($sql);
}

function CountLike($idChannel)
{
    $sql = "SELECT SUM(m.nlike)
              FROM jbsel.channel c, jbsel.media m
             WHERE c.id = m.idchannel
               AND c.id = $idChannel
               AND c.aktif = 1
               AND m.aktif = 1";
    return FetchSingle($sql);
}
?>