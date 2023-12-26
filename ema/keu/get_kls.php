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
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');
OpenDb();
$tingkat=$_REQUEST['tingkat'];
$tahunajaran=$_REQUEST['tahunajaran'];
?>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<select name="kelas" class="cmbfrm" id="kelas" onchange="chg_kls()">
	<?php
	$sql = "SELECT replid,kelas FROM kelas WHERE idtingkat='$tingkat' AND idtahunajaran='$tahunajaran' AND aktif=1 ORDER BY kelas";
	$result = QueryDb($sql);
	while ($row = @mysqli_fetch_row($result)){
		if ($kelas=="")
			$kelas = $row[0];	
	?>
	<option value="<?=$row[0]?>"><?=$row[1]?></option>
	<?php
	}
	?>
</select>