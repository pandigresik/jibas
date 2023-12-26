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
require_once('../include/sessioninfo.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/httpmanager.php');
require_once('../library/genericreturn.php');
require_once('../library/logger.php');
require_once('appserver.config.php');
require_once('pgserver.config.php');
require_once('pgschoolid.config.php');
require_once('pgservice.config.php');
require_once('onlinepay.func.php');

$op = $_REQUEST["op"];
if ($op == "874562834763284")
{
    CheckGetServiceFee();
}
else if ($op == "787458343894734")
{
    CheckAllConfigReady();
}
?>