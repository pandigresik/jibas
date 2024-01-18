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
require_once("library/ImageResize.php");
require_once("library/requestpilihandata.php");
require_once("library/requestdaftardata.php");
require_once("library/requestsoaldata.php");
require_once("library/requestelapsedtime.php");
require_once("library/startujiandata.php");
require_once("library/updateelapsedtimedata.php");
require_once("library/finishujiandata.php");
require_once("common.func.php");

class SoalInfo
{
    public $IdSoal;
    public $Soal;
    public $Jenis;
    public $JenisEssay;
    public $NJawaban;
    public $SoalGabungJawaban;

    public function toJson()
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }
}

function getSessionId()
{
    return $_SESSION["SessionId"];
}

function getElapsedTime()
{
    try
    {
        $jsonSession = $_SESSION["Json"];

        $request = new RequestElapsedTime();
        $request->IdUjianSerta = $_SESSION["IdUjianSerta"];

        $http = new HttpManager();
        $http->setData("requestelapsed", CbeState::GetElapsedTime, "0", $jsonSession, $request->toJson());
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $info = CbeDataProtocol::fromJson($result->Data);
        if ((int) $info->Status < 0)
            return GenericReturn::createJson((int) $info->Status, $info->Data, ""); // Login gagal

        return GenericReturn::createJson(1, "OK", $info->Data);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function getUjianData()
{
    $userId = $_SESSION["UserId"];
    $sessionId = getSessionId();

    $sql = "SELECT intent 
              FROM jbscbe.webuserintent
             WHERE userid = '$userId'
               AND sessionid = '$sessionId'
               AND type = 'ujian'";

    OpenDb();
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return GenericReturn::createJson(-99, "Tidak ditemukan data ujian", "");

    $row = mysqli_fetch_row($res);
    $json = $row[0];
    CloseDb();

    return GenericReturn::createJson(1, "OK", $json);
}

function updateUjianData($json)
{
    $userId = $_SESSION["UserId"];
    $sessionId = getSessionId();

    $json = str_replace("'", "`", (string) $json);

    $sql = "UPDATE jbscbe.webuserintent
               SET intent = '$json' 
             WHERE userid = '$userId'
               AND sessionid = '$sessionId'
               AND type = 'ujian'";

    OpenDb();
    QueryDb($sql);
    CloseDb();

    return GenericReturn::createJson(1, "OK", $json);
}

function getResFilePath($idRes, $resDir, $resType)
{
    return __DIR__ . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . $resDir . DIRECTORY_SEPARATOR . "$idRes-$resType.jpg";
}

function checkResFileGetUrl($idRes, $resDir, $resType)
{
    $resFile = __DIR__ . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . $resDir . DIRECTORY_SEPARATOR . "$idRes-$resType.jpg";
    if (file_exists($resFile))
        return "res/$resDir/$idRes-$resType.jpg";

    return "res/notfound.jpg";
}

function checkCreateResDir($resDir)
{
    $dir = __DIR__ . DIRECTORY_SEPARATOR . "res";
    if (!file_exists($dir))
        mkdir($dir);

    $dir = $dir . DIRECTORY_SEPARATOR . $resDir;
    if (!file_exists($dir))
        mkdir($dir);
}

function getSoal($idSoal)
{
    try
    {
        $userId = $_SESSION["UserId"];
        $sessionId = $_SESSION["SessionId"];
        $idUjianSerta = $_SESSION["IdUjianSerta"];
        $jsonSession = $_SESSION["Json"];

        $sql = "SELECT COUNT(*)
                  FROM jbscbe.webusersoal
                 WHERE userid = '$userId'
                   AND idsoal = '$idSoal'
                   AND idujianserta = '".$idUjianSerta."'";

        OpenDb();
        $nsoal = (int) FetchSingle($sql);
        if ($nsoal > 0)
        {
            $soalInfo = new SoalInfo();

            $sql = "SELECT id, resdir, jenis, jenisessay, njawaban, soalgabungjawaban
                      FROM jbscbe.webusersoal
                     WHERE userid = '$userId'
                       AND idsoal = '$idSoal'
                       AND idujianserta = '".$idUjianSerta."'";
            $res = QueryDb($sql);
            $row = mysqli_fetch_row($res);

            $idRes = $row[0];
            $resDir = $row[1];

            $soalInfo->IdSoal = $idSoal;
            $soalInfo->Soal = checkResFileGetUrl($idRes, $resDir, "qs");
            $soalInfo->Jenis = $row[2];
            $soalInfo->JenisEssay = $row[3];
            $soalInfo->NJawaban = $row[4];
            $soalInfo->SoalGabungJawaban = $row[5];

            CloseDb();

            return GenericReturn::createJson(1, "OK", $soalInfo->toJson());
        }
        CloseDb();

        $requestSoalData = new RequestSoalData();
        $requestSoalData->IdUjianSerta = $idUjianSerta;
        $requestSoalData->IdSoal = $idSoal;

        $http = new HttpManager();
        $http->setTimeout(15000);
        $http->setData("requestsoal", CbeState::RequestSoal, "0", $jsonSession, $requestSoalData->toJson());
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $info = CbeDataProtocol::fromJson($result->Data);
        if ((int) $info->Status < 0)
            return GenericReturn::createJson((int) $info->Status, $info->Data, ""); // Login gagal

        $soalData = json_decode((string) $info->Data, null, 512, JSON_THROW_ON_ERROR);

        $soalInfo = new SoalInfo();
        $soalInfo->IdSoal = $idSoal;
        $soalInfo->Soal = ""; // later
        $soalInfo->Jenis = $soalData->Jenis;
        $soalInfo->JenisEssay = $soalData->JenisEssay;
        $soalInfo->NJawaban = $soalData->NJawaban;
        $soalInfo->SoalGabungJawaban = $soalData->SoalGabungJawaban;

        // -- Resize for Thumbnail
        $imSoal = ImageResize::createFromString(base64_decode((string) $soalData->Soal));
        $imSoal->scale(25);
        $soalThumb = base64_encode($imSoal->getImageAsString());

        $resDir = date("Ym");
        checkCreateResDir($resDir);

        OpenDb();

        $sql = "INSERT INTO jbscbe.webusersoal
                   SET userid = '$userId', idujianserta = '$idUjianSerta', 
                       idsoal = '$idSoal', tanggal = NOW(), soal = '-', soalthumb = '-',
                       penjelasan = '-', jenis = '$soalData->Jenis', jenisessay = '$soalData->JenisEssay', 
                       njawaban = '$soalData->NJawaban', soalgabungjawaban = '$soalData->SoalGabungJawaban',
                       departemen = '$soalData->Departemen', idpelajaran = '$soalData->IdPelajaran', pelajaran = '$soalData->Pelajaran',
                       idtingkat = '$soalData->IdTingkat', tingkat = '$soalData->Tingkat', 
                       idsemester = '$soalData->IdSemester', semester = '$soalData->Semester',
                       idkategori = '$soalData->IdKategori', kategori = '$soalData->Kategori',
                       idindikator = '$soalData->IdIndikator', indikator = '$soalData->IdIndikator',
                       idtema = '$soalData->IdTema', tema = '$soalData->Tema', resdir = '".$resDir."'";
        QueryDb($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $idRes = (int) FetchSingle($sql);

        CloseDb();

        // Simpan Soal
        $resFile = getResFilePath($idRes, $resDir, "qs");
        file_put_contents($resFile, base64ToImage($soalData->Soal));

        // Simpan Thumbnail
        $resFile = getResFilePath($idRes, $resDir, "qsth");
        file_put_contents($resFile, base64ToImage($soalThumb));

        // Simpan Penjelasan
        $resFile = getResFilePath($idRes, $resDir, "exp");
        file_put_contents($resFile, base64ToImage($soalData->Penjelasan));

        // Informasi Url File Soal
        $soalInfo->Soal = checkResFileGetUrl($idRes, $resDir, "qs");

        return GenericReturn::createJson(1, "OK", $soalInfo->toJson());
    }
    catch (Exception $exc)
    {
        return GenericReturn::createJson(-99, $exc->getMessage(), "");
    }
}

function base64ToImage($base64)
{
    try
    {
        return base64_decode((string) $base64);
    }
    catch (Exception)
    {
        try
        {
            $notRight = __DIR__ . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . "notright.jpg";
            if (file_exists($notRight))
                return file_get_contents($notRight);
            return "";
        }
        catch (Exception)
        {
            return "";
        }
    }
}

function simpanJawaban($jwbJson)
{
    $jsonSession = $_SESSION["Json"];

    $http = new HttpManager();
    $http->setTimeout(15000);
    $http->setData("simpanjawaban", CbeState::SimpanJawaban, "0", $jsonSession, $jwbJson);
    $result = $http->send();

    if ((int) $result->Code < 0)
        return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

    $info = CbeDataProtocol::fromJson($result->Data);
    if ((int) $info->Status < 0)
        return GenericReturn::createJson((int) $info->Status, $info->Data, ""); // Process gagal

    return GenericReturn::createJson(1, "OK", "");
}

function updateElapsedTime($elapsed)
{
    //$d = new Debugger();

    $idUjianSerta = $_SESSION["IdUjianSerta"];
    $jsonSession = $_SESSION["Json"];

    $info = new UpdateElapsedTimeData();
    $info->IdUjianSerta = $idUjianSerta;
    $info->Elapsed = $elapsed;

    //$d->Log($info->toJson());

    $http = new HttpManager();
    $http->setData("updateelapsedtime", CbeState::UpdateElapsedTime, "0", $jsonSession, $info->toJson());
    $result = $http->send();

    //$d->Log($result);
    //$d->Close();

    if ((int) $result->Code < 0)
        return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

    $info = CbeDataProtocol::fromJson($result->Data);
    if ((int) $info->Status < 0)
        return GenericReturn::createJson((int) $info->Status, $info->Data, ""); // Process gagal

    return GenericReturn::createJson(1, "OK", "");
}

function finishUjian($ujianData)
{
    //$d = new Debugger();

    $jsonSession = $_SESSION["Json"];
    $idUjianSerta = $_SESSION["IdUjianSerta"];
    $userId = $_SESSION["UserId"];
    $sessionId = $_SESSION["SessionId"];

    $ujianData = str_replace("\\\"", "\"", (string) $ujianData);

    $finishInfo = new FinishUjianData();
    $finishInfo->IdUjianSerta = $idUjianSerta;
    $finishInfo->UjianData = $ujianData;

    //$d->Log($ujianData);
    //$d->Log($finishInfo->toJson());
    //$d->Close();

    $http = new HttpManager();
    $http->setTimeout(30000);
    $http->setData("finishujian", CbeState::FinishUjian, "0", $jsonSession, $finishInfo->toJson());
    $result = $http->send();

    if ((int) $result->Code < 0)
        return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

    $info = CbeDataProtocol::fromJson($result->Data);
    if ((int) $info->Status < 0)
        return GenericReturn::createJson((int) $info->Status, $info->Data, ""); // Process gagal

    OpenDb();
    $sql = "DELETE FROM jbscbe.webuserintent 
             WHERE userid = '$userId' 
               AND sessionid = '$sessionId' 
               AND type = 'ujian'";
    QueryDb($sql);
    CloseDb();

    return GenericReturn::createJson(1, "OK", $info->Data);
    //return GenericReturn::createJson(1, "Message", "DATA");
}

function checkDownloadSoal($idSoal)
{
    try
    {
        $jsonSession = $_SESSION["Json"];
        $userId = $_SESSION["UserId"];
        $idUjianSerta = $_SESSION["IdUjianSerta"];

        $sql = "SELECT COUNT(*) 
                  FROM jbscbe.webusersoal
                 WHERE userid = '$userId'
                   AND idujianserta = '$idUjianSerta'
                   AND idsoal = '".$idSoal."'";
        OpenDb();
        $nSoal = (int) FetchSingle($sql);
        CloseDb();

        if ($nSoal > 0)
            return GenericReturn::createJson(1, "Soal already downloaded", "");

        $requestSoalData = new RequestSoalData();
        $requestSoalData->IdUjianSerta = $idUjianSerta;
        $requestSoalData->IdSoal = $idSoal;

        $http = new HttpManager();
        $http->setData("requestsoal", CbeState::RequestSoal, "0", $jsonSession, $requestSoalData->toJson());
        $result = $http->send();

        if ((int)$result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $info = CbeDataProtocol::fromJson($result->Data);
        if ((int)$info->Status < 0)
            return GenericReturn::createJson((int)$info->Status, $info->Data, ""); // Login gagal

        $soalData = json_decode((string) $info->Data, null, 512, JSON_THROW_ON_ERROR);

        // -- Resize for Thumbnail
        $imSoal = ImageResize::createFromString(base64_decode((string) $soalData->Soal));
        $imSoal->scale(25);
        $soalThumb = base64_encode($imSoal->getImageAsString());

        $resDir = date("Ym");
        checkCreateResDir($resDir);

        OpenDb();
        $sql = "INSERT INTO jbscbe.webusersoal
                   SET userid = '$userId', idujianserta = '$idUjianSerta', 
                       idsoal = '$idSoal', tanggal = NOW(), soal = '-', soalthumb = '-',
                       penjelasan = '-', jenis = '$soalData->Jenis', jenisessay = '$soalData->JenisEssay', 
                       njawaban = '$soalData->NJawaban', soalgabungjawaban = '$soalData->SoalGabungJawaban',
                       departemen = '$soalData->Departemen', idpelajaran = '$soalData->IdPelajaran', pelajaran = '$soalData->Pelajaran',
                       idtingkat = '$soalData->IdTingkat', tingkat = '$soalData->Tingkat', 
                       idsemester = '$soalData->IdSemester', semester = '$soalData->Semester',
                       idkategori = '$soalData->IdKategori', kategori = '$soalData->Kategori',
                       idindikator = '$soalData->IdIndikator', indikator = '$soalData->IdIndikator',
                       idtema = '$soalData->IdTema', tema = '$soalData->Tema', resdir = '".$resDir."'";
        QueryDb($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $idRes = (int) FetchSingle($sql);

        CloseDb();

        // Simpan Soal
        $resFile = getResFilePath($idRes, $resDir, "qs");
        file_put_contents($resFile, base64ToImage($soalData->Soal));

        // Simpan Thumbnail
        $resFile = getResFilePath($idRes, $resDir, "qsth");
        file_put_contents($resFile, base64ToImage($soalThumb));

        // Simpan Penjelasan
        $resFile = getResFilePath($idRes, $resDir, "exp");
        file_put_contents($resFile, base64ToImage($soalData->Penjelasan));

        return GenericReturn::createJson(1, "Soal downloaded", "");
    }
    catch (Exception $exc)
    {
        return GenericReturn::createJson(-99, $exc->getMessage(), "");
    }
}

function getGambarSoal($idSoal)
{
    try
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
        CloseDb();

        $resFile = checkResFileGetUrl($idRes, $resDir, "qs");
        return GenericReturn::createJson(1, "OK", $resFile);
    }
    catch (Exception $exc)
    {
        return GenericReturn::createJson(-99, $exc->getMessage(), "");
    }
}
?>

