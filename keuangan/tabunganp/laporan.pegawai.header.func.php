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
function InitTanggal()
{
    global $tgl1, $bln1, $thn1, $tgl2, $bln2, $thn2;
    
    $sql = "SELECT day(now()), month(now()), year(now()), day(date_sub(now(), INTERVAL 30 DAY)), 
                   month(date_sub(now(), INTERVAL 30 DAY)), year(date_sub(now(), INTERVAL 30 DAY))";		
    $result = QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $tgl2 = $row[0];
    $bln2 = $row[1];
    $thn2 = $row[2];
    $tgl1 = $row[3];
    $bln1 = $row[4];
    $thn1 = $row[5];
}

function ReadRequest()
{
    global $departemen, $tgl1, $bln1, $thn1, $tgl2, $bln2, $thn2, $n1, $n2;
    
    $departemen = "";
    if (isset($_REQUEST['departemen']))
        $departemen = $_REQUEST['departemen'];
    
    if (isset($_REQUEST['tgl1']))
        $tgl1 = (int)$_REQUEST['tgl1'];
    
    if (isset($_REQUEST['bln1']))
        $bln1 = (int)$_REQUEST['bln1'];
    
    if (isset($_REQUEST['thn1']))
        $thn1 = (int)$_REQUEST['thn1'];
    
    if (isset($_REQUEST['tgl2']))
        $tgl2 = (int)$_REQUEST['tgl2'];
    
    if (isset($_REQUEST['bln2']))
        $bln2 = (int)$_REQUEST['bln2'];
    
    if (isset($_REQUEST['thn2']))
        $thn2 = (int)$_REQUEST['thn2'];	
    
    $n1 = JmlHari($bln1,$thn1);
    $n2 = JmlHari($bln2,$thn2);
}
?>