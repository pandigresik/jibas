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
require_once('../include/config.php');
require_once('../include/db_functions.php');
$nip=$_REQUEST['nip'];
$pelajaran=$_REQUEST['pelajaran'];
?>
<select name="jenis" id="jenis" style="width:150px;" onKeyPress="return focusNext('keterangan', event)">
<?php OpenDb();
		$sql = "SELECT s.replid,s.status FROM statusguru s, guru g WHERE g.nip = '$nip' AND g.idpelajaran = $pelajaran AND g.statusguru = s.status ORDER BY status";	
		
		$result = QueryDb($sql);
		CloseDb();
	
		while($row = mysqli_fetch_array($result)) {
			if ($jenis == "")
				$jenis = $row['replid'];				
			?>
          <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $jenis) ?>>
            <?=$row['status']?>
            </option>
          <?php
			} //while
			?>
        </select>ggggggg