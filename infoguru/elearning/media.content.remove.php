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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once("../include/sessionchecker.php");
require_once("../library/debugger.php");
require_once("common.func.php");

try
{
    OpenDb();
    BeginTrans();

    $idMedia = $_REQUEST["idMedia"];

    // --- REMOVE VIDEO
    $sql = "SELECT videoloc, videoname
              FROM jbsel.media
             WHERE id = $idMedia";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $videoloc = UrlToPath($row[0]);
    $videoname = $row[1];

    $delPath = ProperPath($FILESHARE_UPLOAD_DIR);
    $delPath = PathCombine($delPath, $videoloc);
    $delPath = PathCombine($delPath, $videoname);
    if (file_exists($delPath))
        unlink($delPath);

    // --- REMOVE FILES
    $sql = "SELECT filename, fileloc
              FROM jbsel.mediafile
             WHERE idmedia = $idMedia";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $filename = $row[0];
        $fileloc = UrlToPath($row[1]);

        $delPath = ProperPath($FILESHARE_UPLOAD_DIR);
        $delPath = PathCombine($delPath, $fileloc);
        $delPath = PathCombine($delPath, $filename);
        if (file_exists($delPath))
            unlink($delPath);
    }

    $sql = "DELETE FROM jbsel.mediafile WHERE idmedia = $idMedia";
    QueryDb($sql);

    $sql = "DELETE FROM jbsel.medianotes WHERE idmedia = '".$idMedia."'";
    QueryDbEx($sql);

    $sql = "DELETE FROM jbsel.mediamodul WHERE idmedia = '".$idMedia."'";
    QueryDbEx($sql);

    $sql = "DELETE FROM jbsel.medialike WHERE idmedia = '".$idMedia."'";
    QueryDbEx($sql);

    $sql = "DELETE FROM jbsel.media WHERE id = '".$idMedia."'";
    QueryDbEx($sql);

    $sql = "DELETE FROM jbsel.ftdatamedia WHERE idmedia = '".$idMedia."'";
    QueryDbEx($sql);

    CommitTrans();
    CloseDb();

    http_response_code(200);
}
catch(DbException $dbe)
{
    RollbackTrans();
    CloseDb();

    http_response_code(500);
    echo "<font style='font-family: Tahoma; font-size: 20px; color: red;'>";
    echo "Oops, something has gone wrong";
    echo "</font>";
    echo "<br><br><br>";
    echo $dbe->getMessage();
}
catch(Exception $e)
{
    RollbackTrans();
    CloseDb();

    http_response_code(500);
    echo "<font style='font-family: Tahoma; font-size: 20px; color: red;'>";
    echo "Oops, something has gone wrong";
    echo "</font>";
    echo "<br><br><br>";
    echo $e->getMessage();
}
?>