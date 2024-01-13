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
function ShowLaporanMutasi()
{
    global $departemen, $bankNo, $tanggal1, $tanggal2;

    $sql = "SELECT bm.replid, bm.jenis, DATE_FORMAT(bm.waktu, '%d %b %Y<br>%H:%i') AS fwaktu, 
                   IFNULL(bm.petugas, 'admin') AS idpetugas, 
                   IFNULL(p.nama, 'Administrator JIBAS') AS namapetugas,
                   bm.keterangan, bm.nomormutasi
              FROM jbsfina.bankmutasi bm
              LEFT JOIN jbssdm.pegawai p ON bm.petugas = p.nip
             WHERE bm.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
               AND bm.departemen = '$departemen'
               AND bm.bankno = '$bankNo' 
             ORDER BY replid DESC";
    $res = QueryDb($sql);

    if (mysqli_num_rows($res) == 0)
    {
        echo "<br><br>Tidak ada data mutasi";
        return;
    }

    echo "<table id='tabRiwayatMutasi' border='1' cellpadding='5' cellspacing='0' style='border: 1px solid #efefef;'>";
    echo "<tr style='height: 30px;'>";
    echo "<td class='header' width='30' align='center'>No</td>";
    echo "<td class='header' width='90' align='center'>Tanggal</td>";
    echo "<td class='header' width='90' align='center'>Mutasi</td>";
    echo "<td class='header' width='130' align='center'>Jumlah</td>";
    echo "<td class='header' width='350' align='center'>Petugas / Keterangan</td>";
    echo "<td class='header' id='thmenu' width='50' align='center'>&nbsp;</td>";
    echo "</tr>";

    $no = 0;
    while ($row = mysqli_fetch_array($res))
    {
        $no += 1;

        $idMutasi = $row["replid"];

        $jenis = $row["jenis"];
        $namaJenis = $jenis == 1 ? "Simpan" : "Ambil";
        $bgColor = $jenis == 1 ? "#cfdaff" : "#fff2e6";

        $sql = "SELECT SUM(jumlah)
                  FROM jbsfina.bankmutasidata
                 WHERE idmutasi = $idMutasi";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $jumlah = $row2[0];
        $rp = FormatRupiah($jumlah);

        echo "<tr>";
        echo "<td align='center' style='background-color: #efefef'>$no</td>";
        echo "<td align='left'>".$row['fwaktu']."</td>";
        echo "<td align='center' style='background-color: $bgColor'>$namaJenis</td>";
        echo "<td align='right'><span style='font-size: 12px; font-weight: bold;'>$rp</span></td>";
        echo "<td align='left'>Nomor: {$row['nomormutasi']}<br>Petugas: {$row['namapetugas']} ({$row['idpetugas']})<br>".$row['keterangan']."</td>";
        echo "<td class='tdmenu' align='center'>";
        echo "<a href='#' onclick='showRincianMutasi($idMutasi)' title='rincian'><img src='../images/ico/lihat.png' border='0'></a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>
