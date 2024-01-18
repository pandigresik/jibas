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

$stIdPgTrans = $_REQUEST["stidpgtrans"];

OpenDb();

$lsPenerimaan = [];
$sql = "SELECT DISTINCT kategori, IFNULL(idpenerimaan, 0) AS idpenerimaan,
               IFNULL(idtabungan, 0) AS idtabungan,
               IFNULL(idtabunganp, 0) AS idtabunganp
          FROM jbsfina.pgtransdata
         WHERE idpgtrans IN ($stIdPgTrans)";
$res = QueryDb($sql);
while($row = mysqli_fetch_array($res))
{
    $kategori =  $row["kategori"];
    $idPenerimaan = "0";
    $colName = "";

    if ($kategori == "JTT" || $kategori == "SKR")
    {
        $colName = "idpenerimaan";
        $idPenerimaan = $row["idpenerimaan"];
    }
    else if ($kategori == "SISTAB")
    {
        $colName = "idtabungan";
        $idPenerimaan = $row["idtabungan"];
    }
    else if ($kategori == "PEGTAB")
    {
        $colName = "idtabunganp";
        $idPenerimaan = $row["idtabunganp"];
    }

    $namaPenerimaan = NamaPenerimaan($kategori, $idPenerimaan);
    $lsItem = [$kategori, $idPenerimaan, $namaPenerimaan, $colName];
    $lsPenerimaan[] = $lsItem;
}

header('Content-Type: application/vnd.ms-excel'); //IE and Opera
header('Content-Type: application/x-msexcel'); // Other browsers
header('Content-Disposition: attachment; filename=RiwayatTrans.xls');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Riwayat Transaksi</title>
</head>

<body>
<center><font size="4"><strong>RIWAYAT TRANSAKSI</strong></font><br /> </center><br /><br />`
<br />
<table id="tabReport" border="1" cellpadding="5" cellspacing="0">
<tr style="height: 25px">
    <td>No</td>
    <td>Nama</td>
    <td>NIS</td>
    <td>Total</td>
    <td>Tanggal</td>
    <td>Jenis</td>
    <td>Nomor</td>
    <td>Bank</td>
    <td>Bank No</td>
    <td>Id Petugas</td>
    <td>Petugas</td>
    <td>Keterangan</td>
<?php
    for($i = 0; $i < count($lsPenerimaan); $i++)
    {
        $lsItem = $lsPenerimaan[$i];
        $nama = $lsItem[2];

        echo "<td>$nama</td>";
        echo "<td>No Jurnal</td>";
    }
    echo "<tr>";

    $no = 0;
    $sql = "SELECT DISTINCT p.replid, p.nis, s.nama AS namasiswa, p.bankno, b.bank, p.nomor, p.jenis,
                   DATE_FORMAT(p.waktu, '%d %b %Y %H:%i') AS fwaktu, 
                   p.idpetugas, p.petugas, p.ketver
              FROM jbsfina.pgtrans p
             INNER JOIN jbsfina.bank b ON p.bankno = b.bankno
              LEFT JOIN jbsakad.siswa s ON p.nis = s.nis
             WHERE p.replid IN ($stIdPgTrans)
             ORDER BY p.tanggal DESC, p.replid DESC";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        $idPgTrans = $row["replid"];

        $sql = "SELECT SUM(jumlah)
                  FROM jbsfina.pgtransdata
                 WHERE idpgtrans = $idPgTrans";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $jTransaksi = $row2[0];

        $namaMetode = NamaMetode($row["jenis"]);

        echo "<tr>";
        echo "<td>$no</td>";
        echo "<td>".$row['namasiswa']."</td>";
        echo "<td>".$row['nis']."</td>";
        echo "<td>$jTransaksi</td>";
        echo "<td>".$row['fwaktu']."</td>";
        echo "<td>$namaMetode</td>";
        echo "<td>".$row['nomor']."</td>";
        echo "<td>".$row['bank']."</td>";
        echo "<td>".$row['bankno']."</td>";
        echo "<td>".$row['idpetugas']."</td>";
        echo "<td>".$row['petugas']."</td>";
        echo "<td>".$row['ketver']."</td>";

        for($i = 0; $i < count($lsPenerimaan); $i++)
        {
            $lsItem = $lsPenerimaan[$i];
            $kategori = $lsItem[0];
            $idPenerimaan = $lsItem[1];
            $colName = $lsItem[3];

            $jumlah = "";
            $noJurnal = "";

            if ($idPenerimaan == "0")
            {
                $sql = "SELECT jumlah, nokas
                          FROM jbsfina.pgtransdata
                         WHERE idpgtrans = $idPgTrans
                           AND kategori = '".$kategori."'";
            }
            else
            {
                $sql = "SELECT jumlah, nokas
                          FROM jbsfina.pgtransdata
                         WHERE idpgtrans = $idPgTrans
                           AND $colName = $idPenerimaan";
            }

            $res2 = QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $jumlah = $row2[0];
                $noJurnal = $row2[1];
            }

            echo "<td>$jumlah</td>";
            echo "<td>$noJurnal</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

CloseDb();
?>
