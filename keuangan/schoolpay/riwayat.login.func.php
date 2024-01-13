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
$yrNow = date('Y');
$mnNow = date('n');
$dyNow = date('j');

function ShowSelectTanggal()
{
    global $yrNow, $mnNow, $dyNow;

    $sql = "SELECT YEAR(NOW()), MONTH(NOW()), DAY(NOW())";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $yrNow = $row[0];
        $mnNow = $row[1];
        $dyNow = $row[2];
    }

    echo "<select id='tahun' name='tahun' onchange='changeTanggal()'>";
    for($i = 2010; $i <= $yrNow + 1; $i++)
    {
        $sel = $i == $yrNow ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";

    echo "<select id='bulan' name='bulan' onchange='changeTanggal()'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == $mnNow ? "selected" : "";
        echo "<option value='$i' $sel>" . InaMonthName($i) . "</option>";
    }
    echo "</select>";

    $nMax = GetMaxDay($yrNow, $mnNow);
    echo "<span id='spCbTanggal'>";
    echo "<select id='tanggal' name='tanggal' onchange='clearReport()'>";
    for($i = 1; $i <= $nMax; $i++)
    {
        $sel = $i == $dyNow ? "selected" : "";
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

    $nMax = GetMaxDay($tahun, $bulan);
    echo "<select id='tanggal' name='tanggal' onchange='clearReport()'>";
    for($i = 1; $i <= $nMax; $i++)
    {
        $sel = "";
        if ($tahun == $yrNow && $bulan == $mnNow)
            $sel = $i == $dyNow ? "selected" : "";

        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
}

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

function ShowRiwayatLogin($showMenu)
{
    $vendorId = $_REQUEST["vendorid"];
    $bulan = $_REQUEST["bulan"];
    $tahun = $_REQUEST["tahun"];
    $tanggal = $_REQUEST["tanggal"];

    $sql = "SELECT v.nama AS vendor, u.nama AS petugas, DATE_FORMAT(ul.logtime, '%d-%b-%Y %H:%i') AS logtime, ul.localip, ul.device
              FROM jbsfina.userposlog ul, jbsfina.vendor v, jbsfina.userpos u
             WHERE ul.vendorid = v.vendorid
               AND ul.userid = u.userid
               AND YEAR(ul.logtime) = $tahun 
               AND MONTH(ul.logtime) = $bulan
               AND DAY(ul.logtime) = $tanggal
               AND v.vendorid = '$vendorId'
             ORDER BY logtime DESC";
    $res = QueryDb($sql);

    $no = 0;
    echo "<br>";
    if ($showMenu)
    {
        echo "<a href='#' onclick='cetakReport()'><img src='../images/ico/print.png' border='0'>&nbsp;cetak</a>&nbsp;&nbsp;";
        echo "<a href='#' onclick='excelReport()'><img src='../images/ico/excel.png' border='0'>&nbsp;excel</a>";
    }
    echo "<table id='table' border='1' cellpadding='5' cellspacing='0' style='border-width: 1px;'>";
    echo "<tr style='height: 30px'>";
    echo "<td align='center' class='header' width='40'>No</td>";
    echo "<td align='left' class='header' width='150'>Waktu</td>";
    echo "<td align='left' class='header' width='180'>Petugas</td>";
    echo "<td align='left' class='header' width='180'>IP Address</td>";
    echo "<td align='left' class='header' width='220'>Perangkat</td>";
    echo "</tr>";

    while($row = mysqli_fetch_array($res))
    {
        $no += 1;

        echo "<tr style='height: 30px'>";
        echo "<td align='center'>$no</td>";
        echo "<td align='left'>".$row['logtime']."</td>";
        echo "<td align='left'>".$row['petugas']."</td>";
        echo "<td align='left'>".$row['localip']."</td>";
        echo "<td align='left'>".$row['device']."</td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>