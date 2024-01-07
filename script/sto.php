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
require_once('../include/mainconfig.php');
require_once('../include/db_functions.php');
require_once('regis.func.php');

try
{
    if (!isset($_REQUEST["transid"]) ||
        !isset($_REQUEST["count"]) ||
        !isset($_REQUEST["data"]) ||
        !isset($_REQUEST["app"]) ||
        !isset($_REQUEST["nfile"]))
    {
        echo ToGenericReturn(-1, "INVALID REQUEST", "");
        return;
    }

    $transId = $_REQUEST["transid"];
    $encCount = $_REQUEST["count"];
    $encData = $_REQUEST["data"];
    $app = $_REQUEST["app"];
    $nFile = $_REQUEST["nfile"];

    OpenDbEx();

    $sql = "SELECT transkey
              FROM jbsumum.appregis
             WHERE transid = '".$transId."'";
    $res = QueryDbEx($sql);
    if (mysqli_num_rows($res) == 0)
    {
        echo ToGenericReturn(-1, "INVALID TRANS ID", "");
        return;
    }

    $publicKey = 0;
    if ($row = mysqli_fetch_row($res))
        $publicKey = $row[0];

    $privateKey = 3649;
    $encKey = $publicKey + $privateKey;

    $count = XorDecrypt($encCount, $encKey);
    $jsonData = XorDecrypt($encData, $encKey);

    $success = true;
    $lsData = TryParseJson($jsonData, $success);
    if (!$success)
    {
        echo ToGenericReturn(-1, "INVALID TRANS DATA", "");
        return;
    }

    if (count($lsData) != $count)
    {
        echo ToGenericReturn(-1, "INVALID TRANS DATA LENGTH", "");
        return;
    }

    $sql = "DELETE FROM jbsumum.appregis
             WHERE transid = '".$transId."'";
    QueryDbEx($sql);

    $uploadPath = "$FILESHARE_UPLOAD_DIR/$app";
    if (!file_exists($uploadPath))
        mkdir($uploadPath, 0755);

    $listId = array();
    $batchId = rand(100000, 999999);
    for($i = 1; $i <= $nFile; $i++)
    {
        $param = "file$i";

        $fileTmp = $_FILES[$param]["tmp_name"];
        $fileType = $_FILES[$param]["type"];
        $fileSize = $_FILES[$param]["size"];
        $fileName = $_FILES[$param]["name"];
        $fileName = str_replace("'", "`", $fileName);

        $rnd1 = rand(100000, 999999);
        $fileSave = $batchId . "." . $i . "." . $rnd1 . "." . urlencode($fileName);
        $filePath = $app . "/" . $fileSave;
        $fileDest = $uploadPath . "/" . $fileSave;

        move_uploaded_file($fileTmp, $fileDest);

        $sql = "INSERT INTO jbsumum.appupload
                   SET app = '$app', upldate = NOW(), batchid = $batchId,
                       filename = '$fileName', filepath = '$filePath', 
                       filetype = '$fileType', filesize = $fileSize";
        QueryDbEx($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $id = $row[0] . "";

        $listId[] = array($id, $fileSave);
    }

    echo ToGenericReturn(1, "OK", json_encode($listId));
}
catch(Exception $ex)
{
    echo ToGenericReturn(-1, $ex->getMessage(), "");
}
finally
{
    CloseDb();
}
?>