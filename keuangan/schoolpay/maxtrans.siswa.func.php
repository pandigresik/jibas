<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 23.0 (November 12, 2020)
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
$selDepartemen = "";
$selIdTingkat = "";

function ShowCbDepartemen()
{
    global $selDepartemen;

    $lsDept = getDepartemen(getAccess());
    echo "<select id='departemen' name='departemen' style='width: 250px' onchange='changeDepartemen()'>";
    for($i = 0; $i < count($lsDept); $i++)
    {
        $dept = $lsDept[$i];

        if ($selDepartemen == "") $selDepartemen = $dept;
        $sel = $selDepartemen == $dept ? "selected" : "";

        echo "<option value='$dept' $sel>$dept</option>";
    }
    echo "</select>";
}

function ShowCbTingkat($departemen)
{
    global $selIdTingkat;

    $sql = "SELECT replid, tingkat
              FROM jbsakad.tingkat
             WHERE departemen = '$departemen'
               AND aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);

    echo "<select id='tingkat' name='tingkat' style='width: 150px' onchange='changeTingkat()'>";
    while($row = mysqli_fetch_row($res))
    {
        if ($selIdTingkat == "") $selIdTingkat = $row[0];
        $sel = $selIdTingkat == $row[0] ? "selected" : "";

        echo "<option value='".$row[0]."' $sel>".$row[1]."</option>";
    }
    echo "</select>";
}

function ShowCbKelas($departemen, $idTingkat)
{
    $idTahunAjaran = 0;

    $sql = "SELECT replid
              FROM jbsakad.tahunajaran
             WHERE departemen = '$departemen'
               AND aktif = 1";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        $idTahunAjaran = $row[0];

    $sql = "SELECT replid, kelas
              FROM jbsakad.kelas
             WHERE idtahunajaran = $idTahunAjaran
               AND idtingkat = $idTingkat
             ORDER BY kelas";
    $res = QueryDb($sql);
    echo "<select id='kelas' name='kelas' style='width: 250px' onchange='clearReport()'>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
}

function ShowDaftarBatasan($showMenu)
{
    $departemen = $_REQUEST["departemen"];
    $idKelas = $_REQUEST["idkelas"];

    $sql = "SELECT s.nis, s.nama
              FROM jbsakad.siswa s 
             WHERE s.idkelas = $idKelas
               AND s.aktif = 1
             ORDER BY s.nama";

    $lsUser = [];
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $lsUser[] = [$row[0], $row[1]];
    }

    $defaultMaxValue = 0;
    $sql = "SELECT maxtransvendor
              FROM jbsfina.paymenttabungan
             WHERE departemen = '".$departemen."'";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $defaultMaxValue = $row[0];
    }

    echo "<br>";
    echo "<input type='hidden' id='defaultmaxvalue' value='$defaultMaxValue'>";
    echo "<table id='table' border='1' cellpadding='5' cellspacing='0' style='border-width: 1px;'>";
    echo "<tr style='height: 30px'>";
    echo "<td align='center' class='header' width='40'>No</td>";
    echo "<td align='left' class='header' width='150'>NIS</td>";
    echo "<td align='left' class='header' width='180'>Nama</td>";
    echo "<td align='left' class='header' width='460'>Batasan Transaksi Harian Siswa</td>";
    echo "</tr>";

    for($i = 0; $i < count($lsUser); $i++)
    {
        $no = $i + 1;
        $nis = $lsUser[$i][0];
        $nama = $lsUser[$i][1];

        $selMaxType = 0;
        $maxValue = $defaultMaxValue;

        $sql = "SELECT maxtrans
                  FROM jbsfina.paymentmaxtrans
                 WHERE nis = '".$nis."'";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $selMaxType = 1;
            $maxValue =$row[0];
        }

        echo "<tr style='height: 30px'>";
        echo "<td align='center'>$no</td>";
        echo "<td align='left'>$nis</td>";
        echo "<td align='left'>$nama</td>";
        echo "<td align='left'>";

        echo "<select id='maxtype$no' style='font-size: 14px; width: 100px' onchange='changeMaxType($no)'>";
        $sel = $selMaxType == 0 ? "selected" : "";
        echo "<option value='0' $sel>Default</option>";
        $sel = $selMaxType == 1 ? "selected" : "";
        echo "<option value='1' $sel>Custom</option>";
        echo "</select>";

        echo "<input type='hidden' id='nis$no' value='$nis'>";
        echo "<input type='hidden' id='origsel$no' value='$selMaxType'>";
        echo "<input type='hidden' id='origval$no' value='$maxValue'>";
        if ($selMaxType == 0)
        {
            $rp = FormatRupiah($maxValue);
            echo "<input id='val$no' type='text' readonly style='font-size: 14px; width: 200px; background-color: #ccc' value='$rp'  onfocus='unformatRp($no)' onblur='formatRp($no)'>";
        }
        else
        {
            $rp = FormatRupiah($maxValue);
            echo "<input id='val$no' type='text' style='font-size: 14px; width: 200px; background-color: #fff' value='$rp' onfocus='unformatRp($no)' onblur='formatRp($no)'>";
        }

        if (getLevel() != 2)
        {
            echo "<input type='button' id='simpan$no' value='simpan' class='but' style='font-size: 14px;' onclick='saveMaxValue($no)'>";
        }
        echo "</tr>";
    }

    echo "</table>";
}

function SaveMaxValue($sel, $val, $nis)
{
    if ($sel == 0)
    {
        $sql = "DELETE FROM jbsfina.paymentmaxtrans WHERE nis = '".$nis."'";
        QueryDb($sql);
    }
    else
    {
        $sql = "SELECT COUNT(replid) FROM jbsfina.paymentmaxtrans WHERE nis = '".$nis."'";
        $res = QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $nData = $row[0];

        if ($nData != 0)
        {
            $sql = "UPDATE jbsfina.paymentmaxtrans SET maxtrans = '$val' WHERE nis = '".$nis."'";
            QueryDb($sql);
        }
        else
        {
            $sql = "INSERT INTO jbsfina.paymentmaxtrans SET maxtrans = $val, nis = '".$nis."'";
            QueryDb($sql);
        }
    }
}
?>