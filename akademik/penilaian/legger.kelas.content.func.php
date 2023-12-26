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
function GetDataSiswa(): void
{
    global $kelas, $arrSiswa, $nisStr, $tahunajaran;

    $sql = "SELECT aktif 
              FROM jbsakad.tahunajaran
             WHERE replid = $tahunajaran";
    $res = QueryDb($sql);
    $taAktif = 0;
    if ($row = mysqli_fetch_row($res)) {
        $taAktif = $row[0];
    }

    if ($taAktif == 1)
    {
        $sql = "SELECT nis, nama
                  FROM jbsakad.siswa
                 WHERE idkelas = $kelas
                   AND aktif = 1
                 ORDER BY nama";
    }
    else
    {
        $sql = "SELECT s.nis, s.nama
                  FROM jbsakad.siswa s, jbsakad.riwayatkelassiswa r
                 WHERE s.nis = r.nis
                   AND r.idkelas = $kelas
                 ORDER BY nama";
    }

    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $arrSiswa[] = [$row[0], $row[1]];

        if ($nisStr != "") {
            $nisStr .= ",";
        }
        $nisStr .= "'" . $row[0] . "'";
    }

    if (count($arrSiswa) == 0)
    {
        echo "Tidak ditemukan data siswa";
        CloseDb();
        exit();
    }
}

function GetDataPelajaran(): void
{
    global $pelajaran, $semester, $kelas, $nisStr;
    global $arrPel, $idPelStr;

    if ($pelajaran == 0)
    {
        $sql = "SELECT DISTINCT p.replid, p.nama
                  FROM jbsakad.nap n, jbsakad.aturannhb a, jbsakad.pelajaran p, jbsakad.infonap i 
                 WHERE n.idaturan = a.replid
                   AND a.idpelajaran = p.replid
                   AND n.idinfo = i.replid
                   AND i.idsemester = $semester
                   AND i.idkelas = $kelas
                   AND n.nis IN ($nisStr)
                 ORDER BY p.nama";
    }
    else
    {
        $sql = "SELECT DISTINCT p.replid, p.nama
                  FROM jbsakad.nap n, jbsakad.aturannhb a, jbsakad.pelajaran p, jbsakad.infonap i 
                 WHERE n.idaturan = a.replid
                   AND a.idpelajaran = p.replid
                   AND n.idinfo = i.replid
                   AND i.idsemester = $semester
                   AND i.idkelas = $kelas
                   AND i.idpelajaran = $pelajaran
                   AND n.nis IN ($nisStr)
                 ORDER BY p.nama";
    }

    $arrPel = [];
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $arrPel[] = [$row[0], $row[1]];

        if ($idPelStr != "") {
            $idPelStr .= ",";
        }
        $idPelStr .= $row[0];
    }

    if (count($arrPel) == 0)
    {
        echo "Tidak ditemukan data Pelajaran atau nilai rapor belum ditentukan!";
        CloseDb();
        exit();
    }
}

function GetAspekPelajaran(): void
{
    global $arrAspekPel, $arrAspek, $arrPel, $nisStr, $arrPel;

    $arrKodeAspek = [];
    $kodeAspekStr = "";

    $nPel = count($arrPel);
    for($i = 0; $i < $nPel; $i++)
    {
        $idPelajaran = $arrPel[$i][0];

        $sql = "SELECT DISTINCT a.dasarpenilaian
                  FROM jbsakad.nap n, jbsakad.aturannhb a
                 WHERE n.idaturan = a.replid
                   AND n.nis IN ($nisStr)
                   AND a.idpelajaran = $idPelajaran
                 ORDER BY dasarpenilaian";

        $arrTemp = [];
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $kodeAspek = $row[0];

            if (!array_key_exists($kodeAspek, $arrKodeAspek))
            {
                $arrKodeAspek[$kodeAspek] = 1;

                if ($kodeAspekStr != "") {
                    $kodeAspekStr .= ",";
                }
                $kodeAspekStr .= "'$kodeAspek'";
            }

            $arrTemp[] = $kodeAspek;
        }

        $arrAspekPel[$idPelajaran] = $arrTemp;
    }

    if ($kodeAspekStr != "")
    {
        $sql = "SELECT dasarpenilaian, keterangan
                  FROM jbsakad.dasarpenilaian
                 WHERE dasarpenilaian IN ($kodeAspekStr)";
        $res = QueryDb($sql);
        while ($row = mysqli_fetch_row($res)) {
            $arrAspek[] = [$row[0], $row[1]];
        }
    }
}

function GetTableWidth(): int
{
    global $arrPel, $arrAspekPel, $arrAspek;

    $total = 30 + 140 + 280;

    $nPel = count($arrPel);
    for($i = 0; $i < $nPel; $i++)
    {
        $idPel = $arrPel[$i][0];
        $nAspek = count($arrAspekPel[$idPel]);
        $width = 60 * $nAspek;
        $total += $width;
    }

    $nAspek = count($arrAspek);

    return $total + (60 + $nAspek);
}

function Pre($array): void
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
?>