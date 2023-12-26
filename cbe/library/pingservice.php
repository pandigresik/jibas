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
require_once("../../include/cbe.config.php");
require_once("../include/session.php");
require_once("../library/genericreturn.php");
require_once("httprequest.php");
require_once("httpmanager.php");
require_once("cbe.state.php");
require_once("cbe.system.php");
require_once("cbe.session.php");
require_once("cbe.protocol.php");
require_once("debugger.php");

try
{
    $jsonSession = $_SESSION["Json"];

    $http = new HttpManager();
    $http->setTimeout(2000);
    $http->setData("pingserver", CbeState::PingServer, "0", $jsonSession, "");
    $result = $http->send();

    if ((int) $result->Code < 0)
        $return = new GenericReturn(-99, $result->Message, "");
    else
        $return = new GenericReturn(1, "OK", "");
}
catch (Exception $ex)
{
    $return = GenericReturn(-99, $ex->getMessage(), $ex);
}

echo $return->toJson();
?>