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
function ShowMediaVideo($idMedia)
{
    global $FILESHARE_ADDR;

    $sql = "SELECT id, cover, videoloc, videoname
              FROM jbsel.media
             WHERE id = $idMedia";
    $res = QueryDbEx($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $url = UrlCombine($FILESHARE_ADDR, $row['videoloc']);
        $url = UrlCombine($url, $row['videoname']);
        ?>
        <a style="cursor: pointer;" onclick="playVideo('<?=$url?>')" target="_blank" >
            <img src="<?= $row['cover'] ?>" alt=""/>
        </a><br><br>
        <?php
    }
}

function ShowMediaInfo($idMedia)
{
    $sql = "SELECT id, judul, urutan, prioritas, objektif, deskripsi, pertanyaan, katakunci, 
                   IF(idkategori IS NULL, 0, idkategori) AS idkategori
              FROM jbsel.media 
             WHERE id = $idMedia";
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

        $info  = "<span style='font-size: 14px; font-weight: bold'>" . $row['judul'] . "</span><br><br>";
        $info .= "<b>Urutan</b>: " . $row['urutan'] . "<br>";
        $info .= "<b>Prioritas</b>: " . ($row['prioritas'] == 1 ? "Materi Utama" : "Pelengkap") . "<br>";
        $info .= "<b>Kategori CBE</b>: " . $kateValue . "<br>";
        $info .= "<b>Deskripsi</b>: " . $row['deskripsi'] . "<br>";
        $info .= "<b>Objektif</b>: " . $row['objektif'] . "<br>";
        $info .= "<b>Pertanyaan</b>: " . $row['pertanyaan'] . "<br>";
        $info .= "<b>Kata Kunci</b>: " . $row['katakunci'] . "<br>";

        echo $info;
    }
}

function ShowMediaAktif($no, $idMedia)
{
    $sql = "SELECT aktif
              FROM jbsel.media 
             WHERE id = $idMedia";
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

        echo "<a style='cursor: pointer' onclick='setStatusAktif($no, $idMedia, $newAktif)'><img src='$src' border='0' title='$title'></a>";
    }
}

function ShowMediaFiles($cnt, $idMedia)
{
    global $FILESHARE_ADDR;

    $sql = "SELECT id, ofilename, filename, fileloc, fileinfo
              FROM jbsel.mediafile
             WHERE idmedia = $idMedia";
    $res2 = QueryDb($sql);

    $tab = "<table border='1' cellspacing='0' cellpadding='2' width='99%' style='border-width: 1px; border-collapse: collapse;'>";
    while($row2 = mysqli_fetch_array($res2))
    {
        $idFile = $row2['id'];

        $loc = UrlCombine($FILESHARE_ADDR, $row2["fileloc"]);
        $loc = UrlCombine($loc, $row2["filename"]);
        $name = $row2['ofilename'];
        $info = $row2['fileinfo'];

        $tab .= "<tr>";
        $tab .= "<td width='90%'><a href='$loc' target='_blank' download='$name'>$name</a></td>";
        $tab .= "<td width='10%' rowspan='2' align='center'><a onclick='hapusFile($cnt, $idMedia, $idFile)' style='cursor: pointer'><img src='../images/ico/hapus.png' border='0'></a></td>";
        $tab .= "</tr>";
        $tab .= "<tr>";
        $tab .= "<td align='left'>$info</td>";
        $tab .= "</tr>";
    }
    $tab .= "</table>";

    echo $tab;
}

function RemoveMediaFile($idFile)
{
    global $FILESHARE_UPLOAD_DIR;

    $sql = "SELECT filename, fileloc
              FROM jbsel.mediafile
             WHERE id = $idFile";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);

    $filename = $row[0];
    $fileloc = UrlToPath($row[1]);

    $delPath = ProperPath($FILESHARE_UPLOAD_DIR);
    $delPath = PathCombine($delPath, $fileloc);
    $delPath = PathCombine($delPath, $filename);
    if (file_exists($delPath))
        unlink($delPath);

    $sql = "DELETE FROM jbsel.mediafile WHERE id = $idFile";
    QueryDb($sql);
}

function SetNewAktif($idMedia, $newAktif)
{
    $sql = "UPDATE jbsel.media SET aktif = $newAktif WHERE id = $idMedia";
    QueryDb($sql);
}

function CountLike($idMedia)
{
    $sql = "SELECT nlike 
              FROM jbsel.media
             WHERE id = $idMedia";
    return FetchSingle($sql);
}

function CountView($idMedia)
{
    $sql = "SELECT nview 
              FROM jbsel.media
             WHERE id = $idMedia";
    return FetchSingle($sql);
}
?>
