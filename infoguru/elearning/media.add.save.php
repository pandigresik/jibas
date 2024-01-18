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
require_once("../include/sessionchecker.php");
require_once("../library/debugger.php");
require_once("../library/genericreturn.php");
require_once("common.func.php");

try
{
    OpenDb();
    BeginTrans();

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

    $idChannel = $_REQUEST["idChannel"];
    $judul = SafeInputText($_REQUEST["judul"]);
    $urutan = $_REQUEST["urutan"];
    $prioritas = $_REQUEST["prioritas"];
    $deskripsi = SafeInputText($_REQUEST["deskripsi"]);
    $objektif = SafeInputText($_REQUEST["objektif"]);
    $pertanyaan = SafeInputText($_REQUEST["pertanyaan"]);
    $cover = $_REQUEST["coverImage"];
    $kateKunci = SafeInputText($_REQUEST["kataKunci"]);

    $kategori = $_REQUEST["kategori"];
    if ($kategori == 0)
        $kateValue = "NULL";
    else
        $kateValue = "'$kategori'";

    $sql = "INSERT INTO jbsel.media
               SET idchannel = '$idChannel', judul = '$judul', urutan = '$urutan', prioritas = '$prioritas', 
                   deskripsi = '$deskripsi', objektif = '$objektif', pertanyaan = '$pertanyaan', cover = '$cover', 
                   videoname = '-', ovideoname = '-', videosize = 0, videotype = '-', videoloc = '-', tstamp = NOW(),
                   idkategori = $kateValue, katakunci = '".$kateKunci."'";
    //$d->Log($sql);
    QueryDbEx2($sql);


    $ftInfo  = $judul . " ";
    $ftInfo .= $deskripsi . " ";
    $ftInfo .= $objektif . " ";
    $ftInfo .= $pertanyaan . " ";
    $ftInfo .= $kateKunci . " ";

    $sql = "SELECT LAST_INSERT_ID()";
    $res = QueryDbEx2($sql);
    $row = mysqli_fetch_row($res);
    $idMedia = $row[0];
    //$d->Log($idMedia);

    $video = $_FILES["fileVideo"];
    $ovideoname = SafeFileName($video["name"]);
    $videoname = SafeFileName($video["name"]);
    $videoname = str_pad((string) $idMedia, 7, "0", STR_PAD_LEFT) . "." . $videoname;
    $videotype = $video["type"];
    $videosize = $video["size"];
    $videoloc = $mediaUrl;

    $movePath = PathCombine($mediaPath, $videoname);
    move_uploaded_file($video["tmp_name"], $movePath);

    $sql = "UPDATE jbsel.media 
               SET videoname = '$videoname', ovideoname = '$ovideoname', videosize = '$videosize', 
                   videotype = '$videotype', videoloc = '$videoloc'
             WHERE id = $idMedia";
    //$d->Log($sql);
    QueryDbEx2($sql);

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
        //$d->Log($sql);
        QueryDbEx2($sql);
    }


    $sql = "INSERT INTO jbsel.ftdatamedia
               SET idmedia = $idMedia, data = '$ftInfo', timestamp = NOW()";
    //$d->Log($sql);
    QueryDbEx2($sql);

    CommitTrans();
    CloseDb();

    echo GenericReturn::createJson(1, "OK", "");

}
catch(DbException $dbe)
{
    RollbackTrans();
    CloseDb();

    echo GenericReturn::createJson(-1, $dbe->getMessage(), "");
}
catch(Exception $e)
{
    RollbackTrans();
    CloseDb();

    echo GenericReturn::createJson(-1, $e->getMessage(), "");
}
?>