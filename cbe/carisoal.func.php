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
require_once("include/config.php");
require_once("../include/cbe.config.php");
require_once("include/session.php");
require_once("include/db_functions.php");
require_once("library/genericreturn.php");
require_once("library/httprequest.php");
require_once("library/httpmanager.php");
require_once("library/cbe.state.php");
require_once("library/cbe.system.php");
require_once("library/cbe.session.php");
require_once("library/cbe.protocol.php");
require_once("library/debugger.php");
require_once("library/soaltag.php");
require_once("library/soalimagedata.php");
require_once("common.func.php");

function checkResFileGetUrl($idRes, $resDir, $resType)
{
    $resFile = __DIR__ . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . $resDir . DIRECTORY_SEPARATOR . "$idRes-$resType.jpg";
    if (file_exists($resFile))
        return "res/$resDir/$idRes-$resType.jpg";

    return "res/notfound.jpg";
}

function showCariSoal($idSoal)
{
    $userId = $_SESSION["UserId"];

    OpenDb();
    $sql = "SELECT COUNT(*) 
              FROM jbscbe.webusersoal 
             WHERE idsoal = '$idSoal'
               AND userid = '".$userId."'";
    $nData = (int) FetchSingle($sql);
    if ($nData == 0)
    {
        CloseDb();
        echo "Tidak ditemukan soal dengan id $idSoal";
        return 0;
    }

    $sql = "SELECT DISTINCT idsoal, idujianserta, soalthumb, tingkat, semester, jenis, bobot, id, resdir
              FROM jbscbe.webusersoal 
             WHERE idsoal = '$idSoal'
               AND userid = '".$userId."'";
    ?>

    <table border='1' cellpadding='2' cellspacing='0' width='740' style='border-collapse: collapse; border-width: 1px;'>
    <tr style='background-color: #2c8058; color: #fff; height: 25px'>
        <td width='40px' align='center'>No</td>
        <td width='360px'>Soal</td>
        <td width='*'>Informasi</td>
    </tr>

<?php

    $no = 0;
    $res = QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;

        $idSoal = $row["idsoal"];
        $idUjianSerta = $row["idujianserta"];
        //$imSoal = $row["soalthumb"];
        $judul = "";
        $tanggalujian = "";

        $idRes = $row["id"];
        $resDir = $row["resdir"];
        $imSoal = checkResFileGetUrl($idRes, $resDir, "qsth");

        $viewExp = 0;
        $viewKey = 0;
        $viewSoal = 0;
        $viewAfter = 0;
        $dateDiff = 0;

        $sql = "SELECT DATEDIFF(NOW(), us.tanggal) AS diff, u.viewexp, u.viewkey, u.viewsoal, u.viewafter,
                       u.judul, DATE_FORMAT(u.tanggal, '%d-%M-%d %H:%i:%s') AS tanggal
                  FROM jbscbe.ujian u, jbscbe.ujianserta us
                 WHERE u.id = us.idujian
                   AND us.id = $idUjianSerta";
        $res2 = QueryDb($sql);
        if (mysqli_num_rows($res2) > 0)
        {
            $row2 = mysqli_fetch_array($res2);
            $dateDiff = $row2["diff"];
            $viewExp = $row2["viewexp"];
            $viewKey = $row2["viewkey"];
            $viewSoal = $row2["viewsoal"];
            $viewAfter = $row2["viewafter"];
            $judul = $row2["judul"];
            $tanggalujian = $row2["tanggal"];
        }

        $informasi  = "ID Soal: $idSoal<br>";
        $informasi .= "Ujian: $judul<br>";
        $informasi .= "Tanggal: $tanggalujian<br>";

        $tag = new SoalTag();
        $tag->IdSoal = $idSoal;
        $tag->IdUjianSerta = $idUjianSerta;
        $tag->IdPelajaran = 0;
        $tag->JenisSoal = 0;
        $tag->StatusUjian = 0;
        $tag->ViewKey = $viewKey;
        $tag->ViewExp = $viewExp;
        $tag->ViewSoal = $viewSoal;
        $tag->ViewAfter = $viewAfter;
        $tag->DateDiff = $dateDiff;
        $tag->TipeDataJawaban = 0;

        $jsonTag = $tag->toJson();
        $jsonTag = str_replace("\"", "`", (string) $jsonTag);
        ?>

        <tr style='height: 100px;'>
            <td align='center' style='background-color: #ededed'>
                <?= $no ?>
                <input type='hidden' id='tag-<?=$idSoal?>-<?=$idUjianSerta?>' value='<?=$jsonTag?>'>
            </td>
            <td align='center' style='background-color: #fff'>
                <div style='height: 100px; width: 320px; background-color: #fff; overflow: hidden; text-align: left;'>
<?php           if ($imSoal != '')
                    //echo "<img id='imSoal-$idSoal' class='noRightClickList' src='data:image/jpeg;base64,$imSoal' onclick='bs_showImageSoal($idSoal, $idUjianSerta)'>";
                    echo "<img id='imSoal-$idSoal' class='noRightClickList' src='$imSoal' onclick='bs_showImageSoal($idSoal, $idUjianSerta)'>";
                else
                    echo "<span style='font-style: italic; color: #999'>Gambar soal tidak tersedia karena ujian tidak dikerjakan di JIBAS CBE Web Client</span>";
                ?>
                </div>
            </td>
            <td align='left' valign='top' style='font-size:12px; background-color: #fff;'><?=$informasi?></td>
        </tr>
<?php
    }
    CloseDb();
    echo "</table>";
}
?>