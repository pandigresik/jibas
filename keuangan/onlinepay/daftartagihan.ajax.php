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
require_once('../library/logger.php');
require_once('../library/smsmanager.func.php');
require_once('daftartagihan.func.php');
require_once('pgservice.config.php');

$op = $_REQUEST["op"];
if ($op == "8374687346839274")
{
    OpenDb();
    DaftarTagihanInfo();
    CloseDb();
}
else if ($op == "930248032948023948")
{
    OpenDb();
    DaftarTagihanData();
    CloseDb();
}
else if ($op == "984723846234")
{
    OpenDb();
    HapusTagihanData();
    CloseDb();
}
else if ($op == "49384729847682934")
{
    OpenDb();
    HapusTagihanSiswa();
    CloseDb();
}
else if ($op == "23894762874632")
{
    OpenDb();
    $departemen = $_REQUEST["departemen"];
    $bulan = $_REQUEST["bulan"];
    $tahun = $_REQUEST["tahun"];
    ShowTagihanSet();
    CloseDb();
}
else if ($op == "7856875634875")
{
    OpenDb();
    ShowPrepareBatchNotif();
    CloseDb();
}
else if ($op == "8273468874356743723468324")
{
    OpenDb();
    SendBatchNotif();
    CloseDb();
}
else if ($op == "8374628746238746728346")
{
    OpenDb();
    SendNotif();
    CloseDb();
}
else if ($op == "36547346837463")
{
    OpenDb();
    HapusTagihanSet();
    CloseDb();
}
?>