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
function GetCbActivity($aktif)
{
    $sql = "SELECT replid, kegiatan, departemen
              FROM jbssat.frkegiatan k
             WHERE aktif = $aktif
             ORDER BY departemen, kegiatan";
    $res = QueryDb($sql);
    echo "<select id='cbKegiatan' style='width: 380px;' onchange='clearContent()'>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value=$row[0]>($row[2]) $row[1]</option>";
    }
    echo "</select>";
}

function GetCbDepartemen($default, $elmName = "cbDepartemen")
{
    $selectedValue = "";
    $selectedText = "";
    
    $sql = "SELECT departemen
              FROM jbsakad.departemen
             WHERE aktif = 1
             ORDER BY urutan";
    $res = QueryDb($sql);
    $selection = "<select id='$elmName' style='width: 140px;' onchange='changeCbDepartemen()'>\r\n";
    while($row = mysqli_fetch_row($res))
    {
        if ($default == "")
            $default = $row[0];
            
        $selected = $default == $row[0] ? "selected" : "";
        $selection .= "<option value='".$row[0]."' $selected>".$row[0]."</option>\r\n";
        
        if ($selected == "selected")
        {
            $selectedValue = $row[0];
            $selectedText = $row[0];
        }
    }
    $selection .= "</select>\r\n";
    
    $json_array = ['selection' => $selection, 'value' => $selectedValue, 'text' => $selectedText];
    
    return json_encode($json_array, JSON_THROW_ON_ERROR);
}

function GetCbTingkat($departemen, $default)
{
    $selectedValue = "";
    $selectedText = "";
    
    $sql = "SELECT replid, tingkat
              FROM jbsakad.tingkat
             WHERE aktif = 1
               AND departemen = '$departemen' 
             ORDER BY urutan";
    $res = QueryDb($sql);
    $selection = "<select id='cbTingkat' style='width: 140px;' onchange='changeCbTingkat()'>";
    while($row = mysqli_fetch_row($res))
    {
        if ($default == 0)
            $default = $row[0];
            
        $selected = $default == $row[0] ? "selected" : "";
        $selection .= "<option value='".$row[0]."' $selected>".$row[1]."</option>";
        
        if ($selected == "selected")
        {
            $selectedValue = $row[0];
            $selectedText = $row[1];
        }
    }
    $selection .= "</select>";
    
    $json_array = ['selection' => $selection, 'value' => $selectedValue, 'text' => $selectedText];
    
    return json_encode($json_array, JSON_THROW_ON_ERROR);
}

function GetCbKelas($idtingkat, $default)
{
    $selectedValue = "";
    $selectedText = "";
    
    $sql = "SELECT k.replid, k.kelas
              FROM jbsakad.kelas k, jbsakad.tahunajaran ta
             WHERE k.idtahunajaran = ta.replid
               AND ta.aktif = 1 
               AND k.idtingkat = $idtingkat
             ORDER BY kelas";
    $res = QueryDb($sql);
    $selection = "<select id='cbKelas' style='width: 140px;' onchange='changeCbKelas()'>";
    while($row = mysqli_fetch_row($res))
    {
        if ($default == 0)
            $default = $row[0];
            
        $selected = $default == $row[0] ? "selected" : "";
        $selection .= "<option value='".$row[0]."' $selected>".$row[1]."</option>";
        
        if ($selected == "selected")
        {
            $selectedValue = $row[0];
            $selectedText = $row[1];
        }
    }
    $selection .= "</select>";
    
    $json_array = ['selection' => $selection, 'value' => $selectedValue, 'text' => $selectedText];
    
    return json_encode($json_array, JSON_THROW_ON_ERROR);
}

function GetSiswa($idkegiatan, $bulan, $tahun, $idkelas)
{
    $sql = "SELECT s.nis, s.nama, k.kelas
              FROM jbsakad.siswa s, jbsakad.kelas k
             WHERE s.idkelas = k.replid
               AND s.idkelas = $idkelas
               AND s.aktif = 1
             ORDER BY s.nama";
    $res = QueryDb($sql);
    
    $table  = "<table border='1' id='table' class='tab' style='border-width: 1px'  cellpadding='2' cellspacing='2' width='97%'>\r\n";
    $table .= "<tr height='25'>\r\n";
    $table .= "<td width='10%' align='center' class='header'>No</td>\r\n";
    $table .= "<td width='*' align='left' class='header'>Nama</td>\r\n";
    $table .= "<td width='20%' align='center' class='header'>Kelas</td>\r\n";
    $table .= "<td width='12%' class='header'>&nbsp;</td>\r\n";
    $table .= "</tr>\r\n";
    $cnt = 0;
    while($row = mysqli_fetch_row($res))
    {
        $cnt += 1;
        $table .= "<tr>\r\n";
        $table .= "<td align='center'>$cnt</td>\r\n";
        $table .= "<td align='left'><font style='font-size: 8px; color: green;'>".$row[0]."</font><br>".$row[1]."</td>\r\n";
        $table .= "<td align='center'>".$row[2]."</td>\r\n";
        $table .= "<td align='center'><input type='button' class='but' value=' > ' onclick=\"showReport($idkegiatan, $bulan, $tahun, '$row[0]')\"></td>\r\n";
        $table .= "</tr>\r\n";
    }
    $table .= "</table>\r\n";
    
    return $table;
}

function SearchSiswa($idkegiatan, $bulan, $tahun, $filter, $keyword)
{
    $sql = "SELECT s.nis, s.nama, k.kelas
              FROM jbsakad.siswa s, jbsakad.kelas k
             WHERE s.idkelas = k.replid
               AND s.$filter LIKE '%$keyword%'
               AND s.aktif = 1
             ORDER BY s.nama";
    $res = QueryDb($sql);
    
    $table  = "<table border='1' id='table' class='tab' style='border-width: 1px'  cellpadding='2' cellspacing='2' width='97%'>\r\n";
    $table .= "<tr height='25'>\r\n";
    $table .= "<td width='10%' align='center' class='header'>No</td>\r\n";
    $table .= "<td width='*' align='left' class='header'>Nama</td>\r\n";
    $table .= "<td width='20%' align='center' class='header'>Kelas</td>\r\n";
    $table .= "<td width='12%' class='header'>&nbsp;</td>\r\n";
    $table .= "</tr>\r\n";
    $cnt = 0;
    while($row = mysqli_fetch_row($res))
    {
        $cnt += 1;
        $table .= "<tr>\r\n";
        $table .= "<td align='center'>$cnt</td>\r\n";
        $table .= "<td align='left'><font style='font-size: 8px; color: green;'>".$row[0]."</font><br>".$row[1]."</td>\r\n";
        $table .= "<td align='center'>".$row[2]."</td>\r\n";
        $table .= "<td align='center'><input type='button' class='but' value=' > ' onclick=\"showReport($idkegiatan, $bulan, $tahun, '$row[0]')\"></td>\r\n";
        $table .= "</tr>\r\n";
    }
    $table .= "</table>\r\n";
    
    return $table;
}
?>