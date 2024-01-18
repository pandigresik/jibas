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
function ShowSelectDepartemen()
{
    global $departemen;

    echo "<select id='departemen' name='departemen' class='inputbox' style='width: 250px' onchange='clearContent();'>";

    if ($departemen == "")
        $departemen = "ALL";

    if (getLevel() != 2)
    {
        $sel = $departemen == "ALL" ? "selected" : "";
        echo "<option value='ALL' $sel>Semua Departemen</option>";
    }

    $dep = getDepartemen(getAccess());
    foreach($dep as $value)
    {
        $sel = $departemen == $value ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}



function ShowSelectBulan()
{
    $mn3 = date('n', strtotime("-3 months"));
    $yr3 = date('Y', strtotime("-3 months"));
    echo "<select id='bulan1' class='inputbox' style='width: 50px' onchange='clearContent()'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $mn3 == $i ? "selected" : "";
        $mnName = inaMonthName($i);
        echo "<option value='$i' $sel>$mnName</option>";
    }
    echo "</select>";
    echo "<select id='tahun1' class='inputbox' style='width: 60px' onchange='clearContent()'>";
    for($i = 2022; $i <= date('Y') + 1; $i++)
    {
        $sel = $yr3 == $i ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>&nbsp;-&nbsp;";

    $mn = date('n');
    $yr = date('Y');
    echo "<select id='bulan2' class='inputbox' style='width: 50px' onchange='clearContent()'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $mn == $i ? "selected" : "";
        $mnName = inaMonthName($i);
        echo "<option value='$i' $sel>$mnName</option>";
    }
    echo "</select>";
    echo "<select id='tahun2' class='inputbox' style='width: 60px' onchange='clearContent()'>";
    for($i = 2022; $i <= date('Y') + 1; $i++)
    {
        $sel = $yr == $i ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";

}

function ShowSelectBank()
{
    global $departemen;

    $sql = "SELECT bankno, bank 
              FROM jbsfina.bank
             WHERE aktif = 1";
    if ($departemen != "ALL")
        $sql .= " AND departemen = '".$departemen."'";
    $sql .= " ORDER BY bank";
    $res = QueryDb($sql);

    echo "<select id='bankno' class='inputbox' style='width: 250px' onchange='clearContent()'>";
    echo "<option value='ALL' selected>Semua Bank</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]. $row[0]."</option>";
    }
    echo "</select>";
}

function ShowSelectPetugas()
{
    $sql = "SELECT h.login, p.nama
              FROM jbsuser.hakakses h, jbssdm.pegawai p
             WHERE h.login = p.nip
               AND h.modul = 'KEUANGAN'
               AND h.aktif = 1
               AND p.aktif = 1
             ORDER BY p.nama";
    $res = QueryDb($sql);

    echo "<select id='idpetugas' class='inputbox' style='width: 250px' onchange='clearContent()'>";
    echo "<option value='ALL' selected>Semua Petugas</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[0]."'>".$row[1]. $row[0]."</option>";
    }
    echo "</select>";
}

function StatistikPembayaranHarian()
{
    /*
    $log = new Logger();
    foreach($_REQUEST as $k => $v)
    {
        $log->Log("$k = $v");
    }
    $log->Close();

    echo "[1,\"OK Harian\"]";
    */
    try
    {
        OpenDb();

        $departemen = $_REQUEST["departemen"];
        $tanggal1 = $_REQUEST["tanggal1"];
        $tanggal2 = $_REQUEST["tanggal2"];
        $metode = $_REQUEST["metode"];
        $bankNo = $_REQUEST["bankno"];
        $idPetugas = $_REQUEST["idpetugas"];

        $sql = "SELECT DISTINCT tanggal
                  FROM jbsfina.pgtrans
                 WHERE tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
        if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
        if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
        if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
        if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
        $sql .= " ORDER BY tanggal DESC";

        //echo $sql;
        $res = QueryDbEx($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "Belum ada data transaksi pembayaran online";
            return;
        }

        $lsTanggal = [];
        while($row = mysqli_fetch_row($res))
        {
            $lsTanggal[] = $row[0];
        }

        //PrePrintR($lsTanggal);

        echo "<span style='font-size: 16px'>STATISTIK HARIAN</span><br><br>";
        echo "<a href='#' onclick='cetakHarian()'><img src='../images/ico/print.png' border='0'>&nbsp;cetak</a>&nbsp;&nbsp;";
        echo "<a href='#' onclick='excelHarian()'><img src='../images/ico/excel.png' border='0'>&nbsp;excel</a><br>";

        echo "<div id='dvStatistik'>";
        echo "<table id='tabStatistik' cellpadding='5' cellspacing='0'>";
        echo "<tr style='height: 30px'>";
        echo "<td class='header' style='width: 30px' align='center'>No</td>";
        echo "<td class='header' style='width: 170px' align='center'>Tanggal</td>";
        echo "<td class='header' style='width: 150px' align='center'>Jumlah Siswa</td>";
        echo "<td class='header' style='width: 150px' align='center'>Jumlah Transaksi</td>";
        echo "<td class='header' style='width: 180px' align='center'>Besar Transaksi</td>";
        echo "<td class='header' id='thrincian' style='width: 100px' align='center'>&nbsp;</td>";
        echo "</tr>";

        for($i = 0; $i < count($lsTanggal); $i++)
        {
            $no = $i + 1;
            $tanggal = $lsTanggal[$i];

            $sql = "SELECT COUNT(DISTINCT nis)
                      FROM jbsfina.pgtrans
                     WHERE tanggal = '".$tanggal."'";
            if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
            if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
            if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
            if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
            $res = QueryDbEx($sql);
            $row = mysqli_fetch_row($res);
            $nSiswa = $row[0];

            $sql = "SELECT COUNT(replid)
                      FROM jbsfina.pgtrans
                     WHERE tanggal = '".$tanggal."'";
            if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
            if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
            if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
            if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
            $res = QueryDbEx($sql);
            $row = mysqli_fetch_row($res);
            $nTransaksi = $row[0];

            $sql = "SELECT SUM(pd.jumlah)
                      FROM jbsfina.pgtrans p, jbsfina.pgtransdata pd
                     WHERE p.replid = pd.idpgtrans 
                       AND p.tanggal = '".$tanggal."'";
            if ($departemen != "ALL") $sql .= " AND p.departemen = '".$departemen."'";
            if ($bankNo != "ALL") $sql .= " AND p.bankno = '".$bankNo."'";
            if ($idPetugas != "ALL") $sql .= " AND p.idpetugas = '".$idPetugas."'";
            if ($metode != "0") $sql .= " AND p.jenis = '".$metode."'";
            $res = QueryDbEx($sql);
            $row = mysqli_fetch_row($res);
            $sumTransaksi = $row[0];

            echo "<tr style='height: 30px'>";
            echo "<td style='background-color: #efefef' align='center'>$no</td>";
            $fTanggal = formatInaMySqlDate($tanggal);
            echo "<td align='left'><span style='font-size: 12px;'>$fTanggal</span></td>";
            echo "<td align='center'><span style='font-size: 16px;'>$nSiswa</span></td>";
            echo "<td align='center'><span style='font-size: 16px;'>$nTransaksi</span></td>";
            $rp = FormatRupiah($sumTransaksi);
            echo "<td align='right'><span style='font-size: 16px;'>$rp</span></td>";
            echo "<td align='center' class='rincian'>";
            echo "<a href='#' style='font-weight: normal; text-decoration: underline; color: blue;' onclick=\"showRincianHarian('$tanggal','$departemen','$bankNo','$idPetugas','$metode')\">rincian</a>";
            echo "</td>";
            echo "</tr>";
        }

        $sql = "SELECT COUNT(DISTINCT nis)
                  FROM jbsfina.pgtrans
                 WHERE tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
        if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
        if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
        if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
        if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $nSiswa = $row[0];

        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.pgtrans
                 WHERE tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
        if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
        if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
        if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
        if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $nTransaksi = $row[0];

        $sql = "SELECT SUM(pd.jumlah)
                      FROM jbsfina.pgtrans p, jbsfina.pgtransdata pd
                     WHERE p.replid = pd.idpgtrans 
                       AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
        if ($departemen != "ALL") $sql .= " AND p.departemen = '".$departemen."'";
        if ($bankNo != "ALL") $sql .= " AND p.bankno = '".$bankNo."'";
        if ($idPetugas != "ALL") $sql .= " AND p.idpetugas = '".$idPetugas."'";
        if ($metode != "0") $sql .= " AND p.jenis = '".$metode."'";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $sumTransaksi = $row[0];

        echo "<tr style='height: 30px'>";
        echo "<td align='right' style='background-color: #ffc038;' colspan='2'><span style='font-size: 16px;'>Total</span></td>";
        echo "<td align='center' style='background-color: #ffc038;'><span style='font-size: 16px; font-weight: bold;'>$nSiswa</span></td>";
        echo "<td align='center' style='background-color: #ffc038;'><span style='font-size: 16px; font-weight: bold;'>$nTransaksi</span></td>";
        $rp = FormatRupiah($sumTransaksi);
        echo "<td align='right' style='background-color: #ffc038;'><span style='font-size: 16px; font-weight: bold;'>$rp</span></td>";
        echo "<td align='right' class='rincian' style='background-color: #ffc038;'>&nbsp;</td>";
        echo "</tr>";

        echo "</table>";
        echo "</div>";
    }
    catch (Exception $ex)
    {
        echo "ERROR: " . $ex->getMessage();
    }
    finally
    {
        CloseDb();
    }
}

function StatistikPembayaranBulanan()
{
    try
    {
        OpenDb();

        $departemen = $_REQUEST["departemen"];
        $bulan1 = $_REQUEST["bulan1"];
        $tahun1 = $_REQUEST["tahun1"];
        $bulan2 = $_REQUEST["bulan2"];
        $tahun2 = $_REQUEST["tahun2"];
        $metode = $_REQUEST["metode"];
        $bankNo = $_REQUEST["bankno"];
        $idPetugas = $_REQUEST["idpetugas"];

        $sql = "SELECT DISTINCT MONTH(tanggal), YEAR(tanggal)
                  FROM jbsfina.pgtrans
                 WHERE ((MONTH(tanggal) >= $bulan1 AND YEAR(tanggal) >= $tahun1) 
                   AND  (MONTH(tanggal) <= $bulan2 AND YEAR(tanggal) <= $tahun2))";
        if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
        if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
        if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
        if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
        $sql .= " ORDER BY tanggal DESC";

        //echo $sql;
        $res = QueryDbEx($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "Belum ada data transaksi pembayaran online";
            return;
        }

        $lsBulan = [];
        while($row = mysqli_fetch_row($res))
        {
            $lsBulan[] = [$row[0], $row[1]];
        }

        echo "<span style='font-size: 16px'>STATISTIK BULANAN</span><br><br>";
        echo "<a href='#' onclick='cetakBulanan()'><img src='../images/ico/print.png' border='0'>&nbsp;cetak</a>&nbsp;&nbsp;";
        echo "<a href='#' onclick='excelBulanan()'><img src='../images/ico/excel.png' border='0'>&nbsp;excel</a><br>";

        echo "<div id='dvStatistik'>";
        echo "<table id='tabStatistik' cellpadding='5' cellspacing='0'>";
        echo "<tr style='height: 30px'>";
        echo "<td class='header' style='width: 30px' align='center'>No</td>";
        echo "<td class='header' style='width: 170px' align='center'>Bulan</td>";
        echo "<td class='header' style='width: 150px' align='center'>Jumlah Siswa</td>";
        echo "<td class='header' style='width: 150px' align='center'>Jumlah Transaksi</td>";
        echo "<td class='header' style='width: 180px' align='center'>Besar Transaksi</td>";
        echo "<td class='header' id='thrincian' style='width: 100px' align='center'>&nbsp;</td>";
        echo "</tr>";

        for($i = 0; $i < count($lsBulan); $i++)
        {
            $no = $i + 1;
            $lsItem = $lsBulan[$i];
            $bulan = $lsItem[0];
            $tahun = $lsItem[1];

            $sql = "SELECT COUNT(DISTINCT nis)
                      FROM jbsfina.pgtrans
                     WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun";
            if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
            if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
            if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
            if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
            $res = QueryDbEx($sql);
            $row = mysqli_fetch_row($res);
            $nSiswa = $row[0];

            $sql = "SELECT COUNT(replid)
                      FROM jbsfina.pgtrans
                     WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun";
            if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
            if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
            if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
            if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
            $res = QueryDbEx($sql);
            $row = mysqli_fetch_row($res);
            $nTransaksi = $row[0];

            $sql = "SELECT SUM(pd.jumlah)
                      FROM jbsfina.pgtrans p, jbsfina.pgtransdata pd
                     WHERE p.replid = pd.idpgtrans 
                       AND MONTH(p.tanggal) = $bulan AND YEAR(p.tanggal) = $tahun";
            if ($departemen != "ALL") $sql .= " AND p.departemen = '".$departemen."'";
            if ($bankNo != "ALL") $sql .= " AND p.bankno = '".$bankNo."'";
            if ($idPetugas != "ALL") $sql .= " AND p.idpetugas = '".$idPetugas."'";
            if ($metode != "0") $sql .= " AND p.jenis = '".$metode."'";
            $res = QueryDbEx($sql);
            $row = mysqli_fetch_row($res);
            $sumTransaksi = $row[0];

            echo "<tr style='height: 30px'>";
            echo "<td style='background-color: #efefef' align='center'>$no</td>";
            $fBulan = inaMonthName($bulan) . " $tahun";
            echo "<td align='left'><span style='font-size: 12px;'>$fBulan</span></td>";
            echo "<td align='center'><span style='font-size: 16px;'>$nSiswa</span></td>";
            echo "<td align='center'><span style='font-size: 16px;'>$nTransaksi</span></td>";
            $rp = FormatRupiah($sumTransaksi);
            echo "<td align='right'><span style='font-size: 16px;'>$rp</span></td>";
            echo "<td align='center' class='rincian'>";
            echo "<a href='#' style='font-weight: normal; text-decoration: underline; color: blue;' onclick=\"showRincianBulanan('$bulan','$tahun','$departemen','$bankNo','$idPetugas','$metode')\">rincian</a>";
            echo "</td>";
            echo "</tr>";
        }

        $sql = "SELECT COUNT(DISTINCT nis)
                  FROM jbsfina.pgtrans
                 WHERE ((MONTH(tanggal) >= $bulan1 AND YEAR(tanggal) >= $tahun1) 
                   AND  (MONTH(tanggal) <= $bulan2 AND YEAR(tanggal) <= $tahun2))";
        if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
        if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
        if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
        if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $nSiswa = $row[0];

        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.pgtrans
                 WHERE ((MONTH(tanggal) >= $bulan1 AND YEAR(tanggal) >= $tahun1) 
                   AND  (MONTH(tanggal) <= $bulan2 AND YEAR(tanggal) <= $tahun2))";
        if ($departemen != "ALL") $sql .= " AND departemen = '".$departemen."'";
        if ($bankNo != "ALL") $sql .= " AND bankno = '".$bankNo."'";
        if ($idPetugas != "ALL") $sql .= " AND idpetugas = '".$idPetugas."'";
        if ($metode != "0") $sql .= " AND jenis = '".$metode."'";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $nTransaksi = $row[0];

        $sql = "SELECT SUM(pd.jumlah)
                  FROM jbsfina.pgtrans p, jbsfina.pgtransdata pd
                 WHERE p.replid = pd.idpgtrans 
                   AND ((MONTH(p.tanggal) >= $bulan1 AND YEAR(p.tanggal) >= $tahun1) 
                   AND  (MONTH(p.tanggal) <= $bulan2 AND YEAR(p.tanggal) <= $tahun2))";
        if ($departemen != "ALL") $sql .= " AND p.departemen = '".$departemen."'";
        if ($bankNo != "ALL") $sql .= " AND p.bankno = '".$bankNo."'";
        if ($idPetugas != "ALL") $sql .= " AND p.idpetugas = '".$idPetugas."'";
        if ($metode != "0") $sql .= " AND p.jenis = '".$metode."'";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $sumTransaksi = $row[0];

        echo "<tr style='height: 30px'>";
        echo "<td align='right' style='background-color: #ffc038;' colspan='2'><span style='font-size: 16px;'>Total</span></td>";
        echo "<td align='center' style='background-color: #ffc038;'><span style='font-size: 16px; font-weight: bold;'>$nSiswa</span></td>";
        echo "<td align='center' style='background-color: #ffc038;'><span style='font-size: 16px; font-weight: bold;'>$nTransaksi</span></td>";
        $rp = FormatRupiah($sumTransaksi);
        echo "<td align='right' style='background-color: #ffc038;'><span style='font-size: 16px; font-weight: bold;'>$rp</span></td>";
        echo "<td align='right' class='rincian' style='background-color: #ffc038;'>&nbsp;</td>";
        echo "</tr>";

        echo "</table>";
        echo "</div>";
    }
    catch (Exception $ex)
    {
        echo "ERROR: " . $ex->getMessage();
    }
    finally
    {
        CloseDb();
    }
}
?>