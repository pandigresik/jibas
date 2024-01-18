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
require_once("../include/cbe.config.php");
require_once("include/session.php");
require_once("include/config.php");
require_once("include/db_functions.php");
require_once("library/colorfactory.php");

function getWelcome()
{
    $welcome = "";
    $dept = $_SESSION["UserDept"];

    OpenDb();
    $sql = "SELECT pesan 
              FROM jbscbe.welcome
             WHERE departemen = '".$dept."'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $welcome = $row[0];
    }
    CloseDb();

    return $welcome;
}

function getCurrentMonthYear()
{
    $bulan = date('n');
    $tahun = date('Y');

    return match ($bulan) {
        1 => "Januari $tahun",
        2 => "Februai $tahun",
        3 => "Maret $tahun",
        4 => "April $tahun",
        5 => "Mei $tahun",
        6 => "Juni $tahun",
        7 => "Juli $tahun",
        8 => "Agustus $tahun",
        9 => "September $tahun",
        10 => "Oktober $tahun",
        11 => "Nopember $tahun",
        default => "Desember $tahun",
    };
}

function getLastUjian($page)
{
    $userId = $_SESSION["UserId"];
    $userType = $_SESSION["UserType"];
    if ($userType == "pegawai")
        $userCol = "nip";
    else if ($userType == "calonsiswa")
        $userCol = "nic";
    else
        $userCol = "nis";

    $nItemPerPage = 5;
    $startIndex = ($page - 1) * $nItemPerPage;

    OpenDb();
    $sql = "SELECT COUNT(*) 
              FROM jbscbe.ujianserta us
             WHERE us.$userCol = '$userId'
               AND us.status IN (1,2)";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $ndata = $row[0];

    $sql = "SELECT id, tanggal, judul, nilai, status, skalanilai, idujianremed, viewresult, belumhasil, tanggalsort FROM
                  (SELECT us.id, DATE_FORMAT(us.tanggal, '%d-%m-%Y<br>%H:%i') AS tanggal, 
                          u.judul, us.nilai, us.status, u.skalanilai, IFNULL(us.idujianremed, 0) AS idujianremed, viewresult, 0 as belumhasil,
                          DATE_FORMAT(us.tanggal, '%Y-%m-%d %H:%i:%s') AS tanggalsort
                     FROM jbscbe.ujianserta us, jbscbe.ujian u
                    WHERE us.idujian = u.id
                      AND us.$userCol = '$userId'
                      AND us.status IN (1,2)
                    UNION
                   SELECT us.id, DATE_FORMAT(us.tanggal, '%d-%m-%Y<br>%H:%i') AS tanggal, 
                          u.judul, us.nilai, us.status, u.skalanilai, IFNULL(us.idujianremed, 0) AS idujianremed, viewresult, 1 as belumhasil,
                          DATE_FORMAT(us.tanggal, '%Y-%m-%d %H:%i:%s') AS tanggalsort
                     FROM jbscbe.ujianserta us, jbscbe.ujian u
                    WHERE us.idujian = u.id
                      AND us.$userCol = '$userId'
                      AND us.status = 0
                      AND us.ujianmode = 1
                      AND DATE_ADD(us.tanggal, INTERVAL u.durasi MINUTE) < NOW()) AS X
                 ORDER BY tanggalsort DESC
                    LIMIT $startIndex, $nItemPerPage";
    $res = QueryDb($sql);

    $table = "";

    $count = $startIndex;
    while($row = mysqli_fetch_array($res))
    {
        $no = $ndata - $count;
        $viewresult = $row["viewresult"];

        $skalanilai = $row["skalanilai"];
        $nilai = $row["nilai"];
        $status = $row["status"];
        if ($status == 1)
        {
            $hasil = "--";
            $info = "Tunggu Verifikasi Essay";
        }
        else
        {
            $hasil = $row["nilai"];
            $info = "Selesai";
        }
        $judul = $row["judul"];
        $tanggal = $row["tanggal"];

        $idujianremed = $row["idujianremed"];
        if ($idujianremed != 0)
        {
            $sql = "SELECT judul FROM jbscbe.ujian WHERE id = $idujianremed";
            $res2 = QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
                $judul = $row2[0];
        }

        $cf = new ColorFactory(0, $skalanilai);
        $color = $cf->GetColorCode($nilai);

        if ($viewresult == 0)
        {
            $color = "#b6b6b6";
            $hasil = "?";
        }

        $table .= "<tr>";
        $table .= "<td align='center' style='background-color: #ededed;'>$no</td>";
        $table .= "<td align='center' style='background-color: $color; color: #fff'>$hasil</td>";
        $table .= "<td align='left'>$judul<br><i>$info</i></td>";
        $table .= "<td align='center'>$tanggal</td>";
        $table .= "</tr>";

        $count += 1;
    }
    CloseDb();

    return $table;
}

?>