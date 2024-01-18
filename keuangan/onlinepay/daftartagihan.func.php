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

    echo "<select id='departemen' name='departemen' style='width: 250px' class='inputbox' onchange='changeTagihanSel()'>";
    $dep = getDepartemen(getAccess());
    foreach($dep as $value)
    {
        if ($departemen == "") $departemen = $value;
        $sel = $departemen == $value ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowSelectBulan()
{
    global $bulan;

    echo "<select id='bulan' name='bulan' style='width: 100px' class='inputbox' onchange='changeTagihanSel()'>";
    for($bln = 1; $bln <= 12; $bln++)
    {
        $sel = $bln == $bulan ? "selected" : "";
        $nama = NamaBulan($bln);
        echo "<option value='$bln' $sel>$nama</option>";
    }
    echo "</select>";
}

function ShowSelectTahun()
{
    global $tahun;

    $currThn = date('Y');
    echo "<select id='tahun' name='tahun' style='width: 70px' class='inputbox' onchange='changeTagihanSel()'>";
    for($thn = $currThn - 1; $thn <= $currThn + 1; $thn++)
    {
        $sel = $thn == $tahun ? "selected" : "";
        echo "<option value='$thn' $sel>$thn</option>";
    }
    echo "</select>";
}

function ShowTagihanSet()
{
    global $departemen, $tahun, $bulan;

    $sql = "SELECT replid, nama, stiuran, DATE_FORMAT(tanggalbuat, '%d-%b-%Y %H:%i') AS ftanggal, nomor 
              FROM jbsfina.tagihanset
             WHERE departemen = '$departemen'
               AND tahun = $tahun
               AND bulan = $bulan
             ORDER BY tanggalbuat DESC";
    $res = QueryDb($sql);

    $nData = mysqli_num_rows($res);
    if ($nData == 0)
    {
        echo "<br><br>tidak ditemukan data tagihan";
        return;
    }

    $no = 0;
    echo "<table id='tabTagihanSet' border='1' cellpadding='2' cellspacing='0' style='width: 390px; border: 1px solid #999; border-collapse: collapse; background-color: #ffffff;'>";
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        $idTagihanSet = $row["replid"];

        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.tagihansiswainfo
                 WHERE idtagihanset = $idTagihanSet
                   AND status = 0";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $nBelum = $row2[0];

        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.tagihansiswainfo
                 WHERE idtagihanset = $idTagihanSet
                   AND status = 1";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $nKonfirmasi = $row2[0];

        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.tagihansiswainfo
                 WHERE idtagihanset = $idTagihanSet
                   AND status = 2";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $nSelesai = $row2[0];

        echo "<tr>";
        echo "<td rowspan='2' align='center' valign='top' style='width: 30px; background-color: #efefef;'>$no</td>";
        echo "<td style='font-size: 12px; cursor: pointer;' onclick='showTagihanInfo($no)' colspan='4'>";
        echo "<b> " . $row['nama'] . "</b><br><span style='color: #0000FF;'><b>" . $row['stiuran'] . "</b></span><br>";
        echo "<span style='color: #b50000; font-style: italic;'>".$row['nomor']."&nbsp;&nbsp;|&nbsp;&nbsp;".$row['ftanggal']."</span>";
        echo "<input type='hidden' id='idtagihanset-$no' value='$idTagihanSet'>";
        $title = "<b>" . $row["nama"] . "</b><br><span style='color: #0000FF'><b>" . $row['stiuran'] . "</b></span>";
        echo "<input type='hidden' id='tagihanset-$no' value=\"$title\">";
        $infots = $row['nomor']."&nbsp;&nbsp;|&nbsp;&nbsp;".$row['ftanggal'];
        echo "<input type='hidden' id='infotagihanset-$no' value='$infots'>";
        echo "</td>";
        echo "</tr>";
        echo "<tr style='height: 30px'>";
        echo "<td style='background-color: #cecece;font-size: 12px;' width='27%'>";
        echo "Belum: $nBelum";
        echo "<input type='hidden' id='nbelum-$no' value='$nBelum'>";
        echo "</td>";
        echo "<td style='background-color: #f1fff3;font-size: 12px;' width='27%'>";
        echo "Konfirmasi: $nKonfirmasi";
        echo "<input type='hidden' id='nkonfirmasi-$no' value='$nKonfirmasi'>";
        echo "</td>";
        echo "<td style='background-color: #dbf3ff;font-size: 12px;' width='27%'>";
        echo "Selesai: $nSelesai";
        echo "<input type='hidden' id='nselesai-$no' value='$nSelesai'>";
        echo "</td>";
        echo "<td style='background-color: #dbf3ff;font-size: 12px;' width='*' align='center'>";
        echo "<img id='imHapusTagihanSet-$no' src='../images/ico/hapus.png' style='cursor: pointer' 
                   title='hapus semua tagihan ini' onclick='hapusTagihanSet($no, $idTagihanSet)'>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function DaftarTagihanInfo()
{
    global $PG_SERVICE_FEE;

    $rowPerPage = 10;

    $idTagihanSet = $_REQUEST["idtagihanset"];
    $tagihanSet = $_REQUEST["tagihanset"];
    $infoSet = $_REQUEST["infotagihanset"];
    $pageInfo = $_REQUEST["pageinfo"];
    $status = $_REQUEST["status"];
    $limitStart = ($pageInfo - 1) * $rowPerPage;

    $sql = "SELECT COUNT(replid)
              FROM jbsfina.tagihansiswainfo
             WHERE idtagihanset = $idTagihanSet";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $nData = $row[0];
    if ($nData == 0)
    {
        echo "<br><br>Tidak ada data tagihan";
        return;
    }

    $nPage = ceil($nData / $rowPerPage);

    $sql = "SELECT t.replid, t.nis, s.nama, t.info, t.jumlah, t.status, t.notagihan,
                   t.notagihan, DATE_FORMAT(t.ckdate, '&d-%b-%Y') AS fdate, IFNULL(t.ckdesc, '') AS ckdesc
              FROM jbsfina.tagihansiswainfo t, jbsakad.siswa s
             WHERE t.nis = s.nis
               AND t.idtagihanset = $idTagihanSet";
    if ($status != 100)
        $sql .= " AND t.status = $status";
    $sql .= " ORDER BY s.nama
              LIMIT $limitStart, $rowPerPage";
    $res = QueryDb($sql);
    $nTagihan = mysqli_num_rows($res);

    echo "<input type='hidden' id='idtagihanset' value='$idTagihanSet'>";
    echo "<table border='0' cellpadding='2' cellspacing='0' width='98%'>";
    echo "<tr>";
    echo "<td colspan='2' valign='top'><span style='font-size: 12px'>$tagihanSet</span><br>";
    echo "<span style='color: #b50000; font-style: italic;'>$infoSet</span>";
    echo "<input type='hidden' id='tagihanset' value='$tagihanSet'>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td width='50%' valign='top'><br>Status&nbsp;";
    echo "<select id='status' style='width: 120px' class='inputbox' onchange='changeStatus()'>";
    $sel = $status == 100 ? "selected" : "";
    echo "<option value='100' $sel>Semua data</option>";
    $sel = $status == 0 ? "selected" : "";
    echo "<option value='0' $sel>Belum konfirmasi</option>";
    $sel = $status == 1 ? "selected" : "";
    echo "<option value='1' $sel>Sudah konfirmasi</option>";
    $sel = $status == 2 ? "selected" : "";
    echo "<option value='2' $sel>Selesai</option>";
    echo "</select>";
    echo "</td>";
    echo "<td width='50%' valign='top' align='right'><br>halaman&nbsp;";
    echo "<select id='halaman' style='width: 40px' class='inputbox' onchange='changePage()'>";
    for($i = 1; $i <= $nPage; $i++)
    {
        $sel = $i == $pageInfo ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>&nbsp;dari $nPage";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    if ($nTagihan == 0)
    {
        echo "<br><br>Tidak ada data tagihan";
        return;
    }

    echo "<table id='tabTagihanInfo' border='1' cellpadding='2' cellspacing='0' style='width: 390px; border: 1px solid #efefef; border-collapse: collapse; background-color: #ffffff;'>";
    $nSiswa = 0;
    while($row = mysqli_fetch_array($res))
    {
        $nSiswa += 1;

        $idTagihanInfo = $row["replid"];
        $nis = urlencode((string) $row["nis"]);
        $nama = urlencode((string) $row["nama"]);
        $noTagihan = $row["notagihan"];

        $jumlah = $row["jumlah"];
        $jumlah += $PG_SERVICE_FEE;

        echo "<tr style='font-size: 10px; cursor: pointer;' onclick='showTagihanData(\"$nis\",\"$nama\",\"$noTagihan\",\"$idTagihanInfo\")'>";
        echo "<td style='width: 140px' valign='top' align='left'>";
        echo "<input type='checkbox' id='chsiswa-$nSiswa'><b>" . $row["nama"] . "</b><br>" . $row["nis"];
        echo "<input type='hidden' id='nis-$nSiswa' value='$nis'>";
        echo "</td>";
        echo "<td style='width: 230px' valign='top' align='left'>";
        echo "<b>" . FormatRupiah($jumlah) . "</b><br><i>" . $row["notagihan"] . "</i>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table><br>";

    echo "<input type='hidden' id='nsiswa' value='$nSiswa'>";
    if ($nSiswa > 0)
    {
        echo "<img src='../images/selectall.png' width='16' onclick='checkSiswa(1)' title='select all'>&nbsp;&nbsp;";
        echo "<img src='../images/deselectall.png' width='16' onclick='checkSiswa(0)' title='deselect all'>&nbsp;&nbsp;";
        echo "<input type='button' class='but' value='Kirim Pesan Tagihan' style='height: 30px' onclick='prepareBatchNotif()'>";
    }
}

function ShowPrepareBatchNotif()
{
    $departemen = $_REQUEST["departemen"];
    $stNis = $_REQUEST["stnis"];
    $idTagihanSet = $_REQUEST["idtagihanset"];

    // ---- ambil pesan notifikasi tagihan
    $pesanNotifikasiTagihan = "";
    $sql = "SELECT pesan 
              FROM jbsfina.formatpesanpg
             WHERE departemen = '$departemen'
               AND kelompok = 'TAGIHAN'";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $pesanNotifikasiTagihan = $row[0];
    }
    else
    {
        $pesanNotifikasiTagihan = "Kami informasikan {NAMA} {NIS} memiliki tagihan sebesar {JUMLAH} untuk {IURAN} bulan {BULAN} {TAHUN}";

        $sql = "INSERT INTO jbsfina.formatpesanpg
                   SET pesan = '$pesanNotifikasiTagihan', departemen = '$departemen', kelompok = 'TAGIHAN', issync = 0";
        QueryDb($sql);
    }

    echo "<span style='font-size: 16px; font-family: Arial; font-weight: bold;'>Kirim Pesan Tagihan</span><br><br>";
    echo "<textarea rows='5' cols='60' id='pt_pesan' class='inputbox'>$pesanNotifikasiTagihan</textarea><br>";
    echo "<input type='hidden' id='pt_dept' value=\"$departemen\">";
    echo "<input type='hidden' id='pt_stnis' value=\"$stNis\">";
    echo "<input type='hidden' id='pt_idtagihanset' value=\"$idTagihanSet\">";
    echo "<div style='width: 600px'>";
    echo "<input type='button' id='pt_kirim' class='but' style='height: 30px; width: 60px' value='Kirim' onclick='sendBatchNotif()'>";
    echo "&nbsp;&nbsp;<span style='color: #0000ff' id='pt_info'></span>";
    echo "</div>";
}

function SendNotif()
{
    try
    {
        $dept = $_REQUEST["dept"];
        $nis = $_REQUEST["nis"];
        $nama = $_REQUEST["nama"];
        $pesan = $_REQUEST["pesan"];

        $success = 0;
        CreateSMSTunggakan("SISPAY", $dept, $nis, $nama, $pesan, $success);

        echo "[1,\"Berhasil menyiapkan pesan tagihan\"]";
    }
    catch (Exception $ex)
    {
        $msg = $ex->getMessage();
        echo "[-1,\"$msg\"]";
    }
}

function SendBatchNotif()
{
    try
    {
        $stNis = $_REQUEST["stnis"];
        $stNis = str_replace("`", "'", (string) $stNis);
        $idTagihanSet = $_REQUEST["idtagihanset"];
        $dept = $_REQUEST["dept"];
        $pesanNotifikasiTagihan = $_REQUEST["pesan"];

        $sql = "SELECT ts.stiuran, t.jumlah, t.nis, s.nama, ts.bulan, ts.tahun
                  FROM jbsfina.tagihansiswainfo t
                 INNER JOIN jbsakad.siswa s ON t.nis = s.nis
                 INNER JOIN jbsfina.tagihanset ts ON t.idtagihanset = ts.replid
                 WHERE t.idtagihanset = $idTagihanSet
                   AND t.status IN (0, 1)
                   AND t.nis IN ($stNis)";

        //$log = new Logger();
        //$log->Log($sql);

        $count = 0;

        $res = QueryDbEx($sql);
        while($row = mysqli_fetch_array($res))
        {
            $count += 1;

            $nama = $row["nama"];
            $nis = $row["nis"];
            $totalTagihan = $row["jumlah"];
            $stIuran = $row["stiuran"];
            $bulan = $row["bulan"];
            $tahun = $row["tahun"];

            $pesan = str_replace("{NAMA}", $nama, (string) $pesanNotifikasiTagihan);
            $pesan = str_replace("{NIS}", $nis, $pesan);
            $pesan = str_replace("{JUMLAH}", FormatRupiah($totalTagihan), $pesan);
            $pesan = str_replace("{IURAN}", $stIuran, $pesan);
            $pesan = str_replace("{BULAN}", NamaBulan($bulan), $pesan);
            $pesan = str_replace("{TAHUN}", $tahun, $pesan);

            //$log->Log($pesan);

            $success = 0;
            CreateSMSTunggakan("SISPAY", $dept, $nis, $nama, $pesan, $success);
        }
        //$log->Close();

        echo "[1,\"Berhasil menyiapkan $count pesan tagihan\"]";
    }
    catch (Exception $ex)
    {
        $msg = $ex->getMessage();
        echo "[-1,\"$msg\"]";
    }
}

function DaftarTagihanData()
{
    global $PG_SERVICE_FEE;

    $nis = $_REQUEST["nis"];
    $nama = $_REQUEST["nama"];
    $noTagihan = $_REQUEST["notagihan"];
    $idTagihanInfo = $_REQUEST["idtagihaninfo"];

    $sql = "SELECT t.info, t.jumlah, t.status,
                   t.notagihan, DATE_FORMAT(t.ckdate, '%d %M %Y %H:%i') AS fckdate, IFNULL(t.ckdesc, '') AS ckdesc,
                   IFNULL(ts.petugas, 'admin') AS nippetugas, IFNULL(p.nama, 'Administrator JIBAS') AS namapetugas, 
                   DATE_FORMAT(ts.tanggalbuat, '%d %M %Y %H:%i') AS ftanggalbuat,
                   ts.departemen, ts.stiuran, ts.bulan, ts.tahun, ts.nomor AS nomorts
              FROM jbsfina.tagihansiswainfo t
             INNER JOIN jbsfina.tagihanset ts ON t.idtagihanset = ts.replid
              LEFT JOIN jbssdm.pegawai p ON ts.petugas = p.nama
             WHERE t.replid = $idTagihanInfo";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        echo "Tidak ditemukan data tagihan";
        return;
    }

    $row = mysqli_fetch_array($res);
    $departemen = $row["departemen"];
    $stIuran = $row["stiuran"];
    $bulan = $row["bulan"];
    $tahun = $row["tahun"];
    $totalTagihan = $row["jumlah"];
    $nomorTs = $row["nomorts"];

    echo "<input type='hidden' id='departemen' value='$departemen'>";
    echo "<input type='hidden' id='nis' value='$nis'>";
    echo "<input type='hidden' id='nama' value='$nama'>";
    echo "<input type='hidden' id='notagihan' value='$noTagihan'>";
    echo "<input type='hidden' id='idtagihaninfo' value='$idTagihanInfo'>";

    echo "<span style='font-size: 16px; font-family: Verdana; font-weight: bold;'>$nama ($nis)</span><br><br>";
    echo "<table border='0' cellpadding='5' cellspacing='0' style='border-width: 1px; background-color: #ffffff;'>";
    echo "<tr>";
    echo "<td align='right' width='100' style='font-size: 12px'>Nomor:</td>";
    echo "<td align='left' width='500' style='font-size: 12px'><b>$noTagihan</b></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='right' style='font-size: 12px'>Tagihan:</td>";
    echo "<td align='left' style='font-size: 12px'>".$row['info']."<br><span style='color: darkred; font-style: italic; font-size: 12px'>$nomorTs</span></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='right' style='font-size: 12px'>Jumlah:</td>";
    $jumlah = $row['jumlah'];
    $jumlah += $PG_SERVICE_FEE;
    echo "<td align='left'><span style='font-size: 14px; font-weight: bold;'> " . FormatRupiah($jumlah) . "</span></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='right' style='font-size: 12px'>Tanggal Buat:</td>";
    echo "<td align='left' style='font-size: 12px'>".$row['ftanggalbuat']."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='right' style='font-size: 12px'>Petugas:</td>";
    echo "<td align='left' style='font-size: 12px'>".$row['namapetugas']."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='right' valign='top' style='font-size: 12px'>Konfirmasi:</td>";
    $status = $row["status"];
    if ($status == 0)
    {
        echo "<td align='left' style='font-size: 12px;'><span style='font-weight: bold; color: #ff0000;'>BELUM</span></td>";
    }
    else
    {
        echo "<td align='left' style='font-size: 12px;'><span style='font-weight: bold; color: #0000ff;'>SUDAH</span> <i>tanggal '".$row['fckdate']."'</i>";
        if (strlen((string) $row["ckdesc"]) > 0)
            echo "<br><i>".$row['ckdesc']."</i>";
        echo "</td>";
    }
    echo "</tr>";
    echo "</table><br><br>";

    echo "<table border='1' id='tabTagihanData' cellpadding='5' cellspacing='0' style='border: 1px solid #efefef; border-collapse: collapse; border-spacing: 0; background-color: #ffffff;'>";
    echo "<tr style='height: 25px;'>";
    echo "<td class='header' width='30' align='center'>No</td>";
    echo "<td class='header' width='200'>Iuran</td>";
    echo "<td class='header' width='110' align='center'>Tagihan</td>";
    echo "<td class='header' width='110' align='center'>Diskon</td>";
    echo "<td class='header' width='110' align='center'>Sub Total</td>";
    echo "<td class='header' width='60' align='center'>&nbsp;</td>";
    echo "</tr>";

    $totTagihan = 0;
    $totDiskon = 0;

    $sql = "SELECT replid, IFNULL(idpenerimaan, 0) AS idpenerimaan, IFNULL(idbesarjtt, 0) AS idbesarjtt, penerimaan, jtagihan, jdiskon, status 
              FROM jbsfina.tagihansiswadata
             WHERE notagihan = '".$noTagihan."'";
    $res = QueryDb($sql);
    $no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;

        $idPenerimaan = $row["idpenerimaan"];

        $jTagihan = $row["jtagihan"];
        $jDiskon = $row["jdiskon"];

        if ($idPenerimaan == 0)
            $jTagihan += $PG_SERVICE_FEE;

        $jumlah = $jTagihan - $jDiskon;
        $totTagihan += $jTagihan;
        $totDiskon += $jDiskon;

        echo "<tr style='height: 25px;'>";
        echo "<td align='center' valign='top'>$no</td>";
        echo "<td align='left' valign='top'>".$row['penerimaan']."</td>";
        echo "<td align='right' valign='top'>" . FormatRupiah($jTagihan) . "</td>";
        echo "<td align='right' valign='top'>" . FormatRupiah($jDiskon) . "</td>";
        echo "<td align='right' valign='top'>" . FormatRupiah($jumlah) . "</td>";
        if ($status == 0 && $row["idpenerimaan"] != 0)
        {
            echo "<td align='center' valign='top'>";
            echo "<a href='#' onclick='editTagihan({$row['replid']},\"$noTagihan\")'><img src='../images/ico/ubah.png'></a>&nbsp;";
            echo "<a href='#' onclick='hapusTagihan({$row['replid']},\"$noTagihan\")'><img src='../images/ico/hapus.png'></a>";
            echo "</td>";
        }
        else
        {
            echo "<td align='center' valign='top'>&nbsp;</td>";
        }

        echo "</tr>";
    }

    $totJumlah = $totTagihan - $totDiskon;

    echo "<tr style='height: 30px;'>";
    echo "<td align='right' style=' background-color: #ffc038' valign='top' colspan='2'><b>Total</b></td>";
    echo "<td align='right' style=' background-color: #ffc038' valign='top'><b>" . FormatRupiah($totTagihan) . "</b></td>";
    echo "<td align='right' style=' background-color: #ffc038' valign='top'><b>" . FormatRupiah($totDiskon) . "</b></td>";
    echo "<td align='right' style=' background-color: #ffc038' valign='top'><b>" . FormatRupiah($totJumlah) . "</b></td>";
    echo "<td align='right' style=' background-color: #ffc038' valign='top'>&nbsp;</td>";
    echo "</tr>";
    echo "</table><br>";

    if ($status == 0)
        echo "<input type='button' id='btHapusTagihanSiswa' class='but' style='width: 170px; height: 30px; color: #ff0000;' value='Hapus Tagihan Siswa' onclick='hapusTagihanSiswa(\"$noTagihan\")'>";

    echo "<br><br>";
    echo "<span id='spInfo' style='color: #FF0000; font-style: italic;'></span><br>";

    // ---- ambil pesan notifikasi tagihan
    $pesanNotifikasiTagihan = "";
    $sql = "SELECT pesan 
              FROM jbsfina.formatpesanpg
             WHERE departemen = '$departemen'
               AND kelompok = 'TAGIHAN'";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $pesanNotifikasiTagihan = $row[0];
    }
    else
    {
        $pesanNotifikasiTagihan = "Kami informasikan {NAMA} {NIS} memiliki tagihan sebesar {JUMLAH} untuk {IURAN} bulan {BULAN} {TAHUN}";

        $sql = "INSERT INTO jbsfina.formatpesanpg
                   SET pesan = '$pesanNotifikasiTagihan', departemen = '$departemen', kelompok = 'TAGIHAN', issync = 0";
        QueryDb($sql);
    }

    $pesan = str_replace("{NAMA}", $nama, (string) $pesanNotifikasiTagihan);
    $pesan = str_replace("{NIS}", $nis, $pesan);
    $pesan = str_replace("{JUMLAH}", FormatRupiah($totJumlah), $pesan);
    $pesan = str_replace("{IURAN}", $stIuran, $pesan);
    $pesan = str_replace("{BULAN}", NamaBulan($bulan), $pesan);
    $pesan = str_replace("{TAHUN}", $tahun, $pesan);

    echo "<br><br><br>";
    echo "<fieldset style='width: 300px; border: 1px solid #ddd;'>";
    echo "<legend style='font-size: 14px'>Kirim Pesan Tagihan</legend>";
    echo "<br><textarea rows='5' cols='60' id='ps_pesan' class='inputbox'>$pesan</textarea><br>";
    echo "<div style='width: 600px'>";
    echo "<input type='button' id='ps_kirim' class='but' style='height: 30px; width: 60px' value='Kirim' onclick='sendNotif()'>";
    echo "&nbsp;&nbsp;<span style='color: #0000ff' id='ps_info'></span>";
    echo "</div>";
    echo "</fieldset>";

}

function HapusTagihanData()
{
    $idTagihanData = $_REQUEST["idtagihandata"];
    $noTagihan = $_REQUEST["notagihan"];

    try
    {
        BeginTrans();

        $sql = "DELETE FROM jbsfina.tagihansiswadata
                 WHERE replid = $idTagihanData";
        QueryDbEx($sql);

        $jumlah = 0;
        $sql = "SELECT SUM(jtagihan - jdiskon) 
                  FROM jbsfina.tagihansiswadata 
                 WHERE notagihan = '".$noTagihan."'";
        $res = QueryDbEx($sql);
        if ($row = mysqli_fetch_row($res))
            $jumlah = $row[0];

        $sql = "UPDATE jbsfina.tagihansiswainfo
                   SET jumlah = $jumlah
                 WHERE notagihan = '".$noTagihan."'";
        QueryDbEx($sql);

        CommitTrans();

        echo "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        RollbackTrans();

        $msg = $ex->getMessage();
        echo "[1,\"ERROR\",\"$msg\"]";
    }
}

function HapusTagihanSiswa()
{
    $noTagihan = $_REQUEST["notagihan"];

    try
    {
        $sql = "DELETE FROM jbsfina.tagihansiswadata
                 WHERE notagihan = '".$noTagihan."'";
        QueryDbEx($sql);

        $sql = "DELETE FROM jbsfina.tagihansiswainfo
                 WHERE notagihan = '".$noTagihan."'";
        QueryDbEx($sql);

        echo "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $msg = $ex->getMessage();
        echo "[1,\"ERROR\",\"$msg\"]";
    }
}

function HapusTagihanSet()
{
    $idTagihanSet = $_REQUEST["idtagihanset"];

    try
    {
        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.tagihansiswainfo
                 WHERE idtagihanset = $idTagihanSet
                   AND status = 1";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $nKonfirmasi = $row2[0];

        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.tagihansiswainfo
                 WHERE idtagihanset = $idTagihanSet
                   AND status = 2";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $nSelesai = $row2[0];

        if ($nKonfirmasi != 0 || $nSelesai != 0)
        {
            echo "[-1,\"Tidak bisa menghapus tagihan ini karena sudah ada yang terkonfirmasi\"]";
            return;
        }

        BeginTrans();

        $sql = "DELETE FROM jbsfina.tagihansiswadata
                 WHERE idtagihanset = $idTagihanSet";
        QueryDbEx($sql);

        $sql = "DELETE FROM jbsfina.tagihansiswainfo
                 WHERE idtagihanset = $idTagihanSet";
        QueryDbEx($sql);

        $sql = "DELETE FROM jbsfina.tagihanset
                 WHERE replid = $idTagihanSet";
        QueryDbEx($sql);

        CommitTrans();

        echo "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        RollbackTrans();

        Logger::LogErrorOnce($ex, "k70sq");

        $msg = $ex->getMessage();
        echo "[-1,\"$msg\"]";
    }
}
?>