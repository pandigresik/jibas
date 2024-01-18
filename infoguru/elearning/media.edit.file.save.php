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

    $mediaPath = ProperPath($FILESHARE_UPLOAD_DIR);
    $mediaPath = PathCombine($mediaPath, "elearning");
    if (!file_exists($mediaPath))
        mkdir($mediaPath, 0755);

    $mediaPath = PathCombine($mediaPath, "media");
    if (!file_exists($mediaPath))
        mkdir($mediaPath, 0755);

    $mediaPath = PathCombine($mediaPath , date('Y'));
    if (!file_exists($mediaPath))
        mkdir($mediaPath, 0755);

    $mediaUrl = UrlCombine("elearning", "media");
    $mediaUrl = UrlCombine($mediaUrl, date('Y'));

    $nFile = $_REQUEST["nFile"];
    for($i = 1; $i <= $nFile; $i++)
    {
        $fileKey = "file$i";
        $file = $_FILES[$fileKey];

        $infoKey = "info$i";
        $info = $_REQUEST[$infoKey];
        $info = SafeInputText($info);

        $ftInfo .= $info . " ";

        $ofilename = SafeFileName($file["name"]);
        $filename = SafeFileName($file["name"]);
        $filename = str_pad((string) $idMedia, 7, "0", STR_PAD_LEFT) . "." . $filename;
        $filetype = $file["type"];
        $filesize = $file["size"];
        $fileloc = $mediaUrl;

        $movePath = PathCombine($mediaPath, $filename);
        move_uploaded_file($file["tmp_name"], $movePath);

        $sql = "INSERT INTO jbsel.mediafile
                   SET idmedia = '$idMedia', filename = '$filename', ofilename = '$ofilename', filesize = '$filesize',
                       filetype = '$filetype', fileinfo = '$info', fileloc = '".$fileloc."'";
        QueryDbEx($sql);
    }

    CommitTrans();
    CloseDb();

    http_response_code(200);
}
catch(DbException $dbe)
{
    RollbackTrans();
    CloseDb();

    http_response_code(500);
    echo $dbe->getMessage();
}
catch(Exception $e)
{
    RollbackTrans();
    CloseDb();

    http_response_code(500);
    echo $e->getMessage();
}
?>