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
function GetMaxDay($year, $month)
{
    $nday = match ($month) {
        1, 3, 5, 7, 8, 10, 12 => 31,
        2 => $year % 4 == 0 ? 29 : 28,
        default => 30,
    };

    return $nday;
}

function InaMonthName($month)
{
    return match ($month) {
        1 => "Januari",
        2 => "Februari",
        3 => "Maret",
        4 => "April",
        5 => "Mei",
        6 => "Juni",
        7 => "Juli",
        8 => "Agustus",
        9 => "September",
        10 => "Oktober",
        11 => "Nopember",
        12 => "Desember",
        default => "",
    };
}

function LongDateInaFormat($tahun, $bulan, $tanggal)
{
    $result = str_pad((string) $tanggal, 2, "0", STR_PAD_LEFT);
    $result = $result . " " . InaMonthName($bulan);
    $result = $result . " " . $tahun;

    return $result;
}

function FormatMySqlDate($date)
{
    $temp = explode("-", (string) $date);
    if (count($temp) != 3)
        return $date;

    $result = str_pad($temp[2], 2, "0", STR_PAD_LEFT);
    $result = $result . " " . InaMonthName($temp[1]);
    $result = $result . " " . $temp[0];

    return $result;

}
?>
