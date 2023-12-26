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
require_once(__DIR__ . '/../include/errorhandler.php');
require_once(__DIR__ . '/../include/sessioninfo.php');
require_once(__DIR__ . '/../include/compatibility.php');
require_once(__DIR__ . '/../include/common.php');
require_once(__DIR__ . '/../include/config.php');
require_once(__DIR__ . '/../include/db_functions.php');
require_once(__DIR__ . '/../library/departemen.php');
require_once(__DIR__ . "/impnilai.process.func.php");

$op = $_REQUEST['op'];
if ($op == "getselectaspek") {
    $idpelajaran = $_REQUEST['idpelajaran'];
    $idtingkat = $_REQUEST['idtingkat'];
    $nip = $_REQUEST['nip'];
    $selaspek = $_REQUEST['selaspek'];
    OpenDb();
    $select = SelectAspek();
    CloseDb();
    $result = ['idaspek' => $idaspek, 'select' => urlencode((string) $select)];
    echo json_encode($result, JSON_THROW_ON_ERROR);
    http_response_code(200);
} elseif ($op == "getselectjenisujian") {
    $idpelajaran = $_REQUEST['idpelajaran'];
    $idaspek = $_REQUEST['idaspek'];
    $idtingkat = $_REQUEST['idtingkat'];
    $idkelas = $_REQUEST['idkelas'];
    $nip = $_REQUEST['nip'];
    OpenDb();
    $select = SelectJenisUjian();
    CloseDb();
    echo urlencode((string) $select);
    http_response_code(200);
} elseif ($op == "getselectrpp") {
    $idpelajaran = $_REQUEST['idpelajaran'];
    $idtingkat = $_REQUEST['idtingkat'];
    $idsemester = $_REQUEST['idsemester'];
    OpenDb();
    $select = SelectRpp();
    CloseDb();
    echo urlencode((string) $select);
    http_response_code(200);
}
?>
