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
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/errorhandler.php');
require_once('../library/departemen.php');
require_once('caritagihan.func.php');
require_once('pgservice.config.php');

$op = $_REQUEST["op"];
if ($op == "9749873249832784")
{
    OpenDb();
    ShowTagihanSiswaTable();
    CloseDb();
}
else if ($op == "785462837462834")
{
    OpenDb();
    $nis = $_REQUEST["nis"];
    $nama = $_REQUEST["nama"];
    $bulan = $_REQUEST["bulan"];
    $tahun = $_REQUEST["tahun"];
    ShowTagihanSiswa($nis, $nama, $bulan, $tahun);
    CloseDb();
}
else if ($op == "874368723462837468723")
{
    OpenDb();
    SearchTagihan();
    CloseDb();
}
else if ($op == "983479824798324723")
{
    $search = $_REQUEST["search"];
    if ($search == "siswa")
        ShowSeachSiswa();
    else
        ShowSearchTagihan();
}

?>