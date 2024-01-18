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
require_once("common.func.php");

echo "<link rel='stylesheet' type='text/css' href='../../style/style.css'>";
echo "<table border='1' cellpadding='2' cellspacing='0' width='500' style='border-collapse: collapse; border-width: 1px;'>\r\n";

$eset = GetEmoticonSet();
for($i = 0; $i < count($eset); $i++)
{
    $iconname = $eset[$i][0];
    $symbols = $eset[$i][1];
    
    $icon = "<img src='../images/emoticons/$iconname'>";
    $iconname = str_replace(".png",  "", (string) $iconname);
    $symbol = "";
    for($j = 0; $j < count($symbols); $j++)
    {
        $symbol .= $symbols[$j] . "&nbsp;&nbsp;";;    
    }
    echo "<tr>\r\n";
    echo "<td width='50' align='center' style='background-color: #effbe3' valign='middle'>\r\n";
    echo $icon;
    echo "</td>\r\n";
    echo "<td width='100' align='left' style='background-color: #effbe3' valign='middle'>\r\n";
    echo $iconname;
    echo "</td>\r\n";
    echo "<td width='*' style='background-color: #fff; font-family: courier; font-size: 14px;' align='left' valign='middle'>\r\n";
    echo $symbol;
    echo "</td>\r\n";
    echo "</tr>\r\n";    
}

echo "</table>\r\n";