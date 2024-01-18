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
require_once("../include/theme.php"); 
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once("../include/sessionchecker.php");

OpenDb();
$dasar_penilaian=$_REQUEST["dasarpenilaian"];
$departemen=$_REQUEST["departemen"];
$tahunajaran=$_REQUEST["tahunajaran"];
$semester=$_REQUEST["semester"];
$tingkat=$_REQUEST["tingkat"];
$kelas=$_REQUEST["kelas"];
$pelajaran=$_REQUEST["pelajaran"];
$nip=$_REQUEST["nip"];
?>
<div align="right">
<select name="jen_uji" id="jen_uji" onchange="jen_uji()" style="width:150px">
			  <?php
			  $sql_jenispengujian="SELECT * FROM jbsakad.aturannhb WHERE idtingkat='$tingkat' AND idpelajaran='$pelajaran' AND aktif=1 AND dasarpenilaian='$dasar_penilaian' AND nipguru='$nip'";
			  $result_jenispengujian=QueryDb($sql_jenispengujian);
			   if (@mysqli_num_rows($result_jenispengujian)>0){
			  while ($row_jenispengujian=@mysqli_fetch_array($result_jenispengujian)){
				$sql_jenisuji="SELECT jenisujian FROM jbsakad.jenisujian WHERE replid='".$row_jenispengujian['idjenisujian']."'";
				$result_jenisuji=QueryDb($sql_jenisuji);
				$row_jenisuji=@mysqli_fetch_array($result_jenisuji);
				?>
    		    <option value="<?=urlencode((string) $row_jenispengujian['replid'])?>" <?php //IntIsSelected($row['replid'], $kelas) ?>>
   		        <?=$row_jenisuji['jenisujian']?>
   		        </option>
<?php
			  }
				} else {
			  echo "<option value='' > - - - </option>";
			  }
			  ?>
  		    </select>
			</div>