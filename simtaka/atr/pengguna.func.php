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
function GetPerpus($tingkat, $dep)
{
    echo "<select name='perpustakaan' id='perpustakaan'>\r\n";
	if ($tingkat == 1)
    { 
	    echo "<option value='-1'>Semua Perpustakaan</option>\r\n";
    }
	else
	{
        $filter = ($dep == "--ALL--") ? "" : "WHERE departemen = '".$dep."'";
        $sql = "SELECT *
                  FROM perpustakaan
                       $filter
                 ORDER BY replid";
		$res = QueryDb($sql);						
						
        while ($row = mysqli_fetch_array($res))
		{
            echo "<option value='".$row['replid'].":".$row['nama']."'>\r\n";
			echo $row['nama'];
			echo "</option>\r\n";
        }
	} 
    echo "</select>\r\n";
}
?>