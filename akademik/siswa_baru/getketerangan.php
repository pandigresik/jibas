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

$kapasitas = 0;
$isi = 0;
if ($_REQUEST['kelompok'] <> "") {
OpenDb();
			  $sql_cek_kap="SELECT kapasitas FROM jbsakad.kelompokcalonsiswa WHERE replid = '".$_REQUEST['kelompok']."'";
			  $res_cek_kap=QueryDb($sql_cek_kap);
			  $row_cek_kap=@mysqli_fetch_array($res_cek_kap);
			  
			  $sql_cek_jum = "SELECT COUNT(replid) FROM calonsiswa WHERE idkelompok = '".$_REQUEST['kelompok']."' AND aktif = 1";
			  $res_cek_jum = QueryDb($sql_cek_jum);				
			  $row_cek_jum = mysqli_fetch_row($res_cek_jum);
			  CloseDb();

OpenDb();
$sql = "SELECT keterangan FROM kelompokcalonsiswa WHERE replid = '".$_REQUEST['kelompok']."'";
$result = QueryDb($sql);
CloseDb();
$row = @mysqli_fetch_array($result);
}			
?>
<textarea name="keterangan" id="keterangan" rows="2" cols="60" readonly style="background-color:#E5F7FF" ><?=$row['keterangan'] ?>
</textarea>
<input type="hidden" name="kapasitas" id="kapasitas" value="<?=$row_cek_kap['kapasitas']?>" />
<input type="hidden" name="isi" id="isi" value="<?=$row_cek_jum[0]?>" />