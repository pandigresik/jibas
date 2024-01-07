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

$kelas = $_REQUEST['kelas'];
$angkatan = $_REQUEST['angkatan'];
$tahunajaran = $_REQUEST['tahunajaran'];

OpenDb();
$sql = "SELECT kapasitas FROM kelas WHERE replid = '".$kelas."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$kapasitas = $row[0];

$sql1 = "SELECT COUNT(*) FROM siswa WHERE idkelas = '$kelas' AND idangkatan = '$angkatan' AND aktif = 1";
$result1 = QueryDb($sql1);
$row1 = mysqli_fetch_row($result1);
$isi = $row1[0];
CloseDb();
	
?>
<input type="text" name="kapasitas" id="kapasitas" value="<?=$kapasitas?>" />
<input type="text" name="isi" id="isi" value="<?=$isi?>" />