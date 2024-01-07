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
require_once("../inc/config.php");
require_once("../inc/common.php");
require_once("../inc/db_functions.php");
OpenDb();
$nis = $_REQUEST['nis'];
$nama = $_REQUEST['nama'];
$filter = "";
if ($nis!="")
	$filter = $filter." nis LIKE '%$nis%' AND ";
if ($nama!="")
	$filter = $filter." nama LIKE '%$nama%' AND ";	
echo "<table width='100%' border='1' class='tab'>
  <tr>
	<td width='10' height='25' align='center' class='header'>No.</td>
	<td width='100' height='25' align='center' class='header'>NIS</td>
	<td width='*' height='25' align='center' class='header'>Nama</td>
	<td width='30' height='25' align='center' class='header'>&nbsp;</td>
  </tr>";
$sql = "SELECT * FROM siswa WHERE $filter aktif = 1 ORDER BY nama";
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
if ($num==0){
	echo "<tr>
  			<td height='20' colspan='4' align='center' class='nodata'>Tidak ada Data</td>
		</tr>";	
} else {
	$cnt=1;
	while ($row = @mysqli_fetch_array($result)){
		echo "<tr>
				<td height='20' align='center'>$cnt</td>
				<td height='20' align='center'>".$row['nis']."</td>
				<td height='20'>".$row['nama']."</td>
				<td height='20' align='center'><input type='button' class='cmbfrm2' value='>' onclick=\"pilihsiswa('".$row['nis']."')\"></td>
				</tr>";
				$cnt++;
	}		
}
echo "</table>";
?>