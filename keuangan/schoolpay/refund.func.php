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
$selVendorId = "";
$selDepartemen = "";
$selIdTahunBuku = "";

function ShowCbVendor()
{
    global $selVendorId;

    $sql = "SELECT vendorid, nama 
              FROM jbsfina.vendor
             WHERE aktif = 1
             ORDER BY nama";
    $res = QueryDb($sql);

    echo "<select id='vendor' name='vendor' onchange='changeVendor()' style='width: 250px'>";
    while($row = mysqli_fetch_row($res))
    {
        if ($selVendorId == "") $selVendorId = $row[0];
        echo "<option value='".$row[0]."'>".$row[1]."</option>";
    }
    echo "</select>";
}

function ShowCbDepartemen()
{
    global $selDepartemen;

    $sql = "SELECT departemen
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);

    echo "<select id='departemen' name='departemen' onchange='changeDepartemen()' style='width: 250px'>";
    while($row = mysqli_fetch_row($res))
    {
        if ($selDepartemen == "") $selDepartemen = $row[0];
        echo "<option value='".$row[0]."'>".$row[0]."</option>";
    }
    echo "</select>";
}

function ShowTahunBuku($dept)
{
    global $selIdTahunBuku;

    $sql = "SELECT replid, tahunbuku
              FROM jbsfina.tahunbuku
             WHERE departemen = '$dept'
               AND aktif = 1";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        if ($selIdTahunBuku == "")
            $selIdTahunBuku = $row[0];

        echo "<input type='hidden' readonly id='idtahunbuku' name='idtahunbuku' value='".$row[0]."'>";
        echo "<input type='text' readonly id='tahunbuku' name='tahunbuku' value='".$row[1]."' style='background-color: #ededed; width: 120px;'>";
    }
    else
    {
        $selIdTahunBuku = 0;

        echo "<input type='hidden' readonly id='idtahunbuku' name='idtahunbuku' value='0'>";
        echo "<input type='text' readonly id='tahunbuku' name='tahunbuku' value='belum ada tahun buku' style='background-color: #ededed; width: 120px;'>";
    }
}

function ShowLastRefundDate($vendorId, $idTahunBuku)
{
    $sql = "SELECT DATE_FORMAT(rd.tanggal, '%d-%b-%Y')
              FROM jbsfina.paymenttrans pt, jbsfina.refunddate rd, jbsfina.refund r
             WHERE pt.idrefund = r.replid
               AND rd.idrefund = r.replid
               AND pt.vendorid = '$vendorId'
               AND r.idtahunbuku = '$idTahunBuku'
               AND pt.jenistrans = 0
               AND pt.tanggal <= CURDATE()
             ORDER BY rd.tanggal DESC
             LIMIT 1";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        echo $row[0];
    else
        echo "(belum pernah refund)";
}

function ShowTagihanVendor($vendorId, $dept)
{
    $deptPeg = "---";
    $sql = "SELECT departemen
              FROM jbsfina.paymenttabungan
             WHERE jenis = 1";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        $deptPeg = $row[0];

    $tagihan = 0;
    $sql = "SELECT IFNULL(SUM(p.jumlah), 0)
              FROM jbsfina.paymenttrans p, jbsakad.siswa s, jbsakad.angkatan a
             WHERE p.nis = s.nis
               AND s.idangkatan = a.replid
               AND a.departemen = '$dept'
               AND p.vendorid = '$vendorId'
               AND p.jenis = 2
               AND p.jenistrans = 0
               AND p.idrefund IS NULL
               AND p.tanggal <= CURDATE()";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        $tagihan = $row[0];

    if ($deptPeg == $dept)
    {
        $sql = "SELECT IFNULL(SUM(p.jumlah), 0)
                  FROM jbsfina.paymenttrans p
                 WHERE p.vendorid = '$vendorId'
                   AND p.jenis = 1
                   AND p.jenistrans = 0
                   AND p.idrefund IS NULL
                   AND p.tanggal <= CURDATE()";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $tagihan = $tagihan + $row[0];
    }

    echo FormatRupiah($tagihan);
    echo "<input type='hidden' id='tagihan' name='tagihan' value='$tagihan'>";
}

function ShowRefundHistory($showMenu)
{
    $vendorId = $_REQUEST["vendorid"];
    $departemen = $_REQUEST["departemen"];
    $idTahunBuku = $_REQUEST["idtahunbuku"];

    $sb = new StringBuilder();
    $sb->AppendLine("<br>");
    if ($showMenu)
    {
        $sb->AppendLine("<a href='#' onclick='cetakReport()'><img src='../images/ico/print.png' border='0'>&nbsp;cetak</a>&nbsp;&nbsp;");
        $sb->AppendLine( "<a href='#' onclick='excelReport()'><img src='../images/ico/excel.png' border='0'>&nbsp;excel</a>");
    }

    $sb->AppendLine("<table id='table' border='1' cellspacing='0' cellpadding='5' style='border-width: 1px; border-collapse: collapse'>");
    $sb->AppendLine("<tr style='height: 30px'>");
    $sb->AppendLine("<td width='50' align='center' class='header'>No</td>");
    $sb->AppendLine("<td width='150' align='center' class='header'>Waktu</td>");
    $sb->AppendLine("<td width='150' align='center' class='header'>Petugas</td>");
    $sb->AppendLine("<td width='150' align='center' class='header'>Penerima</td>");
    $sb->AppendLine("<td width='150' align='center' class='header'>Jumlah</td>");
    $sb->AppendLine("<td width='180' align='center' class='header'>Tanggal Penerimaan Transaksi</td>");
    $sb->AppendLine("<td width='250' align='center' class='header'>Keterangan</td>");
    if ($showMenu)
        $sb->AppendLine("<td width='100' align='center' class='header'>&nbsp;</td>");
    $sb->AppendLine("</tr>");

    $sql = "SELECT r.replid, DATE_FORMAT(r.waktu, '%d-%b-%Y %H:%i') AS waktu, IFNULL(pg.nama, 'Administrator JIBAS') AS petugas, 
                   u.nama AS penerima, r.jumlah, r.keterangan
              FROM jbsfina.refund r
              LEFT JOIN jbssdm.pegawai pg ON r.nip = pg.nip
              LEFT JOIN jbsfina.userpos u ON r.idpenerima = u.userid
             WHERE vendorid = '$vendorId' 
               AND idtahunbuku = $idTahunBuku
             ORDER BY r.waktu DESC
             LIMIT 20";
    $res = QueryDb($sql);

    $no = 0;
    while($row = mysqli_fetch_row($res))
    {
        $no += 1;

        $idRefund = $row[0];
        $sql = "SELECT DATE_FORMAT(tanggal, '%d-%b-%Y') AS tanggal
                  FROM jbsfina.refunddate
                 WHERE idrefund = $idRefund
                 ORDER BY tanggal";
        $res2 = QueryDb($sql);
        $stTanggal = "";
        while($row2 = mysqli_fetch_row($res2))
        {
            if ($stTanggal <> "") $stTanggal .= "<br>";
            $stTanggal .= $row2[0];
        }

        $sb->AppendLine("<tr style='height: 30px'>");
        $sb->AppendLine("<td align='center' valign='top'>$no</td>");
        $sb->AppendLine("<td align='left' valign='top'>".$row[1]."</td>");
        $sb->AppendLine("<td align='left' valign='top'>".$row[2]."</td>");
        $sb->AppendLine("<td align='left' valign='top'>".$row[3]."</td>");
        $sb->AppendLine("<td align='right' valign='top'>" . FormatRupiah($row[4]) . "</td>");
        $sb->AppendLine("<td align='center' valign='top'>$stTanggal</td>");
        $sb->AppendLine("<td align='left' valign='top'>".$row[5]."</td>");
        if ($showMenu)
        {
            $sb->AppendLine("<td align='center' valign='top'>");
            $sb->AppendLine("<a href='#' onclick='cetakKuitansi($row[0])' title='cetak kuitansi'><img src='../images/ico/print.png' border='0'></a>");
            $sb->AppendLine("</td>");
        }
        $sb->AppendLine("</tr>");
    }
    $sb->AppendLine("</table>");

    echo $sb->ToString();
}
?>