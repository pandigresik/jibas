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
$departemen=$_REQUEST['departemen'];
?>
		<select name="dep_asal" id="dep_asal"  onKeyPress="return focusNext('sekolah', event)" onChange="ubah_dep_sekolah_asal()">
        <option value="">['departemen']</option>
      	<?php // Olah untuk combo sekolah
		OpenDb();
		$sql_dep_asal="SELECT departemen FROM jbsakad.departemen ORDER BY urutan";
		$result_dep_asal=QueryDB($sql_dep_asal);
		while ($row_dep_asal = mysqli_fetch_array($result_dep_asal)) {
			if ($departemen=="")
				$departemen=$row_dep_asal['departemen'];
		?>
       <option value="<?=$row_dep_asal['departemen']?>" <?=StringIsSelected($row_dep_asal['departemen'],$departemen)?>>
        <?=$row_dep_asal['departemen']?>
        </option>
      <?php
    	} 
		CloseDb();
		// Akhir Olah Data sekolah
		?>
    	</select>