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

OpenDb();

$nRowPerPage = 10;

$stIdPgTrans = $_REQUEST["stidpgtrans"];
$jsonPen = $_REQUEST["jsonpen"];
$jsonTgl = $_REQUEST["jsontgl"];
$page = $_REQUEST["page"];

$jsonPen2 = str_replace("`", "\"", (string) $jsonPen);
$lsPen = json_decode($jsonPen2, null, 512, JSON_THROW_ON_ERROR);

$stPenerimaan = "";
$penQl = "";
for($i = 0; $i < count($lsPen); $i++)
{
    $lsItem = $lsPen[$i];

    $kategori = $lsItem[0];
    $idPenerimaan = $lsItem[1];
    $penerimaan = NamaPenerimaan($kategori, $idPenerimaan);

    if ($stPenerimaan != "") $stPenerimaan .= ", ";
    $stPenerimaan .= $penerimaan;

    if ($penQl != "") $penQl .= " OR ";

    if ($idPenerimaan != "0")
    {
        if ($kategori == "JTT" || $kategori == "SKR")
            $penQl .= " pd.idpenerimaan = $idPenerimaan";
        else if ($kategori == "SISTAB")
            $penQl .= " pd.idtabungan = $idPenerimaan";
        else if ($kategori == "PEGTAB")
            $penQl .= " pd.idtabunganp = $idPenerimaan";
    }
    else
    {
        $penQl .= " pd.kategori = '".$kategori."'";
    }
}

$jsonTgl2 = str_replace("`", "\"", (string) $jsonTgl);
$lsTgl = json_decode($jsonTgl2, null, 512, JSON_THROW_ON_ERROR);

$stTanggal = "";
$tglQl = "";
for($i = 0; $i < count($lsTgl); $i++)
{
    $tgl = $lsTgl[$i];

    if ($tglQl != "") $tglQl .= " OR ";
    $tglQl .= " p.tanggal = '".$tgl."'";

    if ($i == 0)
    {
        $stTanggal = formatInaMySqlDate($tgl);
    }
    else
    {
        if ($i == count($lsTgl) - 1)
            $stTanggal .= " s/d " . formatInaMySqlDate($tgl);
    }
}

$stCurIdPgTrans = "";
if (!isset($_REQUEST["stcuridpgtrans"]))
{
    $sql = "SELECT DISTINCT p.replid
              FROM jbsfina.pgtrans p, jbsfina.pgtransdata pd
             WHERE p.replid = pd.idpgtrans
               AND p.replid IN ($stIdPgTrans)
               AND ($tglQl) AND ($penQl)";
    $res = QueryDb($sql);

    while ($row = mysqli_fetch_row($res))
    {
        if ($stCurIdPgTrans != "") $stCurIdPgTrans .= ",";
        $stCurIdPgTrans .= $row[0];
    }
}
else
{
    $stCurIdPgTrans = $_REQUEST["stcuridpgtrans"];
}

$lsIdPgTrans = explode(",", (string) $stCurIdPgTrans);
$nData = count($lsIdPgTrans);
$nPage = ceil($nData / $nRowPerPage);
$nStart = ($page - 1) * $nRowPerPage;
$nEnd = $page * $nRowPerPage - 1;
$stSelIdPgTrans = "";
for($i = 0; $i < $nData; $i++)
{
    if ($i < $nStart) continue;
    if ($i > $nEnd) continue;

    if ($stSelIdPgTrans != "") $stSelIdPgTrans .= ",";
    $stSelIdPgTrans .= $lsIdPgTrans[$i];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Riwayat Transaksi</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="../script/themes/ui-lightness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/ui/jquery-ui.custom.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/dateutil.js"></script>
    <script language="javascript" src="../script/stringutil.js"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="riwayattrans.rekap.detail.js?r=<?=filemtime('riwayattrans.rekap.detail.js')?>"></script>
</head>

<body>
<input type="hidden" id="stidpgtrans" value="<?=$stIdPgTrans?>">
<input type="hidden" id="stcuridpgtrans" value="<?=$stCurIdPgTrans?>">
<input type="hidden" id="jsonpen" value="<?=$jsonPen?>">
<input type="hidden" id="jsontgl" value="<?=$jsonTgl?>">

<table border="0" cellspacing="0" cellpadding="10" width="100%">
<tr>
    <td align="left">
        <span style="font-size: 18px">Riwayat Pembayaran Online</span><br><br>
        <table border="0" cellpadding="5" cellspacing="0" width="90%">
        <tr>
            <td width="80" align="right" valign="top" style="font-size: 13px">Penerimaan:</td>
            <td width="*" align="left" style="font-size: 13px">
                <?=$stPenerimaan?>
            </td>
        </tr>
        <tr>
            <td align="right"  style="font-size: 13px" valign="top">Tanggal:</td>
            <td width="*"  style="font-size: 13px" align="left">
                <?=$stTanggal?>
            </td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td align="left" style="background-color: #efefef">
        Halaman
        <select id="page" class="inputbox" onchange="changePage()">
            <?php   for($i = 1; $i <= $nPage; $i++)
            {
                $sel = $page == $i ? "selected" : "";
                echo "<option value='$i' $sel>$i</option>";
            } ?>
        </select>
        &nbsp;dari&nbsp;<?= $nPage ?>
    </td>
</tr>
<tr>
    <td align="left">

        <table id="tabReport" border="1" cellpadding="5" style="border-width: 1px; border-collapse: collapse; border-color: #dddddd">
        <tr style="height: 30px">
            <td class="header" width="30" align="center">No</td>
            <td class="header" width="100" align="center">Tanggal</td>
            <td class="header" width="160" align="center">Siswa</td>
            <td class="header" width="180" align="center">Nomor/Payment ID</td>
            <td class="header" width="180" align="center">Bank</td>
        </tr>

<?php

        $sql = "SELECT DISTINCT p.replid, p.nis, s.nama, p.bankno, b.bank, DATE_FORMAT(p.waktu, '%d %b %Y<br>%H:%i') AS fwaktu, 
                       p.idpetugas, p.petugas, p.transaksi, p.nomor, p.jenis, p.paymentid 
                  FROM jbsfina.pgtrans p
                 INNER JOIN jbsfina.pgtransdata pd ON p.replid = pd.idpgtrans AND p.replid IN ($stSelIdPgTrans)
                 INNER JOIN jbsfina.bank b ON p.bankno = b.bankno
                  LEFT JOIN jbsakad.siswa s ON p.nis = s.nis
                 WHERE ($tglQl) AND ($penQl)";

        $no = ($page - 1) * $nRowPerPage;
        $res = QueryDb($sql);
        while ($row = mysqli_fetch_array($res))
        {
            $idPgTrans = $row["replid"];

            $nomorTs = "";
            if ($row["jenis"] == 1)
            {
                $sql = "SELECT ts.nomor
                          FROM jbsfina.tagihansiswainfo tsi, jbsfina.tagihanset ts
                         WHERE tsi.idtagihanset = ts.replid
                           AND tsi.notagihan = '".$row['nomor']."'";
                $res2 = QueryDb($sql);
                if ($row2 = mysqli_fetch_row($res2))
                    $nomorTs = $row2[0];
            }

            $no += 1;
            echo "<tr>";
            echo "<td align='center' style='background-color: #efefef;' rowspan='3' valign='top'>$no</td>";
            echo "<td align='left' style='background-color: #e5fdff;' colspan='4'><b>".$row['transaksi']."</b></td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td align='left' style='background-color: #fff;'>".$row['fwaktu']."</td>";
            echo "<td align='left' style='background-color: #fff;'><b>".$row['nama']."</b><br>".$row['nis']."</td>";
            echo "<td align='left' style='background-color: #fff;' valign='top'>";
            if ($row["jenis"] == 1)
                echo "<b>".$row['nomor']."</b><br><i>$nomorTs</i><br><i>".$row['paymentid']."</i>";
            else
                echo "<b>".$row['nomor']."</b><br><i>".$row['paymentid']."</i>";
            echo "</td>";
            echo "<td align='left' style='background-color: #fff;'>".$row['bank']."<br>".$row['bankno']."</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td align='left' colspan='4' style='background-color: #efefef;'>";

            echo "<table id='tabReportDetail' border='1' cellpadding='2' cellspacing='0' style='border: 1px #efefef; border-collapse: collapse'>";
            $sql = "SELECT pd.kategori, IFNULL(pd.idpenerimaan, 0) AS idpenerimaan, IFNULL(pd.idtabungan, 0) AS idtabungan, 
                           IFNULL(pd.idtabunganp, 0) AS idtabunganp, jumlah, diskon, nokas
                      FROM jbsfina.pgtransdata pd
                     WHERE pd.idpgtrans = $idPgTrans
                       AND ($penQl)";
            $res2 = QueryDb($sql);
            while($row2 = mysqli_fetch_array($res2))
            {
                $kategori = $row2["kategori"];

                $idPenerimaan = 0;
                if ($kategori == "JTT" || $kategori == "SKR")
                    $idPenerimaan = $row2["idpenerimaan"];
                else if ($kategori == "SISTAB")
                    $idPenerimaan = $row2["idtabungan"];
                else if ($kategori == "PEGTAB")
                    $idPenerimaan = $row2["idtabunganp"];

                $nama = NamaPenerimaan($kategori, $idPenerimaan);
                $jumlah = $row2["jumlah"];
                $rp = FormatRupiah($jumlah);
                $noKas = $row2["nokas"];

                echo "<tr>";
                echo "<td width='180px' align='left' style='background-color: #fff;'>$nama</td>";
                echo "<td width='120px' align='right' style='background-color: #fff;'>$rp</td>";
                echo "<td width='150px' align='center' style='background-color: #fff;'>$noKas</td>";
                echo "</tr>";
            }
            echo "</table>";

            echo "</td>";
            echo "</tr>";
        }

        if ($page == $nPage)
        {
            $sql = "SELECT SUM(pd.jumlah)
                      FROM jbsfina.pgtransdata pd, jbsfina.pgtrans p
                     WHERE p.replid = pd.idpgtrans
                       AND p.replid IN ($stIdPgTrans)
                       AND ($tglQl) AND ($penQl)";
            $res = QueryDb($sql);
            $row = mysqli_fetch_row($res);
            $total = $row[0];
            $rp = FormatRupiah($total);

            echo "<tr>";
            echo "<td align='right' style='background-color: #ffc038' colspan='4'><span style='font-size: 14px; font-weight: bold'>Total</span></td>";
            echo "<td align='right' style='background-color: #ffc038'><span style='font-size: 14px; font-weight: bold'>$rp</span></td>";
            echo "</tr>";
        }


?>
        </table>

    </td>
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>