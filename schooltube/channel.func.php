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
function ShowFollowButton($idChannel)
{
    if (!isset($_SESSION["IsLogin"]))
    {
        echo "<img src='images/follow.png' style='height: 32px;' title='login terlebih dahulu untuk follow channel ini'>";
        return;
    }

    $userCol = $_SESSION["UserCol"];
    $userId = $_SESSION["UserId"];

    $sql = "SELECT COUNT(id)
              FROM jbsel.channelfollow
             WHERE idchannel = $idChannel
               AND $userCol = '".$userId."'";
    $nData = FetchSingle($sql);

    if ($nData != 0)
    {
        $aFollow = 0;
        $aInfo = "unfollow";
        $img = "images/following.png";
    }
    else
    {
        $aFollow = 1;
        $aInfo = "follow";
        $img = "images/follow.png";
    }

    echo "<a style='cursor: pointer' onclick='cn_setFollow($aFollow, $idChannel)'><img src='$img' style='height: 32px;' title='klik untuk $aInfo channel ini'></a>";
}

function SaveFollow($idChannel, $follow)
{
    $userCol = $_SESSION["UserCol"];
    $userId = $_SESSION["UserId"];

    if ($follow == 1)
    {
        $sql = "INSERT INTO jbsel.channelfollow
                   SET $userCol = '$userId', idchannel = $idChannel, timestamp = NOW()";
    }
    else
    {
        $sql = "DELETE FROM jbsel.channelfollow
                 WHERE $userCol = '$userId'
                   AND idchannel = $idChannel";
    }
    QueryDb($sql);

    $sql = "UPDATE jbsel.channel
               SET nfollower = (SELECT COUNT(id) FROM jbsel.channelfollow WHERE idchannel = $idChannel)
             WHERE id = $idChannel";
    QueryDb($sql);
}

function GetFollowerCount($idChannel)
{
    $sql = "SELECT nfollower
              FROM jbsel.channel
             WHERE id = $idChannel";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        return $row[0];

    return 0;
}

function ShowModulChannel($idChannel)
{
    $sql = "SELECT id, judul 
              FROM jbsel.modul
             WHERE idchannel = $idChannel
               AND aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);

    $nRowPerCol = 5;
    $nRow = 0;
    $no = 0;

    echo "<table cellpadding='5' cellspacing='0'>";
    echo "<tr>";
    while($row = mysqli_fetch_array($res))
    {
        $idModul = $row[0];

        $no += 1;
        $nRow += 1;

        if ($nRow == 1)
        {
            echo "<td align='left' valign='top' width='300px'>";
        }

        echo "<a style='font-weight: bold; cursor: pointer; font-family: Verdana' onclick='cn_showModul($idModul)'>".$no. $row['judul']."</a><br><br>";

        if ($nRow == $nRowPerCol)
        {
            echo "</td>";
            $nRow = 0;
        }
    }

    if ($nRow != $nRowPerCol)
        echo "</td>";

    echo "</tr>";
    echo "</table>";
}

function GetModulCount($idChannel)
{
    $sql = "SELECT COUNT(id)
              FROM jbsel.modul
             WHERE idchannel = $idChannel";
    return FetchSingle($sql);
}

function GetVideoCount($idChannel)
{
    $sql = "SELECT COUNT(id)
              FROM jbsel.media
             WHERE idchannel = $idChannel";
    return FetchSingle($sql);
}

function GetVideoList($idChannel, $urutan)
{
    $sql = "SELECT id 
              FROM jbsel.media
             WHERE idchannel = $idChannel
               AND aktif = 1 ";
    if ($urutan == 2)
        $sql .= " ORDER BY nlike DESC";
    else if ($urutan == 3)
        $sql .= " ORDER BY nview DESC";
    else
        $sql .= " ORDER BY urutan ASC";

    $idList = "";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        if ($idList != "") $idList .= ",";
        $idList .= $row[0];
    }

    return $idList;
}

function ShowMedia($idMediaList, $page)
{
    global $G_ROW_PER_PAGE;

    if (strlen(trim((string) $idMediaList)) == 0)
    {
        echo "<br><br><i>Belum ada video</i>";
        return;
    }

    $startIndex = ($page - 1) * $G_ROW_PER_PAGE;
    $stopIndex = ($page * $G_ROW_PER_PAGE) - 1;

    $idArr = explode(",", (string) $idMediaList);
    if ($startIndex > count($idArr))
    {
        echo "";
        return;
    }

    for($i = 0; $i < count($idArr); $i++)
    {
        if ($i < $startIndex)
            continue;

        if ($i > $stopIndex)
            continue;

        $idMedia = $idArr[$i];

        echo "<tr>";
        echo "<td align='center' valign='top'>";
        ShowMediaVideo($idMedia);
        echo "</td>";
        echo "<td align='left' valign='top' style='line-height: 20px;'>";
        ShowMediaInfo($idMedia);
        echo "</td>";
        echo "<td align='left' valign='top' style='line-height: 24px;'>";
        ShowSearchMediaCount($idMedia);
        echo "</td>";
        echo "</tr>";
    }
}

function ShowSearchMediaCount($idMedia)
{
    $sql = "SELECT nlike, nview
              FROM jbsel.media
             WHERE id = $idMedia";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $nlike = $row["nlike"];
        $nview = $row["nview"];

        $info  = "<span style='color: #666; margin-left: 10px;'>$nlike Like</span><br>";
        $info .= "<span style='color: #666; margin-left: 10px;'>$nview View</span><br>";
    }

    echo $info;
}

function ShowMediaVideo($idMedia)
{
    $sql = "SELECT id, cover
              FROM jbsel.media
             WHERE id = $idMedia";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $idMedia = $row["id"];
        ?>
        <a style="cursor: pointer;" onclick="cn_playVideo('<?=$idMedia?>')">
            <img src="<?= $row['cover'] ?>" alt=""/>
        </a><br><br>
        <?php
    }
}

function ShowMediaInfo($idMedia)
{
    $sql = "SELECT m.id, m.judul, m.deskripsi, IF(m.idkategori IS NULL, 0, m.idkategori) AS idkategori, 
                   m.urutan, m.nlike, m.nview
              FROM jbsel.media m
             WHERE m.id = $idMedia";

    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $idKategori = $row['idkategori'];
        $kateValue = "(tidak ada kategori)";

        if ($idKategori != 0)
        {
            $sql = "SELECT kategori
                      FROM jbscbe.kategori
                     WHERE id = $idKategori";
            $res2 = QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
                $kateValue = $row2[0];
        }

        $info  = "<span style='font-family: Arial; font-size: 15px; font-weight: bold;'>" . $row['judul'] . "</span><br>";
        $info .= "<span style='font-family: 'Times New Roman'; font-size: 12px; color: #666'>" . $row['deskripsi'] . "</span><br>";
        $info .= "<b>Kategori CBE</b>: " . $kateValue . "<br><br>";

        echo $info;
    }
}
?>