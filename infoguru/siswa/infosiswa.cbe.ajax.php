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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once("infosiswa.cbe.func.php");
require_once("../library/colorfactory.php");

$op = $_REQUEST["op"];
if ($op == "getpelajaran")
{
    $bulan = $_REQUEST["bulan"];
    $tahun = $_REQUEST["tahun"];
    $jenis = $_REQUEST["jenis"];

    OpenDb();
    echo showSelectPelajaran($bulan, $tahun, $jenis);
    CloseDb();
}
else if ($op == "gethasil")
{
    $bulan = $_REQUEST["bulan"];
    $tahun = $_REQUEST["tahun"];
    $jenis = $_REQUEST["jenis"];
    $idpelajaran = $_REQUEST["idpelajaran"];

    OpenDb();
    echo showRekapUjian($bulan, $tahun, $jenis, $idpelajaran);
    CloseDb();
}

?>