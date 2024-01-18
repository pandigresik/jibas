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
require_once("common.func.php");

function showSelectBulan()
{
    $arrBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];

    $select = "<select id='rekap_cbBulan' class='inputbox' style='width: 120px' onchange='rekap_getPelajaran()'>";
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
    $select = "<select id='rekap_cbTahun' class='inputbox' style='width: 70px' onchange='rekap_getPelajaran()'>";
    for($i = $yearNow - 1; $i <= $yearNow + 1; $i++)
    {
        $selected = $i == $yearNow ? "selected" : "";
        $select .= "<option value='$i' $selected>$i</option>";
    }
    $select .= "</select>";

    return $select;
}

function showSelectJenisUjian()
{
    $select  = "<select id='rekap_cbJenisUjian' class='inputbox' style='width: 90px' onchange='rekap_getPelajaran()'>";
    $select .= "<option value='-1'>(semua)</option>";
    $select .= "<option value='0'>Umum</option>";
    $select .= "<option value='1'>Khusus</option>";
    $select .= "</select>";

    return $select;
}

function getPelajaran($bulan, $tahun, $jenisUjian)
{
    try
    {
        $jsonSession = $_SESSION["Json"];

        $request = new RequestRekapData();
        $request->Bulan = $bulan;
        $request->Tahun = $tahun;
        $request->JenisUjian = $jenisUjian;
        $request->IdPelajaran = 0;
        $request->IdUjianSerta = 0;

        $http = new HttpManager();
        $http->setData("pelajaran", CbeState::RekapPelajaran, "0", $jsonSession, $request->toJson());
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $jsonData = $result->Data;
        $protocol = CbeDataProtocol::fromJson($jsonData);

        if ((int) $protocol->Status < 0)
            return sendCbeServerError($protocol->Data); // CBE Server Application send Error

        $jsonPelajaran = trim((string) $protocol->Data);

        $select = createSelectPelajaran($jsonPelajaran);

        return GenericReturn::createJson(1, "OK", $select);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function createSelectPelajaran($jsonPelajaran)
{
    $info = json_decode((string) $jsonPelajaran, null, 512, JSON_THROW_ON_ERROR);

    $select = "<select id='rekap_cbPelajaran' class='inputbox' style='width: 220px' onchange='rekap_changeCbPelajaran()'>";
    foreach($info as $key => $value)
    {
        $select .= "<option value='$key'>$value</option>";
    }
    $select .= "</select>";

    return urlencode($select);
}

function getRekapUjian($bulan, $tahun, $jenisUjian, $idPelajaran)
{
    try
    {
        $jsonSession = $_SESSION["Json"];

        $request = new RequestRekapData();
        $request->Bulan = $bulan;
        $request->Tahun = $tahun;
        $request->JenisUjian = $jenisUjian;
        $request->IdPelajaran = $idPelajaran;
        $request->IdUjianSerta = 0;

        $http = new HttpManager();
        $http->setData("daftar", CbeState::RekapDaftar, "0", $jsonSession, $request->toJson());
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $jsonData = $result->Data;
        $protocol = CbeDataProtocol::fromJson($jsonData);

        if ((int) $protocol->Status < 0)
            return sendCbeServerError($protocol->Data); // CBE Server Application send Error

        $jsonRekap = trim((string) $protocol->Data);

        $table = createTableRekap($jsonRekap);

        return GenericReturn::createJson(1, "OK", $table);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function createTableRekap($jsonRekap)
{
    $data = json_decode((string) $jsonRekap, null, 512, JSON_THROW_ON_ERROR);

    $table  = "<table border='1' cellpadding='2' cellspacing='0' style='border-color: #144da4; border-collapse: collapse'>";
    $table .= "<tr style='height: 30px'>";
    $table .= "<td style='width: 40px; background-color: #144da4; color: #fff;' align='center'>No</td>";
    $table .= "<td style='width: 120px; background-color: #144da4; color: #fff;' align='center'>Nilai</td>";
    $table .= "<td style='width: 350px; background-color: #144da4; color: #fff;' align='center'>Ujian</td>";
    $table .= "<td style='width: 100px; background-color: #144da4; color: #fff;' align='center'>Benar</td>";
    $table .= "<td style='width: 100px; background-color: #144da4; color: #fff;' align='center'>Salah</td>";
    $table .= "<td style='width: 150px; background-color: #144da4; color: #fff;' align='center'>Status</td>";
    $table .= "<td style='width: 100px; background-color: #144da4; color: #fff;' align='center'>&nbsp;</td>";
    $table .= "</tr>";

    for($i = 0; $i < count($data); $i++)
    {
        $no = $i + 1;
        $info = $data[$i];

        $ujian  = "<span style='font-size: 14px; font-weight: bold'>" . $info->Judul . "</span><br>";
        $ujian .= "Tanggal: " . $info->Tanggal . "<br>";
        $ujian .= "Waktu: " . $info->Waktu . " menit";

        $status  = "Nilai KKM: " . $info->NilaiKkm . "<br>";
        $status .= "Hasil: " . $info->Status . "<br>";
        $status .= "Status: " . $info->Info;

        $table .= "<tr style='height: 60px'>";
        $table .= "<td align='center' valign='top' style='background-color: #ededed'>$no</td>";
        $table .= "<td align='center' valign='top' style='font-size: 20px; color: #fff; background-color: $info->Warna'>$info->Hasil</td>";
        $table .= "<td align='left' valign='top'>$ujian</td>";
        $table .= "<td align='center' valign='top' style='font-size: 16px; background-color: #ecfbfc'>$info->JBenar</td>";
        $table .= "<td align='center' valign='top' style='font-size: 16px; background-color: #fcf2ec'>$info->JSalah</td>";
        $table .= "<td align='left' valign='top'>$status</td>";
        $table .= "<td align='center' valign='top'><input id='btDetail-$no' type='button' class='BtnPrimary' value='lihat' onclick='rekap_showDetail($no, $info->IdUjianSerta)'></td>";
        $table .= "</tr>";
        $table .= "<tr id='trDetail-$no' data-visible='0' style='height: 1px; visibility: hidden; display: none;'>";
        $table .= "<td colspan='7'>";
        $table .= "<div id='divDetail-$no' style='position: relative; background-color: #cdf1ff; height: 295px; overflow: auto;'>";
        $table .= "<div id='divDetailContent-$no' style='position: relative; padding-left: 15px; height: 295px;'></div>";
        $table .= "<span style='position: absolute; top: 0; right: 0; background-color: #cdf1ff; color: blue; text-decoration: underline; cursor: pointer;' onclick='rekap_showNewWindow($no,$info->IdUjianSerta)'>buka di jendela baru</span>";
        $table .= "</div>";
        $table .= "</td>";
        $table .= "</tr>";
    }

    $table .= "</table>";

    return $table;
}
?>