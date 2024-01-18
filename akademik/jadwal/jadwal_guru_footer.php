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
require_once('../cek.php');

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

$op = $_REQUEST['op'];
if ($op == "xm8r389xemx23xb2378e23")
{
	if ($_REQUEST['field']) 
		$filter = 'nipguru';
	else 
		$filter = 'replid';
	
	$sql = "DELETE FROM jadwal WHERE $filter = '".$_REQUEST['replid']."' ";
	QueryDb($sql);
}	

function loadJam($id)
{	
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

function loadJadwal()
{		
	$sql = "SELECT j.replid AS id, j.hari AS hari, j.jamke AS jam, j.njam AS njam, j.keterangan AS ket, ".
	       "l.nama AS pelajaran, k.kelas, ".
	       "CASE j.status WHEN 0 THEN 'Mengajar' WHEN 1 THEN 'Asistensi' WHEN 2 THEN 'Tambahan' END AS status ".
	       "FROM jadwal j, pelajaran l, kelas k ".
	       "WHERE j.nipguru = '".$_REQUEST['nip'].
	       "' AND j.departemen = '".$_REQUEST['departemen'].
	       "' AND j.infojadwal = ".$_REQUEST['info'].
	       " AND j.idkelas = k.replid ".
	       "AND j.idpelajaran = l.replid";
	
	$result = QueryDb($sql);
	
	while($row = mysqli_fetch_assoc($result))
	{
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['id'] = $row['id'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['njam'] = $row['njam'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['pelajaran'] = $row['pelajaran'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['kelas'] = $row['kelas'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['status'] = $row['status'];
		$GLOBALS['jadwal']['row'][$row['hari']][$row['jam']]['ket'] = $row['ket'];
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
			$mask[$c] = $jadwal['row'][$c][$r]['njam'] - 1;
			
			$s = "<td class='jadwal' rowspan='{$jadwal['row'][$c][$r]['njam']}' width='110px'>";
			$s.= "{$jadwal['row'][$c][$r]['kelas']}<br>";
			$s.= "<b>{$jadwal['row'][$c][$r]['pelajaran']}</b><br>";
			$s.= "<i>{$jadwal['row'][$c][$r]['status']}</i><br>{$jadwal['row'][$c][$r]['ket']}<br>";
			$s.= "<img src='../images/ico/ubah.png' style='cursor:pointer' ";
			$s.= " onclick='edit({$jadwal['row'][$c][$r]['id']})' onMouseOver='showhint(\"Ubah Jadwal!\", this, event, \"90px\")'> &nbsp;";
			$s.= "<img src='../images/ico/hapus.png' style='cursor:pointer' ";
			$s.= " onclick='hapus({$jadwal['row'][$c][$r]['id']},0)' onMouseOver='showhint(\"Hapus Jadwal!\", this, event, \"90px\")'>";
			$s.= "</td>";
			
			return $s;
		}
		else
		{
			$s = "<td class='jadwal' width='110px'>";			
			$s.= "<img src='../images/ico/tambah.png' style='cursor:pointer' onclick='tambah($r, $c)' ";
			$s.= "onMouseOver='showhint(\"Tambah Jadwal!\", this, event, \"100px\")'>";
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
	$mask['i'] = 0;
}

loadJam($departemen);
loadJadwal();

?>

<html>
<head>
<title>Jadwal Guru</title>
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
<script language="javascript" src="../script/tooltips.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script type="text/javascript" language="javascript" src="../javascript/tables.js"></script>
<script type="text/javascript" language="javascript" src="../javascript/common.js"></script>
<script type="text/javascript" language="javascript">

function tambah(jam, hari) {		
	var nip = document.getElementById('nip').value;
	var info = document.getElementById('info').value;
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var maxJam = document.getElementById('maxJam').value;
			
	newWindow('jadwal_guru_add.php?departemen='+departemen+'&tahunajaran='+tahunajaran+'&nip='+nip+'&info='+info+'&maxJam='+maxJam+'&jam='+jam+'&hari='+hari, 'TambahJadwalGuru', '500', '480', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(replid, field) {
	var departemen = document.getElementById('departemen').value;
	var nip = document.getElementById('nip').value;
	var info = document.getElementById('info').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	
	if (confirm("Apakah anda yakin akan menghapus jadwal kelas ini?"))
		document.location.href = "jadwal_guru_footer.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&field="+field+"&nip="+nip+"&info="+info+"&departemen="+departemen+"&tahunajaran="+tahunajaran;
}

function edit(replid) {
	var maxJam = document.getElementById('maxJam').value;	
	newWindow('jadwal_guru_edit.php?maxJam='+maxJam+'&replid='+replid, 'UbahJadwalGuru','500','480','resizable=1,scrollbars=1,status=0,toolbar=0')
		
}

function cetak() {
	var departemen = document.getElementById('departemen').value;
	var nip = document.getElementById('nip').value;
	var info = document.getElementById('info').value;
	
	newWindow('jadwal_guru_cetak.php?nip='+nip+'&info='+info+'&departemen='+departemen, 'CetakJadwalGuru','790', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refresh() {	
	document.location.reload();
}

</script>
</head>

<body topmargin="0" leftmargin="0">
<form id="reqForm" name="reqForm" method="post">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>">
<input type="hidden" name="nip" id="nip" value="<?=$nip ?>">
<input type="hidden" name="info" id="info" value="<?=$info ?>">
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran ?>">
<input type="hidden" name="maxJam" id="maxJam" value="<?=$maxJam ?>">

</form>

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td> 
<?php OpenDb(); 
	$sql = "SELECT * FROM pelajaran p, guru g WHERE g.nip = '$nip' AND p.departemen = '$departemen' AND g.idpelajaran = p.replid";	
	
	$result = QueryDb($sql);
	CloseDb();      
	if (@mysqli_num_rows($result)>0){			
?>
    <table width="100%" border="0" align="center">
  	<tr>
		<td align="right">
       <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
   	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
	    <a title="Hapus" href="JavaScript:hapus('<?=$nip ?>', 1)">
        <img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Jadwal!', this, event, '90px')"/>&nbsp;Hapus Jadwal Guru Ini</a>&nbsp;&nbsp;
	<?php } ?>  
    	<a href="JavaScript:cetak()">
        <img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
    	</td>
    </tr>
    </table>
    <br>
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
        <td class="jam" width="110px"><b><?=++$j ?>.</b> <?=$v['jam1'] ?> - <?=$v['jam2'] ?></td>
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
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data jadwal mengajar. <br /><br />Tambah data pelajaran yang akan diajar oleh guru <?=$_REQUEST['nama']?> pada departemen <?=$departemen?><br> di menu Pendataan Guru pada bagian Guru & Pelajaran.</b></font>
		</td>
	</tr>
	</table> 
<?php } ?>     
     </td></tr>
<!-- END TABLE CENTER -->    
</table>
</body>

</html>