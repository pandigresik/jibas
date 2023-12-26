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

$replid = $_REQUEST['replid'];


OpenDb();
$sql_kelas="SELECT k.kelas,k.idtahunajaran,k.idtingkat,t.departemen,t.tahunajaran,k.aktif,i.tingkat FROM jbsakad.kelas k, jbsakad.tahunajaran t, jbsakad.tingkat i WHERE k.replid='$replid' AND t.replid=k.idtahunajaran AND k.idtingkat=i.replid";
$result_kelas=QueryDb($sql_kelas);
if ($row_kelas=@mysqli_fetch_row($result_kelas)){
	$departemen=$row_kelas[3];
	$namatahunajaran=$row_kelas[4];
	$kelas=$row_kelas[0];
	$tingkat = $row_kelas[6];
	
}
CloseDb();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Lihat Daftar Siswa]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center>
  <font size="4"><strong>DAFTAR SISWA	</strong></font><br />
 </center><br /><br />

<table>
<tr>
	<td><strong>Departemen</strong> </td> 
	<td><strong>:&nbsp;<?=$departemen?></strong></td>
</tr>
<tr>
	<td><strong>Tahun Ajaran</strong></td>
	<td><strong>:&nbsp;<?=$namatahunajaran?></strong></td>
</tr>
<tr>
	<td><strong>Kelas</strong></td>
	<td><strong>:&nbsp;<?=$tingkat." - ".$kelas?></strong></td>
</tr>
</table>
<br />
	<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left" bordercolor="#000000">
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="15%" class="header" align="center">NIS</td>
        <td width="*" class="header" align="center">Nama</td>        
        <td width="*" class="header" align="center">Keterangan</td>
        <!--<td width="10%" class="header" align="center">Status</td>-->
    </tr>
<?php 	OpenDb();
	$sql = "SELECT * FROM jbsakad.siswa WHERE idkelas='$replid' AND aktif=1 ORDER BY nama";
	$result = QueryDB($sql);
	$cnt = 0;
	while ($row = mysqli_fetch_array($result)) { ?>
    <tr height="25">    	
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nis'] ?></td>        
        <td><?=$row['nama'] ?></td>
        <td><?=$row['keterangan'] ?></td>
        <!--<td align="center">
			<?php if ($row['aktif'] == 1) 
					echo 'Aktif';
				else
					echo 'Tidak Aktif';
			?>		
        </td>-->
    </tr>
<?php } 
	CloseDb() ?>	
    <!-- END TABLE CONTENT -->
    </table>

</td></tr></table>
<p align="center">&nbsp;<input type="button" class="but" value="Tutup" onclick="Javascript:window.close()" /></p>
</body>
</html>