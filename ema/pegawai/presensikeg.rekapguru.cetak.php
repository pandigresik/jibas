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
require_once('../inc/errorhandler.php');
require_once('../inc/sessioninfo.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../lib/departemen.php');
require_once('../lib/datearith.php');
require_once('../inc/getheader.php');

OpenDb();

$nip = $_REQUEST['nip'];
$bulan = $_REQUEST['bulan'];
$tahun = $_REQUEST['tahun'];

$departemen = "yayasan";

$sql = "SELECT nama
          FROM jbssdm.pegawai
         WHERE nip = '".$nip."'";   
$res = QueryDB($sql);	
$row = mysqli_fetch_row($res);
$nama = $row[0];

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Rekapitulasi Presensi Kegiatan Pegawai]</title>
</head>

<body>
    
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
		<?php getHeader($departemen) ?>
		<center>
			<font size="4"><strong>REKAPITULASI PRESENSI KEGIATAN PEGAWAI</strong></font><br />
		</center>
		<br /><br />
	</td>
</tr>	
<tr>
	<td width='60'><strong>Nama</strong></td>
    <td><strong>: <?= $nip . ' - ' . $nama ?></strong></td>
</tr>
<tr>
	<td align="left" valign="top" colspan="2">

<?php
    $showbutton = false;
    require_once("presensikeg.rekapguru.report.func.php");
?>            
    </td>
</tr>	        
</table>

</body>
</html>
<?php
CloseDb();
?>
<script>
    window.print();
</script>