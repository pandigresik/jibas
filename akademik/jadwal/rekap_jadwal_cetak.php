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
//require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php');

$info = $_REQUEST['info'];
OpenDb();
$sql="SELECT i.deskripsi, j.departemen, t.tahunajaran, t.tglmulai, t.tglakhir FROM infojadwal i, jadwal j, tahunajaran t WHERE i.replid=$info AND j.infojadwal = i.replid AND t.replid = i.idtahunajaran";

$result=QueryDb($sql);
CloseDb();
$row=@mysqli_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Rekap Jadwal Guru]</title>
</head>

<body>

<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?=getHeader($row['departemen'])?>
	
<center>
  <font size="4"><strong>REKAP JADWAL GURU</strong></font><br />
 </center><br /><br />

<br />
<table>
<tr>
	<td width="35%"><strong>Departemen</strong></td>
    <td><strong>: <?=$row['departemen']?></strong></td>
</tr>
<tr>
	<td><strong>Tahun Ajaran</strong></td>
    <td><strong>: <?=$row['tahunajaran']?></strong></td>
</tr>
<tr>
	<td width="10%"><strong>Info Jadwal</strong></td>
    <td><strong>: <?=$row['deskripsi']?></strong></td>
</tr>
<tr>
	<td><strong>Periode</strong></td>
    <td><strong>: <?=format_tgl($row['tglmulai']).' s/d '. format_tgl($row['tglakhir'])?></strong></td>
</tr>
</table>
<br />
<table border="1" width="100%" id="table" class="tab" align="center" style="border-collapse:collapse" bordercolor="#000000" />
<tr height="15">
    <td width="4%" rowspan="2 "class="header" align="center">No</td>
    <td width="10%"rowspan="2" class="header" align="center">NIP</td>
    <td width="*"rowspan="2" class="header" align="center">Nama</td>
    <td colspan="6" width="60%" class="header" align="center">Jumlah</td>
</tr>
<tr height="15">
    <td width="8%" class="header" align="center">Mengajar</td>
    <td width="8%" class="header" align="center">Asistensi</td>
    <td width="8%" class="header" align="center">Tambahan</td>
    <td width="8%" class="header" align="center">Jam</td>
    <td width="8%" class="header" align="center">Kelas</td>
    <td width="8%" class="header" align="center">Hari</td>
</tr>
<?php 	OpenDb();
    
    $sql = "SELECT p.nip, p.nama, SUM(IF(j.status = 0, 1, 0)), SUM(IF(j.status = 1, 1, 0)), SUM(IF(j.status = 2, 1, 0)), SUM(j.njam), COUNT(DISTINCT(j.idkelas)), COUNT(DISTINCT(j.hari)) FROM jadwal j, jbssdm.pegawai p WHERE j.nipguru = p.nip AND j.infojadwal = '$info' GROUP BY j.nipguru ORDER BY p.nama";
    
    $result = QueryDb($sql);
    $cnt = 0;
    while ($row = mysqli_fetch_row($result)) {
?>
<tr height="25">
    <td align="center"><?=++$cnt?></td>
    <td align="center"><?=$row[0]?></td>        
    <td><?=$row[1]?></td>        
    <td align="center"><?=$row[2]?></td>        
    <td align="center"><?=$row[3]?></td>        
    <td align="center"><?=$row[4]?></td>        
    <td align="center"><?=$row[5]?></td>        
    <td align="center"><?=$row[6]?></td> 
    <td align="center"><?=$row[7]?></td>        
</tr>
<?php } 
CloseDb() ?>	
<!-- END TABLE CONTENT -->
</table>

</td></tr>
</table>
</body>
<script language="javascript">
window.print();
</script>
</html>