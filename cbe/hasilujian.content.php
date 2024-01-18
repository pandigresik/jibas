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
require_once("common.func.php");

function checkResFileGetUrl($idRes, $resDir, $resType)
{
    $resFile = __DIR__ . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . $resDir . DIRECTORY_SEPARATOR . "$idRes-$resType.jpg";
    if (file_exists($resFile))
        return "res/$resDir/$idRes-$resType.jpg";

    return "res/notfound.jpg";
}

$jsonSession = $_SESSION["Json"];

if (isset($_REQUEST["IdUjianSerta"]))
    $_SESSION["IdUjianSerta"] = $_REQUEST["IdUjianSerta"];

$showInfo = 1;
if (isset($_REQUEST["ShowInfo"]))
    $showInfo = $_REQUEST["ShowInfo"];

$idUjianSerta = $_SESSION["IdUjianSerta"];
$userId = $_SESSION["UserId"];

$request = new RequestRekapData();
$request->IdUjianSerta = $idUjianSerta;

$http = new HttpManager();
$http->setData("detail", CbeState::RekapDetail, "0", $jsonSession, $request->toJson());
$result = $http->send();

if ((int) $result->Code < 0)
{
    echo $result->Message;
    exit();
}

$info = CbeDataProtocol::fromJson($result->Data);
if ((int) $info->Status < 0)
{
    echo $info->Data;
    exit();
}

$hasilUjianJson = $info->Data;
$hasilUjian = json_decode((string) $hasilUjianJson, null, 512, JSON_THROW_ON_ERROR);

$nilaiColor = "#DDDDDD";
$sNilai = "--";
$status = "Tunggu Verifikasi Essay";
if ($hasilUjian->StatusUjian != 1)
{
    $cf = new ColorFactory(0, $hasilUjian->SkalaNilai);
    $nilaiColor = $cf->GetColorCode($hasilUjian->Nilai);

    $sNilai = $hasilUjian->Nilai;
    $status = "SELESAI";
}

if (!$hasilUjian->ViewResult)
{
    $nilaiColor = "#DDDDDD";
    $sNilai = "--";
    $status = "--";
}
?>

<?php

echo "<table border='0' width='840' cellpadding='0' cellspacing='0'>";
echo "<tr><td align='left'>";

if ($showInfo == 1)
{
    ?>

    <span style="font-size: 24px"><?= $hasilUjian->Judul ?></span><br>
    <span style="font-size: 18px"><?= $hasilUjian->Pelajaran ?></span><br><br>
    <table border="0" cellpadding="5" cellspacing="0" width="500">
    <tr>
        <td width="90" align="left">Waktu (menit)</td>
        <td width="50" align="center" style="font-weight: bold; font-size: 12px;"><?= $hasilUjian->Waktu ?></td>
        <td width="*" rowspan="6" align="left" valign="top">

            <table border="1" cellpadding="0" cellspacing="0" style="width: 100%; height: 100%">
            <tr style="height: 110px;">
                <td align="center" valign="middle">
                    <div style="width: 100%; height: 110px; background-color: <?= $nilaiColor ?>;
                            line-height: 110px; text-align: center; font-size: 64px; color: #fff;"><?= $sNilai ?></div>
                </td>
            </tr>
            <tr style="height: 30px;">
                <td align="center" valign="middle">
                    <div style="width: 100%; height: 30px; background-color: #686868;
        line-height: 30px; text-align: center; font-size: 14px; color: #fff;">
                        <?= $status ?>
                    </div>
                </td>
            </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td align="left">Jumlah Benar</td>
        <td align="center" style="font-weight: bold; font-size: 12px;"><?= $hasilUjian->ViewResult ? $hasilUjian->JumlahBenar : "?" ?></td>
    </tr>
    <tr>
        <td align="left">Jumlah Salah</td>
        <td align="center" style="font-weight: bold; font-size: 12px;"><?= $hasilUjian->ViewResult ? $hasilUjian->JumlahSalah : "?" ?></td>
    </tr>
    <tr>
        <td align="left">Total Nilai</td>
        <td align="center" style="font-weight: bold; font-size: 12px;"><?= $hasilUjian->ViewResult ? $hasilUjian->TotalNilai : "?" ?></td>
    </tr>
    <tr>
        <td align="left">Total Bobot</td>
        <td align="center" style="font-weight: bold; font-size: 12px;"><?= $hasilUjian->ViewResult ? $hasilUjian->TotalBobot : "?" ?></td>
    </tr>
    <tr>
        <td align="left">Skala Nilai</td>
        <td align="center" style="font-weight: bold; font-size: 12px;"><?= $hasilUjian->SkalaNilai ?></td>
    </tr>
    </table>

<?php
}   // $showInfo
?>

<br>
<table border="1" cellpadding="2" cellspacing="0" width="740" style="border-collapse: collapse; border-width: 1px;">
<tr style="background-color: #2c8058; color: #fff; height: 25px">
    <td width="30px" align="center">No</td>
    <td width="60px" align="center">Hasil</td>
    <td width="320px">Soal</td>
    <td width="100px" align="center">Jawaban</td>
    <td width="100px" align="center">Kunci</td>
    <td width="*" align="center">Nilai</td>
</tr>

<?php
OpenDb();

for($i = 0; $i < count($hasilUjian->LsHasilSoal); $i++)
{
    $info = $hasilUjian->LsHasilSoal[$i];
    $idSoal = $info->IdSoal;

    $imHasil = "images/wait24.png";
    $colorHasil = "#efefef";
    if ($info->Hasil === -1)
    {
        $imHasil = "images/cross24.png";
        $colorHasil = "#fff8f1";
    }
    else if ($info->Hasil === 1)
    {
        $imHasil = "images/check24.png";
        $colorHasil = "#eaffee";
    }

    $fontSize = "24px";
    if ($info->JenisSoal == 4)
        $fontSize = "14px";

    //$hasilUjian->ViewKey = false;

    $nilaiSoal = $info->Nilai;

    $jawaban = "{..}";
    $kunci = "{..}";
    if ($info->TipeDataJawaban === 0)
    {
        $jawaban = $info->Jawaban[0];
        $kunci = $info->Kunci[0];
    }

    // Tidak menampilkan kunci
    if (!$hasilUjian->ViewKey)
        $kunci = "?";

    if (!$hasilUjian->ViewResult)
    {
        $kunci = "?";
        $imHasil = "images/wait24.png";
        $nilaiSoal = "..";
    }

    $tag = new SoalTag();
    $tag->IdSoal = $info->IdSoal;
    $tag->IdUjianSerta = $hasilUjian->IdUjianSerta;
    $tag->IdPelajaran = $hasilUjian->IdPelajaran;
    $tag->JenisSoal = $info->JenisSoal;
    $tag->StatusUjian = $hasilUjian->StatusUjian;
    $tag->ViewKey = $hasilUjian->ViewKey ? 1 : 0;
    $tag->ViewExp = $hasilUjian->ViewExp ? 1 : 0;
    $tag->ViewSoal = $hasilUjian->ViewSoal ? 1 : 0;
    $tag->ViewAfter = $hasilUjian->ViewAfter;
    $tag->DateDiff = $hasilUjian->DateDiff;
    $tag->TipeDataJawaban = $info->TipeDataJawaban;

    $jsonTag = $tag->toJson();
    $jsonTag = str_replace("\"", "`", (string) $jsonTag);

    $imSoal = "";
    $sql = "SELECT id, resdir 
              FROM jbscbe.webusersoal 
             WHERE userid = '$userId' 
               AND idujianserta = '$idUjianSerta' 
               AND idsoal = '".$idSoal."'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $idRes = $row[0];
        $resDir = $row[1];
        $imSoal = checkResFileGetUrl($idRes, $resDir, "qsth");
    }
    ?>
    <tr style="height: 100px;">
        <td align="center" style="background-color: #fefefe">
            <?=$i + 1?>
            <input type="hidden" id="tag-<?=$idSoal?>" value="<?= $jsonTag ?>">
        </td>
        <td align="center" style="background-color: <?=$colorHasil?>"><img src="<?=$imHasil?>"></td>
        <td align="center" style="background-color: #fff">
            <div style="height: 100px; width: 320px; background-color: #fff; overflow: hidden; text-align: left;">
                <?php
                if ($imSoal != "")
                    //echo "<img id='imSoal-$idSoal' class='noRightClickList' src='data:image/jpeg;base64,$imSoal' onclick='hasilujian_showImageSoal($idSoal)'>";
                    echo "<img id='imSoal-$idSoal' class='noRightClickList' src='$imSoal' onclick='hasilujian_showImageSoal($idSoal)'>";
                else
                    echo "<span style='font-style: italic; color: #999'>Gambar soal tidak tersedia karena ujian tidak dikerjakan di JIBAS CBE Web Client</span>";
                ?>
            </div>
        </td>
        <td align="center" style="font-size:<?=$fontSize?>; background-color: #fff"><?=$jawaban?></td>
        <td align="center" style="font-size:<?=$fontSize?>; background-color: #fff"><?=$kunci?></td>
        <td align="center" style="font-size:24px; background-color: #e5ffff;"><?=$nilaiSoal?></td>
    </tr>
    <?php
}
CloseDb();
?>
</table>
<script>
    $('.noRightClickList').bind('contextmenu', function(e) {
        return false;
    });
</script>
<?php
echo "</td></tr></table>";
?>
