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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

OpenDb();
$jenis=$_REQUEST['jenis'];
	if ($jenis == 'darah' || $jenis == 'kondisi' || $jenis == 'status' || $jenis == 'agama' || $jenis == 'suku') {	
		if ($jenis == 'darah') {
			$row = ['A', '0', 'B', 'AB'];
			$jum = 4;
?>				
			<select name="cari" id="cari" onchange="change_cari()" style="width:150px;">
<?php 			for ($i=0;$i<$jum;$i++) { 	 ?>
        		<option value="<?=$row[$i]?>" <?=StringIsSelected($row[$i], $cari)?> ><?=$row[$i]?></option>
              	
<?php 			} ?>   
        	</select>
<?php 		} else if ($jenis == 'kondisi') {								
			$query = "SELECT kondisisiswa FROM jbsakad.kondisisiswa ORDER BY kondisisiswa ";
			$result = QueryDb($query);			
		} elseif ($jenis == 'status') {	
			$query = "SELECT status FROM jbsakad.statussiswa ORDER BY statusiswa ";
			$result = QueryDb($query);
		} elseif ($jenis == 'agama' || $jenis == 'suku') {		
			$query = "SELECT $jenis FROM jbsumum.$jenis ORDER BY $jenis";
			$result = QueryDb($query);
		}

?>		<select name="cari" id="cari" onchange="change_cari()" style="width:150px;">
<?php 	while ($row = mysqli_fetch_row($result)) {	?>
   			<option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $cari)?> ><?=$row[0]?></option>
<?php 		} ?>    
         </select>

<?php }	else { 	 ?>
    	<input type="text" name="cari" id="cari" size="15"  />
        
<?php 	} 
	
CloseDb();

?>