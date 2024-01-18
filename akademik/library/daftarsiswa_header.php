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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/sessioninfo.php');
require_once('../include/db_functions.php');
require_once('departemen.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$tahunajaran ="";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
$kelas = "";
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daftar Pegawai</title>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="script/string.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript">
function validate() {
	var nama = '' + document.getElementById('nama').value;
	var nis = '' + document.getElementById('nis').value;
	nama = trim(nama);
	nis = trim(nis);
	return (nama.length != 0) || (nis.length != 0);
}

function change_dep() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var flag = document.getElementById("flag").value;
	
	parent.header.location.href = "daftarsiswa_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&flag="+flag;
	parent.footer.location.href = "../blank_white.php";
}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var tingkat = document.getElementById("tingkat").value;
	var flag = document.getElementById("flag").value;
	
	parent.header.location.href = "daftarsiswa_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&flag="+flag;
	parent.footer.location.href = "../blank_white.php";
}

function change() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;	
	var tingkat = document.getElementById("tingkat").value;
	var kelas = document.getElementById("kelas").value;
	var flag = document.getElementById("flag").value;
	
	parent.header.location.href = "daftarsiswa_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&flag="+flag;	
	parent.footer.location.href = "../blank_white.php";
	
}
function tampil() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;	
	var tingkat = document.getElementById("tingkat").value;
	var kelas = document.getElementById("kelas").value;
	var flag = document.getElementById("flag").value;
	
	if (kelas.length == 0) {	
		alert ('Pastikan kelas sudah ada!');
		return false();		
	}
	
	document.location.href =  "daftarsiswa_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&flag="+flag;		
	parent.footer.location.href = "daftarsiswa_footer.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&flag="+flag;
}	

</script>
</head>

<body topmargin="0" leftmargin="0">
<!--<form name="main" method="post" action="../presensi/presensi_tambah_siswa.php" enctype="multipart/form-data" onsubmit="window.close();">-->
<form name="main" enctype="multipart/form-data">
<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
<table border="0" cellpadding="5" cellspacing="5" width="100%" align="center">
<tr><td align="left">
<!-- BOF CONTENT -->
<font size="2"><strong>Daftar Siswa</strong></font><br />
<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center" background="">
<tr>
	<td width="10%"><strong>Departemen:</strong></td>
    <td width="15%">
    <select name="departemen" id="departemen" onChange="change_dep()">
	<?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
			if ($departemen == "")
				$departemen = $value; ?>
	<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
<?php 	} ?>
	</select>
	</td>
	<td align="left" width="20%"><strong>Tahun Ajaran</strong></td>
   	<td colspan="2">
        <?php  OpenDb();			
			$sql = "SELECT replid,tahunajaran FROM tahunajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY replid DESC";
			$result = QueryDb($sql);
			CloseDb();
			$row = @mysqli_fetch_array($result);	
			$tahunajaran = $row['replid'];				
		?>
        <input type="text" name="tahun" id="tahun" size="15" readonly style="background-color:#CCCCCC" value="<?=$row['tahunajaran']?>" />
        <input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$row['replid']?>">
        <!--<input type="hidden" name="ajaran" id="ajaran" value="<?=$row['tahunajaran']?>">-->
     </td>      
</tr>
<tr>
	<td><strong>Tingkat </strong></td>
    <td>
    	<select name="tingkat" id="tingkat" onchange="change_tingkat()">
     	<?php 	OpenDb();
			$sql = "SELECT replid,tingkat FROM tingkat WHERE aktif=1 AND departemen='$departemen' ORDER BY urutan";	
			$result = QueryDb($sql);
			CloseDb();
	
			while($row = mysqli_fetch_array($result)) {
			if ($tingkat == "")
				$tingkat = $row['replid'];				
			?>
          <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tingkat) ?>>
            <?=$row['tingkat']?>
            </option>
          <?php
			} //while
			?>
        </select>
	</td>
    <td><strong>Kelas </strong></td>
    <td width="20%">
        <select name="kelas" id="kelas" onchange="change()">
			<?php OpenDb();
			$sql = "SELECT replid,kelas FROM kelas WHERE aktif=1 AND idtahunajaran = '$tahunajaran' AND idtingkat = '$tingkat' ORDER BY kelas";	
			$result = QueryDb($sql);
			CloseDb();
	
			while($row = mysqli_fetch_array($result)) {
			if ($kelas == "")
				$kelas = $row['replid'];				 
			?>
    	<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelas) ?>><?=$row['kelas']?></option>
             
    		<?php
			} //while
			?>
    	</select>        </td>
  	<td><input type="submit" name="cari" value="Cari" class="but" onClick="tampil()"/>
    	<input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="parent.tutup()" />
    </td>
</tr>
</table>

<!-- EOF CONTENT -->
</td></tr>
</table>
</form>
<script language="javascript">
	Tables('table', 1, 0);
</script>
</body>
</html>