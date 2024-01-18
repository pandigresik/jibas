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
require_once("library/requestrekapdata.php");
require_once("library/soaltag.php");
require_once("library/colorfactory.php");
require_once("library/soalimagedata.php");
require_once("common.func.php");

function checkResFileGetUrl($idRes, $resDir, $resType)
{
    $resFile = __DIR__ . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . $resDir . DIRECTORY_SEPARATOR . "$idRes-$resType.jpg";
    if (file_exists($resFile))
        return "res/$resDir/$idRes-$resType.jpg";

    return "res/notfound.jpg";
}

function getSoalPenjelasan($idSoal, $viewExp)
{
    $userId = $_SESSION["UserId"];
    $idUjianSerta = $_SESSION["IdUjianSerta"];

    $sql = "SELECT id, resdir 
              FROM jbscbe.webusersoal
             WHERE userid = '$userId'
               AND idujianserta = '$idUjianSerta'
               AND idsoal = '".$idSoal."'";

    OpenDb();

    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        CloseDb();
        return GenericReturn::createJson(-99, "Soal tidak ditemukan!", "");
    }

    $row = mysqli_fetch_row($res);
    $idRes = $row[0];
    $resDir = $row[1];

    $imSoal = checkResFileGetUrl($idRes, $resDir, "qs");
    $imPenjelasan = checkResFileGetUrl($idRes, $resDir, "exp");

    $jenisJawaban = 0;
    $jawaban = "";

    $sql = "SELECT jawaban
              FROM jbscbe.ujiandata
             WHERE idserta = '$idUjianSerta'
               AND idsoal = '".$idSoal."'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $jawaban = $row[0];
        $jenisJawaban = 0;
    }
    else
    {
        $sql = "SELECT jenis, jawaban, jawabanim
                  FROM jbscbe.ujiandataesai
                 WHERE idserta = '$idUjianSerta'
                   AND idsoal = '".$idSoal."'";
        $res = QueryDb($sql);
        $no = 0;
        while ($row = mysqli_fetch_row($res))
        {
            $jenis = (int) $row[0];
            if ($jenis == 1)
            {
                $jawaban = base64_encode((string) $row[2]);
                $jenisJawaban = 2;
                break;
            }
            else
            {
                $no += 1;
                $jenisJawaban = 1;
                $jawaban .= $no . ". " . $row[1] . "<br>";
            }
        }
    }

    CloseDb();

    $soalData = new SoalImageData();
    $soalData->ImageSoal = $imSoal;
    $soalData->ImagePenjelasan = $viewExp == 1 ? $imPenjelasan : "";
    $soalData->JenisJawaban = $jenisJawaban;
    $soalData->Jawaban = $jawaban;

    return GenericReturn::createJson(1, "OK", $soalData->toJson());
}
?>