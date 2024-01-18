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

header('Content-Type: application/vnd.ms-excel'); //IE and Opera
header('Content-Type: application/x-msexcel'); // Other browsers
header('Content-Disposition: attachment; filename=StatBulanan.xls');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$departemen = $_REQUEST["departemen"];
$tanggal1 = $_REQUEST["tanggal1"];
$tanggal2 = $_REQUEST["tanggal2"];
$metode = $_REQUEST["metode"];
$bankNo = $_REQUEST["bankno"];
$idPetugas = $_REQUEST["idpetugas"];

$sql = "SELECT DISTINCT tanggal
          FROM jbsfina.pgtrans
         WHERE tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
$sql .= " ORDER BY tanggal DESC";

$res = QueryDbEx($sql);
if (mysqli_num_rows($res) == 0)
{
    CloseDb();
    echo "Belum ada data transaksi pembayaran online";
    exit();
}

$lsTanggal = [];
while($row = mysqli_fetch_row($res))
{
    $lsTanggal[] = $row[0];
}

//PrePrintR($lsTanggal);

echo "<table>";
echo "<tr>";
echo "<td>No</td>";
echo "<td>Tanggal</td>";
echo "<td>Jumlah Siswa</td>";
echo "<td>Jumlah Transaksi</td>";
echo "<td>Besar Transaksi</td>";
echo "</tr>";

for($i = 0; $i < count($lsTanggal); $i++)
{
    $no = $i + 1;
    $tanggal = $lsTanggal[$i];

    $sql = "SELECT COUNT(DISTINCT nis)
              FROM jbsfina.pgtrans
             WHERE tanggal = '".$tanggal."'";
    if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
    if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
    if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
    if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $nSiswa = $row[0];

    $sql = "SELECT COUNT(replid)
              FROM jbsfina.pgtrans
             WHERE tanggal = '".$tanggal."'";
    if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
    if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
    if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
    if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $nTransaksi = $row[0];

    $sql = "SELECT SUM(pd.jumlah)
              FROM jbsfina.pgtrans p, jbsfina.pgtransdata pd
             WHERE p.replid = pd.idpgtrans
               AND p.tanggal = '".$tanggal."'";
    if ($departemen != "ALL") $sql .= " AND p.departemen = '".$departemen."'";
    if ($bankNo != "ALL") $sql .= " AND p.bankno = '".$bankNo."'";
    if ($idPetugas != "ALL") $sql .= " AND p.idpetugas = '".$idPetugas."'";
    if ($metode != "0") $sql .= " AND p.jenis = '".$metode."'";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $sumTransaksi = $row[0];

    echo "<tr>";
    echo "<td>$no</td>";
    $fTanggal = formatInaMySqlDate($tanggal);
    echo "<td>$fTanggal</td>";
    echo "<td>$nSiswa</td>";
    echo "<td>$nTransaksi</td>";
    $rp = FormatRupiah($sumTransaksi);
    echo "<td>$rp</td>";
    echo "</tr>";
}

$sql = "SELECT COUNT(DISTINCT nis)
          FROM jbsfina.pgtrans
         WHERE tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
$res = QueryDbEx($sql);
$row = mysqli_fetch_row($res);
$nSiswa = $row[0];

$sql = "SELECT COUNT(replid)
          FROM jbsfina.pgtrans
         WHERE tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
$res = QueryDbEx($sql);
$row = mysqli_fetch_row($res);
$nTransaksi = $row[0];

$sql = "SELECT SUM(pd.jumlah)
          FROM jbsfina.pgtrans p, jbsfina.pgtransdata pd
         WHERE p.replid = pd.idpgtrans
           AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
if ($departemen != "ALL") $sql .= " AND p.departemen = '".$departemen."'";
if ($bankNo != "ALL") $sql .= " AND p.bankno = '".$bankNo."'";
if ($idPetugas != "ALL") $sql .= " AND p.idpetugas = '".$idPetugas."'";
if ($metode != "0") $sql .= " AND p.jenis = '".$metode."'";
$res = QueryDbEx($sql);
$row = mysqli_fetch_row($res);
$sumTransaksi = $row[0];

echo "<tr>";
echo "<td></td>";
echo "<td>Total</td>";
echo "<td>$nSiswa</td>";
echo "<td>$nTransaksi</td>";
$rp = FormatRupiah($sumTransaksi);
echo "<td>$rp</td>";
echo "</tr>";
echo "</table>";

CloseDb();
?>