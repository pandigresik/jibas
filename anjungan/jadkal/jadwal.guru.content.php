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
require_once('../include/db_functions.php');

OpenDb();

$kelompokJam = NULL;
$jam = NULL;
$jadwal = NULL;

if (isset($_REQUEST['info']))
	$info = $_REQUEST['info'];
if (isset($_REQUEST['nip']))
	$nip = $_REQUEST['nip'];
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];	

function loadJam($id)
{	
	$sql = "SELECT jamke, TIME_FORMAT(jam1, '%H:%i'), TIME_FORMAT(jam2, '%H:%i') ".
	       "FROM jbsakad.jam WHERE departemen = '$id' ORDER BY jamke";
	
	$result = QueryDb($sql);
	$GLOBALS['maxJam'] = mysqli_num_rows($result);
	
	while($row = mysqli_fetch_array($result)) {
		$GLOBALS['jam']['row'][$row[0]][jam1] = $row[1];
		$GLOBALS['jam']['row'][$row[0]][jam2] = $row[2];
	}
	return true;
}

function loadJadwal()
{		
	$sql = "SELECT j.replid AS id, j.hari AS hari, j.jamke AS jam, j.njam AS njam, j.keterangan AS ket, ".
	       "l.nama AS pelajaran, k.kelas, ".
	       "CASE j.status WHEN 0 THEN 'Mengajar' WHEN 1 THEN 'Asistensi' WHEN 2 THEN 'Tambahan' END AS status ".
	       "FROM jbsakad.jadwal j, jbsakad.pelajaran l, jbsakad.kelas k ".
	       "WHERE j.nipguru = '".$_REQUEST['nip'].
	       "' AND j.departemen = '".$_REQUEST['departemen'].
	       "' AND j.infojadwal = ".$_REQUEST['info'].
	       " AND j.idkelas = k.replid ".
	       "AND j.idpelajaran = l.replid";
	
	$result = QueryDb($sql);
	
	while($row = mysqli_fetch_assoc($result))
	{
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']][id] = $row['id'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']][njam] = $row['njam'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']][pelajaran] = $row['pelajaran'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']][kelas] = $row['kelas'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']][status] = $row['status'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']][ket] = $row['ket'];
	}
	return true;
}

function getCell($r, $c)
{
	global $mask, $jadwal;
	
	if($mask[$c] == 0)
	{
		if(isset($jadwal['row'][$c][$r]))
		{
			$mask[$c] = $jadwal['row'][$c][$r][njam] - 1;
			
			$s = "<td class='jadwal' rowspan='{$jadwal['row'][$c][$r][njam]}' width='110px'>";
			$s.= "{$jadwal['row'][$c][$r][kelas]}<br>";
			$s.= "<b>{$jadwal['row'][$c][$r][pelajaran]}</b><br>";
			$s.= "<i>{$jadwal['row'][$c][$r][status]}</i><br>{$jadwal['row'][$c][$r][ket]}<br>";
			$s.= "</td>";
			
			return $s;
		}
		else
		{
			$s = "<td class='jadwal' width='110px'>";			
			$s.= " ";
			$s.= "</td>";

			return $s;
		}
	}
	else
	{
		--$mask[$c];
	}
}

$mask = NULL;
for($i = 1; $i <= 7; $i++)
{
	$mask[i] = 0;
}

loadJam($departemen);
loadJadwal();

?>
<style>
	.jadwal {
		border: 1px solid black;
		text-align: center;
		vertical-align: middle;
	}

	.jam {
		border: 1px solid black;
		height: 90px;
		background-color: #A0A0A0;
		text-align: center;
		vertical-align: middle;
	}
</style>
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td> 
<?php OpenDb(); 
	$sql = "SELECT * FROM jbsakad.pelajaran p, jbsakad.guru g WHERE g.nip = '$nip' AND p.departemen = '$departemen' AND g.idpelajaran = p.replid";	
	
	$result = QueryDb($sql);
	CloseDb();      
	if (@mysqli_num_rows($result)>0){			
?>
    <table border="1" width="100%" id="table" class="tab" align="center" style="border-collapse:collapse">
    <tr height="30">
        <td width="110px" class="header" align="center">Jam</td>
        <td width="110px" class="header" align="center">Senin</td>
        <td width="110px" class="header" align="center">Selasa</td>
        <td width="110px" class="header" align="center">Rabu</td>
        <td width="110px" class="header" align="center">Kamis</td>
        <td width="110px" class="header" align="center">Jumat</td>
        <td width="110px" class="header" align="center">Sabtu</td>
        <td width="110px" class="header" align="center">Minggu</td>
    </tr>
	<?php
	
		if(isset($jam['row'])) {
			
			foreach($jam['row'] as $k => $v) {
	?> 
    <tr>
        <td class="jam" width="110px"><b><?=++$j ?>.</b> <?=$v[jam1] ?> - <?=$v[jam2] ?></td>
        <?php for($i = 1; $i <= 7; $i++) {?> 
        <?=getCell($k, $i); ?> 
        <?php }?>  
    </tr>
        
	<?php } ?> 	
	
    <!-- END TABLE CONTENT -->
    </table>
   
	
<?php 		} else { ?> 
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Belum ada data Jam Belajar untuk Departemen <?=$departemen?>. </font>
		</td>
	</tr>
	</table> 
<?php
		}
	} else {
	
?> 	
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data jadwal mengajar. <br /><br /></b></font>
		</td>
	</tr>
	</table> 
<?php } ?>     
     </td></tr>
<!-- END TABLE CENTER -->    
</table>