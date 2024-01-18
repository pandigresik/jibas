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
require_once('../../include/sessioninfo.php');
require_once('../../include/common.php');
require_once('../../include/config.php');
require_once('../../include/getheader.php');
require_once('../../include/db_functions.php');

OpenDb();

$kelompokJam = NULL;
$jam = NULL;
$jadwal = NULL;

if (isset($_REQUEST['info']))
	$info = $_REQUEST['info'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
OpenDb();	
function loadJam($id) {	
	$sql = "SELECT jamke, TIME_FORMAT(jam1, '%H:%i'), TIME_FORMAT(jam2, '%H:%i') ".
	       "FROM jam WHERE departemen = '$id' ORDER BY jamke";
	
	$result = QueryDb($sql);
	$GLOBALS['maxJam'] = mysqli_num_rows($result);
	
	while($row = mysqli_fetch_array($result)) {
		$GLOBALS['jam']['row'][$row[0]][\JAM1] = $row[1];
		$GLOBALS['jam']['row'][$row[0]][\JAM2] = $row[2];
	}
	return true;
}

function loadJadwal() {	
	$sql = "SELECT j.replid AS id, j.hari AS hari, j.jamke AS jam, j.njam AS njam, j.keterangan AS ket, ".
	       "l.nama AS pelajaran, p.nama AS guru, ".
	       "CASE j.status WHEN 0 THEN 'Mengajar' WHEN 1 THEN 'Asistensi' WHEN 2 THEN 'Tambahan' END AS status ".
	       "FROM jadwal j, pelajaran l, jbssdm.pegawai p ".
	       "WHERE j.idkelas = '".$_REQUEST['kelas']."'".
	       " AND j.departemen = '".$_REQUEST['departemen']."'".
	       " AND j.infojadwal = '".$_REQUEST['info']."'".
	       " AND j.nipguru = p.nip ".
	       " AND j.idpelajaran = l.replid";
	
	$result = QueryDb($sql);
	
	while($row = mysqli_fetch_assoc($result)) {
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['id'] = $row['id'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['njam'] = $row['njam'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['pelajaran'] = $row['pelajaran'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['guru'] = $row['guru'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['status'] = $row['status'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['ket'] = $row['ket'];
	}
	return true;
}

function getCell($r, $c) {
	global $mask, $jadwal;
	if($mask[$c] == 0) {
		if(isset($jadwal['row'][$c][$r])) {
			$mask[$c] = $jadwal['row'][$c][$r]['njam'] - 1;
			
			$s = "<td class='jadwal' rowspan='{$jadwal['row'][$c][$r]['njam']}' width='95px'>";
			$s.= "<b>{$jadwal['row'][$c][$r]['pelajaran']}</b><br>";
			$s.= "{$jadwal['row'][$c][$r]['guru']}<br><i>{$jadwal['row'][$c][$r]['status']}</i><br>{$jadwal['row'][$c][$r]['ket']}<br>";
			$s.= "</td>";
			
			return $s;
		} else {
			$s = "<td class='jadwal' width='110px'>";			
			$s.= "</td>";

			return $s;
		}
	} else {
		--$mask[$c];
	}
}

$mask = NULL;
for($i = 1; $i <= 7; $i++) {
	$mask['i'] = 0;
}


loadJam($departemen);
loadJadwal();

$sql = "SELECT DISTINCT i.deskripsi, k.kelas, t.tahunajaran, t.departemen FROM jadwal j, infojadwal i, kelas k, tahunajaran t WHERE j.idkelas = k.replid AND j.infojadwal = i.replid AND j.departemen = '$departemen' AND j.infojadwal = '$info' AND j.idkelas = '$kelas' AND k.idtahunajaran = t.replid";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);

?>

<html>
<head>
<title>JIBAS SIMAKA [Cetak Jadwal Kelas]</title>
<link rel="stylesheet" type="text/css" href="../../style/style.css">
<style>
	.jadwal {
		border: 1px solid black;
		text-align: center;
		vertical-align: middle;
	}

	.jam {
		border: 1px solid black;
		height: 50px;
		background-color: #A0A0A0;
		text-align: center;
		vertical-align: middle;
	}
.style2 {color: #000000}
</style>
<!--<script language="javascript" src="../script/tools.js"></script>
<script type="text/javascript" language="javascript" src="../javascript/tables.js"></script>
<script type="text/javascript" language="javascript" src="../javascript/common.js"></script>
<script type="text/javascript" language="javascript">-->
</script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">
  	<?=getHeader($row['departemen'])?>
<center>
  <font size="4"><strong>JADWAL KELAS</strong></font><br />
 </center><br /><br />

<table>
<tr>
	<td><strong>Departemen</strong></td>
    <td><strong>: <?=$departemen?></strong></td>
</tr>
<tr>
	<td><strong>Tahun Ajaran</strong></td>
    <td><strong>: <?=$row['tahunajaran']?></strong></td>
</tr>
<tr>
	<td><strong>Kelas</strong></td>
    <td><strong>: <?=$row['kelas'] ?></strong></td>
</tr>
<tr>
	<td><strong>Info Jadwal</strong></td>
    <td><strong>: <?=$row['deskripsi']?></strong></td>
</tr>
</table>
<br>
<table border="1" width="100%" id="table" class="tab" align="center" cellpadding="2" style="border-collapse:collapse" cellspacing="2">
<tr>
    <td width="110px" align="center" bgcolor="#A0A0A0" class="style2 header"><strong>Jam</strong></td>
    <td width="95px" align="center" bgcolor="#A0A0A0" class="style2 header"><strong>Senin</strong></td>
    <td width="95px" align="center" bgcolor="#A0A0A0" class="style2 header"><strong>Selasa</strong></td>
    <td width="95px" align="center" bgcolor="#A0A0A0" class="style2 header"><strong>Rabu</strong></td>
    <td width="95px" align="center" bgcolor="#A0A0A0" class="style2 header"><strong>Kamis</strong></td>
    <td width="95px" align="center" bgcolor="#A0A0A0" class="style2 header"><strong>Jumat</strong></td>
    <td width="95px" align="center" bgcolor="#A0A0A0" class="style2 header"><strong>Sabtu</strong></td>
    <td width="95px" align="center" bgcolor="#A0A0A0" class="style2 header"><strong>Minggu</strong></td>
</tr>
<?php

if(isset($jam['row'])) {
	
	foreach($jam['row'] as $k => $v) {
	?> 
	<tr>
		<td class="jam" width="110px"><b><?=++$j ?>.</b> <?=$v[\JAM1] ?> - <?=$v[\JAM2] ?></td>
		<?php for($i = 1; $i <= 7; $i++) {?> 
		<?=getCell($k, $i); ?> 
		<?php }?>  
	</tr>
	<?php } ?>
	</table>
<?php } ?> 
	</td>
<tr>
</table>
</body>

</html>
<script language='javascript'>
	window.print();
</script>