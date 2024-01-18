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
function ShowCbVendor()
{
    $sql = "SELECT vendorid, nama 
              FROM jbsfina.vendor
             WHERE aktif = 1
             ORDER BY nama";
    $res = QueryDb($sql);

    echo "<select id='vendor' name='vendor' onchange='clearReport()' style='width: 250px'>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
}

function ShowSelectTanggal1()
{
    $sql = "SELECT YEAR(DATE_SUB(NOW(), INTERVAL 7 DAY)), 
                   MONTH(DATE_SUB(NOW(), INTERVAL 7 DAY)),
                   DAY(DATE_SUB(NOW(), INTERVAL 7 DAY))";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $tahun = $row[0];
    $bulan = $row[1];
    $tanggal = $row[2];

    echo "<select id='tahun1' name='tahun1' onchange='changeTanggal1()'>";
    for($i = 2020; $i <= $tahun + 1; $i++)
    {
        $sel = $i == $tahun ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";

    echo "<select id='bulan1' name='bulan1' onchange='changeTanggal1()'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == $bulan ? "selected" : "";
        echo "<option value='$i' $sel>" . InaMonthName($i) . "</option>";
    }
    echo "</select>";

    $nMax = GetMaxDay($tahun, $bulan);
    echo "<span id='spCbTanggal1'>";
    echo "<select id='tanggal1' name='tanggal1' onchange='clearReport()'>";
    for($i = 1; $i <= $nMax; $i++)
    {
        $sel = $i == $tanggal ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
    echo "</span>";
}

function ShowSelectTanggal2()
{
    $sql = "SELECT YEAR(NOW()), 
                   MONTH(NOW()),
                   DAY(NOW())";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $tahun = $row[0];
    $bulan = $row[1];
    $tanggal = $row[2];

    echo "<select id='tahun2' name='tahun2' onchange='changeTanggal2()'>";
    for($i = 2020; $i <= $tahun + 1; $i++)
    {
        $sel = $i == $tahun ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";

    echo "<select id='bulan2' name='bulan2' onchange='changeTanggal2()'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == $bulan ? "selected" : "";
        echo "<option value='$i' $sel>" . InaMonthName($i) . "</option>";
    }
    echo "</select>";

    $nMax = GetMaxDay($tahun, $bulan);
    echo "<span id='spCbTanggal2'>";
    echo "<select id='tanggal2' name='tanggal2' onchange='clearReport()'>";
    for($i = 1; $i <= $nMax; $i++)
    {
        $sel = $i == $tanggal ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
    echo "</span>";
}

function ShowCbTanggal()
{
    $yrNow = date('Y');
    $mnNow = date('n');
    $dyNow = date('j');

    $tahun = $_REQUEST["tahun"];
    $bulan = $_REQUEST["bulan"];
    $idSelect = $_REQUEST["idselect"];

    $nMax = GetMaxDay($tahun, $bulan);
    echo "<select id='$idSelect' name='$idSelect' onchange='clearReport()'>";
    for($i = 1; $i <= $nMax; $i++)
    {
        $sel = "";
        if ($tahun == $yrNow && $bulan == $mnNow)
            $sel = $i == $dyNow ? "selected" : "";

        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
}

function ShowRekapTransReport($showMenu)
{
    $vendorId = $_REQUEST["vendorid"];
    $dtStart = $_REQUEST["dtstart"];
    $dtEnd = $_REQUEST["dtend"];

    $sql = "SELECT vu.userid, vu.tingkat, u.nama
              FROM jbsfina.vendoruser vu, jbsfina.userpos u 
             WHERE vu.userid = u.userid
               AND vu.vendorid = '$vendorId'
             ORDER BY vu.tingkat, u.nama";
    $res = QueryDb($sql);

    $lsUser = [];
    while($row = mysqli_fetch_row($res))
    {
        $userId = $row[0];
        $tingkat = $row[1];
        $nama = $row[2];
        if ($tingkat == 1) $nama .= " (M)";

        $lsUser[] = [$userId, $nama, 0];
    }

    if (count($lsUser) == 0)
    {
        echo "Belum ada transaksi";
        return;
    }

    $sql = "SELECT DISTINCT DATE_FORMAT(tanggal, '%d-%b-%Y') AS ftanggal, tanggal
              FROM jbsfina.paymenttrans
             WHERE vendorid = '$vendorId'
               AND tanggal BETWEEN '$dtStart' AND '$dtEnd'
             ORDER BY tanggal DESC";
    $res = QueryDb($sql);

    $lsTanggal = [];
    while($row = mysqli_fetch_row($res))
    {
        $lsTanggal[] = [$row[0], $row[1]];
    }

    if (count($lsTanggal) == 0)
    {
        echo "Belum ada transaksi";
        return;
    }

    $sb = new StringBuilder();
    $sb->AppendLine("<br>");
    if ($showMenu)
    {
        $sb->AppendLine("<a href='#' onclick='cetakReport()'><img src='../images/ico/print.png' border='0'>&nbsp;cetak</a>&nbsp;&nbsp;");
        $sb->AppendLine( "<a href='#' onclick='excelReport()'><img src='../images/ico/excel.png' border='0'>&nbsp;excel</a>");
    }

    $sb->AppendLine("<table id='table' border='1' cellspacing='0' cellpadding='5' style='border-width: 1px; border-collapse: collapse'>");
    $sb->AppendLine("<tr style='height: 30px'>");
    $sb->AppendLine("<td width='50' align='center' class='header'>No</td>");
    $sb->AppendLine("<td width='150' align='center' class='header'>Tanggal</td>");
    for($i = 0; $i < count($lsUser); $i++)
    {
        $sb->AppendLine("<td width='175' align='center' class='header'>");
        $sb->AppendLine($lsUser[$i][1]);
        $sb->AppendLine("</td>");
    }
    $sb->AppendLine("<td width='175' align='center' class='header'>SUB TOTAL HARIAN</td>");
    $sb->AppendLine("</tr>");

    $allTotal = 0;
    $no = 0;
    for($i = 0; $i < count($lsTanggal); $i++)
    {
        $no += 1;
        $ftanggal = $lsTanggal[$i][0];
        $tanggal = $lsTanggal[$i][1];

        $sb->AppendLine("<tr style='height: 30px'>");
        $sb->AppendLine("<td align='center'>$no</td>");
        $sb->AppendLine("<td align='center'>$ftanggal</td>");

        $subTotal = 0;
        for($j = 0; $j < count($lsUser); $j++)
        {
            $userId = $lsUser[$j][0];
            $jumlah = 0;

            $sql = "SELECT SUM(jumlah)
                      FROM jbsfina.paymenttrans
                     WHERE vendorid = '$vendorId'
                       AND userid = '$userId'
                       AND tanggal = '".$tanggal."'";
            $res = QueryDb($sql);
            if ($row = mysqli_fetch_row($res))
            {
                $jumlah = $row[0];
                $rp = FormatRupiah($row[0]);
                $sb->AppendLine("<td align='right'>$rp</td>");
            }
            else
            {
                $sb->AppendLine("<td align='right'>Rp 0</td>");
            }

            $lsUser[$j][2] = $lsUser[$j][2] + $jumlah;
            $subTotal = $subTotal + $jumlah;
        }
        $allTotal = $allTotal + $subTotal;

        $rp = FormatRupiah($subTotal);
        $sb->AppendLine("<td align='right'><strong>$rp</strong></td>");
        $sb->AppendLine("</tr>");
    }

    // SUB TOTAL PETUGAS
    $sb->AppendLine("<tr style='height: 50px; background-color: #ededed'>");
    $sb->AppendLine("<td align='right' colspan='2' style='background-color: #ededed'><strong>SUB TOTAL PETUGAS</strong></td>");
    for($j = 0; $j < count($lsUser); $j++)
    {
        $rp = FormatRupiah($lsUser[$j][2]);
        $sb->AppendLine("<td align='right' style='background-color: #ededed'><strong>$rp</strong></td>");
    }
    $rp = FormatRupiah($allTotal);
    $sb->AppendLine("<td align='right' style='background-color: #ededed'><strong>$rp</strong></td>");
    $sb->AppendLine("</tr>");

    $sb->AppendLine("</table>");


    echo $sb->ToString();
}
?>
