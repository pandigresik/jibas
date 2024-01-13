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
function ShowCbDepartemen()
{
    global $departemen;
    
    $sql = "SELECT departemen
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);
    
    echo "<select id='cbDept' name='cbDept' class='inputbox' onchange='cbDept_OnChange()'>";
    while($row = mysqli_fetch_row($res))
    {
        if ($departemen == "")
            $departemen = $row[0];
            
        echo "<option value='".$row[0]."'>".$row[0]."</option>";
    }
    echo "</select>";
}

function ShowCbKategori($departemen)
{
    $sql = "SELECT kategori, replid
              FROM jbsletter.kategori
             WHERE departemen = '$departemen'
               AND aktif = 1
             ORDER BY kategori";
    $res = QueryDb($sql);
    
    echo "<select id='cbKategori' name='cbKategori' class='inputbox' onchange='showBlank()'>";
    echo "<option value='0'>(Semua Kategori)</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='".$row[1]."'>".$row[0]."</option>";
    }
    echo "</select>";
}

function ShowCbDate90()
{
    $sql = "SELECT DAY(NOW()) AS DD1, MONTH(NOW()) AS MM1, YEAR(NOW()) AS YY1,
                   DAY(DATE_SUB(NOW(), INTERVAL 30 DAY)) AS DD2, 
                   MONTH(DATE_SUB(NOW(), INTERVAL 30 DAY)) AS MM2, 
                   YEAR(DATE_SUB(NOW(), INTERVAL 30 DAY)) AS YY2,
                   DAY(DATE_SUB(NOW(), INTERVAL 90 DAY)) AS DD3, 
                   MONTH(DATE_SUB(NOW(), INTERVAL 90 DAY)) AS MM3, 
                   YEAR(DATE_SUB(NOW(), INTERVAL 90 DAY)) AS YY3";
    $res = QueryDb($sql);
    $row = mysqli_fetch_array($res);
    
    echo "<select id='cbBulan1' name='cbBulan1' class='inputbox' onchange='showBlank()'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == $row["MM1"] ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
    }
    echo "</select>";
    echo "<select id='cbTahun1' name='cbTahun1' class='inputbox' onchange='showBlank()'>";
    for($i = $G_START_YEAR; $i <= $row['YY1']; $i++)
    {
        $sel = $i == $row['YY1'] ? "selected" : "";
        echo "<option value='$i' $sel>" . $i . "</option>";
    }
    echo "</select>";
    
    echo "&nbsp;&nbsp;s.d&nbsp;&nbsp;";
    
    echo "<select id='cbBulan2' name='cbBulan2' class='inputbox' onchange='showBlank()'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == $row["MM1"] ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
    }
    echo "</select>";
    echo "<select id='cbTahun2' name='cbTahun2' class='inputbox' onchange='showBlank()'>";
    for($i = $G_START_YEAR; $i <= $row['YY1']; $i++)
    {
        $sel = $i == $row['YY1'] ? "selected" : "";
        echo "<option value='$i' $sel>" . $i . "</option>";
    }
    echo "</select>";
}
?>