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
function ShowTagihanReport($showMenu)
{
    $lsDept = [];
    $nDept = 0;

    $sql = "SELECT departemen
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $lsDept[] = $row[0];
    }
    $nDept = count($lsDept);

    $deptPeg = "---";
    $sql = "SELECT departemen
              FROM jbsfina.paymenttabungan
             WHERE jenis = 1";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        $deptPeg = $row[0];

    $lsVendor = [];
    $sql = "SELECT vendorid, nama
              FROM jbsfina.vendor
             WHERE aktif = 1 
             ORDER BY nama";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $lsVendor[] = [$row[0], $row[1]];
    }

    $lsSubTotal = [];
    for($i = 0; $i < $nDept; $i++)
    {
        $lsSubTotal[] = 0; // Siswa
    }
    $lsSubTotal[] = 0; // Pegawai

    $sb = new StringBuilder();
    $sb->AppendLine("<br>");
    if ($showMenu)
    {
        $sb->AppendLine("<a href='#' onclick='location.reload()'><img src='../images/ico/refresh.png' border='0'>&nbsp;refresh</a>&nbsp;&nbsp;");
        $sb->AppendLine("<a href='#' onclick='cetakReport()'><img src='../images/ico/print.png' border='0'>&nbsp;cetak</a>&nbsp;&nbsp;");
        $sb->AppendLine( "<a href='#' onclick='excelReport()'><img src='../images/ico/excel.png' border='0'>&nbsp;excel</a>");
    }

    $payWidth = 130;

    $sb->AppendLine("<table id='table' border='1' cellspacing='0' cellpadding='5' style='border-width: 1px; border-collapse: collapse'>");
    $sb->AppendLine("<tr style='height: 30px'>");
    $sb->AppendLine("<td width='50' rowspan='2' align='center' class='header'>No</td>");
    $sb->AppendLine("<td width='180' rowspan='2' align='center' class='header'>Vendor</td>");
    $width = $payWidth * $nDept;
    $sb->AppendLine("<td width='$width' colspan='$nDept' align='center' class='header'>Siswa</td>");
    $sb->AppendLine("<td width='$payWidth' align='center' class='header'>Pegawai</td>");
    $sb->AppendLine("<td width='$payWidth' rowspan='2' align='center' class='header'>Sub Total</td>");
    $sb->AppendLine("</tr>");
    $sb->AppendLine("<tr style='height: 20px'>");
    for($i = 0; $i < $nDept; $i++)
    {
        $dept = $lsDept[$i];
        $sb->AppendLine("<td width='$payWidth' align='center' class='header'>$dept</td>");
    }
    $sb->AppendLine("<td width='$payWidth' align='center' class='header'>$deptPeg</td>");
    $sb->AppendLine("</tr>");

    $no = 0;
    for($i = 0; $i < count($lsVendor); $i++)
    {
        $vendorId = $lsVendor[$i][0];
        $vendorName = $lsVendor[$i][1];
        $vendorTotal = 0;

        $no += 1;
        $sb->AppendLine("<tr style='height: 30px'>");
        $sb->AppendLine("<td align='center' valign='middle' rowspan='2'>$no</td>");
        $sb->AppendLine("<td align='left' valign='top'><strong>$vendorName</strong></td>");
        for($j = 0; $j < $nDept; $j++)
        {
            $dept = $lsDept[$j];

            $sql = "SELECT IFNULL(SUM(p.jumlah), 0)
                      FROM jbsfina.paymenttrans p, jbsakad.siswa s, jbsakad.angkatan a
                     WHERE p.nis = s.nis
                       AND s.idangkatan = a.replid
                       AND a.departemen = '$dept'
                       AND p.vendorid = '$vendorId'
                       AND p.jenis = 2
                       AND p.jenistrans = 0
                       AND p.idrefund IS NULL";
            $res = QueryDb($sql);

            $sum = 0;
            if ($row = mysqli_fetch_row($res))
                $sum = $row[0];
            $rp = FormatRupiah($sum);
            $vendorTotal += $sum;
            $lsSubTotal[$j] += $sum;

            if ($sum == 0)
                $sb->AppendLine("<td align='right'>$rp</td>");
            else
                $sb->AppendLine("<td align='right'><a href='#' style='color: #0000ff;' onclick=\"showDetail('$dept','$vendorId', 2)\"".$rp."</a></td>");
        }

        $sql = "SELECT IFNULL(SUM(p.jumlah), 0)
                  FROM jbsfina.paymenttrans p
                 WHERE p.vendorid = '$vendorId'
                   AND p.jenis = 1
                   AND p.jenistrans = 0
                   AND p.idrefund IS NULL";
        $res = QueryDb($sql);

        $sum = 0;
        if ($row = mysqli_fetch_row($res))
            $sum = $row[0];
        $rp = FormatRupiah($sum);
        $vendorTotal += $sum;
        $lsSubTotal[$nDept] += $sum;

        if ($sum == 0)
            $sb->AppendLine("<td align='right'>$rp</td>");
        else
            $sb->AppendLine("<td align='right'><a href='#' style='color: #0000ff;' onclick=\"showDetail('$deptPeg','$vendorId', 1)\"".$rp."</a></td>");

        $rp = FormatRupiah($vendorTotal);
        $sb->AppendLine("<td align='right' valign='middle' rowspan='2'><strong>$rp</strong></td>");

        $sb->AppendLine("</tr>");

        // Tanggal Refund Terakhir utk Siswa
        $sb->AppendLine("<tr style='height: 30px'>");
        $sb->AppendLine("<td align='right' valign='top'><i>Tanggal Refund Terakhir</i></td>");
        for($j = 0; $j < $nDept; $j++)
        {
            $dept = $lsDept[$j];

            $sql = "SELECT DATE_FORMAT(rd.tanggal, '%d-%b-%Y')
                      FROM jbsfina.paymenttrans pt, jbsfina.refunddate rd, jbsfina.refund r, jbsfina.tahunbuku tb
                     WHERE pt.idrefund = r.replid
                       AND rd.idrefund = r.replid
                       AND r.idtahunbuku = tb.replid
                       AND pt.vendorid = '$vendorId'
                       AND pt.jenis = 1
                       AND tb.departemen = '$dept'
                     ORDER BY rd.tanggal DESC
                     LIMIT 1";
            $res = QueryDb($sql);

            $tglRefund = "(belum pernah)";
            if ($row = mysqli_fetch_row($res))
                $tglRefund = $row[0];

            $sb->AppendLine("<td align='right'><i>$tglRefund</i></td>");
        }

        // Tanggal Refund Terakhir utk Pegawai
        $sql = "SELECT DATE_FORMAT(rd.tanggal, '%d-%b-%Y')
                      FROM jbsfina.paymenttrans pt, jbsfina.refunddate rd, jbsfina.refund r
                     WHERE pt.idrefund = r.replid
                       AND rd.idrefund = r.replid
                       AND pt.vendorid = '$vendorId'
                       AND pt.jenis = 1
                     ORDER BY rd.tanggal DESC
                     LIMIT 1";
        $res = QueryDb($sql);

        $tglRefund = "(belum pernah)";
        if ($row = mysqli_fetch_row($res))
            $tglRefund = $row[0];

        $sb->AppendLine("<td align='right'><i>$tglRefund</i></td>");
        $sb->AppendLine("</tr>");
    }

    $total = 0;
    $sb->AppendLine("<tr style='height: 50px; background-color: #ededed'>");
    $sb->AppendLine("<td style='background-color: #ededed' align='right' colspan='2'><strong>TOTAL</strong></td>");
    for($i = 0; $i < count($lsSubTotal); $i++)
    {
        $total += $lsSubTotal[$i];
        $rp = FormatRupiah($lsSubTotal[$i]);
        $sb->AppendLine("<td style='background-color: #ededed' align='right'><strong>$rp</strong></td>");
    }
    $rp = FormatRupiah($total);
    $sb->AppendLine("<td  style='background-color: #ededed' align='right'><strong>$rp</strong></td>");
    $sb->AppendLine("</tr>");

    $sb->AppendLine("</table>");

    echo $sb->ToString();
}
?>