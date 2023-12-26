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
require_once("media.content.func.php");
require_once("common.func.php");

$op = $_REQUEST["op"];
if ($op == "refreshVideo")
{
    $idMedia = $_REQUEST["idMedia"];

    OpenDb();
    ShowMediaVideo($idMedia);
    CloseDb();

    http_response_code(200);
}
else if ($op == "refreshInfo")
{
    $idMedia = $_REQUEST["idMedia"];

    OpenDb();
    ShowMediaInfo($idMedia);
    CloseDb();

    http_response_code(200);
}
else if ($op == "removeFile")
{
    $idFile = $_REQUEST["idFile"];

    OpenDb();
    RemoveMediaFile($idFile);
    CloseDb();
}
else if ($op == "refreshFile")
{
    $no = $_REQUEST['no'];
    $idMedia = $_REQUEST["idMedia"];

    OpenDb();
    ShowMediaFiles($no, $idMedia);
    CloseDb();

    http_response_code(200);
}
else if ($op == "setStatusAktif")
{
    $idMedia = $_REQUEST["idMedia"];
    $newAktif = $_REQUEST["newAktif"];

    OpenDb();
    SetNewAktif($idMedia, $newAktif);
    CloseDb();

    http_response_code(200);
}
else if ($op == "getStatusAktif")
{
    $no = $_REQUEST['no'];
    $idMedia = $_REQUEST["idMedia"];

    OpenDb();
    ShowMediaAktif($no, $idMedia);
    CloseDb();

    http_response_code(200);
}


?>