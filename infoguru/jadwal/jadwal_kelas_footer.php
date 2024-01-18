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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once("../include/sessionchecker.php");

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

$op = $_REQUEST['op'];
if ($op == "xm8r389xemx23xb2378e23") {
	if ($_REQUEST['field']) 
		$filter = 'idkelas';
	else 
		$filter = 'replid';
	
	OpenDb();
	$sql = "DELETE FROM jadwal WHERE $filter = '".$_REQUEST['replid']."'";
	QueryDb($sql);
	CloseDb();
}	
	
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
	       " AND j.departemen = '".$_REQUEST['departemen'].
	       "' AND j.infojadwal = '".$_REQUEST['info']."'".
	       " AND j.nipguru = p.nip ".
	       "AND j.idpelajaran = l.replid";
	
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
			
			$s = "<td class='jadwal' rowspan='{$jadwal['row'][$c][$r]['njam']}' width='110px'>";
			$s.= "<b>{$jadwal['row'][$c][$r]['pelajaran']}</b><br>";
			$s.= "{$jadwal['row'][$c][$r]['guru']}<br><i>{$jadwal['row'][$c][$r]['status']}</i><br>{$jadwal['row'][$c][$r]['ket']}<br>";
			//$s.= "<img src='../images/ico/ubah.png' style='cursor:pointer' ";
			//$s.= " onclick='edit({$jadwal['row'][$c][$r]['id']})'> &nbsp;";
			//$s.= "<img src='../images/ico/hapus.png' style='cursor:pointer' ";
			//$s.= " onclick='hapus({$jadwal['row'][$c][$r]['id']},0)'>";
			$s.= "</td>";
			
			return $s;
		} else {
			$s = "<td class='jadwal' width='110px'>";			
			//$s.= "<img src='../images/ico/tambah.png' style='cursor:pointer' onclick='tambah($r, $c)'>";
			$s.= "['Kosong']";
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

?>

<html>
<head>
<title>Jadwal Kelas</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
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
<script language="javascript" src="../script/tools.js"></script>
<script type="text/javascript" language="javascript" src="../javascript/tables.js"></script>
<script type="text/javascript" language="javascript" src="../javascript/common.js"></script>
<script type="text/javascript" language="javascript">

function tambah(jam, hari) {
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var info = document.getElementById('info').value;
	var maxJam = document.getElementById('maxJam').value;

	newWindow('jadwal_kelas_add.php?departemen='+departemen+'&kelas='+kelas+'&info='+info+'&maxJam='+maxJam+'&jam='+jam+'&hari='+hari, 'TambahJadwalKelas', '500', '460', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(replid, field) {
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var info = document.getElementById('info').value;
	
	if (confirm("Apakah anda yakin akan menghapus jadwal kelas ini?"))
		document.location.href = "jadwal_kelas_footer.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&field="+field+"&departemen="+departemen+"&kelas="+kelas+"&info="+info;
}

function edit(replid) {
	var maxJam = document.getElementById('maxJam').value;
	
	newWindow('jadwal_kelas_edit.php?maxJam='+maxJam+'&replid='+replid, 'UbahJadwalKelas','500','460','resizable=1,scrollbars=0,status=0,toolbar=0')
		
}

function cetak() {
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var info = document.getElementById('info').value;
			
	newWindow('jadwal_kelas_cetak.php?departemen='+departemen+'&kelas='+kelas+'&info='+info, 'CetakJadwalKelas', '800', '650', 'resizable=1,scrollbars=0,status=0,toolbar=0');
}

function refresh() {	
	document.location.reload();
}

</script>
</head>

<body style="background-image:url(../images/ico/b_jadwalkelas.png); background-repeat:no-repeat;">
<form id="reqForm" name="reqForm" method="post">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>">
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>">
<input type="hidden" name="info" id="info" value="<?=$info ?>">
<input type="hidden" name="maxJam" id="maxJam" value="<?=$maxJam ?>">

</form>

<table border="0" width="100%" align="center" style="background-repeat:no-repeat; background-attachment:fixed">
<tr>
	<td>
 <?php OpenDb(); 
	$sql = "SELECT * FROM pelajaran p WHERE p.departemen = '".$departemen."'";	
	$result = QueryDb($sql);
	CloseDb();      
	if (@mysqli_num_rows($result)>0){			
?>
  <strong></strong>
    <table width="95%" border="0" align="center">
  	<tr>
		<td align="right">
        <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
   	 
    	<a href="JavaScript:cetak()">
        <img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
    	</td>
    </tr>
    </table>
    <br>
   <table border="1" width="95%" id="table" class="tab" align="center" cellpadding="2" style="border-collapse:collapse" cellspacing="2" bordercolor="#000000">
    <tr>		
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
			<td class="jam" width="110px"><b><?=++$j ?>.</b> <?=$v[\JAM1] ?> - <?=$v[\JAM2] ?></td>
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
    	<font size = "2" color ="red"><b>Belum ada data Jam Belajar untuk Departemen <?=$departemen?>. 
        <br> Silahkan isi terlebih dahulu di menu Jam Belajar pada bagian Jadwal & Pelajaran.</font>
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
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data jadwal mengajar. <br /><br />Tambah data pelajaran pada departemen <?=$departemen?> dan guru yang akan mengajar<br> di menu Pendataan Guru pada bagian Guru & Pelajaran.</b></font>
		</td>
	</tr>
	</table>
<?php } ?>     
     </td></tr>
<!-- END TABLE CENTER -->    
</table>
</body>

</html>