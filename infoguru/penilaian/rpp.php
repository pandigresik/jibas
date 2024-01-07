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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
OpenDb();
if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];
if(isset($_REQUEST["tingkat"]))
	$tingkat = $_REQUEST["tingkat"];
if(isset($_REQUEST["departemen"]))
	$departemen = $_REQUEST["departemen"];		
if(isset($_REQUEST["pelajaran"]))
	$pelajaran = $_REQUEST["pelajaran"];
    ?>
<select name="idrpp" id="idrpp"><!---->
                  <?php
				  $sql_rpp="SELECT * FROM jbsakad.rpp WHERE idtingkat='$tingkat' AND idsemester='$semester' AND idpelajaran='$pelajaran' AND aktif=1";
				  $result_rpp=QueryDb($sql_rpp);
				  while ($row_rpp=@mysqli_fetch_array($result_rpp)){
				  ?>
                  <option value="<?=$row_rpp['replid']?>"><?=$row_rpp['rpp']?></option>
                  <?php
				  }
				  ?>
                  </select><!---->
                  <!--<input type="text" name="rpp" id="rpp" size="25" readonly onClick="get_rpp('<?=$row_dep['departemen']?>','<?=$row_get_nhb['idtingkat']?>','<?=$semester?>','<?=$row_get_nhb['idpelajaran']?>')"><input type="hidden" name="idrpp" id="idrpp" size="25"--><img src="../images/ico/tambah.png" onClick="get_rpp('<?=$departemen?>','<?=$tingkat?>','<?=$semester?>','<?=$pelajaran?>')" onMouseOver="showhint('Cari RPP!', this, event, '120px')">
                  <?php
				  CloseDb();
				 ?>