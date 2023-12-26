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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');

$json = "{\"status\":\"-1\", \"message\":\"EMPTY\", \"userid\":\"EMPTY\"}";

$idkategori = $_REQUEST['idkategori'];
$departemen = $_REQUEST['departemen'];
$kode = $_REQUEST['kode'];

OpenDb();
if ($idkategori == "JTT" || $idkategori == "SKR")
{
    $sql = "SELECT s.replid, a.departemen 
              FROM jbsakad.siswa s, jbsakad.angkatan a 
             WHERE s.nis = '$kode' 
               AND s.aktif = 1
               AND s.idangkatan = a.replid";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $userId = $kode;
        $userDept = $row[1];

        if ($departemen == $userDept)
        {
            $json = "{\"status\":\"1\", \"message\":\"\", \"userid\":\"$userId\"}";
        }
        else
        {
            $json = "{\"status\":\"-1\", \"message\":\"siswa tidak dapat melakukan transaksi di $departemen\", \"userid\":\"\"}";
        }
    }
    else
    {
        $json = "{\"status\":\"-1\", \"message\":\"tidak ditemukan data siswa\", \"userid\":\"\"}";
    }
}
else
{
    $sql = "SELECT cs.replid, p.departemen 
              FROM jbsakad.calonsiswa cs, jbsakad.prosespenerimaansiswa p 
             WHERE cs.nopendaftaran = '$kode'
               AND cs.aktif = 1
               AND cs.idproses = p.replid";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $userId = $row[0];
        $userDept = $row[1];

        if ($departemen == $userDept)
        {
            $json = "{\"status\":\"1\", \"message\":\"\", \"userid\":\"$userId\"}";
        }
        else
        {
            $json = "{\"status\":\"-1\", \"message\":\"calon siswa tidak dapat melakukan transaksi di $departemen\", \"userid\":\"\"}";
        }
    }
    else
    {
        $json = "{\"status\":\"-1\", \"message\":\"tidak ditemukan data calon siswa\", \"userid\":\"\"}";
    }
}
CloseDb();

echo $json;
?>