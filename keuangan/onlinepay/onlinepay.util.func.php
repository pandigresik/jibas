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
function inaMonthName($month)
{
    return match ($month) {
        1 => "Jan",
        2 => "Feb",
        3 => "Mar",
        4 => "Apr",
        5 => "Mei",
        6 => "Jun",
        7 => "Jul",
        8 => "Agt",
        9 => "Sep",
        10 => "Okt",
        11 => "Nop",
        default => "Des",
    };
}

function formatInaMySqlDate($date)
{
    $lsDate = explode("-", (string) $date);
    $d = str_pad($lsDate[2], 2, "0", STR_PAD_LEFT);
    $m = inaMonthName($lsDate[1]);
    $y = $lsDate[0];

    return "$d $m $y";
}

function NamaMetode($metode)
{
    if ($metode == 1)
        return "Tagihan";

    if ($metode == 2)
        return "Karanjang";

    return "";
}

function NamaKategori($kategori)
{
    if ($kategori == "JTT")
        return "Iuran Wajib";

    if ($kategori == "SKR")
        return "Iuran Sukarela";

    if ($kategori == "SISTAB")
        return "Tabungan Siswa";

    if ($kategori == "PEGTAB")
        return "Tabungan Pegawai";

    if ($kategori == "BL")
        return "Biaya Layanan";

    if ($kategori == "LB")
        return "Kelebihan Pembayaran";

    if ($kategori == "DPST")
        return "Deposit Bank";

    return "-";
}

function NamaPenerimaan($kategori, $idPenerimaan)
{
    if ($idPenerimaan == "0")
    {
        if ($kategori == "BL")
            return "Biaya Layanan";

        if ($kategori == "LB")
            return "Kelebihan Transfer";
    }

    if ($kategori == "JTT" || $kategori == "SKR")
    {
        $sql = "SELECT nama
                  FROM jbsfina.datapenerimaan
                 WHERE replid = $idPenerimaan";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            return $row[0];

        return "-";
    }

    if ($kategori == "SISTAB")
    {
        $sql = "SELECT nama
                  FROM jbsfina.datatabungan
                 WHERE replid = $idPenerimaan";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            return $row[0];

        return "-";
    }

    if ($kategori == "PEGTAB")
    {
        $sql = "SELECT nama
                  FROM jbsfina.datatabunganp
                 WHERE replid = $idPenerimaan";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            return $row[0];

        return "-";
    }

    if ($kategori == "DPST")
    {
        $sql = "SELECT nama
                  FROM jbsfina.bankdeposit
                 WHERE replid = $idPenerimaan";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            return $row[0];

        return "-";
    }

    return "-";
}

function PrePrintR($list)
{
    echo "<pre>";
    print_r($list);
    echo "</pre>";
}

function EchoBr($data)
{
    echo "$data<br>";
}

function SafeInput($data)
{
    $data = str_replace("\"", "`", (string) $data);
    $data = str_replace("<", "&lt;", $data);
    return str_replace(">", "&gt;", $data);
}
?>
