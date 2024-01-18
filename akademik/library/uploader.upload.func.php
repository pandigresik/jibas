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
//require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
require_once('../include/compatibility.php');
require_once('../cek.php');

$departemen = $_REQUEST['departemen'];
$deskripsi = $_REQUEST["deskripsi"];
$gambar = $_FILES["gambar"];
$size = $gambar['size'];
$ext = substr(strrchr((string) $gambar["name"], "."), 1); 
[$width, $height] = getimagesize($gambar["tmp_name"]);

$nama = $gambar["name"];
$nama = str_replace(" ", "_", (string) $nama);
$nama = str_replace("'", "", $nama);

$fspath = "gambar/" . date('Y');

$uploadPath = "$FILESHARE_UPLOAD_DIR/gambar";
if (!file_exists($uploadPath))
    mkdir($uploadPath, 0755);

$uploadPath = "$uploadPath/" . date('Y');
if (!file_exists($uploadPath))
    mkdir($uploadPath, 0755);

    
OpenDb();

$sql = "INSERT INTO jbsumum.gambar
           SET departemen = '$departemen',
               modul = 'SIMAKA',
               tanggal = NOW(),
               nama = '$nama', 
               berkas = 'file',
               lebar = $width,
               tinggi = $height,
               ukuran = $size,
               lokasi = '$fspath',
               deskripsi = '".$deskripsi."'";
QueryDb($sql);

$sql = "SELECT LAST_INSERT_ID()";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$id = $row[0];

$fname = $id . "_" . RandStr(7) . "." . $ext;

$sql = "UPDATE jbsumum.gambar
           SET berkas = '$fname'
         WHERE replid = $id";
QueryDb($sql);

CloseDb();

$dest = "$uploadPath/$fname";
move_uploaded_file($gambar["tmp_name"], $dest);

http_response_code(200);
?>