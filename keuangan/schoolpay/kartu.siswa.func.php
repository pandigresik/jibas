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
        echo "<option value='".$row[0]."' $sel>".$row[1]."</option>";
    }
    echo "</select>";
}

function ChangeKartuAktif()
{
    $replid = $_REQUEST["replid"];
    $newAktif = $_REQUEST["newaktif"];

    $sql = "UPDATE jbsfina.paymentid SET aktif = $newAktif WHERE replid = $replid";
    QueryDb($sql);

    if ($newAktif == 1)
    {
        echo "<a href='#' onclick='changeAktif($replid, 0)' title='set non aktif'><img src='../images/ico/aktif.png' border='0'></a>";
    }
    else
    {
        echo "<a href='#' onclick='changeAktif($replid, 1)' title='set aktif'><img src='../images/ico/nonaktif.png' border='0'></a>";
    }
}

function ShowKartuSiswa($showMenu)
{
    $idKelas = $_REQUEST["idkelas"];

    $sql = "SELECT p.nis, max(p.replid) 
              FROM jbsfina.paymentid p, jbsakad.siswa s
             WHERE p.nis = s.nis 
               AND s.idkelas = $idKelas
             GROUP BY p.nis
             ORDER BY s.nama";

    $lsUser = [];
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $lsUser[] = [$row[0], $row[1]];
    }

    echo "<br>";
    if ($showMenu)
    {
        echo "<a href='#' onclick='cetakReport()'><img src='../images/ico/print.png' border='0'>&nbsp;cetak</a>&nbsp;&nbsp;";
        echo "<a href='#' onclick='excelReport()'><img src='../images/ico/excel.png' border='0'>&nbsp;excel</a>";
    }
    echo "<table id='table' border='1' cellpadding='5' cellspacing='0' style='border-width: 1px;'>";
    echo "<tr style='height: 30px'>";
    echo "<td align='center' class='header' width='40'>No</td>";
    echo "<td align='left' class='header' width='150'>NIS</td>";
    echo "<td align='left' class='header' width='180'>Nama</td>";
    echo "<td align='left' class='header' width='180'>Payment ID</td>";
    echo "<td align='left' class='header' width='180'>Tanggal Buat</td>";
    echo "<td align='center' class='header' width='100'>Aktif</td>";
    echo "</tr>";

    for($i = 0; $i < count($lsUser); $i++)
    {
        $nis = $lsUser[$i][0];
        $idPayment = $lsUser[$i][1];

        $sql = "SELECT s.nama, p.paymentid, DATE_FORMAT(p.tanggal, '%d-%b-%Y %H:%i') as tanggal, p.aktif, p.replid
                  FROM jbsfina.paymentid p, jbsakad.siswa s
                 WHERE p.nis = s.nis
                   AND p.replid = $idPayment";
        $res = QueryDb($sql);

        while($row = mysqli_fetch_array($res))
        {
            $no = $i + 1;
            $replid = $row["replid"];

            echo "<tr style='height: 30px'>";
            echo "<td align='center'>$no</td>";
            echo "<td align='left'>$nis</td>";
            echo "<td align='left'>".$row['nama']."</td>";
            echo "<td align='left'>".$row['paymentid']."</td>";
            echo "<td align='left'>".$row['tanggal']."</td>";
            echo "<td align='center'>";
            echo "<span id='spAktif$replid'>";
            if (getLevel() != 2)
            {
                if ($row["aktif"] == 1)
                    echo "<a href='#' onclick='changeAktif($replid, 0)' title='set non aktif'><img src='../images/ico/aktif.png' border='0'></a>";
                else
                    echo "<a href='#' onclick='changeAktif($replid, 1)' title='set aktif'><img src='../images/ico/nonaktif.png' border='0'></a>";
            }
            else
            {
                if ($row["aktif"] == 1)
                    echo "<img src='../images/ico/aktif.png' border='0'>";
                else
                    echo "<img src='../images/ico/nonaktif.png' border='0'>";
            }
            echo "</span>";
            echo "</td>";
            echo "</tr>";
        }
    }

    echo "</table>";
}
?>
