<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 24.0 (April 01, 2021)
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
require_once("include/cbe.version.php");
require_once("include/db_functions.php");
require_once("../include/cbe.config.php");
require_once("include/session.php");
require_once("library/genericreturn.php");
require_once("library/httprequest.php");
require_once("library/httpmanager.php");
require_once("library/cbe.state.php");
require_once("library/cbe.system.php");
require_once("library/cbe.session.php");
require_once("library/cbe.protocol.php");
require_once("library/debugger.php");
require_once("common.func.php");

function doLogin($login, $password)
{
    try
    {
        // TEST KONEKSI KE CBE SERVER
        $result = testCbeConnection();
        if ((int) $result->Code < 0)
            return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

        // TEST KONEKSI KE DATABASE
        $result = testDbConnection();
        if ((int) $result->Code < 0)
            return sendConnectDbError($result->Message); // Tidak dapat koneksi ke CBE Server

        $result = sendLogin($login, $password);

        return $result->toJson();
    }
    catch (Exception $ex)
    {
        return GenericReturn::createJson(-99, $ex->getMessage(), "");
    }
}

function sendLogin($login, $password)
{
    global $CBE_CFG_VERSION;

    $cbeSession = new CbeSession();
    $cbeSession->UserId = $login;
    $cbeSession->UserPassword = $password;
    $cbeSession->AppCompVersion = CbeSystem::APP_COMP_VERSION;
    $cbeSession->LocalIp = $_SERVER["REMOTE_ADDR"]; //"CBE Web Server";
    $cbeSession->AndroidVersion = "AppVer: " . $CBE_CFG_VERSION . " Agent: " . $_SERVER['HTTP_USER_AGENT']; // "Web Client";

    $http = new HttpManager();
    $http->setTimeout(30000);
    $http->setData("connect", CbeState::Connect, "0", $cbeSession->toJson(), "");
    $result = $http->send();

    if ((int) $result->Code < 0)
        return sendConnectError($result->Message); // Tidak dapat koneksi ke CBE Server

    $info = CbeDataProtocol::fromJson($result->Data);
    if ((int) $info->Status < 0)
        return new GenericReturn((int) $info->Status, $info->Data, ""); // Login gagal

    $data = json_decode((string) $result->Data, null, 512, JSON_THROW_ON_ERROR);
    processLogin($data->Session, $data->Data);

    return new GenericReturn(1, "Login Success", "");
}

function processLogin($jsonSession, $jsonData)
{
    $session = CbeSession::fromJson($jsonSession);

    $_SESSION["UserId"] = $session->UserId;
    $_SESSION["UserName"] = $session->UserName;
    $_SESSION["UserPassword"] = $session->UserPassword;
    $_SESSION["UserDept"] = $session->UserDept;
    $_SESSION["UserType"] = $session->UserType;
    $_SESSION["SessionId"] = $session->SessionId;
    $_SESSION["LoginTime"] = $session->LoginTime;
    $_SESSION["LocalIp"] = $session->LocalIp;
    $_SESSION["AppCompVersion"] = $session->AppCompVersion;
    $_SESSION["AndroidVersion"] = $session->AndroidVersion;
    $_SESSION["Mode"] = $session->Mode;
    $_SESSION["Json"] = $jsonSession;
    $_SESSION["IsLogin"] = true;

    OpenDb();
    $sql = "SELECT nama 
              FROM jbsumum.identitas
             WHERE departemen = '$session->UserDept'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $_SESSION["Departemen"] = $row[0];
    }
    else
    {
        $sql = "SELECT nama 
                  FROM jbsumum.identitas
                 WHERE departemen = 'yayasan'";
        $res = QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            $_SESSION["Departemen"] = $row[0];
        }
        else
        {
            $_SESSION["Departemen"] = "";
        }
    }

    //2018-12-04 
    $sql = "DELETE FROM jbscbe.webuserintent
             WHERE userid = '$session->UserId'";
    QueryDb($sql);

    // 2019-09-10
    $sql = "SELECT COUNT(*)
              FROM information_schema.TABLES
             WHERE TABLE_SCHEMA = 'jbscbe'
               AND TABLE_NAME = 'timadmin'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $ndata = (int) $row[0];
    if ($ndata > 0)
    {
        $sql = "SELECT COUNT(*)
                  FROM jbscbe.timadmin
                 WHERE userid = '$session->UserId'";
        $res = QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $ndata = (int)$row[0];

        $_SESSION["TimAdmin"] = $ndata > 0;
    }
    else
    {
        $_SESSION["TimAdmin"] = false;
    }

    CloseDb();

    $jsonData = str_replace("\r\n", "<br>", (string) $jsonData);
    saveUserInfo($session->UserId, $session->SessionId, $jsonData);
}

function safeText($text)
{
    $text = str_replace("'", "`", (string) $text);
    //$text = str_replace("<", "&lt;", $text);
    //$text = str_replace(">", "&gt;", $text);

    return $text;
}

function saveUserInfo($userid, $sessionid, $jsonData)
{
    $login = json_decode((string) $jsonData, null, 512, JSON_THROW_ON_ERROR);

    $userpict = $login->UserPict;
    $welcome = safeText($login->Welcome);

    OpenDb();

    $sql = "DELETE FROM jbscbe.webuserinfo
             WHERE userid = '".$userid."'";
    QueryDb($sql);

    $sql = "INSERT INTO jbscbe.webuserinfo 
               SET userid = '$userid', sessionid = '$sessionid', 
                   logintime = NOW(), userpict = '$userpict', welcome = '".$welcome."'";
    QueryDb($sql);

    CloseDb();
}

function testCbeConnection()
{
    $http = new HttpManager();
    $http->setData("test", CbeState::Test, "0", "", "");

    return $http->send();
}

function testDbConnection()
{
    global $db_host, $db_user, $db_pass, $db_name, $mysqlconnection;

    $mysqlconnection = @mysqli_connect($db_host, $db_user, $db_pass);
    if (!$mysqlconnection)
        $result = new GenericReturn(-99, "Tidak dapat terhubung dengan server database JIBAS di $db_host", "");
    else
        $result = new GenericReturn(1, "OK", "");

    return $result;
}
?>