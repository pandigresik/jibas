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
$stIdPgTrans = $_REQUEST["stidpgtrans"];
//EchoBr($stIdPgTrans);

$jsonPen = $_REQUEST["jsonpen"];
$jsonPen = str_replace("`", "\"", (string) $jsonPen);
$lsPembayaran = json_decode($jsonPen, null, 512, JSON_THROW_ON_ERROR);
//PrePrintR($lsPembayaran);

$jsonTgl = $_REQUEST["jsontgl"];
$jsonTgl = str_replace("`", "\"", (string) $jsonTgl);
$lsTanggal = json_decode($jsonTgl, null, 512, JSON_THROW_ON_ERROR);
//PrePrintR($lsTanggal);

header('Content-Type: application/vnd.ms-excel'); //IE and Opera
header('Content-Type: application/x-msexcel'); // Other browsers
header('Content-Disposition: attachment; filename=RekapTrans.xls');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

echo "<table id='tabReport' border='1' cellpadding='5' cellspacing='0'>";
echo "<tr>";
echo "<td>No</td>";
echo "<td>Tanggal</td>";
for($i = 0; $i < count($lsPembayaran); $i++)
{
    $lsItem = $lsPembayaran[$i];

    $kategori = $lsItem[0];
    $idPenerimaan = $lsItem[1];

    $namaKategori = NamaKategori($kategori);
    $namaPenerimaan = NamaPenerimaan($kategori, $idPenerimaan);

    echo "<td>$namaPenerimaan</td>";
}
echo "<td>Sub Total</td>";
echo "</tr>";

$lsSubTotalPenerimaan = [];
for($j = 0; $j < count($lsPembayaran); $j++)
{
    $lsSubTotalPenerimaan[] = 0;
}

$allTotal = 0;
for($i = 0; $i < count($lsTanggal); $i++)
{
    $tanggal = $lsTanggal[$i];
    $fTanggal = formatInaMySqlDate($tanggal);

    $no = $i + 1;
    echo "<tr>";
    echo "<td>$no</td>";
    echo "<td>$fTanggal</td>";

    $subTotal = 0;
    for($j = 0; $j < count($lsPembayaran); $j++)
    {
        $lsItem = $lsPembayaran[$j];

        $kategori = $lsItem[0];
        $idPenerimaan = $lsItem[1];

        $sql = "SELECT IFNULL(SUM(jumlah), 0)
                  FROM jbsfina.pgtrans p, jbsfina.pgtransdata pd
                 WHERE p.replid = pd.idpgtrans
                   AND p.departemen = '$departemen'
                   AND p.tanggal = '$tanggal'
                   AND pd.kategori = '".$kategori."'";

        if ($idPenerimaan != "0")
        {
            if ($kategori == "JTT")
                $sql .= " AND pd.idpenerimaan = $idPenerimaan";
            else if ($kategori == "SKR")
                $sql .= " AND pd.idpenerimaan = $idPenerimaan";
            else if ($kategori == "SISTAB")
                $sql .= " AND pd.idtabungan = $idPenerimaan";
            else if ($kategori == "PEGTAB")
                $sql .= " AND pd.idtabunganp = $idPenerimaan";
        }
        $res = QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $jumlah = $row[0];

        $subTotal += $jumlah;
        $lsSubTotalPenerimaan[$j] = $lsSubTotalPenerimaan[$j] + $jumlah;

        echo "<td>$jumlah</td>";
    }

    echo "<td>$subTotal</td>";
    echo "</tr>";

    $lsSubTotal[] = $subTotal;
    $allTotal += $subTotal;
}

echo "<tr>";
echo "<td>&nbsp;</td>";
echo "<td>Sub Total</td>";

$jsonPen = json_encode($lsPembayaran, JSON_THROW_ON_ERROR);
$jsonPen = str_replace("\"", "`", $jsonPen);
for($i = 0; $i < count($lsSubTotalPenerimaan); $i++)
{
    $lsItem = $lsPembayaran[$i];

    $kategori = $lsItem[0];
    $idPenerimaan = $lsItem[1];

    $lsPen = [$lsItem];
    $jsonPen = json_encode($lsPen, JSON_THROW_ON_ERROR);
    $jsonPen = str_replace("\"", "`", $jsonPen);

    $jsonTgl = json_encode($lsTanggal, JSON_THROW_ON_ERROR);
    $jsonTgl = str_replace("\"", "`", $jsonTgl);

    $jumlah = $lsSubTotalPenerimaan[$i];
    echo "<td>$jumlah</td>";
}

echo "<td>$allTotal</td>";
echo "</tr>";
echo "</table>";
echo "</div>";

CloseDb();

?>
