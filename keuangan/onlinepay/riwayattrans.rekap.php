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
require_once('riwayattrans.func.php');
require_once('onlinepay.util.func.php');

$departemen = $_REQUEST["departemen"];
$tanggal1 = $_REQUEST["tanggal1"];
$tanggal2 = $_REQUEST["tanggal2"];
$metode = $_REQUEST["metode"];
$pembayaran = $_REQUEST["pembayaran"];
$idPembayaran = $_REQUEST["idpembayaran"];
$siswa = $_REQUEST["siswa"];
$nis = $_REQUEST["nis"];
$bankNo = $_REQUEST["bankno"];
$idPetugas = $_REQUEST["idpetugas"];

OpenDb();

$sql = "SELECT DISTINCT p.replid
          FROM jbsfina.pgtrans p, jbsfina.pgtransdata pd
         WHERE pd.idpgtrans = p.replid
           AND p.departemen = '$departemen'
           AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'";

if ($metode != "0")
    $sql .= " AND p.jenis = $metode";

if ($pembayaran != "ALL")
    $sql .= " AND pd.kategori = '".$pembayaran."'";

if ($idPembayaran != "0")
{
    if ($pembayaran == "JTT")
        $sql .= " AND pd.idpenerimaan = $idPembayaran";
    else if ($pembayaran == "SKR")
        $sql .= " AND pd.idpenerimaan = $idPembayaran";
    else if ($pembayaran == "SISTAB")
        $sql .= " AND pd.idtabungan = $idPembayaran";
}

if ($siswa != "ALL")
    $sql .= " AND p.nis = '".$nis."'";

if ($bankNo <> "ALL")
    $sql .= " AND p.bankno = '".$bankNo."'";

if ($idPetugas != "ALL")
    $sql .= " AND p.idpetugas = '".$idPetugas."'";

//echo "$sql<br>";

$nData = 0;
$stIdPgTrans = "";
$res = QueryDb($sql);
while($row = mysqli_fetch_row($res))
{
    $nData++;

    if ($stIdPgTrans != "") $stIdPgTrans .= ",";
    $stIdPgTrans .= $row[0];
}

if ($nData == 0)
{
    echo "<br><br><span style='font-size: 14px; color: #666;'>Tidak ditemukan data transaksi pembayaran online</span>";
    return;
}

//echo "$stIdPgTrans<br>";

$sql = "SELECT DISTINCT tanggal
          FROM jbsfina.pgtrans
         WHERE replid IN ($stIdPgTrans)
         ORDER BY tanggal DESC";
//echo "$sql<br>";

$lsTanggal = [];
$res = QueryDb($sql);
while($row = mysqli_fetch_row($res))
{
    $lsTanggal[] = $row[0];
}

//echo "<pre>";
//print_r($lsTanggal);
//echo "</pre>";

$lsPembayaran = [];
if ($pembayaran == "ALL")
{
    $sql = "SELECT DISTINCT kategori, IFNULL(idpenerimaan, 0) AS idpenerimaan,
                   IFNULL(idtabungan, 0) AS idtabungan,
                   IFNULL(idtabunganp, 0) AS idtabunganp
              FROM jbsfina.pgtransdata
             WHERE idpgtrans IN ($stIdPgTrans)
             ORDER BY kelompok";
    //echo "$sql<br>";

    $res = QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        $kategori = $row["kategori"];

        $idPembayaran = "0";
        if ($kategori == "JTT")
            $idPembayaran = $row["idpenerimaan"];
        else if ($kategori == "SKR")
            $idPembayaran = $row["idpenerimaan"];
        else if ($kategori == "SISTAB")
            $idPembayaran = $row["idtabungan"];
        else if ($kategori == "PEGTAB")
            $idPembayaran = $row["idtabunganp"];

        $lsPembayaran[] = [$kategori, $idPembayaran];
    }
}
else
{
    if ($idPembayaran != "0")
    {
        $lsPembayaran[] = [$pembayaran, $idPembayaran];
    }
    else
    {
        $sql = "SELECT DISTINCT kategori, IFNULL(idpenerimaan, 0) AS idpenerimaan,
                       IFNULL(idtabungan, 0) AS idtabungan,
                       IFNULL(idtabunganp, 0) AS idtabunganp
                  FROM jbsfina.pgtransdata
                 WHERE idpgtrans IN ($stIdPgTrans)
                   AND kategori = '$pembayaran'
                 ORDER BY kelompok";
        //echo "$sql<br>";

        $res = QueryDb($sql);
        while($row = mysqli_fetch_array($res))
        {
            $kategori = $row["kategori"];

            $idPembayaran = "0";
            if ($kategori == "JTT")
                $idPembayaran = $row["idpenerimaan"];
            else if ($kategori == "SKR")
                $idPembayaran = $row["idpenerimaan"];
            else if ($kategori == "SISTAB")
                $idPembayaran = $row["idtabungan"];
            else if ($kategori == "PEGTAB")
                $idPembayaran = $row["idtabunganp"];

            $lsPembayaran[] = [$kategori, $idPembayaran];
        }
    }
}

//echo "<pre>";
//print_r($lsPembayaran);
//echo "</pre>";
echo "<a href='#' onclick='cetakRekap()'><img src='../images/ico/print.png' border='0'>&nbsp;cetak</a>&nbsp;&nbsp;";
echo "<a href='#' onclick='excelRekap()'><img src='../images/ico/excel.png' border='0'>&nbsp;excel</a><br><br>";

echo "<div id='dvTableContent'>";

echo "<input type='hidden' id='stidpgtrans' value='$stIdPgTrans'>";
$jsonPen = json_encode($lsPembayaran, JSON_THROW_ON_ERROR);
$jsonPen = str_replace("\"", "`", $jsonPen);
echo "<input type='hidden' id='jsonpen' value='$jsonPen'>";
$jsonTgl = json_encode($lsTanggal, JSON_THROW_ON_ERROR);
$jsonTgl = str_replace("\"", "`", $jsonTgl);
echo "<input type='hidden' id='jsontgl' value='$jsonTgl'>";

echo "<table id='tabReport' border='1' cellpadding='5' cellspacing='0'>";
echo "<tr style='height: 25px'>";
echo "<td class='header' width='35' align='center'>No</td>";
echo "<td class='header' width='140' align='left'>Tanggal</td>";
for($i = 0; $i < count($lsPembayaran); $i++)
{
    $lsItem = $lsPembayaran[$i];

    $kategori = $lsItem[0];
    $idPenerimaan = $lsItem[1];

    $namaKategori = NamaKategori($kategori);
    $namaPenerimaan = NamaPenerimaan($kategori, $idPenerimaan);

    echo "<td class='header' width='180' align='center'>$namaPenerimaan</td>";
}
echo "<td class='header' width='180' align='center'>Sub Total</td>";
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
    echo "<td align='center' style='background-color: #efefef;'>$no</td>";
    echo "<td align='left'>$fTanggal</td>";

    $subTotal = 0;
    for($j = 0; $j < count($lsPembayaran); $j++)
    {
        $lsItem = $lsPembayaran[$j];

        $kategori = $lsItem[0];
        $idPenerimaan = $lsItem[1];

        $sql = "SELECT IFNULL(SUM(jumlah), 0)
                  FROM jbsfina.pgtrans p, jbsfina.pgtransdata pd
                 WHERE p.replid = pd.idpgtrans
                   AND p.tanggal = '$tanggal'
                   AND p.departemen = '$departemen'
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

        $rp = FormatRupiah($jumlah);
        echo "<td align='right'>";
        if ($jumlah == 0)
        {
            echo $rp;
        }
        else
        {
            $lsPen = [$lsItem];
            $jsonPen = json_encode($lsPen);
            $jsonPen = str_replace("\"", "`", $jsonPen);

            $lsTgl = [$tanggal];
            $jsonTgl = json_encode($lsTgl, JSON_THROW_ON_ERROR);
            $jsonTgl = str_replace("\"", "`", $jsonTgl);

            //echo "<a href='#' onclick='showRekapDetail(\"$stIdPgTrans\",\"$kategori\",\"$idPenerimaan\",\"$namaPenerimaan\",\"$tanggal\")' style='color: #0000ff; font-weight: normal; text-decoration: none'>$rp</a>";
            echo "<a href='#' onclick='showRekapDetail2(\"$stIdPgTrans\",\"$jsonPen\",\"$jsonTgl\")' style='color: #0000ff; font-weight: normal; text-decoration: none'>$rp</a>";
        }
        echo "</td>";
    }

    echo "<td align='right'>";
    $rp = FormatRupiah($subTotal);
    if ($subTotal == 0)
    {
        echo "<strong>$rp</strong>";
    }
    else
    {
        $jsonPen = json_encode($lsPembayaran, JSON_THROW_ON_ERROR);
        $jsonPen = str_replace("\"", "`", $jsonPen);

        $lsTgl = [$tanggal];
        $jsonTgl = json_encode($lsTgl, JSON_THROW_ON_ERROR);
        $jsonTgl = str_replace("\"", "`", $jsonTgl);

        echo "<a href='#' onclick='showRekapDetail2(\"$stIdPgTrans\",\"$jsonPen\",\"$jsonTgl\")' style='color: #0000ff; font-weight: bold; text-decoration: none'>$rp</a>";
    }
    echo "</td>";
    echo "</tr>";

    $lsSubTotal[] = $subTotal;
    $allTotal += $subTotal;
}

echo "<tr>";
echo "<td align='right' colspan='2' style=' background-color: #ffc038'><strong>Sub Total</strong></td>";

$jsonPen = json_encode($lsPembayaran, JSON_THROW_ON_ERROR);
$jsonPen = str_replace("\"", "`", $jsonPen);
for($i = 0; $i < count($lsSubTotalPenerimaan); $i++)
{
    $lsItem = $lsPembayaran[$i];

    $kategori = $lsItem[0];
    $idPenerimaan = $lsItem[1];

    $lsPen = [$lsItem];
    $jsonPen = json_encode($lsPen);
    $jsonPen = str_replace("\"", "`", $jsonPen);

    $jsonTgl = json_encode($lsTanggal, JSON_THROW_ON_ERROR);
    $jsonTgl = str_replace("\"", "`", $jsonTgl);

    $jumlah = $lsSubTotalPenerimaan[$i];
    $rp = FormatRupiah($jumlah);
    echo "<td align='right' style=' background-color: #ffc038'>";
    if ($jumlah == 0)
        echo "<strong>$rp</strong>";
    else
        echo "<a href='#' onclick='showRekapDetail2(\"$stIdPgTrans\",\"$jsonPen\",\"$jsonTgl\")' style='color: #0000ff; font-weight: bold; text-decoration: none'>$rp</a>";
    echo "</td>";
}

$rp = FormatRupiah($allTotal);
echo "<td align='right' style=' background-color: #ffc038'>";
if ($allTotal == 0)
{
    echo "<strong>$rp</strong>";
}
else
{
    $jsonPen = json_encode($lsPembayaran, JSON_THROW_ON_ERROR);
    $jsonPen = str_replace("\"", "`", $jsonPen);

    $jsonTgl = json_encode($lsTanggal, JSON_THROW_ON_ERROR);
    $jsonTgl = str_replace("\"", "`", $jsonTgl);

    echo "<a href='#' onclick='showRekapDetail2(\"$stIdPgTrans\",\"$jsonPen\",\"$jsonTgl\")' style='color: #0000ff; font-weight: bold; text-decoration: none'>$rp</a>";
}
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</div>";

CloseDb();
?>

