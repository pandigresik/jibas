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
require_once('../include/config.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/sessioninfo.php');
require_once('../include/db_functions.php');
OpenDb();
$dbnameperpus = "jbsperpus";
$IdKatalog = $_REQUEST['IdKatalog'];
$keyword = $_REQUEST['keyword'];
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
  <tr>
    <td height="25" align="center" class="header">No</td>
    <td height="25" align="center" class="header">Judul</td>
    <td height="25" align="center" class="header">Penulis</td>
    <td height="25" align="center" class="header">Penerbit</td>
    <td height="25" align="center" class="header">Jumlah</td>
    <td height="25" align="center" class="header">Tersedia</td>
    <td align="center" class="header">&nbsp;</td>
  </tr>
<?php
$sql = "SELECT * FROM $dbnameperpus.pustaka WHERE katalog='$IdKatalog' AND judul LIKE '%$keyword%' ORDER BY judul";
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
if ($num==0){
	$sql = "SELECT * FROM $dbnameperpus.pustaka WHERE katalog='$IdKatalog' AND abstraksi LIKE '%$keyword%' ORDER BY judul";
	$result = QueryDb($sql);
	$num = @mysqli_num_rows($result);
	if ($num==0){
		$sql = "SELECT * FROM $dbnameperpus.pustaka WHERE katalog='$IdKatalog' AND keyword LIKE '%$keyword%' ORDER BY judul";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		if ($num==0){
			$sql = "SELECT * FROM $dbnameperpus.pustaka WHERE katalog='$IdKatalog' AND penulis IN(SELECT replid FROM $dbnameperpus.penulis WHERE kode LIKE '%$keyword%' || nama LIKE '%$keyword%')  ORDER BY judul";
			$result = QueryDb($sql);
			$num = @mysqli_num_rows($result);
			if ($num==0){
				$sql = "SELECT * FROM $dbnameperpus.pustaka WHERE katalog='$IdKatalog' AND penerbit IN(SELECT replid FROM $dbnameperpus.penerbit WHERE kode LIKE '%$keyword%' || nama LIKE '%$keyword%')  ORDER BY judul";
				$result = QueryDb($sql);
				$num = @mysqli_num_rows($result);
			}
		}
	}
}

if ($num > 0){
$cnt=1;
while ($row = @mysqli_fetch_array($result)){
$sqlPenulis = "SELECT kode,nama FROM $dbnameperpus.penulis WHERE replid = '".$row['penulis']."'";
$resultPenulis = QueryDb($sqlPenulis);
$rowPenulis = @mysqli_fetch_row($resultPenulis);
$penulis = $rowPenulis[0]."&nbsp;-&nbsp;".$rowPenulis[1];

$sqlPenerbit = "SELECT kode,nama FROM $dbnameperpus.penerbit WHERE replid = '".$row['penerbit']."'";
$resultPenerbit = QueryDb($sqlPenerbit);
$rowPenerbit = @mysqli_fetch_row($resultPenerbit);
$penerbit = $rowPenerbit[0]."&nbsp;-&nbsp;".$rowPenerbit[1];

$rtotal = @mysqli_num_rows(QueryDb("SELECT * FROM $dbnameperpus.daftarpustaka d WHERE d.pustaka='".$row[0]."'"));
$rtersedia = @mysqli_num_rows(QueryDb("SELECT * FROM $dbnameperpus.daftarpustaka d WHERE d.pustaka='".$row[0]."' AND d.status=1"));
?>
  <tr>
    <td height="20" align="center"><div class="tab_content"><?=$cnt?></div></td>
    <td height="20" class="td"><div class="tab_content"><?=$row['judul']?></div></td>
    <td height="20" class="td"><div class="tab_content"><?=$penulis?></div></td>
    <td height="20" class="td"><div class="tab_content"><?=$penerbit?></div></td>
    <td height="20" align="center"><div class="tab_content"><?=$rtotal?></div></td>
    <td height="20" align="center" ><div class="tab_content"><?=$rtersedia?></div></td>
    <td align="center" ><div class="tab_content"><a href="javascript:ViewDetail('<?=$row['replid']?>')"><img src="../images/ico/lihat.png" width="16" height="16" border="0" /></a></div></td>
  </tr>
<?php
$cnt++;
}
} else {
?>
  <tr>
    <td height="20" colspan="7" align="center" class="nodata">Tidak ada data</td>
  </tr>
<?php } ?>
</table>