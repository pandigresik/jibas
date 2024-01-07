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
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$idangkatan = 0;
if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];

$idkelas = 0;
if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/rupiah.js"></script>
<script language="javascript">
function change_dep() {
	var dep = document.getElementById('departemen').value;
	document.location.href = "dsp2.php?departemen=" + dep;
}

function change_ang() {
	var dep = document.getElementById('departemen').value;
	var idang = document.getElementById('idangkatan').value;
	
	document.location.href = "dsp2.php?departemen=" + dep + "&idangkatan=" + idang;
}

function change_kel() {
	var dep = document.getElementById('departemen').value;
	var idang = document.getElementById('idangkatan').value;
	var idkel = document.getElementById('idkelas').value;
	
	document.location.href = "dsp2.php?departemen=" + dep + "&idangkatan=" + idang + "&idkelas=" + idkel;
}

function manageLock(no) {
	var check = document.getElementById('ch' + no).checked;
	document.getElementById('dsp' + no).disabled = !check;
	document.getElementById('ket' + no).disabled = !check;
}
</script>
</head>

<body background="images/bkmain.png">
<table border="0" cellpadding="10" cellspacing="10" width="80%" align="center">
<tr><td align="left">
<!-- BOF CONTENT -->

	<font size="5" color="#660000"><b>Pendataan DSP</b></font><br />
	<a href="index.php" style="color:#0000FF">Menu Utama</a> > <strong>Pendataan DSP</strong><br /><br />
    
    <a href="#" onclick="document.location.reload();">Refresh</a><br />

	<?php  OpenDb(); ?>
	<table border="0" cellspacing="3" cellpadding="0" width="80%">
	<tr>
    	<td width="100">Departemen:</td>
        <td>
        
        <select id="departemen" name="departemen" style="width:120px" onchange="change_dep()">
        	<?php 
			$sql = "SELECT departemen FROM jbsakad.departemen ORDER BY kode";
			$result = QueryDb($sql);
			while($row = mysqli_fetch_row($result)) {
				if ($departemen == "")
					$departemen = $row[0];
			?>
            	<option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $departemen)?> > <?=$row[0]?></option>
            <?php
			}
			?>
        </select>
        </td>
    </tr>    
    <tr>
    	<td width="100">Angkatan:</td>
        <td>
        <select id="idangkatan" name="idangkatan" style="width:150px" onchange="change_ang()">
        	<?php 
			$sql = "SELECT replid, angkatan FROM jbsakad.angkatan WHERE departemen = '$departemen' ORDER BY replid";
			$result = QueryDb($sql);
			while($row = mysqli_fetch_row($result)) {
				if ($idangkatan == 0)
					$idangkatan = $row[0];
			?>
            	<option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $idangkatan)?> > <?=$row[1]?></option>
            <?php
			}
			?>
        </select>
        </td>
    </tr>    
    <tr>
    	<td width="100">Kelas:</td>
        <td>
        <select id="idkelas" name="idkelas" style="width:150px" onchange="change_kel()">
        	<?php 
			$sql = "SELECT DISTINCT idkelas, kelas FROM jbsakad.siswa, jbsakad.kelas WHERE jbsakad.siswa.idkelas = jbsakad.kelas.replid AND idangkatan='$idangkatan' ORDER BY idkelas";
			$result = QueryDb($sql);
			while($row = mysqli_fetch_row($result)) {
				if ($idkelas == 0)
					$idkelas = $row[0];
			?>
            	<option value="<?=$row[0]?>" <?=IntIsSelected($row[0], $idkelas)?> > <?=$row[1]?></option>
            <?php
			}
			?>
        </select>
        </td>
    </tr>    
    </table>
    <br /><br />
    <table border="0" id="table" class="tab" cellpadding="2" cellspacing="0" width="100%">
    <tr height="30">
    	<td class="header" width="5%" align="center">No</td>
        <td class="header" width="10%" align="center">NIS</td>
        <td class="header" width="25%">Nama</td>
        <td class="header" width="6%">&nbsp;</td>
        <td class="header" width="15%">Biaya DSP</td>
        <td class="header" width="40%">Keterangan</td>
    </tr>
    <?php 
	$sql = "SELECT nis, nama FROM jbsakad.siswa WHERE idkelas = '$idkelas' ORDER BY nama";
	$result = QueryDb($sql);
	$no = 0;
	while ($row = mysqli_fetch_array($result)) {
		$sql = "SELECT replid AS id, dsp, keterangan FROM jbsfina.datadsp WHERE nis = '".$row[0]."'";
		$result2 = QueryDb($sql);
		$ndsp = mysqli_num_rows($result2);
		$iddsp = 0;
		$dsp = "";
		$ket = "";
		$status = "";
		$isnew = 1;
		if ($ndsp > 0) {
			$isnew = 0;
			$status = "disabled";
			$row2 = mysqli_fetch_row($result2);
			$iddsp = $row2[0];
			$dsp = $row2[1];
			$ket = $row2[2];
		}
	?>
    <input type="hidden" name="isnew<?=$no?>" id="isnew<?=$no?>" value="<?=$isnew ?>" />
    <tr height="25">
    	<td align="center"><?=++$no ?></td>
        <td align="center"><?=$row['nis'] ?></td>
        <td><?=$row['nama'] ?></td>
        <td align="center">
        <?php if ($ndsp > 0) { ?>
	          <input type="checkbox" name="ch<?=$no?>" id="ch<?=$no?>" onchange="manageLock(<?=$no?>)" />&nbsp;edit
        <?php } ?>
        </td>
        <td align="center"><input type="text" name="dsp<?=$no?>" id="dsp<?=$no?>" size="15" value="<?=FormatRupiah($dsp) ?>" <?=$status ?> onblur="formatRupiah('dsp<?=$no?>')" onfocus="unformatRupiah('dsp<?=$no?>')"/></td>
        <td><input type="text" name="ket<?=$no?>" id="ket<?=$no?>" size="50" value="<?=$ket ?>" <?=$status ?> /></td>
    </tr>
    <?php
	}
	?>
    <tr height="30">
    	<td colspan="6" align="right" bgcolor="#999900">
        <input type="hidden" name="ndata" id="ndata" value="<?=$no?>" />
        <input type="button" value="Simpan" name="Simpan" id="Simpan" class="but" />
        </td>
    </tr>
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
    <?php  CloseDb() ?>
<!-- EOF CONTENT -->
</td></tr>
</table>
</body>
</html>