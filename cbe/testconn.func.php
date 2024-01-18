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
require_once("include/session.php");
require_once("include/db_functions.php");
require_once("library/genericreturn.php");
require_once("library/httprequest.php");
require_once("library/httpmanager.php");
require_once("library/cbe.state.php");
require_once("library/cbe.system.php");
require_once("library/cbe.session.php");
require_once("library/cbe.protocol.php");
require_once("library/debugger.php");

function AllowTestConn()
{
    try
    {
        $jsonSession = $_SESSION["Json"];

        $http = new HttpManager();
        $http->setData("checktestconn", CbeState::CheckTestConn, "0", $jsonSession, "");
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $jsonData = $result->Data;
        $protocol = CbeDataProtocol::fromJson($jsonData);

        if ((int) $protocol->Status < 0)
            return false; // CBE Server Application send Error

        $value = $protocol->Data;
        return strtolower((string) $value) == "true";
    }
    catch (Exception)
    {
        return false;
    }
}

function sendTestConn($dataSize)
{
    global $data32;

    if (!AllowTestConn())
        return GenericReturn::createJson(100, "Tidak diperkenankan melakukan Test Koneksi. Hubungi Administrator JIBAS.", "");

    if ($dataSize == 64)
        $data = $data32 . $data32;
    else
        $data = $data32;

    try
    {
        $timeStart = microtime(true);

        $jsonSession = $_SESSION["Json"];

        $http = new HttpManager();
        $http->setData("testconn", CbeState::TestConn, "0", $jsonSession, $data);
        $result = $http->send();

        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        $elapeed = microtime(true) - $timeStart ;
        $elapeed = round(1000 * $elapeed, 2);

        return GenericReturn::createJson(1, "OK", $elapeed);
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }

}
?>
