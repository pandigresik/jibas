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
function GetPegawai($bulan, $tahun, $bagian)
{
    $sql = "SELECT p.nip, p.nama
              FROM jbssdm.pegawai p
             WHERE p.bagian = '$bagian'
               AND p.aktif = 1
             ORDER BY p.nama";
    $res = QueryDb($sql);
    
    $table  = "<table border='1' id='table' class='tab' style='border-width: 1px'  cellpadding='2' cellspacing='2' width='99%'>\r\n";
    $table .= "<tr height='25'>\r\n";
    $table .= "<td width='10%' align='center' class='header'>No</td>\r\n";
    $table .= "<td width='*' align='left' class='header'>Nama</td>\r\n";
    $table .= "<td width='12%' class='header'>&nbsp;</td>\r\n";
    $table .= "</tr>\r\n";
    $cnt = 0;
    while($row = mysqli_fetch_row($res))
    {
        $cnt += 1;
        $table .= "<tr>\r\n";
        $table .= "<td align='center'>$cnt</td>\r\n";
        $table .= "<td align='left'><font style='font-size: 8px; color: green;'>".$row[0]."</font><br>".$row[1]."</td>\r\n";
        $table .= "<td align='center'><input type='button' class='but' value=' > ' onclick=\"showReport($bulan, $tahun, '$row[0]')\"></td>\r\n";
        $table .= "</tr>\r\n";
    }
    $table .= "</table>\r\n";
    
    return $table;
}

function SearchPegawai($bulan, $tahun, $filter, $keyword)
{
    $sql = "SELECT p.nip, p.nama
              FROM jbssdm.pegawai p
             WHERE p.$filter LIKE '%$keyword%'
               AND p.aktif = 1
             ORDER BY p.nama";
    $res = QueryDb($sql);
    
    $table  = "<table border='1' id='table' class='tab' style='border-width: 1px'  cellpadding='2' cellspacing='2' width='99%'>\r\n";
    $table .= "<tr height='25'>\r\n";
    $table .= "<td width='10%' align='center' class='header'>No</td>\r\n";
    $table .= "<td width='*' align='left' class='header'>Nama</td>\r\n";
    $table .= "<td width='12%' class='header'>&nbsp;</td>\r\n";
    $table .= "</tr>\r\n";
    $cnt = 0;
    while($row = mysqli_fetch_row($res))
    {
        $cnt += 1;
        $table .= "<tr>\r\n";
        $table .= "<td align='center'>$cnt</td>\r\n";
        $table .= "<td align='left'><font style='font-size: 8px; color: green;'>".$row[0]."</font><br>".$row[1]."</td>\r\n";
        $table .= "<td align='center'><input type='button' class='but' value=' > ' onclick=\"showReport($bulan, $tahun, '$row[0]')\"></td>\r\n";
        $table .= "</tr>\r\n";
    }
    $table .= "</table>\r\n";
    
    return $table;
}
?>