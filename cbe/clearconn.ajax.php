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
require_once("include/db_functions.php");
require_once("library/genericreturn.php");
require_once("library/httprequest.php");
require_once("library/httpmanager.php");
require_once("library/cbe.state.php");
require_once("library/cbe.system.php");
require_once("library/cbe.session.php");
require_once("library/cbe.protocol.php");
require_once("library/debugger.php");
require_once("common.func.php");

$login = $_REQUEST["login"];
$password = $_REQUEST["password"];

$cbeSession = new CbeSession();
$cbeSession->UserId = $login;
$cbeSession->UserPassword = $password;
$cbeSession->AppCompVersion = CbeSystem::APP_COMP_VERSION;
$cbeSession->LocalIp = "CBE Web Server";
$cbeSession->AndroidVersion = "Web Client";

$http = new HttpManager();
$http->setData("clearconn", CbeState::ClearConn, "0", $cbeSession->toJson(), "");
$result = $http->send();

if ((int) $result->Code < 0)
{
    echo GenericReturn::createJson(-1, $result->Message, ""); // Tidak dapat koneksi ke CBE Server
    exit();
}

$jsonData = $result->Data;
$protocol = CbeDataProtocol::fromJson($jsonData);

if ((int) $protocol->Status < 0)
{
    echo GenericReturn::createJson(-1, $protocol->Data, "");  // CBE Server Application send Error
    exit();
}

echo GenericReturn::createJson(1, $protocol->Data, "");
exit();
?>