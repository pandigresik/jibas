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
    switch ($month)
    {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            $nday = 31;
            break;
        case 2:
            $nday = $year % 4 == 0 ? 29 : 28;
            break;
        default:
            $nday = 30;
            break;
    }

    return $nday;
}

function InaMonthName($month)
{
    switch ($month)
    {
        case 1:
            return "Januari";
        case 2:
            return "Februari";
        case 3:
            return "Maret";
        case 4:
            return "April";
        case 5:
            return "Mei";
        case 6:
            return "Juni";
        case 7:
            return "Juli";
        case 8:
            return "Agustus";
        case 9:
            return "September";
        case 10:
            return "Oktober";
        case 11:
            return "Nopember";
        case 12:
            return "Desember";
        default:
            return "";
    }
}

function LongDateInaFormat($tahun, $bulan, $tanggal)
{
    $result = str_pad($tanggal, 2, "0", STR_PAD_LEFT);
    $result = $result . " " . InaMonthName($bulan);
    $result = $result . " " . $tahun;

    return $result;
}

function FormatMySqlDate($date)
{
    $temp = explode("-", $date);
    if (count($temp) != 3)
        return $date;

    $result = str_pad($temp[2], 2, "0", STR_PAD_LEFT);
    $result = $result . " " . InaMonthName($temp[1]);
    $result = $result . " " . $temp[0];

    return $result;

}
?>
