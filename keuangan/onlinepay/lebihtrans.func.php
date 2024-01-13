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

    $dep = getDepartemen(getAccess());

    echo "<select id='departemen' name='departemen' class='inputbox' style='width: 250px' onchange='clearContent()'>";
    foreach($dep as $value)
    {
        if ($departemen == "") $departemen = $value;
        $sel = $departemen == $value ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowLebihTransSudah()
{
    global $departemen, $tanggal1, $tanggal2, $status;

    $sql = "SELECT p.id, DATE_FORMAT(p.waktu, '%d %b %Y<br>%H:%i') AS fwaktu, p.metode, p.nomor, p.jlebihtrans, p.jlebihsisa, p.bankno, b.bank, 
                   IFNULL(p.prpetugas, '') AS idpetugas, IFNULL(pg.nama, '') AS namapetugas, p.prstatus, 
                   p.prmetode, p.prket, DATE_FORMAT(p.prwaktu, '%d %b %Y %H:%i') AS fprwaktu, p.prpetugas, 
                   IFNULL(p.prjurnalbank, '') AS prjurnalbank, p.pridtabungan, IFNULL(p.prnamatabungan, '') AS prnamatabungan, 
                   IFNULL(p.prjurnaltabungan, '') AS prjurnaltabungan, 
                   IFNULL(p.prpetugastf, '') AS prpetugastf,
                   IFNULL(bm.nomormutasi, '') AS nomormutasi,
                   IFNULL(bm.adaberkas, 0) AS adaberkas,
                   pgt.nis, IFNULL(s.nama, '') AS namasiswa
              FROM jbsfina.pgtranslebih p
             INNER JOIN jbsfina.pgtrans pgt ON p.nomor = pgt.nomor
             INNER JOIN jbsfina.bank b ON p.bankno = b.bankno
              LEFT JOIN jbsfina.bankmutasi bm ON p.pridmutasi = bm.replid
              LEFT JOIN jbssdm.pegawai pg ON p.prpetugas = pg.nip
              LEFT JOIN jbsakad.siswa s ON pgt.nis = s.nis
             WHERE p.departemen = '$departemen'
               AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
               AND p.prstatus = $status
             ORDER BY p.tanggal DESC, p.id DESC";
    $res = QueryDb($sql);
    if (0 == mysqli_num_rows($res))
    {
        echo "<br><br><i>Tidak ditemukan data</i>";
        return;
    }

    echo "<table id='tabLebihTrans' border='1' cellpadding='5' cellspacing='0' style='border: 1px solid #efefef'>";
    echo "<tr style='height: 45px;'>";
    echo "<td class='header' width='30' align='center' >No</td>";
    echo "<td class='header' width='90' align='center'>Tanggal</td>";
    echo "<td class='header' width='180' align='center'>Siswa</td>";
    echo "<td class='header' width='130' align='center'>Lebih Transfer<br><span style='color: #ccc'>A</span></td>";
    echo "<td class='header' width='130' align='center'>Lebih Sisa Iuran<br><span style='color: #ccc'>B</span></td>";
    echo "<td class='header' width='130' align='center'>Total Kelebihan<br><span style='color: #ccc'>A+B</span></td>";
    echo "<td class='header' width='250' align='center'>Transaksi</td>";
    echo "</tr>";

    $no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;

        echo "<tr>";
        echo "<td style='background-color: #efefef;' align='center' rowspan='2' valign='top'>$no</td>";
        echo "<td align='left'>".$row['fwaktu']."</td>";

        echo "<td align='left'><b>".$row['namasiswa']."</b><br>".$row['nis']."</td>";

        $rp = FormatRupiah($row["jlebihtrans"]);
        echo "<td align='right'><span style='font-size: 13px; font-weight: bold'>$rp</span></td>";

        $rp = FormatRupiah($row["jlebihsisa"]);
        echo "<td align='right'><span style='font-size: 13px; font-weight: bold'>$rp</span></td>";

        $jumlah = $row["jlebihtrans"] + $row["jlebihsisa"];
        $rp = FormatRupiah($jumlah);

        echo "<td align='right'><span style='font-size: 13px; font-weight: bold'>$rp</span></td>";
        echo "<td align='left'><a href='#' style='color: #0000ff; font-weight: normal; text-decoration: underline' onclick='showRincian(".$row['id'].")'>".$row['nomor']."</a><br>{$row['bank']} - {$row['bankno']}</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td colspan='6' align='left' style='background-color: #fff;'><strong>Proses:</strong><br>";

        if ($row["prmetode"] == 1)
        {
            $stNoJurnal = urlencode("{$row['prjurnalbank']},{$row['prjurnaltabungan']}");

            echo "<table cellspacing='0' cellpadding='2'>";
            echo "<tr>";
            echo "<td width='80'>Metode:</td>";
            echo "<td width='220'><b>Simpan ke Tabungan</b></td>";
            echo "<td width='120'>Jurnal Bank:</td>";
            echo "<td width='320'>";
            echo "<a href='#' style='color: #0000ff; text-decoration: underline; font-weight: normal;' onclick='showRincianJurnal(\"$stNoJurnal\")'>".$row['prjurnalbank']."</a>";
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Tabungan:</td>";
            echo "<td>".$row['prnamatabungan']."</td>";
            echo "<td>Jurnal Tabungan:</td>";
            echo "<td>";
            echo "<a href='#' style='color: #0000ff; text-decoration: underline; font-weight: normal;' onclick='showRincianJurnal(\"$stNoJurnal\")'>".$row['prjurnaltabungan']."</a>";
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Tanggal:</td>";
            echo "<td>".$row['fprwaktu']."</td>";
            echo "<td>Bukti Transfer:</td>";
            echo "<td>";
            if ($row["adaberkas"] == 1)
                echo "<a href='#' onclick='showBuktiTf(".$row['id'].")' title='lihat bukti transfer' style='text-decoration: underline; font-weight: normal; color: #0000ff;'>".$row['nomormutasi']."</a>";
            else
                echo $row["nomormutasi"];
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Petugas:</td>";
            echo "<td>".$row['prpetugas']."</td>";
            echo "<td>Keterangan</td>";
            echo "<td>".$row['prket']."</td>";
            echo "</tr>";
            echo "</table>";
        }
        else
        {
            $stNoJurnal = urlencode("{$row['prjurnalbank']}");
            echo "<table cellspacing='0' cellpadding='2'>";
            echo "<tr>";
            echo "<td width='80'>Metode:</td>";
            echo "<td width='220'><b>Transfer Balik</b></td>";
            echo "<td width='120'>Jurnal Bank:</td>";
            echo "<td width='320'>";
            echo "<a href='#' style='color: #0000ff; text-decoration: underline; font-weight: normal;' onclick='showRincianJurnal(\"$stNoJurnal\")'>".$row['prjurnalbank']."</a>";
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Tanggal:</td>";
            echo "<td>".$row['fprwaktu']."</td>";
            echo "<td>Bukti Transfer:</td>";
            echo "<td>";
            if ($row["adaberkas"] == 1)
                echo "<a href='#' onclick='showBuktiTf(".$row['id'].")' title='lihat bukti transfer' style='text-decoration: underline; font-weight: normal; color: #0000ff;'>".$row['nomormutasi']."</a>";
            else
                echo $row["nomormutasi"];
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Petugas:</td>";
            echo "<td>".$row['prpetugas']."</td>";
            echo "<td>Keterangan:</td>";
            echo "<td>".$row['prket']."</td>";
            echo "</tr>";
            echo "</table>";
        }

        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

function ShowLebihTransBelum()
{
    global $departemen, $tanggal1, $tanggal2, $status;

    $sql = "SELECT p.id, DATE_FORMAT(p.waktu, '%d %b %Y<br>%H:%i') AS fwaktu, p.metode, p.nomor, p.jlebihtrans, p.jlebihsisa, p.bankno, b.bank, 
                   IFNULL(p.prpetugas, '') AS idpetugas, IFNULL(pg.nama, '') AS namapetugas, p.prstatus,
                   pgt.nis, IFNULL(s.nama, '') AS namasiswa
              FROM jbsfina.pgtranslebih p
             INNER JOIN jbsfina.pgtrans pgt ON p.nomor = pgt.nomor
             INNER JOIN jbsfina.bank b ON p.bankno = b.bankno
              LEFT JOIN jbssdm.pegawai pg ON p.prpetugas = pg.nip
              LEFT JOIN jbsakad.siswa s ON pgt.nis = s.nis
             WHERE p.departemen = '$departemen'
               AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
               AND p.prstatus = $status
             ORDER BY p.id";
    $res = QueryDb($sql);
    if (0 == mysqli_num_rows($res))
    {
        echo "<br><br><i>Tidak ditemukan data</i>";
        return;
    }

    echo "<table id='tabLebihTrans' border='1' cellpadding='5' cellspacing='0' style='border: 1px solid #efefef'>";
    echo "<tr style='height: 45px;'>";
    echo "<td class='header' width='30' align='center'>No</td>";
    echo "<td class='header' width='90' align='center'>Tanggal</td>";
    echo "<td class='header' width='180' align='center'>Siswa</td>";
    echo "<td class='header' width='130' align='center'>Lebih Transfer<br><span style='color: #ccc'>A</span></td>";
    echo "<td class='header' width='130' align='center'>Lebih Sisa Iuran<br><span style='color: #ccc'>B</span></td>";
    echo "<td class='header' width='130' align='center'>Total Kelebihan<br><span style='color: #ccc'>A+B</span></td>";
    echo "<td class='header' width='250' align='center'>Transaksi</td>";
    echo "<td class='header' width='60' align='center'>&nbsp;</td>";
    echo "</tr>";

    $no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;

        echo "<tr>";
        echo "<td style='background-color: #efefef;' align='center'>$no</td>";
        echo "<td align='left'>".$row['fwaktu']."</td>";

        echo "<td align='left'><span style='font-size: 13px; font-weight: bold'>".$row['namasiswa']."</span><br>".$row['nis']."</td>";

        $rp = FormatRupiah($row["jlebihtrans"]);
        echo "<td align='right'><span style='font-size: 13px; font-weight: bold'>$rp</span></td>";

        $rp = FormatRupiah($row["jlebihsisa"]);
        echo "<td align='right'><span style='font-size: 13px; font-weight: bold'>$rp</span></td>";

        $jumlah = $row["jlebihtrans"] + $row["jlebihsisa"];
        $rp = FormatRupiah($jumlah);

        echo "<td align='right'><b>$rp</b></td>";
        echo "<td align='left'><a href='#' style='color: #0000ff; font-weight: normal; text-decoration: underline' onclick='showRincian(".$row['id'].")'>".$row['nomor']."</a><br>".$row['bank'] - $row['bankno']."</td>";
        echo "<td align='center'>";
        if ($row["prstatus"] == 0)
            echo "<a href='#' title='proses' onclick='prosesLebihTrans(".$row['id'].")'><img src='../images/ico/ubah.png' border='0'></a>";
        else
            echo "&nbsp;";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>