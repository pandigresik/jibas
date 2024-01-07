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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../library/departemen.php');
require_once('../include/errorhandler.php');
require_once('onlinepay.util.func.php');

OpenDb();

$departemen = $_REQUEST["departemen"];
$bankNo = $_REQUEST["bankno"];
$tanggal1 = $_REQUEST["tanggal1"];
$tanggal2 = $_REQUEST["tanggal2"];

header('Content-Type: application/vnd.ms-excel'); //IE and Opera
header('Content-Type: application/x-msexcel'); // Other browsers
header('Content-Disposition: attachment; filename=RekapMutasi.xls');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$sql = "SELECT bm.replid, bm.jenis, DATE_FORMAT(bm.waktu, '%d %b %Y<br>%H:%i') AS fwaktu, 
               IFNULL(bm.petugas, 'admin') AS idpetugas, 
               IFNULL(p.nama, 'Administrator JIBAS') AS namapetugas,
               bm.keterangan, bm.berkas, bm.nomormutasi
          FROM jbsfina.bankmutasi bm
          LEFT JOIN jbssdm.pegawai p ON bm.petugas = p.nip
         WHERE bm.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
           AND bm.departemen = '$departemen'
           AND bm.bankno = '$bankNo' 
         ORDER BY replid DESC";
$res = QueryDb($sql);

if (mysqli_num_rows($res) == 0)
{
    echo "Tidak ada data mutasi";
    return;
}

echo "<table id='tabReport' border='1' cellpadding='5' cellspacing='0'>";
echo "<tr>";
echo "<td>No</td>";
echo "<td>Tanggal</td>";
echo "<td>Mutasi</td>";
echo "<td>Jumlah</td>";
echo "<td>Nomor</td>";
echo "<td>Petugas</td>";
echo "<td>Petugas</td>";
echo "<td>Keterangan</td>";
echo "</tr>";

$no = 0;
while ($row = mysqli_fetch_array($res))
{
    $no += 1;

    $idMutasi = $row["replid"];

    $jenis = $row["jenis"];
    $namaJenis = $jenis == 1 ? "Simpan" : "Ambil";

    $sql = "SELECT SUM(jumlah)
              FROM jbsfina.bankmutasidata
             WHERE idmutasi = $idMutasi";
    $res2 = QueryDb($sql);
    $row2 = mysqli_fetch_row($res2);
    $jumlah = $row2[0];

    echo "<tr>";
    echo "<td>$no</td>";
    echo "<td>".$row['fwaktu']."</td>";
    echo "<td>$namaJenis</td>";
    echo "<td>$jumlah</td>";
    echo "<td>".$row['nomormutasi']."</td>";
    echo "<td>".$row['idpetugas']."</td>";
    echo "<td>".$row['namapetugas']."</td>";
    echo "<td>".$row['keterangan']."</td>";
    echo "</tr>";
}

echo "</table>";

CloseDb();
?>