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
require_once ("include/session.php");
require_once ("include/config.php");
require_once ("include/db_functions.php");
require_once ("home.func.php");
require_once ("common.func.php");

$op = $_REQUEST["op"];
if ($op == "nextRandomVideo")
{
    $dept = $_REQUEST["dept"];

    OpenDb();
    ShowRandomVideo($dept);
    CloseDb();
}
else if ($op == "nextMostLiked")
{
    $idList = $_REQUEST["idList"];
    $page = $_REQUEST["page"];

    OpenDb();
    ShowMediaList($idList, $page);
    CloseDb();
}
else if ($op == "nextMostViewed")
{
    $idList = $_REQUEST["idList"];
    $page = $_REQUEST["page"];

    OpenDb();
    ShowMediaList($idList, $page);
    CloseDb();
}
else if ($op == "getRandomVideo")
{
    $dept = $_REQUEST["dept"];

    OpenDb();
    ShowRandomVideo($dept);
    CloseDb();
}
else if ($op == "getMostLikedVideoIdList")
{
    $dept = $_REQUEST["dept"];

    OpenDb();
    $idList = GetMostLikedList($dept);
    CloseDb();

    echo $idList;
}
else if ($op == "showMostLikedVideo")
{
    $idList = $_REQUEST["idList"];

    OpenDb();
    ShowMediaList($idList, 1);
    CloseDb();
}
else if ($op == "getMostViewedVideoIdList")
{
    $dept = $_REQUEST["dept"];

    OpenDb();
    $idList = GetMostViewedList($dept);
    CloseDb();

    echo $idList;
}
else if ($op == "showMostViewedVideo")
{
    $idList = $_REQUEST["idList"];

    OpenDb();
    ShowMediaList($idList, 1);
    CloseDb();
}
?>
