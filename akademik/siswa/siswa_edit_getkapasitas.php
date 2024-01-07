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
$kelas=$_POST['kelas'];
if ($kelas <> "") {
OpenDb();
$sql1 = "SELECT kapasitas FROM kelas WHERE replid = '".$kelas."'";
$result1 = QueryDb($sql1);
$row_cek1 = mysqli_fetch_array($result1);
$sql2 = "SELECT COUNT(*) AS jum FROM siswa WHERE idkelas = '$kelas' AND aktif = 1";
$result2 = QueryDb($sql2);
$row_cek2 = mysqli_fetch_array($result2);
CloseDb();

?>
<input type="hidden" name="kapasitas" id="kapasitas" value="<?=$row_cek1['kapasitas']?>" />
<input type="hidden" name="isi" id="isi" value="<?=$row_cek2['jum']?>" />
<?php } ?>