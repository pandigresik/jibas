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
require_once("../include/sessionchecker.php");

OpenDb();
$departemen = $_POST['departemen'];
//$kalender = $_POST['kalender'];
if ($kalender==""){
	$kalender="null";
	} 
?>
<select name="kalender" id="kalender" onchange="change_kalender()">
<option value="null" <?php if($kalender=="null") 
	echo "selected='selected'";
	?> >
        -- Pilih Kalender --
</option>
<?php 
	$sql_kalender = "SELECT * FROM jbsakad.kalenderakademik WHERE departemen = '$departemen' ORDER BY tglmulai";
	$result_kalender = QueryDb($sql_kalender);
	if (mysqli_num_rows($result_kalender)>0){
	while($row_kalender = mysqli_fetch_array($result_kalender)) {
	if ($kalender == "")
	$kalender = $row_kalender['replid'];
?>
		<option value="<?=urlencode((string) $row_kalender['replid'])?>" <?=StringIsSelected($row_kalender['replid'], $kalender) ?>><?=$row_kalender['kalender']?></option>
<?php
	} //while
	} 
?>
</select>
<img src="../images/ico/tambah.png" onclick="tambah_kalender('<?=$departemen?>');" />
    <?php
	if (@mysqli_num_rows($result_kalender)==0){
	?>

	<?php
	} else { 
    ?>
    <img src="../images/ico/ubah.png" onclick="ubah_kalender('<?=$kalender?>');" />
    <img src="../images/ico/hapus.png" onclick="hapus_kalender('<?=$kalender?>');" />
	<?php
	}
	CloseDb();
	?>