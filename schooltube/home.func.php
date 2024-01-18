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

function IsAllowPlaying()
{
    global $G_VIEW_MEDIA_ALLOW, $G_VIEW_MEDIA_INFO;

    if (!$G_VIEW_MEDIA_ALLOW)
    {
        echo "<fieldset style='background-color: #fff6d0; font-size: 14px; line-height: 24px;'>";
        echo "<legend><strong>Maaf, tidak diperkenankan memutar video</strong></legend>";
        echo "$G_VIEW_MEDIA_INFO";
        echo "</fieldset><br><br>";
    }
}

function ShowRandomVideo($dept)
{
    if ($dept == "ALLDEPT")
    {
        $sql = "SELECT id
                  FROM jbsel.media
                 WHERE aktif = 1
                 ORDER BY RAND()
                 LIMIT 8";
    }
    else
    {
        $sql = "SELECT m.id
                  FROM jbsel.media m, jbsel.channel c, jbsakad.pelajaran p
                 WHERE m.idchannel = c.id
                   AND c.idpelajaran = p.replid
                   AND m.aktif = 1
                   AND p.departemen = '$dept'
                 ORDER BY RAND()
                 LIMIT 8";
    }

    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        echo "belum ada video";
        return;
    }

    $cnt = 0;
    echo "<table border='0' cellpadding='2' cellspacing='0' style='margin-top: 10px; margin-left: 10px'>";

    while($row = mysqli_fetch_row($res))
    {
        $idMedia = $row[0];

        $cnt += 1;
        if ($cnt == 1)
        {
            echo "<tr>";
        }

        echo "<td width='250' align='center' valign='top'>";
        ShowMedia($idMedia);
        echo "</td>";

        if ($cnt == 4)
        {
            $cnt = 0;
            echo "</tr>";
        }
    }

    echo "</table>";
}

function GetMostLikedList($dept)
{
    if ($dept == "ALLDEPT")
    {
        $sql = "SELECT id
                  FROM jbsel.media
                 WHERE aktif = 1
                 ORDER BY nlike DESC
                 LIMIT 24";
    }
    else
    {
        $sql = "SELECT m.id
                  FROM jbsel.media m, jbsel.channel c, jbsakad.pelajaran p
                 WHERE m.idchannel = c.id
                   AND c.idpelajaran = p.replid
                   AND m.aktif = 1
                   AND p.departemen = '$dept'
                 ORDER BY nlike DESC
                 LIMIT 24";
    }

    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return "";

    $idList = "";
    while($row = mysqli_fetch_row($res))
    {
        if ($idList != "") $idList .= ",";
        $idList .= $row[0];
    }
    return $idList;
}

function ShowMediaList($idList, $page)
{
    if ($idList == "")
    {
        echo "belum ada video";
        return;
    }

    $startIndex = ($page - 1) * 8;
    $stopIndex = ($page * 8) - 1;

    $idArr = explode(",", (string) $idList);
    if ($startIndex > count($idArr))
    {
        echo "";
        return;
    }

    $cnt = 0;

    echo "<tr><td>";
    echo "<table border='0' cellpadding='2' cellspacing='0' style='margin-top: 10px; margin-left: 10px'>";

    for($i = 0; $i < count($idArr); $i++)
    {
        if ($i < $startIndex)
            continue;

        if ($i > $stopIndex)
            continue;

        $cnt += 1;

        if ($cnt == 1)
            echo "<tr>";

        $idMedia = $idArr[$i];

        echo "<td width='250' align='center' valign='top'>";
        ShowMedia($idMedia);
        echo "</td>";

        if ($cnt == 4)
        {
            $cnt = 0;
            echo "</tr>";
        }
    }

    echo "</table>";
    echo "</td></tr>";
}

function GetMostViewedList($dept)
{
    if ($dept == "ALLDEPT")
    {
        $sql = "SELECT id
                  FROM jbsel.media
                 WHERE aktif = 1
                 ORDER BY nview DESC
                 LIMIT 24";
    }
    else
    {
        $sql = "SELECT m.id
                  FROM jbsel.media m, jbsel.channel c, jbsakad.pelajaran p
                 WHERE m.idchannel = c.id
                   AND c.idpelajaran = p.replid
                   AND m.aktif = 1
                   AND p.departemen = '$dept'
                 ORDER BY nview DESC
                 LIMIT 24";
    }


    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return "";

    $idList = "";
    while($row = mysqli_fetch_row($res))
    {
        if ($idList != "") $idList .= ",";
        $idList .= $row[0];
    }
    return $idList;
}


function ShowMedia($idMedia)
{
    $sql = "SELECT id, cover, judul
              FROM jbsel.media
             WHERE id = $idMedia";
    $res2 = QueryDbEx($sql);
    if ($row2 = mysqli_fetch_array($res2))
    {
        ?>
        <a style="cursor: pointer;" onclick="cn_playVideo('<?=$idMedia?>')">
            <img src="<?= $row2['cover'] ?>" alt=""/>
        </a><br>
        <span style="font-family: Arial; font-size: 12px; font-weight: bold;"><?=$row2['judul']?></span><br><br>

        <?php
    }
}

function ShowCbHomeDepartemen($dept)
{
    $sql = "SELECT departemen
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);
    echo "<select id='homeDept' style='font-size: 12px; height: 27px; width: 170px; background-color: #fff6d0' onchange='hm_changeDept()'>";
    $selected = "ALLDEPT" == $dept ? "selected" : "";
    echo "<option value='ALLDEPT' $selected>(all departement)</option>";
    while($row = mysqli_fetch_row($res))
    {
        $selected = $row[0] == $dept ? "selected" : "";
        echo "<option value='".$row[0]."' $selected>".$row[0]."</option>";
    }
    echo "</select>";
}

?>
