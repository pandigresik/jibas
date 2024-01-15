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
function ShowSelectDepartemen()
{
    global $departemen;
    
    echo "<select name='departemen' id='departemen' style='width:100px' onchange='change_dep()'>";
    $dep = getDepartemen(getAccess());
    foreach($dep as $value)
    {
        if ($departemen == "")
            $departemen = $value; 
        echo "<option value='$value'" .StringIsSelected($value, $departemen) . ">".$value."</option>";
    }
    echo "</select>&nbsp;";
}

function ShowTahunBuku()
{
    global $departemen;
    
    $sql = "SELECT replid AS id, tahunbuku
              FROM tahunbuku
             WHERE aktif = 1
               AND departemen = '".$departemen."'";
    $result = QueryDb($sql);
	$row = mysqli_fetch_array($result);		
    echo "<input type='text' name='tahunbuku' id='tahunbuku' size='30' readonly style='background-color:#CCCC99' value='" . $row['tahunbuku'] ."'>";
    echo "<input type='hidden' name='idtahunbuku' id='idtahunbuku' value='" . $row['id'] . "'>";
}

function ShowJenisTabungan()
{
    global $departemen;
		
    $sql = "SELECT replid, nama
              FROM datatabunganp
             WHERE aktif = 1
               AND departemen = '$departemen'
             ORDER BY replid";
    $result = QueryDb($sql);
    
    echo "<select name='idtabungan' id='idtabungan' style='width:188px;' onchange='change_tabungan()'>";
    while ($row = mysqli_fetch_array($result))
    {
        echo "<option value='" . $row['replid'] . "'>" . $row['nama'] . "</option>";
    }
    echo "</select>";
}
?>