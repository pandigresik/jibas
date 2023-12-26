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
require_once('../../include/common.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
$bulan="";
if (isset($_REQUEST['bulan']))
	$bulan=$_REQUEST['bulan'];

$tahun="";
if (isset($_REQUEST['tahun']))
	$tahun=$_REQUEST['tahun'];

$xxx="";
if (isset($_REQUEST['xxx']))
	$xxx=$_REQUEST['xxx'];

$departemen="";
if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];
$tahunajaran="";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran=$_REQUEST['tahunajaran'];
$tingkat="";
if (isset($_REQUEST['tingkat']))
	$tingkat=$_REQUEST['tingkat'];
$kelas="";
if (isset($_REQUEST['kelas']))
	$kelas=$_REQUEST['kelas'];		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<title>Untitled Document</title>
<script language="javascript" type="text/javascript" src="../../script/tables.js"></script>
<script language="javascript" type="text/javascript">
function cek_all() {
	var x;
	var jum = document.tujuan.numsiswa.value;
	var ceked = document.tujuan.cek.checked;
	for (x=1;x<=jum;x++){
		if (ceked==true){
			document.getElementById("ceknis"+x).checked=true;
		} else {
			document.getElementById("ceknis"+x).checked=false;
		}
	}
}
</script>
</head>
<body>
<form name="tujuan" id="tujuan" action="pesansimpan.php">
<table width="100%" border="0" cellspacing="0" class="tab" id="table">
  <tr>
    <th width="18%" height="30" class="header" scope="row">No</th>
    <td width="3%" height="30" class="header"><input type="checkbox" name="cek" id="cek" onClick="cek_all()" title="Pilih semua" onMouseOver="showhint('Pilih semua', this, event, '120px')"/></td>
    <td width="*" height="30" class="header">Nama</td>
  </tr>
  <?php 
			OpenDb();
			$sql="SELECT * FROM jbsakad.siswa WHERE idkelas='$kelas'  ORDER BY nama";
			$result=QueryDb($sql);
			$cnt=1;
			while ($row=@mysqli_fetch_array($result)){
  ?>
   <tr>
    <th height="25" scope="row"><?=$cnt?></th>
    <td height="25"><input type="checkbox" name="ceknis<?=$cnt?>" id="ceknis<?=$cnt?>"/></td>
    <td height="25">
		<?=$row['nis']?><br>
		<strong><?=$row['nama']?></strong>
		<input type="hidden" name="nis<?=$cnt?>" id="nis<?=$cnt?>" value="<?=$row['nis']?>"/>
		<input type="hidden" name="kirimin<?=$cnt?>" id="kirimin<?=$cnt?>"/></td>
  </tr>
  <?php 
  $cnt++;
  } 
  ?>
</table>
<input type="hidden" name="numsiswa" id="numsiswa" value="<?=$cnt-1?>" />
<input type="hidden" name="numsiswakirim" id="numsiswakirim"/>    

<script language='JavaScript'>
			Tables('table', 1, 0);
</script>
</body>
</html>