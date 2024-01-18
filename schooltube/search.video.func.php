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
function DisplayVideoSearchList($idMediaList, $page)
{
    global $G_ROW_PER_PAGE;

    if ($idMediaList == "")
    {
        echo "belum ada data video";
        return;
    }

    $startIndex = $page * $G_ROW_PER_PAGE;
    $stopIndex = (($page + 1) * $G_ROW_PER_PAGE) - 1;

    $idArr = explode(",", (string) $idMediaList);
    if ($stopIndex < 0) $stopIndex = count($idArr);

    for($i = 0; $i < count($idArr); $i++)
    {
        if ($i < $startIndex)
            continue;

        if ($i > $stopIndex)
            continue;

        $idMedia = $idArr[$i];

        echo "<tr>";
        echo "<td align='center' valign='top'>";
        ShowSearchMediaVideo($idMedia);
        echo "</td>";
        echo "<td align='left' valign='top'>";
        echo "<div style='height: 180px; background-color: white; overflow: auto; line-height: 20px;'>";
        ShowSearchMediaInfo($idMedia);
        echo "</div>";
        echo "</td>";
        echo "<td align='left' valign='top' style='line-height: 24px;'>";
        ShowSearchMediaCount($idMedia);
        echo "</td>";
        echo "</tr>";
    }
}

function ShowSearchMediaVideo($idMedia)
{
    $sql = "SELECT id, cover
              FROM jbsel.media
             WHERE id = $idMedia";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $idMedia = $row["id"];
        ?>
        <a style="cursor: pointer;" onclick="sr_playVideo('<?=$idMedia?>')">
            <img src="<?= $row['cover'] ?>" alt=""/>
        </a><br><br>
        <?php
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

function ShowSearchMediaInfo($idMedia)
{
    $sql = "SELECT m.id, m.judul, m.deskripsi, IF(m.idkategori IS NULL, 0, m.idkategori) AS idkategori, 
                   c.judul as channel, pg.nama AS guru, pl.nama as pelajaran
              FROM jbsel.media m, jbsel.channel c, jbssdm.pegawai pg, jbsakad.pelajaran pl
             WHERE m.idchannel = c.id
               AND c.nip = pg.nip
               AND c.idpelajaran = pl.replid
               AND m.id = $idMedia";

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

        $info  = "<span style='color: #114fc1'>" . $row["pelajaran"] . " | " .  $row["channel"] . " | " . $row["guru"] . "</span><br>";
        $info .= "<span style='font-family: Arial; font-size: 15px; font-weight: bold;'>" . $row['judul'] . "</span><br>";
        $info .= "<span style='font-family: 'Times New Roman'; font-size: 12px; color: #666'>" . $row['deskripsi'] . "</span><br>";
        $info .= "<b>Kategori CBE</b>: " . $kateValue . "<br>";

        echo $info;
    }
}

?>