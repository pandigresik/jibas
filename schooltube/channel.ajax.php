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
require_once ("channel.func.php");
require_once ("common.func.php");

$op = $_REQUEST["op"];
if ($op == "setFollow")
{
    $follow = $_REQUEST["follow"];
    $idChannel = $_REQUEST["idChannel"];

    OpenDb();
    SaveFollow($idChannel, $follow);
    CloseDb();
}
else if ($op == "getFollowerCount")
{
    $idChannel = $_REQUEST["idChannel"];

    OpenDb();
    echo GetFollowerCount($idChannel);
    CloseDb();
}
else if ($op == "nextSearch")
{
    $idMediaList = $_REQUEST["idMediaList"];
    $page = $_REQUEST["page"];

    OpenDb();
    ShowMedia($idMediaList, $page);
    CloseDb();
}
else if ($op == "changeVideoOrder")
{
    $idChannel = $_REQUEST["idChannel"];
    $urutan = $_REQUEST["urutan"];

    OpenDb();
    echo GetVideoList($idChannel, $urutan);
    CloseDb();
}
else if ($op == "reloadVideoList")
{
    $idMediaList = $_REQUEST["idList"];

    OpenDb();
    ShowMedia($idMediaList, 1);
    CloseDb();
}
?>
