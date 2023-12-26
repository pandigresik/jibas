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
require_once("modul.content.func.php");
require_once("common.func.php");

$op = $_REQUEST["op"];
if ($op == "setStatusAktif")
{
    $idModul = $_REQUEST["idModul"];
    $newAktif = $_REQUEST["newAktif"];

    OpenDb();
    SetNewAktif($idModul, $newAktif);
    CloseDb();

    http_response_code(200);
}
else if ($op == "getStatusAktif")
{
    $no = $_REQUEST['no'];
    $idModul = $_REQUEST["idModul"];

    OpenDb();
    ShowModulAktif($no, $idModul);
    CloseDb();

    http_response_code(200);
}
else if ($op == "removeModul")
{
    $idModul = $_REQUEST["idModul"];

    OpenDb();
    RemoveModul($idModul);
    CloseDb();

    http_response_code(200);
}


?>