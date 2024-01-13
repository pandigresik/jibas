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

$stIdPgTrans = "";
$nData = "";

$page = 1;
if (isset($_REQUEST["page"]))
    $page = $_REQUEST["page"];

if (!isset($_REQUEST["stidpgtrans"]))
{
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

    //$sql .= " ORDER BY p.tanggal DESC";
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

    //echo "$stIdPgTrans<br>";
}
else
{
    $stIdPgTrans = $_REQUEST["stidpgtrans"];
    $nData = $_REQUEST["ndata"];
}

if ($nData == 0)
{
    echo "<br><span style='font-size: 14px; color: #999; font-weight: bold;'>Tidak ditemukan data pembayaran online</span>";
    return;
}

$nRowPerPage = 10;
$limitStart = ($page - 1) * $nRowPerPage;
$nPage = ceil($nData / $nRowPerPage);
?>
<table border="0" cellpadding="10" cellspacing="0">
<tr>
    <td align="left">
        <input type="hidden" id="report" value="RIWAYAT">
        <input type="hidden" id="stidpgtrans" value="<?=$stIdPgTrans?>">
        <input type="hidden" id="ndata" value="<?=$nData?>">
        Halaman
        <select id="page" class="inputbox" style="width: 50px;" onchange="changeReportPage()">
            <?php
            for($i = 1; $i <= $nPage; $i++)
            {
                $sel = $i == $page ? "selected" : "";
                echo "<option value='$i' $sel>$i</option>";
            }
            ?>
        </select>
        dari <?= $nPage ?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#" onclick="cetakRiwayat()"><img src="../images/ico/print.png" border="0">&nbsp;cetak</a>&nbsp;&nbsp;
        <a href="#" onclick="excelRiwayat()"><img src="../images/ico/excel.png" border="0">&nbsp;excel</a>
    </td>
</tr>
<tr>
    <td>
<?php
        $sql = "SELECT DISTINCT p.replid, p.nis, s.nama AS namasiswa, p.bankno, b.bank, p.nomor, p.jenis,
                       DATE_FORMAT(p.waktu, '%d %b %Y<br>%H:%i') AS fwaktu, DATE_FORMAT(p.tanggal, '%d %b %Y') AS ftanggal,
                       p.idpetugas, p.petugas, p.ketver, p.transaksi, p.paymentid
                  FROM jbsfina.pgtrans p
                 INNER JOIN jbsfina.bank b ON p.bankno = b.bankno
                  LEFT JOIN jbsakad.siswa s ON p.nis = s.nis
                 WHERE p.replid IN ($stIdPgTrans)
                 ORDER BY p.tanggal DESC, p.replid DESC
                 LIMIT $limitStart, $nRowPerPage";
?>
        <div id="dvTableContent">

        <table id="tabReport" border="1" cellpadding="5" cellspacing="0">
        <tr style="height: 25px">
            <td class="header" width="35" align="center">No</td>
            <td class="header" width="160" align="center">Jumlah</td>
            <td class="header" width="180" align="center">Nomor/Payment ID</td>
            <td class="header" width="150" align="center">Bank</td>
            <td class="header" width="150" align="center">Petugas</td>
            <td class="header" id="thrincian" width="480" align="center">Rincian</td>
        </tr>
<?php
        $res = QueryDb($sql);
        $cnt = ($page - 1) * $nRowPerPage;
        while($row = mysqli_fetch_array($res))
        {
            $cnt += 1;
            $idPgTrans = $row['replid'];

            $sql = "SELECT SUM(jumlah)
                      FROM jbsfina.pgtransdata
                     WHERE idpgtrans = $idPgTrans
                       AND kategori <> 'LB'";
            $res2 = QueryDb($sql);
            $row2 = mysqli_fetch_row($res2);
            $jTransaksi = $row2[0];
            $rpTransaksi = FormatRupiah($jTransaksi);

            $sql = "SELECT SUM(jumlah)
                      FROM jbsfina.pgtransdata
                     WHERE idpgtrans = $idPgTrans
                       AND kategori = 'LB'";
            $res2 = QueryDb($sql);
            $row2 = mysqli_fetch_row($res2);
            $jLebih = $row2[0];

            $jenis = $row["jenis"];
            $namaMetode = NamaMetode($jenis);

            $nomorTs = "";
            if ($jenis == 1)
            {
                $sql = "SELECT ts.nomor
                          FROM jbsfina.tagihansiswainfo tsi, jbsfina.tagihanset ts
                         WHERE tsi.idtagihanset = ts.replid
                           AND tsi.notagihan = '".$row['nomor']."'";
                $res2 = QueryDb($sql);
                if ($row2 = mysqli_fetch_row($res2))
                    $nomorTs = $row2[0];
            }

            echo "<tr>";
            echo "<td align='center' valign='top' style='background-color: #efefef;' rowspan='2'>$cnt</td>";
            echo "<td align='left' valign='top' colspan='4'>";

            echo "<table border='0' cellpadding='2' cellspacing='0' width='100%'>";
            echo "<tr>";
            echo "<td width='80%'>";
            echo "<strong>".$row['namasiswa']."</strong>  |  ".$row['nis']."<br>";
            echo "<strong>".$row['transaksi']."</strong>";
            echo "</td>";
            echo "<td width='20%' align='right'>";
            echo "<i>".$row['fwaktu']."</i>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";

            echo "</td>";
            echo "<td align='left' class='rincian'  rowspan='2' valign='top' style='background-color: #efefef'>";

            echo "<table id='tabReportDetail' border='1' cellpadding='2' cellspacing='0' style='border: 1px #efefef; background-color: #fff; border-collapse: collapse'>";
            $sql = "SELECT pd.kategori, pd.jumlah, pd.diskon, pd.nokas, dp.nama AS namapenerimaan, dt.nama AS namatabungan
                      FROM jbsfina.pgtransdata pd
                      LEFT JOIN jbsfina.datapenerimaan dp ON pd.idpenerimaan = dp.replid
                      LEFT JOIN jbsfina.datatabungan dt ON pd.idtabungan = dt.replid
                     WHERE idpgtrans = $idPgTrans
                     ORDER BY kelompok;";
            $res2 = QueryDb($sql);
            $stNoJurnal = "";
            while($row2 = mysqli_fetch_array($res2))
            {
                $kategori = $row2["kategori"];

                $nama = "";
                if ($kategori == "SISTAB")
                    $nama = $row2["namatabungan"];
                else if ($kategori == "JTT")
                    $nama = $row2["namapenerimaan"];
                else if ($kategori == "SKR")
                    $nama = $row2["namapenerimaan"];
                else if ($kategori == "BL")
                    $nama = "Biaya Layanan";
                else if ($kategori == "LB")
                    $nama = "Kelebihan Transfer";

                $rp = FormatRupiah($row2["jumlah"]);

                if ($stNoJurnal != "") $stNoJurnal .= ",";
                $stNoJurnal .= $row2["nokas"];

                echo "<tr>";
                echo "<td width='220px' align='left'>$nama</td>";
                echo "<td width='110px' align='right'>$rp</td>";
                echo "<td width='110px' align='center'>";
                echo "<a href='#' style='color: #0000ff; text-decoration: underline; font-weight: normal;' onclick='showRincianJurnal($cnt)'>".$row2['nokas']."</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<input type='hidden' id=\"stnojurnal-$cnt\" value=\"$stNoJurnal\">";
            echo "</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td align='right' valign='top'><span style='font-size: 15px; font-weight: bold'>$rpTransaksi</span><br>";
            if ($jLebih > 0)
            {
                $rpLebih = FormatRupiah($jLebih);
                echo "<span style='font-style: italic; color: blue;'>lebih <b>$rpLebih</b></span><br>";

                $jTotal = $jTransaksi + $jLebih;
                $rpTotal = FormatRupiah($jTotal);
                echo "<span style='font-style: italic; color: darkgreen'>total <b>$rpTotal</b></span><br>";
            }
            echo "</td>";
            echo "<td align='left' valign='top'>";
            if ($jenis == 1)
                echo "<b>".$row['nomor']."</b><br><i>$nomorTs</i><br><i>".$row['paymentid']."</i>";
            else
                echo "<b>".$row['nomor']."</b><br><i>".$row['paymentid']."</i>";
            echo "</td>";
            echo "<td align='left' valign='top'><strong>".$row['bank']."</strong><br><i>".$row['bankno']."</i></td>";
            echo "<td align='left' valign='top'>".$row['petugas']."<br>".$row['idpetugas']."<br><i>".$row['ketver']."</i></td>";

            echo "</tr>";
        }
?>
        </table>
        </div>

    </td>
</tr>
</table>







<?php
CloseDb()
?>

