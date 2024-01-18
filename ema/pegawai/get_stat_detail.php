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
require_once('../inc/getheader.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');


$kriteria = $_REQUEST['kriteria'];
$kondisi = $_REQUEST['kondisi'];
$krit = ['', 'Bagian', 'Agama', 'Gelar', 'Jenis Kelamin', 'Status Aktif', 'Status Menikah', 'Suku', 'Tahun Kelahiran', 'Usia'];
$field = ['', 'bagian', 'agama', 'gelar', 'kelamin', 'aktif', 'nikah', 'suku', 'YEAR(tgllahir)', ''];

if ($kriteria == 9){
	$kondisi = str_replace("__","-",(string) $kondisi);
	$filter2 = " AND $kondisi";
} else {
	$filter2 = " AND ".$field[$kriteria]."='$kondisi'";
}
//if {
	//$filter2 = " AND ".$field[$kriteria]."='$kondisi'";
//}

//FLOOR(DATEDIFF(NOW(), tgllahir) / 365)
$sql = "SELECT nip,nama,replid FROM 
		$db_name_sdm.pegawai 
		WHERE aktif=1 $filter2 ORDER BY nama";	
//echo $sql;
?>
<link href="../style/style.css" rel="stylesheet" type="text/css">

<table width="100%" border="1" class="tab" align="left">
  <tr>
    <td width="25" height="25" align="center" class="header">No.</td>
    <td height="25" align="center" class="header">NIP</td>
    <td height="25" align="center" class="header">Nama</td>
    <td height="25" align="center" class="header">&nbsp;</td>
  </tr>
  <?php
  OpenDb();
  $result = QueryDb($sql);
  $cnt=1;
  while ($row = @mysqli_fetch_row($result)){
  ?>
  <tr>
    <td width="25" align="center"><?=$cnt?></td>
    <td align="center"><?=$row[0]?></td>
    <td>&nbsp;&nbsp;<?=$row[1]?></td>
    <td align="center"><a href="javascript:lihat_pegawai('<?=$row[2]?>')"><img src="../img/lihat.png" width="16" height="16" border="0"></a></td>
  </tr>
  <?php
  $cnt++;
  }
  ?>
</table>