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

$json = "{\"status\":\"-1\",\"message\":\"EMPTY\",\"data\":\"EMPTY\", \"noid\":\"EMPTY\", \"nama\":\"EMPTY\", \"kelas\":\"EMPTY\"}";

$kode = $_REQUEST['kode'];
$departemen = $_REQUEST['departemen'];

OpenDb();

$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen
          FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t
         WHERE s.idkelas = k.replid
           AND k.idtingkat = t.replid
           AND s.nis = '$kode'
           AND s.aktif = 1
           AND s.alumni = 0";
$res = QueryDb($sql);
if (mysqli_num_rows($res) > 0)
{
    $row = mysqli_fetch_row($res);
    $data = "siswa";
    $nis = $row[0];
    $nama = $row[1];
    $kelas = $row[2];
    $dept = $row[3];

    if ($departemen == $dept)
    {
        $json = "{\"status\":\"1\",\"message\":\"OK\",\"data\":\"$data\", \"noid\":\"$nis\", \"nama\":\"$nama\", \"kelas\":\"$kelas\"}";
    }
    else
    {
        $json = "{\"status\":\"-1\",\"message\":\"siswa tidak dapat melakukan transaksi di $departemen\",\"data\":\"EMPTY\", \"noid\":\"EMPTY\", \"nama\":\"EMPTY\", \"kelas\":\"EMPTY\"}";
    }
}
else
{
    $sql = "SELECT c.nopendaftaran, c.nama, k.kelompok, p.departemen
              FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p
             WHERE c.idkelompok = k.replid
               AND k.idproses = p.replid
               AND c.nopendaftaran = '$kode'
               AND c.aktif = 1";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $data = "calon";
        $nic = $row[0];
        $nama = $row[1];
        $kelas = $row[2];
        $dept = $row[3];

        if ($departemen == $dept)
        {
            $json = "{\"status\":\"1\",\"message\":\"OK\",\"data\":\"$data\", \"noid\":\"$nic\", \"nama\":\"$nama\", \"kelas\":\"$kelas\"}";
        }
        else
        {
            $json = "{\"status\":\"-1\",\"message\":\"calon siswa tidak dapat melakukan transaksi di $departemen\",\"data\":\"EMPTY\", \"noid\":\"EMPTY\", \"nama\":\"EMPTY\", \"kelas\":\"EMPTY\"}";
        }
    }
    else
    {
        $json = "{\"status\":\"-1\",\"message\":\"tidak ditemukan data siswa atau calon siswa\",\"data\":\"EMPTY\", \"noid\":\"EMPTY\", \"nama\":\"EMPTY\", \"kelas\":\"EMPTY\"}";
    }
}
CloseDb();

echo $json;
?>