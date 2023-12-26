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
require_once('../inc/config.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');

$json = "{\"status\":\"-1\",\"message\":\"EMPTY\",\"username\":\"EMPTY\",\"usertype\":\"EMPTY\"}";

$kode = $_REQUEST['kode'];

OpenDb();
$sql = "SELECT nama 
          FROM jbsakad.siswa
         WHERE nis = '$kode'
           AND aktif = 1";
$res = QueryDb($sql);
if (mysqli_num_rows($res) > 0)
{
    $row = mysqli_fetch_row($res);
    $nama = $row[0];
    $json = "{\"status\":\"1\",\"message\":\"OK\",\"username\":\"$nama\",\"usertype\":\"1\"}";
}
else
{
    $sql = "SELECT nama 
              FROM jbssdm.pegawai
             WHERE nip = '$kode'
               AND aktif = 1";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $nama = $row[0];
        $json = "{\"status\":\"1\",\"message\":\"OK\",\"username\":\"$nama\",\"usertype\":\"0\"}";
    }
    else
    {
        $json = "{\"status\":\"-1\",\"message\":\"Tidak ditemukan data pengguna\",\"username\":\"EMPTY\",\"usertype\":\"EMPTY\"}";
    }
}
CloseDb();

echo $json
?>