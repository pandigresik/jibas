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

    echo "<select id='departemen' name='departemen' class='inputbox' style='width: 250px' onchange='changeDept(); clearContent();'>";
    if (getLevel() != 2)
    {
        if ($departemen == "") $departemen = "ALL";
        $sel = $departemen == "ALL" ? "selected" : "";
        echo "<option value='ALL' $sel>Semua Departemen</option>";
    }

    $dep = getDepartemen(getAccess());
    foreach($dep as $value)
    {
        if ($departemen == "") $departemen = $value;
        $sel = $departemen == $value ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowBankSaldo($showMenu)
{
    global $departemen;

    $sql = "SELECT DISTINCT b.bank, bs.bankno
              FROM jbsfina.bank b, jbsfina.banksaldo bs
             WHERE b.bankno = bs.bankno";
    if ($departemen != "ALL")
        $sql .= " AND bs.departemen = '".$departemen."'";
    $sql .= " ORDER BY b.bank";

    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        echo "<br>Belum ada saldo bank";
        return;
    }

    $lsBank = [];
    while($row = mysqli_fetch_row($res))
    {
        $lsBank[] = [$row[0], $row[1]];
    }

    echo "<a href='#' onclick='cetakSaldo()'><img src='../images/ico/print.png' border='0'>&nbsp;cetak</a>&nbsp;&nbsp;";
    echo "<a href='#' onclick='excelSaldo()'><img src='../images/ico/excel.png' border='0'>&nbsp;excel</a>&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<a href='#' onclick='refreshSaldo(); clearContent();' style='font-weight: normal; text-decoration: underline; color: blue;'>muat ulang</a>";

    echo "<div id='dvBankSaldoContent'>";
    echo "<table id='tabBankSaldo' border='1' cellspacing='0' cellpadding='5' style='border: 1px solid #efefef;'>";
    echo "<tr>";
    echo "<td class='header' align='center' width='175'>Bank</td>";
    echo "<td class='header' align='center' width='175'>Saldo</td>";
    echo "</tr>";

    for($i = 0; $i < count($lsBank); $i++)
    {
        $bank = $lsBank[$i][0];
        $bankNo = $lsBank[$i][1];

        $sql = "SELECT SUM(saldo)
                  FROM jbsfina.banksaldo
                 WHERE bankno = '".$bankNo."'";
        if ($departemen != "ALL")
            $sql .= " AND departemen = '".$departemen."'";
        $res = QueryDb($sql);
        $saldo = 0;
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            $saldo = $row[0];
        }
        $rp = FormatRupiah($saldo);

        echo "<tr style='cursor: pointer;' onclick='showRincianSaldo(\"$bank\",\"$bankNo\")'>";
        echo "<td><strong>$bank</strong><br><i>$bankNo</i></td>";
        echo "<td align='right'><span style='font-weight: bold; font-size: 13px;'>$rp</span></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}


function ShowRincianSaldo()
{
    $departemen = $_REQUEST["departemen"];
    $bank = $_REQUEST["bank"];
    $bankNo = $_REQUEST["bankno"];

    $sql = "SELECT DISTINCT replid, kategori, idpenerimaan, idtabungan, idtabunganp, iddeposit, saldo, DATE_FORMAT(lasttime, '%d %b %Y %H:%i') AS flasttime
              FROM jbsfina.banksaldo
             WHERE bankno = '".$bankNo."'";
    if ($departemen != "ALL")
        $sql .= " AND departemen = '".$departemen."'";
    $sql .= " ORDER BY kelompok";

    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        echo "Tidak ditemukan rincian saldo";
        return;
    }

    echo "<span style='font-size: 16px; font-weight: bold;'>$bank</span><br>";
    echo "<span style='font-size: 12px;'>$bankNo</span><br><br>";

    echo "<a href='#' onclick='cetakRincianSaldo()'><img src='../images/ico/print.png' border='0'>&nbsp;cetak</a>&nbsp;&nbsp;";
    echo "<a href='#' onclick='excelRincianSaldo()'><img src='../images/ico/excel.png' border='0'>&nbsp;excel</a>&nbsp;&nbsp;";

    echo "<input type='hidden' id='bank' value='$bank'>";
    echo "<input type='hidden' id='bankno' value='$bankNo'>";

    echo "<div id='dvRincianSaldoContent'>";
    echo "<table id='tabRincianSaldo' border='1' cellspacing='0' cellpadding='5' style='border: 1px solid #efefef;'>";
    echo "<tr>";
    echo "<td class='header' align='center' width='30'>No</td>";
    echo "<td class='header' align='center' width='225'>Kategori</td>";
    echo "<td class='header' align='center' width='220'>Saldo</td>";
    echo "<td class='header' align='center' width='180'>Transaksi Terakhir</td>";
    echo "</tr>";

    $total = 0;
    $no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;

        $kategori = $row["kategori"];

        $idPenerimaan = 0;
        if ($kategori == "JTT")
            $idPenerimaan = $row["idpenerimaan"];
        else if ($kategori == "SKR")
            $idPenerimaan = $row["idpenerimaan"];
        else if ($kategori == "SISTAB")
            $idPenerimaan = $row["idtabungan"];
        else if ($kategori == "PEGTAB")
            $idPenerimaan = $row["idtabunganp"];
        else if ($kategori == "DPST")
            $idPenerimaan = $row["iddeposit"];

        $namaPenerimaan = NamaPenerimaan($kategori, $idPenerimaan);
        $namaKategori = NamaKategori($kategori);

        $saldo = $row["saldo"];
        $total += $saldo;
        $rp = FormatRupiah($saldo);

        echo "<tr>";
        echo "<td align='center'>$no</td>";
        echo "<td align='left'><strong>$namaPenerimaan</strong><br><i>$namaKategori</i></td>";
        echo "<td align='right'><span style='font-size: 14px; font-weight: bold'>$rp</span></td>";
        echo "<td align='center'><i>".$row['flasttime']."</i></td>";
        echo "</tr>";
    }

    $rp = FormatRupiah($total);
    echo "<tr style='height: 35px;'>";
    echo "<td align='right' colspan='2' style='background-color: #ffc038'><strong>TOTAL</strong></td>";
    echo "<td align='right' style='background-color: #ffc038'><span style='font-weight: bold; font-size: 14px'>$rp</span></td>";
    echo "<td style='background-color: #ffc038'>&nbsp;</td>";
    echo "</tr>";

    echo "</table>";
    echo "</div>";
}
?>