<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 15 (January 02, 2019)
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
function showSelectBulan()
{
    $arrBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];

    $select = "<select id='cbe_cbBulan' class='inputbox' style='width: 120px' onchange='cbe_getPelajaran()'>";
    for($i = 0; $i < count($arrBulan); $i++)
    {
        $selected = ($i + 1) == date('n') ? "selected" : "";
        $select .= "<option value='" . ($i + 1) . "' $selected>" . $arrBulan[$i] . "</option>";
    }
    $select .= "</select>";

    return $select;
}

function showSelectTahun()
{
    $yearNow = date('Y');
    $select = "<select id='cbe_cbTahun' class='inputbox' style='width: 70px' onchange='cbe_getPelajaran()'>";
    for($i = $yearNow - 1; $i <= $yearNow + 1; $i++)
    {
        $selected = $i == $yearNow ? "selected" : "";
        $select .= "<option value='$i' $selected>$i</option>";
    }
    $select .= "</select>";

    return $select;
}

function showSelectJenisUjian()
{
    $select  = "<select id='cbe_cbJenisUjian' class='inputbox' style='width: 90px' onchange='cbe_getPelajaran()'>";
    $select .= "<option value='-1'>(semua)</option>";
    $select .= "<option value='0'>Umum</option>";
    $select .= "<option value='1'>Khusus</option>";
    $select .= "</select>";

    return $select;
}

function showSelectPelajaran($bulan, $tahun, $jenis)
{
    $nis = $_SESSION["infosiswa.nis"];

    $sql = "SELECT DISTINCT p.idpelajaran, pel.nama
              FROM jbscbe.pengujian p, jbscbe.ujian u, jbscbe.ujianserta us, jbsakad.pelajaran pel
             WHERE p.id = u.idpengujian
               AND u.id = us.idujian
               AND p.idpelajaran = pel.replid
               AND us.nis = '$nis'
               AND MONTH(us.tanggal) = $bulan
               AND YEAR(us.tanggal) = $tahun";

    if ($jenis <> -1)
        $sql .= " AND p.status = $jenis";

    $sql .= " ORDER BY pel.nama";

    $select = "<select id='cbe_cbPelajaran' class='inputbox' style='width: 180px' onchange='cbe_changePelajaran()'>";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $select .= "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    $select .= "</select>";

    return $select;
}

function showRekapUjian($bulan, $tahun, $jenis, $idpelajaran)
{
    $nis = $_SESSION["infosiswa.nis"];

    $sql = "SELECT DISTINCT IFNULL(us.idujianremed, us.idujian), DATE_FORMAT(us.tanggal, '%d-%m-%Y %h:%i:%s')
              FROM jbscbe.ujian u, jbscbe.ujianserta us, jbscbe.pengujian p
             WHERE u.id = us.idujian
               AND u.idpengujian = p.id
               AND p.idpelajaran = $idpelajaran
               AND us.nis = '$nis'
               AND MONTH(us.tanggal) = $bulan
               AND YEAR(us.tanggal) = $tahun";

    if ($jenis != -1)
        $sql .= " AND p.status = $jenis";

    $sql .= " ORDER BY us.tanggal DESC";

    $lsId = [];
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $lsId[] = [$row[0], $row[1]];
    }

    if (count($lsId) == 0)
        return " Tidak ada data hasil ujian!";

    $stIdUjian = "";
    $lsIdUjian = [];

    for($i = 0; $i < count($lsId); $i++)
    {
        $id = $lsId[$i][0];
        $tglUjian = $lsId[$i][1];

        $sql = "SELECT u.id, IFNULL(u.idremedujian, 0), u.judul
                  FROM jbscbe.ujian u 
                 WHERE u.id = $id";
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $lsIdUjian[] = [$row[0], $row[1], $row[2], $tglUjian];
        }
    }

    if (count($lsIdUjian) == 0)
        return " Tidak ada data hasil ujian!";

    $table  = "<table border='1' cellpadding='2' cellspacing='0' style='border-color: #144da4; border-collapse: collapse'>";
    $table .= "<tr style='height: 30px'>";
    $table .= "<td style='width: 40px; background-color: #144da4; color: #fff;' align='center'>No</td>";
    $table .= "<td style='width: 120px; background-color: #144da4; color: #fff;' align='center'>Nilai</td>";
    $table .= "<td style='width: 350px; background-color: #144da4; color: #fff;' align='center'>Ujian</td>";
    $table .= "<td style='width: 100px; background-color: #144da4; color: #fff;' align='center'>Benar</td>";
    $table .= "<td style='width: 100px; background-color: #144da4; color: #fff;' align='center'>Salah</td>";
    $table .= "<td style='width: 250px; background-color: #144da4; color: #fff;' align='center'>Status</td>";
    $table .= "</tr>";

    $no = 0;
    for($i = 0; $i < count($lsIdUjian); $i++)
    {
        $idUjian = $lsIdUjian[$i][0];
        $idRemedUjian = $lsIdUjian[$i][1];
        $judul = $lsIdUjian[$i][2];
        $tglUjian = $lsIdUjian[$i][3];

        $idUjianInUjianSerta = $idRemedUjian != 0 ? $idRemedUjian : $idUjian;

        $haveRemed = false;
        $sql = "SELECT COUNT(id) 
                  FROM jbscbe.ujianserta
                 WHERE idujian = $idUjianInUjianSerta
                   AND nis = '$nis'
                   AND idujianremed IS NOT NULL";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $haveRemed = $row[0] > 0;

        $sifatUjian = 0; // 0 Umum 1 Tertutup
        $sql = "SELECT p.status
                  FROM jbscbe.ujian u, jbscbe.pengujian p
                 WHERE u.idpengujian = p.id
                   AND u.id = $idUjian";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $sifatUjian = $row[0];

        $skalanilai = 10;
        $nilaikkm = 0;
        $viewresult = true;

        $sql = "SELECT skalanilai, kkm, viewresult
                  FROM jbscbe.ujian
                 WHERE id = $idUjianInUjianSerta";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $skalanilai = $row[0];
            $nilaikkm = $row[1];
            $viewresult = $row[2] == 1;
        }

        $cf = new ColorFactory(0, $skalanilai);

        $sql = "SELECT u.id, u.jbenar, u.jsalah, u.tbobot, u.tnilai, 
                       u.nilai, u.elapsed, u.idujian, 
                       IFNULL(u.idujianremed, 0) AS idujianremed,
                       DATE_FORMAT(u.tanggal, '%d-%m-%Y %H:%i') AS tanggal, u.status,
                       DATE_FORMAT(u.tanggal, '%Y%m%d%H%i') AS tanggalsort     
                  FROM jbscbe.ujianserta u
                 WHERE u.idujian = $idUjianInUjianSerta
                   AND u.nis = '$nis' 
                   AND u.status IN (1,2)";

        if ($sifatUjian == 1)
        {
            // Untuk ujian tertutup
            if ($idRemedUjian != 0)
            {
                // Bila yang dipilih ujian remedial, maka nilai terakhir adalah hasil remedial
                $sql .= " AND u.idujianremed = $idUjian";
            }
            else
            {
                // Bila yang dipilih ujian awal, maka nilai ditampilkan adalah
                //  bila ada remedial, maka nilai awal
                $sql .= " AND u.lastdata = " . ($haveRemed ? 0 : 1);
            }
        }

        $sql .= " ORDER BY u.tanggal DESC";

        $res = QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
            continue;

        while($row = mysqli_fetch_array($res))
        {
            $no += 1;

            $idUjianRemed = $row["idujianremed"];
            $isRemed = $idUjianRemed == 0 ? 0 : 1;
            $statusUjian = getStatusUjian($row["status"], $isRemed);
            $statusNilai = getStatusNilai($row["nilai"], $nilaikkm, $row["status"]);
            $nilaiInfo = $row["status"] != 2 ? "--" : $row["nilai"];

            $ujian  = "<span style='font-size: 14px; font-weight: bold'>" . $judul . "</span><br>";
            $ujian .= "Tanggal: " . $row['tanggal'] . "<br>";
            $ujian .= "Waktu: " . $row['elapsed'] . " menit";

            $status  = "Nilai KKM: " . $nilaikkm . "<br>";
            $status .= "Hasil: " . $statusNilai . "<br>";
            $status .= "Status: " . $statusUjian;

            $nilaiColor = $cf->GetColorCode($row['nilai'] );

            $table .= "<tr style='height: 60px'>";
            $table .= "<td align='center' valign='top' style='background-color: #ededed'>$no</td>";
            $table .= "<td align='center' valign='top' style='font-size: 20px; color: #fff; background-color: $nilaiColor'>" . $nilaiInfo . "</td>";
            $table .= "<td align='left' valign='top' style='background-color: #fff'>$ujian</td>";
            $table .= "<td align='center' valign='top' style='font-size: 16px; background-color: #ecfbfc'>" . $row['jbenar'] . "</td>";
            $table .= "<td align='center' valign='top' style='font-size: 16px; background-color: #fcf2ec'>" . $row['jsalah'] . "</td>";
            $table .= "<td align='left' valign='top' style='background-color: #fff'>$status</td>";
            $table .= "</tr>";
        }
    }

    $table .= "</table>";

    return $table;
}

function getStatusNilai($nilai, $kkm, $statusUjian)
{
    if ($statusUjian != 2)
        return "--";

    if ($nilai >= $kkm)
        return "Lulus";

    return "Kurang";
}

function getStatusUjian($status, $isRemed)
{
    $statusUjian = $isRemed == 1 ? "Remedial, " : "";

    if ($status == -1)
        $statusUjian .= "Pending";

    if ($status == 0)
        $statusUjian .= "Sedang Berlangsung";

    if ($status == 1)
        $statusUjian .= "Tunggu Verifikasi Esai";

    if ($status == 2)
        $statusUjian .= "Selesai";

    return $statusUjian;

}












































?>