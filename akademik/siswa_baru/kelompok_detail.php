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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php');

//echo $_REQUEST['departemen'];
$departemen = $_REQUEST['departemen'];
$proses = $_REQUEST['proses'];
$replid = $_REQUEST['replid'];

OpenDb();
$sql = "SELECT proses, kelompok FROM prosespenerimaansiswa p, kelompokcalonsiswa k WHERE p.replid = '$proses' AND k.replid = '$replid' AND k.idproses = p.replid";
$result=QueryDb($sql);
$row = @mysqli_fetch_array($result);

CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daftar Calon Siswa</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>
<?php OpenDb(); ?>
<center>
  <font size="4"><strong>DAFTAR CALON SISWA</strong></font><br />
 </center><br /><br />
<table>
<tr>
	<td><strong>Departemen</strong> </td> 
	<td><strong>:&nbsp;<?=$departemen?></strong></td>
</tr>
<tr>
	<td><strong>Penerimaan</strong></td>
	<td><strong>:&nbsp;<?=$row['proses']?></strong></td>
</tr>
<tr>
	<td><strong>Kelompok</strong></td>
	<td><strong>:&nbsp;<?=$row['kelompok']?></strong></td>
</tr>

</table>
    <br />
	<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="0" width="100%" align="left">
   <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="15%" class="header" align="center">No Pendaftaran</td>
        <td width="30%" class="header" align="center">Nama</td>
        <td width="*" class="header" align="center">Keterangan</td>
    </tr>
<?php 	
	OpenDb();
    $sql = "SELECT nopendaftaran, nama, keterangan FROM calonsiswa WHERE idkelompok = '$replid' ";  
	$result = QueryDB($sql);
	$cnt = 0;
	while ($row = @mysqli_fetch_array($result)) { ?>
    <tr height="25">    	
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nopendaftaran'] ?></td>        
        <td><?=$row['nama'] ?></td>
        <td><?=$row['keterangan']?></td>
<?php }
	CloseDb(); ?>
    <!-- END TABLE CONTENT -->
    </table>
	</td>
</tr>
<tr>
	<td align="center">
 	<input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />
    </td>
</tr>
</table>	
</body>
</html>