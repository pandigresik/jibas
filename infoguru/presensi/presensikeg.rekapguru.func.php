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
function ShowCbBulan($bulan)
{
    echo "<select id='cbBulan' onchange='changeOption()'>\r\n";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == $bulan ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>\r\n";
    }
    echo "</select>\r\n";
}

function ShowCbTahun($tahun)
{
    global $G_START_YEAR;
    
    echo "<select id='cbTahun' onchange='changeOption()'>\r\n";
    for($i = $G_START_YEAR; $i <= date('Y') + 1; $i++)
    {
        $sel = $i == $tahun ? "selected" : "";
        echo "<option value='$i' $sel>" . $i . "</option>\r\n";
    }
    echo "</select>\r\n";
}
?> 