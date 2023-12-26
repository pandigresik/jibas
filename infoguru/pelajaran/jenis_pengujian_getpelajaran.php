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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

$departemen = $_POST['departemen'];

	$sql = "SELECT j.replid,j.jenisujian,j.idpelajaran,j.keterangan,p.replid,p.nama,p.departemen FROM jenisujian j, pelajaran p WHERE j.idpelajaran = p.replid AND p.departemen = '$departemen' ORDER BY p.nama"; 
	OpenDb();
	$result = QueryDb($sql);
	CloseDb();
			
	while($row = mysqli_fetch_array($result)) {
?>
	
		<?=++$cnt ?>
        <a href="jenis_pengujian_content.php?id=<?=$row[0]?>" target = "content" ><?=$row[5]?></a>

<?php
	} //while
?>