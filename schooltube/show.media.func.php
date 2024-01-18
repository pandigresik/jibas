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
        echo "<span style='font-family: Arial; font-size: 14px;'>";
        echo "<strong>Maaf, tidak diperkenankan memutar video ini karena <br><br>$G_VIEW_MEDIA_INFO</strong>";
        echo "</span>";
        exit();
    }
}

function SaveViewCount($sessionId, $idMedia)
{
    $sql = "SELECT COUNT(*)
              FROM jbsel.viewhistory
             WHERE viewdate = CURDATE()
               AND sessionid = '$sessionId'
               AND idmedia = $idMedia";
    $nData = FetchSingle($sql);
    if ($nData == 0)
    {
        $sql = "INSERT INTO jbsel.viewhistory
                   SET viewdate = CURDATE(), sessionid = '$sessionId', idmedia = $idMedia";
        QueryDb($sql);

        $sql = "UPDATE jbsel.media
                   SET nview = nview + 1
                 WHERE id = $idMedia";
        QueryDb($sql);
    }
}

function ShowLikeCount($idMedia)
{
    $sql = "SELECT nlike
              FROM jbsel.media
             WHERE id = $idMedia";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        echo $row[0];
    }
    else
    {
        echo 0;
    }
}

function ShowViewCount($idMedia)
{
    $sql = "SELECT nview
              FROM jbsel.media
             WHERE id = $idMedia";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        echo $row[0];
    }
    else
    {
        echo 0;
    }
}

function ShowLikeButton($idMedia)
{
    if (!isset($_SESSION["IsLogin"]))
    {
        echo "<img src='images/nlike.png' title='login dahulu untuk like video ini'>";
        return;
    }

    $userCol = $_SESSION["UserCol"];
    $userId = $_SESSION["UserId"];

    $sql = "SELECT COUNT(id)
              FROM jbsel.medialike
             WHERE idmedia = $idMedia
               AND $userCol = '".$userId."'";
    $nLike = FetchSingle($sql);

    if ($nLike == 0)
        echo "<a style='cursor: pointer' onclick=\"setMediaLike(1, $idMedia)\"><img src='images/nlike.png' title='klik untuk like video ini'></a>";
    else
        echo "<a style='cursor: pointer' onclick=\"setMediaLike(0, $idMedia)\"><img src='images/alike.png' title='klik untuk unlike video ini'></a>";
}

function ShowMediaInfo()
{
    global $deskripsi, $objektif, $pertanyaan, $idKategori;

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

    echo "<span style='font-size: 12px; line-height:22px'><br>";
    echo "$deskripsi<br><br>";
    echo "<strong>Kategori CBE</strong>: $kateValue<br><br>";
    echo "<strong>Objektif</strong>: $objektif<br><br>";
    echo "<strong>Pertanyaan</strong>: $pertanyaan<br><br>";
    echo "</span>";
}

function ShowCountFiles($idMedia)
{
    $sql = "SELECT COUNT(id)
              FROM jbsel.mediafile
             WHERE idmedia = $idMedia";
    return FetchSingle($sql);
}

function ShowMediaFiles($idMedia)
{
    global $FILESHARE_ADDR;

    $sql = "SELECT id, ofilename, filename, fileloc, fileinfo
              FROM jbsel.mediafile
             WHERE idmedia = $idMedia";
    $res2 = QueryDb($sql);

    $tab = "<table border='1' cellspacing='0' cellpadding='5' width='99%' style='border-width: 1px; border-collapse: collapse;'>";
    while($row2 = mysqli_fetch_array($res2))
    {
        $loc = UrlCombine($FILESHARE_ADDR, $row2["fileloc"]);
        $loc = UrlCombine($loc, $row2["filename"]);
        $name = $row2['ofilename'];
        $info = $row2['fileinfo'];

        $tab .= "<tr style='height: 24px;'>";
        $tab .= "<td width='50%'><a href='$loc' target='_blank' download='$name'>$name</a></td>";
        $tab .= "<td width='40%'>$info</td>";
        $tab .= "<tr>";
    }
    $tab .= "</table>";

    echo $tab;
}

function ShowCountNotes($idMedia)
{
    if (!isset($_SESSION["IsLogin"]))
        return "-";

    $userCol = $_SESSION["UserCol"];
    $userId = $_SESSION["UserId"];

    $sql = "SELECT COUNT(id)
              FROM jbsel.medianotes
             WHERE idmedia = $idMedia
               AND $userCol = '".$userId."'";
    return FetchSingle($sql);

}

function SetMediaLike($like, $idMedia)
{
    if (!isset($_SESSION["IsLogin"]))
        return 0;

    $userCol = $_SESSION["UserCol"];
    $userId = $_SESSION["UserId"];

    if ($like == 1)
        $sql = "INSERT INTO jbsel.medialike 
                   SET idmedia = $idMedia, $userCol = '$userId', timestamp = NOW()";
    else
        $sql = "DELETE FROM jbsel.medialike 
                 WHERE idmedia = $idMedia
                   AND $userCol = '".$userId."'";
    QueryDb($sql);

    $nLike = 0;
    $sql = "SELECT COUNT(id)
              FROM jbsel.medialike
             WHERE idmedia = $idMedia";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        $nLike = $row[0];

    $sql = "UPDATE jbsel.media
               SET nlike = $nLike
             WHERE id = $idMedia";
    QueryDb($sql);

    return $nLike;
}

function ShowMediaNotes($idMedia)
{
    if (!isset($_SESSION["IsLogin"]))
    {
        echo "<br><br>Login terlebih dahulu untuk melihat catatan";
        return;
    }

    echo "<span style='font-size: 12px; font-weight: bold'>Catatan Baru:</span><br>";
    echo "<table border='0' cellspacing='2' cellpadding='0'>";
    echo "<tr>";
    echo "<td valign='top'><textarea rows='3' cols='70' id='notes'></textarea></td>";
    echo "<td valign='top'><input id='btSaveNotes' type='button' class='but' value='Simpan' onclick='saveNotes($idMedia)'></td>";
    echo "</tr>";
    echo "</table>";
    echo "<br>";
    echo "<span style='font-size: 12px; font-weight: bold'>Daftar Catatan:</span><br>";
    echo "<div id='divNotesList' style='height: 200px; overflow: auto;'>";
    ShowMediaNotesList($idMedia);
    echo "</div>";
    ?>
<?php
}

function SaveNotes($idMedia, $notes)
{
    if (!isset($_SESSION["IsLogin"]))
        return;

    $notes = SafeInputText($notes);
    $notes = str_replace("\n", "<br>", (string) $notes);
    $userCol = $_SESSION["UserCol"];
    $userId = $_SESSION["UserId"];

    $sql = "INSERT INTO jbsel.medianotes
               SET $userCol = '$userId', idmedia = $idMedia, notes = '$notes', timestamp = NOW()";
    QueryDb($sql);
}

function ShowMediaNotesList($idMedia)
{
    if (!isset($_SESSION["IsLogin"]))
        echo "-";

    $userCol = $_SESSION["UserCol"];
    $userId = $_SESSION["UserId"];

    $sql = "SELECT id, DATE_FORMAT(timestamp, '%d-%m-%Y %H:%i:%s') AS tanggal, notes
              FROM jbsel.medianotes
             WHERE $userCol = '$userId'
               AND idmedia = $idMedia
             ORDER BY timestamp DESC";
    $res = QueryDb($sql);
    $nNotes = mysqli_num_rows($res);

    echo "<input type='hidden' id='nNotes' value='$nNotes'>";
    echo "<table border='0' cellpadding='2' cellspacing='0' width='700'>";
    while($row = mysqli_fetch_row($res))
    {
        $idNotes = $row[0];

        echo "<tr>";
        echo "<td width='650' align='left' valign='top' style='line-height: 18px;'>";
        echo "<span style='font-style: italic; color: #666'>" . $row[1] . "</span><br>";
        echo $row[2];
        echo "<br></td>";
        echo "<td align='center' valign='top'>";
        echo "<a style='cursor: pointer;' onclick='removeNotes($idMedia, $idNotes)'><img src='images/hapus.png' border='0'></a>";
        echo "</td>";
        echo "</tr>";
        echo "<tr><td colspan='2'><hr style='border: none; height: 1px; color: #333; background-color: #333;'></td></tr>";
    }
    echo "</table>";
}

function RemoveNotes($idNotes)
{
    $sql = "DELETE FROM jbsel.medianotes
             WHERE id = $idNotes";
    QueryDb($sql);
}
?>
