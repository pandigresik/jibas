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
$sql = "SELECT replid,proses FROM prosespenerimaansiswa WHERE aktif=1 AND departemen='".$_REQUEST['departemen']."'";				
$result = QueryDb($sql);
CloseDb();
$row = mysqli_fetch_array($result);
$proses = $row['replid'];

?>
<input type="text" name="nama_proses" id="nama_proses" style="width:270px;" class="disabled" value="<?=$row['proses']?>" readonly />
<input type="hidden" name="proses" id="proses"  value="<?=$proses?>" />

<!--
<select name="kondisi" id="kondisi" class="ukuran">
    <?php // Olah untuk combo kondisi
/*	$kondisi_kiriman=$_REQUEST['kondisi_kiriman'];
	OpenDb();
	$sql_kondisi="SELECT kondisi,urutan FROM jbsakad.kondisisiswa ORDER BY urutan";
	$result_kondisi=QueryDB($sql_kondisi);
	while ($row_kondisi = mysqli_fetch_array($result_kondisi)) {
	if ($kondisi_kiriman=="")
	$kondisi_kiriman=$row_kondisi['kondisi'];*/
	?>
	<option value="<?=$row_kondisi['kondisi']?>"<?=StringIsSelected($row_kondisi['kondisi'],$kondisi_kiriman)?> >
	<?=$row_kondisi['kondisi']?></option>
	<?php
    //} 
	//CloseDb();
	// Akhir Olah Data kondisi
	?>
    </select>&nbsp;<img src="../images/ico/tambah.png" onClick="tambah_kondisi();" />-->