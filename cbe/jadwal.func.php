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
require_once("library/requestjadwaldata.php");
require_once("common.func.php");

function getDataRuangan()
{
    try
    {
        $jsonSession = $_SESSION["Json"];

        $http = new HttpManager();
        $http->setData("ruangan", CbeState::RequestRuangan, "0", $jsonSession, "");
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $jsonData = $result->Data;
        $protocol = CbeDataProtocol::fromJson($jsonData);

        if ((int) $protocol->Status < 0)
            return sendCbeServerError($protocol->Data); // CBE Server Application send Error

        $jsonRuangan = trim((string) $protocol->Data);
        $select = createSelectRuangan($jsonRuangan);

        return GenericReturn::createJson(1, "OK", $select);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function createSelectRuangan($jsonRuangan)
{
    $info = json_decode((string) $jsonRuangan, null, 512, JSON_THROW_ON_ERROR);

    $select = "<select id='jadwal_cbRuangan' class='inputbox' style='width: 220px' onchange='jadwal_changeCbRuangan()'>";
    foreach($info as $key => $value)
    {
        $select .= "<option value='$key'>$value</option>";
    }
    $select .= "</select>";

    return urlencode($select);
}

function showSelectBulan()
{
    $arrBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];

    $select = "<select id='jadwal_cbBulan' class='inputbox' style='width: 120px' onchange='jadwal_changeCbRuangan()'>";
    for($i = 0; $i < count($arrBulan); $i++)
    {
        $selected = ($i + 1) == date('n') ? "selected" : "";
        $select .= "<option value='" . ($i + 1) . "' $selected>" . $arrBulan[$i] . "</option>";
    }
    $select .= "</select>";

    return $select;
}

function showSelectTahun()
{
    $yearNow = date('Y');
    $select = "<select id='jadwal_cbTahun' class='inputbox' style='width: 70px' onchange='jadwal_changeCbRuangan()'>";
    for($i = $yearNow - 1; $i <= $yearNow + 1; $i++)
    {
        $selected = $i == $yearNow ? "selected" : "";
        $select .= "<option value='$i' $selected>$i</option>";
    }
    $select .= "</select>";

    return $select;
}

function getCurrJadwalUjian()
{
    try
    {
        $bulan = date('n');
        $tahun = date('Y');

        echo getJadwalUjian(0, $bulan, $tahun);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function getJadwalUjian($idRuangan, $bulan, $tahun)
{
    try
    {
        $jsonSession = $_SESSION["Json"];

        $request = new RequestJadwalData();
        $request->IdRuang = $idRuangan;
        $request->Bulan = $bulan;
        $request->Tahun = $tahun;

        $http = new HttpManager();
        $http->setData("jadwal", CbeState::JadwalUjian, "0", $jsonSession, $request->toJson());
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $jsonData = $result->Data;
        $protocol = CbeDataProtocol::fromJson($jsonData);

        if ((int) $protocol->Status < 0)
            return sendCbeServerError($protocol->Data); // CBE Server Application send Error

        $jsonJadwal = trim((string) $protocol->Data);

        $table = createTableJadwal($jsonJadwal);

        return GenericReturn::createJson(1, "OK", $table);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function createTableJadwal($jsonJadwal)
{
    $jsonJadwal = str_replace("\\n", "<br>", (string) $jsonJadwal);
    $data = json_decode($jsonJadwal, null, 512, JSON_THROW_ON_ERROR);

    $table  = "<table border='1' cellpadding='2' cellspacing='0' width='1050' style='border-color: #144da4; border-collapse: collapse'>";
    $table .= "<tr style='height: 40px'>";
    $table .= "<td style='width: 150px; font-size: 14px; background-color: #144da4; color: #fff;' align='center'>Senin</td>";
    $table .= "<td style='width: 150px; font-size: 14px; background-color: #144da4; color: #fff;' align='center'>Selasa</td>";
    $table .= "<td style='width: 150px; font-size: 14px; background-color: #144da4; color: #fff;' align='center'>Rabu</td>";
    $table .= "<td style='width: 150px; font-size: 14px; background-color: #144da4; color: #fff;' align='center'>Kamis</td>";
    $table .= "<td style='width: 150px; font-size: 14px; background-color: #144da4; color: #fff;' align='center'>Jumat</td>";
    $table .= "<td style='width: 150px; font-size: 14px; background-color: #144da4; color: #fff;' align='center'>Sabtu</td>";
    $table .= "<td style='width: 150px; font-size: 14px; background-color: #144da4; color: #fff;' align='center'>Minggu</td>";
    $table .= "</tr>";

    for($i = 0; $i < count($data); $i++)
    {
        $Week = $data[$i];

        if ($i % 2 == 0)
        {
            $bgColor1 = "#f5f5dc";
            $bgColor2 = "#eaeac1";
        }
        else
        {
            $bgColor1 = "#eaeac1";
            $bgColor2 = "#f5f5dc";
        }

        $table .= "<tr style='height: 140px'>";
        $table .= "<td style='background-color: $bgColor1' align='left' valign='top'>" . $Week->Info->senin . "</td>";
        $table .= "<td style='background-color: $bgColor2' align='left' valign='top'>" . $Week->Info->selasa . "</td>";
        $table .= "<td style='background-color: $bgColor1' align='left' valign='top'>" . $Week->Info->rabu . "</td>";
        $table .= "<td style='background-color: $bgColor2' align='left' valign='top'>" . $Week->Info->kamis . "</td>";
        $table .= "<td style='background-color: $bgColor1' align='left' valign='top'>" . $Week->Info->jumat . "</td>";
        $table .= "<td style='background-color: $bgColor2' align='left' valign='top'>" . $Week->Info->sabtu . "</td>";
        $table .= "<td style='background-color: $bgColor1' align='left' valign='top'>" . $Week->Info->minggu . "</td>";
        $table .= "</tr>";
    }
    $table .= "</table>";

    return $table;
}
?>