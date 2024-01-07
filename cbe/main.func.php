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
require_once("../include/cbe.config.php");
require_once("include/session.php");
require_once("include/config.php");
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

function doLogout()
{
    $jsonSession = $_SESSION["Json"];

    $http = new HttpManager();
    $http->setData("logout", CbeState::Logout, "0", $jsonSession, "");
    $result = $http->send();

    if ((int) $result->Code < 0)
        return sendConnectError($result->Message);

    return GenericReturn::createJson(1, "OK", "");
}

function getUserPict($height)
{
    $userId = $_SESSION["UserId"];
    $sessionId = $_SESSION["SessionId"];

    OpenDb();
    $sql = "SELECT userpict FROM jbscbe.webuserinfo WHERE userid = '$userId' AND sessionid = '".$sessionId."'";
    $userPict = FetchSingle($sql);
    CloseDb();

    return "<img src='data:image/jpeg;base64,$userPict' height='$height'>";
}

function getWelcomeMessage()
{
    $userId = $_SESSION["UserId"];
    $sessionId = $_SESSION["SessionId"];

    OpenDb();
    $sql = "SELECT welcome FROM jbscbe.webuserinfo WHERE userid = '$userId' AND sessionid = '".$sessionId."'";
    $welcome = FetchSingle($sql);
    CloseDb();

    return $welcome;
}

function showContentPage()
{
    $page = "blank.php";
    if ($_SESSION["UserType"] == "admin")
    {
        include $page;
        return;
    }

    if (isset($_SESSION["finishujian"]))
    {
        unset($_SESSION["finishujian"]);
        $page = "hasilujian.php";
    }
    else if (!isset($_REQUEST['page']))
    {
        $page = "welcome.php";
    }
    else
    {
        if ($_REQUEST['page'] == "hasilujian")
            $page = "hasilujian.php";
        else if ($_REQUEST['page'] == "ujiankhusus")
            $page = "ujiankhusus.php";
        else if ($_REQUEST['page'] == "jadwal")
            $page = "jadwal.php";
    }

    include $page;
}
?>