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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once("../include/sessionchecker.php");
require_once("modul.media.func.php");
require_once("common.func.php");

$op = $_REQUEST["op"];
if ($op == "addMedia")
{
    $idModul = $_REQUEST["idModul"];
    $idMedia = $_REQUEST["idMedia"];
    $urutan = $_REQUEST["urutan"];
    $keterangan = SafeInputText($_REQUEST["keterangan"]);

    OpenDb();
    SimpanKeterangan($idModul, $idMedia, $urutan, $keterangan);
    CloseDb();
}
else if ($op == "setStatusAktif")
{
    $idMediaModul = $_REQUEST["idMediaModul"];
    $newAktif = $_REQUEST["newAktif"];

    OpenDb();
    SetNewMediaModulAktif($idMediaModul, $newAktif);
    CloseDb();

    http_response_code(200);
}
else if ($op == "getStatusAktif")
{
    $no = $_REQUEST['no'];
    $idMediaModul = $_REQUEST["idMediaModul"];

    OpenDb();
    ShowMediaModulAktif($no, $idMediaModul);
    CloseDb();

    http_response_code(200);
}
else if ($op == "editKeterangan")
{
    $idMediaModul = $_REQUEST["idMediaModul"];
    $urutan = $_REQUEST["urutan"];
    $keterangan = SafeInputText($_REQUEST["keterangan"]);

    OpenDb();
    EditKeterangan($idMediaModul, $urutan, $keterangan);
    CloseDb();
}
else if ($op == "hapusMediaModul")
{
    $idMediaModul = $_REQUEST["idMediaModul"];

    OpenDb();
    HapusMediaModul($idMediaModul);
    CloseDb();
}
?>