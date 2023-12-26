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
$departemen=$_REQUEST['departemen'];
?>
<select name="tahunajaran" id="tahunajaran" onchange="change_tahunajaran()">
  <?php
OpenDb();
$sql_tahunajaran="SELECT t.replid,t.tahunajaran FROM jbsakad.tahunajaran t WHERE t.departemen='$departemen' AND t.aktif=1 ORDER BY t.tglmulai";
$result_tahunajaran=QueryDb($sql_tahunajaran);
$sql_tahunajaran;
while ($row_tahunajaran=@mysqli_fetch_row($result_tahunajaran)){
	if ($tahunajaran=="")
		$tahunajaran=$row_tahunajaran[0];
?>
      <option value="<?=$row_tahunajaran[0]?>" <?=StringIsSelected($row_tahunajaran[0], $tahunajaran) ?>>
        <?=$row_tahunajaran[1]?>
        </option>
      <?php
}
CloseDb();
?></select>