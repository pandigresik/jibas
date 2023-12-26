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
    $judul = SafeInputText($_REQUEST["judul"]);
    $urutan = $_REQUEST["urutan"];
    $prioritas = $_REQUEST["prioritas"];
    $deskripsi = SafeInputText($_REQUEST["deskripsi"]);
    $objektif = SafeInputText($_REQUEST["objektif"]);
    $pertanyaan = SafeInputText($_REQUEST["pertanyaan"]);
    $kataKunci = SafeInputText($_REQUEST["kataKunci"]);
    $kategori = $_REQUEST["kategori"];

    if ($kategori == 0)
        $kateValue = "NULL";
    else
        $kateValue = "'$kategori'";

    $sql = "UPDATE jbsel.media
               SET judul = '$judul', urutan = '$urutan', prioritas = '$prioritas', 
                   deskripsi = '$deskripsi', objektif = '$objektif', pertanyaan = '$pertanyaan',
                   idkategori = $kateValue, katakunci = '$kataKunci'
             WHERE id = $idMedia";
    QueryDbEx($sql);
    //$d->Log($sql);

    $ftInfo  = $judul . " ";
    $ftInfo .= $deskripsi . " ";
    $ftInfo .= $objektif . " ";
    $ftInfo .= $pertanyaan . " ";
    $ftInfo .= $kataKunci . " ";

    $sql = "UPDATE jbsel.ftdatamedia
               SET data = '$ftInfo'
             WHERE idmedia = $idMedia";
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