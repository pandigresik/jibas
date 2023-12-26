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

$replid = $_REQUEST['replid'];
OpenDb();
$sql_kelas="SELECT k.kelas,k.idtahunajaran,k.idtingkat,t.departemen,t.tahunajaran FROM jbsakad.kelas k, jbsakad.tahunajaran t WHERE k.replid='$replid' AND t.replid=k.idtahunajaran";
$result_kelas=QueryDb($sql_kelas);
if ($row_kelas=@mysqli_fetch_row($result_kelas)){
	$departemen=$row_kelas[3];
	$namatahunajaran=$row_kelas[4];
	$kelas=$row_kelas[0];
}
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cetak Angkatan</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?php include("../library/headercetak.php") ?>

<center>
  <font size="3"><strong>DAFTAR SISWA	</strong></font><br />
 </center><br /><br />

<br />
	<font size="2"><strong>Departemen : <?=$departemen?></strong></font>
<br />
	<font size="2"><strong>Tahun Ajaran : <?=$namatahunajaran?></strong></font>
<br />
	<font size="2"><strong>Kelas : <?=$kelas?></strong></font>
<br /><br />
<br />
	<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left" bordercolor="#000000">
    <tr height="30">
    	<td width="3%" class="header" align="center">No</td>
        <td width="12%" class="header" align="center">NIS</td>
        <td width="30%" class="header" align="center">Nama</td>
        <td width="55%" class="header" align="center">Keterangan</td>
    </tr>
<?php 	OpenDb();
	$sql = "SELECT * FROM jbsakad.siswa WHERE idkelas='$replid' AND aktif=1 ORDER BY nama";
	$result = QueryDB($sql);
	$cnt = 0;
	while ($row = mysqli_fetch_array($result)) { ?>
    <tr height="25">    	
    	<td align="center"><?=++$cnt ?></td>
        <td><?=$row['nis'] ?></td>        
        <td><?=$row['nama'] ?></td>
        <td><?=$row['keterangan'] ?></td>
    </tr>
<?php } 
	CloseDb() ?>	
    <!-- END TABLE CONTENT -->
    </table>

</td></tr></table>
<p align="center">&nbsp;<input type="button" class="but" value="Tutup" onclick="Javascript:window.close()" /></p>
</body>
</html>