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
function ShowCbBulan()
{
    echo "<select name='cbBulan' id='cbBulan'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == date('n') ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
    }
    echo "</select>";
}

function ShowCbTahun()
{
    global $G_START_YEAR;
    
    echo "<select name='cbTahun' id='cbTahun'>";
    for($i = $G_START_YEAR; $i <= date('Y') + 1; $i++)
    {
        $sel = $i == date('Y') ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
}

function ShowList()
{
    global $FILESHARE_ADDR;
    
    $departemen = $_REQUEST['departemen'];
    $bulan = $_REQUEST['bulan'];
    $tahun = $_REQUEST['tahun'];
    
    OpenDb();
    
    $sql = "SELECT *
              FROM jbsumum.gambar
             WHERE departemen = '$departemen'
               AND YEAR(tanggal) = $tahun
               AND MONTH(tanggal) = $bulan
               AND modul = 'SIMAKA'
             ORDER BY tanggal DESC";
    $res = QueryDb($sql);
    
    $no = 1;
    echo "<table width='100%' cellpadding='5'>";
    while($row = mysqli_fetch_array($res))
    {
        if ($no == 1)
            echo "<tr>";
        
        echo "<td width='50%' valign='top' align='center'>";
        
        $pict = $FILESHARE_ADDR . "/" . $row['lokasi'] . "/" . $row['berkas'];
        echo "<a href='#' onclick=\"selectPict('$pict')\">";
        echo "<img src='$pict' width='200'><br>";
        echo $row['nama'];
        
        $deskripsi = trim((string) $row['deskripsi']);
        $deskripsi = strlen($deskripsi) == 0 ? "&nbsp;": $deskripsi;
        echo "<br><font style='color: #666'>";
        echo $deskripsi;
        echo "</font></a>";
        echo "</td>";
        
        $no += 1;
        if ($no == 3)
        {
            echo "</tr>";
            $no = 1;
        }
    }
    echo "</table>";
}
?>