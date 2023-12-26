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
require_once("library/requestpilihandata.php");
require_once("library/requestdaftardata.php");
require_once("library/startujiandata.php");
require_once("library/ujiandatatag.php");
require_once("common.func.php");

function getPilihanUjian()
{
    try
    {
        $userId = $_SESSION["UserId"];
        $sessionId = $_SESSION["SessionId"];

        $jsonSession = $_SESSION["Json"];

        $request = new RequestPilihanData();
        $request->ViewDaftarUjian = 0;

        $http = new HttpManager();
        $http->setData("pilihan", CbeState::PilihanUjianUmum, "0", $jsonSession, $request->toJson());
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $jsonData = $result->Data;
        $protocol = CbeDataProtocol::fromJson($jsonData);

        if ((int) $protocol->Status < 0)
            return sendCbeServerError($protocol->Data); // CBE Server Application send Error

        $jsonPilihan = trim($protocol->Data);

        //$d = new Debugger();
        //$d->Log($jsonPilihan);
        //$d->Close();

        OpenDb();
        $sql = "DELETE FROM jbscbe.webuserintent
                 WHERE userid = '$userId'
                   AND sessionid = '$sessionId'
                   AND type = 'pilihanumum'";
        QueryDb($sql);

        $sql = "INSERT INTO jbscbe.webuserintent
                   SET userid = '$userId', sessionid = '$sessionId', intent = '$jsonPilihan', type = 'pilihanumum'";
        QueryDb($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $idPilihanUmum = (int) FetchSingle($sql);

        $_SESSION["IdPilihanUmum"] = $idPilihanUmum;
        CloseDb();

        return GenericReturn::createJson(1, "OK", $idPilihanUmum);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function getIntentData()
{
    $idPilihanUmum = $_SESSION["IdPilihanUmum"];

    OpenDb();
    $sql = "SELECT intent
              FROM jbscbe.webuserintent
             WHERE id = '$idPilihanUmum'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $result = $row[0];
    }
    else
    {
        $result = "{}";
    }
    CloseDb();

    return $result;
}

function getPilihanDept()
{
    try
    {
        $jsonData = getIntentData();

        $data = json_decode($jsonData);
        $select = createSelectDept($data);

        return GenericReturn::createJson(1, "OK", $select);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function createSelectDept($data)
{
    $select = "<select id='ums_cbDept' class='inputbox' style='width: 220px' onchange='ums_changeCbDept()'>";
    foreach($data as $key => $value)
    {
        $select .= "<option value='$key'>$key</option>";
    }
    $select .= "</select>";

    return $select;
}

function getPilihanPelajaran($dept)
{
    try
    {
        $jsonData = getIntentData();

        $data = json_decode($jsonData);

        $select = "<select id='ums_cbPelajaran' class='inputbox' style='width: 220px' onchange='ums_changeCbPelajaran()'>";
        foreach($data as $key => $info)
        {
            if ($key != $dept)
                continue;

            $arrPel = $info;
            for($i = 0; $i < count($arrPel); $i++)
            {
                $pelInfo = $arrPel[$i];
                $select .= "<option value='$pelInfo->IdPelajaran'>$pelInfo->Pelajaran</option>";
            }
        }
        $select .= "</select>";

        return GenericReturn::createJson(1, "OK", $select);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function getPilihanTingkat($dept, $idPelajaran)
{
    try
    {
        $jsonData = getIntentData();

        $data = json_decode($jsonData);

        $select = "<select id='ums_cbTingkat' class='inputbox' style='width: 220px' onchange='ums_changeCbTingkat()'>";
        foreach($data as $key => $info)
        {
            if ($key != $dept)
                continue;

            $arrPel = $info;
            for($i = 0; $i < count($arrPel); $i++)
            {
                $pelInfo = $arrPel[$i];
                if ($pelInfo->IdPelajaran != $idPelajaran)
                    continue;

                $tkInfo = $pelInfo->DcTingkat;
                foreach($tkInfo as $idtingkat => $tingkat)
                {
                    $select .= "<option value='$idtingkat'>$tingkat</option>";
                }
            }
        }
        $select .= "</select>";

        return GenericReturn::createJson(1, "OK", $select);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function getPilihanSemester($dept, $idPelajaran)
{
    try
    {
        $jsonData = getIntentData();

        $data = json_decode($jsonData);

        $select = "<select id='ums_cbSemester' class='inputbox' style='width: 220px' onchange='ums_changeCbSemester()'>";
        foreach($data as $key => $info)
        {
            if ($key != $dept)
                continue;

            $arrPel = $info;
            for($i = 0; $i < count($arrPel); $i++)
            {
                $pelInfo = $arrPel[$i];
                if ($pelInfo->IdPelajaran != $idPelajaran)
                    continue;

                $smInfo = $pelInfo->DcSemester;
                foreach($smInfo as $idsemester => $semester)
                {
                    $select .= "<option value='$idsemester'>$semester</option>";
                }
            }
        }
        $select .= "</select>";

        return GenericReturn::createJson(1, "OK", $select);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function getDaftarUjian($dept, $viewDaftarUjian, $idPelajaran, $idTingkat, $idSemester)
{
    try
    {
        $jsonSession = $_SESSION["Json"];

        $info = new RequestDaftarData();
        $info->ViewDaftarUjian = $viewDaftarUjian;
        $info->IdPelajaran = $idPelajaran;
        $info->IdTahunAjaran = 0;
        $info->Departemen = $dept;
        $info->IdTingkat = $idTingkat;
        $info->IdSemester = $idSemester;

        $http = new HttpManager();
        $http->setData("daftar", CbeState::DaftarUjianUmum, "0", $jsonSession, $info->toJson());
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $jsonData = $result->Data;
        $protocol = CbeDataProtocol::fromJson($jsonData);

        $jsonUjian = trim($protocol->Data);
        if (strlen($jsonUjian) == 0)
            return GenericReturn::createJson(1, "Tidak ada data ujian", "Tidak ada data ujian");

        $table = createTableUjian($jsonUjian);

        return GenericReturn::createJson(1, "OK", $table);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function createTableUjian($jsonUjian)
{
    $ujianArr = json_decode($jsonUjian);

    $table  = "<table border='1' cellpadding='5' cellspacing='0' style='border-width: 1px; border-collapse: collapse; border-color: #0a6aa1;' >";
    $table .= "<tr style='background-color: #0a6aa1; height: 30px; color: white;'>";
    $table .= "<td align='center' valign='middle' width='20'>No</td>";
    $table .= "<td align='center' valign='middle' width='300'>Ujian</td>";
    $table .= "<td align='center' valign='middle' width='200'>Pelajaran/Peserta</td>";
    $table .= "<td align='center' valign='middle' width='250'>Jadwal</td>";
    $table .= "<td align='center' valign='middle' width='100'>&nbsp;</td>";
    $table .= "</tr>";
    for ($i = 0; $i < count($ujianArr); $i++)
    {
        $bgcolor = $i % 2 == 0 ? "#FFF" : "#EBFAFF";
        $no = $i + 1;
        $ujianInfo = $ujianArr[$i];

        $tag = new UjianDataTag();
        $tag->IdUjian = $ujianInfo->IdUjian;
        $tag->IdUjianSerta = $ujianInfo->IdUjianSerta;
        $tag->IdRemedUjian = $ujianInfo->IdRemedUjian;
        $tag->IdJadwalUjian = $ujianInfo->IdJadwalUjian;
        $tag->StatusUjian = $ujianInfo->StatusUjian;
        $tag->JumlahSoal = $ujianInfo->JumlahSoal;
        $tag->Judul = str_replace("\"", "'", $ujianInfo->Judul);

        $jsonTag = $tag->toJson();
        $jsonTag = str_replace("\"", "`", $jsonTag);


        $table .= "<tr style='background-color: $bgcolor'>";
        $table .= "<td align='center' valign='top'>";
        $table .= "<input type=\"hidden\" id=\"tag-$no\" value=\"$jsonTag\">";
        $table .= "$no</td>";
        $table .= "<td align='left' valign='top'>";
        $table .= nlToBr($ujianInfo->UjianInfo) . "<br>";
        $table .= "</td>";
        $table .= "<td align='left' valign='top'>";
        $table .= nlToBr($ujianInfo->Pelajaran) . "<br>";
        $table .= "</td>";
        $table .= "<td align='left' valign='top'>";
        $table .= nlToBr($ujianInfo->JadwalInfo) . "<br>";
        $table .= "</td>";
        $table .= "<td align='center' valign='top'>";
        $table .= "<input type='button' value='Mulai' class='BtnPrimary' name='btMulai' ";
        $table .= "onclick='ums_startUjian($no)'>";
        $table .= "<span style='color: blue' id='lbInfo-$no'></span>";
        $table .= "</td>";
        $table .= "</tr>";
    }
    $table .= "</table>";

    return $table;
}

function startUjian($idUjian, $idRemedUjian, $idUjianSerta, $idJadwalUjian)
{
    try
    {
        $jsonSession = $_SESSION["Json"];

        $info = new StartUjianData();
        $info->IdUjian = $idUjian;
        $info->IdRemedUjian = $idRemedUjian;
        $info->IdUjianSerta = $idUjianSerta;
        $info->IdJadwalUjian = $idJadwalUjian;

        $http = new HttpManager();
        $http->setData("startujian", CbeState::StartUjian, "0", $jsonSession, $info->toJson());
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $protocol = CbeDataProtocol::fromJson($result->Data);

        $ujianData = json_decode($protocol->Data);
        $_SESSION["UserName"] = $ujianData->Info->UserName;
        $_SESSION["IdUjian"] = $ujianData->Info->IdUjian;
        $_SESSION["IdUjianRemed"] = $ujianData->Info->IdUjianRemed;
        $_SESSION["IdRemedUjian"] = $ujianData->Info->IdRemedUjian;
        $_SESSION["IdUjianSerta"] = $ujianData->Info->IdUjianSerta;
        $_SESSION["IdJadwalUjian"] = $ujianData->Info->IdJadwalUjian;
        $_SESSION["Judul"] = $ujianData->Info->Judul;
        $_SESSION["UjianStarted"] = true;

        $userid = $_SESSION["UserId"];
        $sessionid = $_SESSION["SessionId"];
        $intent = $protocol->Data;
        $intent = str_replace("'", "`", $intent);

        OpenDb();
        $sql = "SELECT COUNT(*) 
                  FROM jbscbe.webuserintent 
                 WHERE userid='$userid' 
                   AND sessionid='$sessionid' 
                   AND type='ujian'";
        $nData = (int) FetchSingle($sql);
        if ($nData == 0)
        {
            $sql = "INSERT INTO jbscbe.webuserintent 
                       SET userid='$userid', 
                           sessionid='$sessionid', 
                           intent='$intent', 
                           type='ujian'";
            QueryDb($sql);
        }
        CloseDb();

        return GenericReturn::createJson(1, "OK", $result->Data);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

?>
