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
function DayName($weekday)
{
    return match ($weekday) {
        1 => "Mgu",
        2 => "Sen",
        3 => "Sel",
        4 => "Rab",
        5 => "Kam",
        6 => "Jum",
        default => "Sab",
    };
}

function SafeInput($text)
{
    $text = str_replace("'", "`", (string) $text);
    $text = str_replace("<", "&lt;", $text);
    $text = str_replace(">", "^gt;", $text);
    
    return $text;
}

function SecToAge($secdiff)
{
    if ($secdiff >= 86400)
        return ceil($secdiff / 86400) . " hari yang lalu";
    
    if ($secdiff >= 3600)
        return ceil($secdiff / 3600) . " jam yang lalu";
    
    if ($secdiff >= 60)
        return ceil($secdiff / 60) . " menit yang lalu";
    
    return $secdiff . " detik yang lalu";
}
?>