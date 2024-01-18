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
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

if (isset($_REQUEST['replid']))
	$replid = $_REQUEST['replid'];
	
OpenDb();

$sql = "SELECT *, j.keterangan AS ket, k.kelas, p.nama, t.replid AS tahun, t.tahunajaran 
          FROM jadwal j, jbssdm.pegawai p, jbsakad.tahunajaran t, kelas k 
		 WHERE j.replid = '$replid' AND j.nipguru = p.nip AND k.replid = j.idkelas AND k.idtahunajaran = t.replid";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$pelajaran = $row['idpelajaran'];
$departemen = $row['departemen'];
$info = $row['infojadwal'];
$hari = $row['hari'];
$kelas = $row['idkelas'];
$nipguru = $row['nipguru'];
$tahun = $row['tahunajaran'];
$tahunajaran = $row['tahun'];
$kls = $row['kelas'];
$nama = $row['nama'];
$jam = $row['jamke'];
$jam2 = $row['njam'] + $jam-1;
$jum = $jam2 - $jam + 1;
$status = $row['status'];
$keterangan = $row['ket'];
$jamorig1 = $row['jamke'];
$jamorig2 = $row['njam'] + $jamorig1 - 1;

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['info']))
	$info = $_REQUEST['info'];
if (isset($_REQUEST['maxJam']))
	$maxJam = (int)$_REQUEST['maxJam'];
if (isset($_REQUEST['jam']))
	$jam = $_REQUEST['jam'];
if (isset($_REQUEST['hari']))
	$hari = $_REQUEST['hari'];

if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];	
if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);	
if (isset($_REQUEST['jam2']))
	$jam2 = (int)$_REQUEST['jam2'];	
if (isset($_REQUEST['nip']))
	$nip = $_REQUEST['nip'];		
if (isset($_REQUEST['nipguru']))
	$nipguru = $_REQUEST['nipguru'];		
if (isset($_REQUEST['status']))
	$status = $_REQUEST['status'];	
	
if (strlen((string) $nip) > 0)
{
	$sql = "SELECT nama FROM jbssdm.pegawai WHERE nip='$nip'";	
	$res = QueryDb($sql);
	$row = mysqli_fetch_row($res);
	$namasel = $row[0];
	$nipsel = $nip;
}
else
{	
	$namasel = $nama;
	$nipsel = $nipguru;
}
CloseDb();

$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) 
{		
	OpenDb();
	$sql = "SELECT replid FROM infojadwal WHERE aktif=1";
	$res = QueryDb($sql);
	$num = mysqli_num_rows($res);
	if ($num>0){
		$dayname = ["", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu", "Minggu"];
		
		$sql = "SELECT replid FROM infojadwal WHERE aktif=1";
		$res = QueryDb($sql);
		$idinfo_aktif = "";
		while($row = mysqli_fetch_row($res))
		{
			if (strlen($idinfo_aktif) > 0)
				$idinfo_aktif .= "','";
			$idinfo_aktif .= $row[0];	
		}
		
		$idinfo_aktif = "'".$idinfo_aktif."'";
		
		// -- Cek jadwal guru di kelas yang lain
		$sqljam = "";
		for($i = $jam; $i <= $jam2; $i++)
		{
			if (strlen($sqljam) != 0)
				$sqljam .= " OR ";
				
			if ($i >= $jamorig1 && $i <= $jamorig2)	
				$sqljam .= "('$i' >= jamke AND '$i' <= jamke + njam - 1 AND j.replid <> '$replid')";
			else			
				$sqljam .= "('$i' >= jamke AND '$i' <= jamke + njam - 1)";
		}

		$sql = "SELECT ij.deskripsi, pg.nama, p.nama AS pelajaran, k.kelas, j.departemen, j.hari, j.jamke AS jam1, j.jamke + j.njam - 1 AS jam2
				  FROM infojadwal ij, jadwal j, jbssdm.pegawai pg, pelajaran p, kelas k 
				 WHERE ij.replid = j.infojadwal AND j.nipguru = pg.nip AND j.idpelajaran = p.replid 
				   AND j.idkelas = k.replid AND ij.replid IN ($idinfo_aktif) 
				   AND hari = '$hari' AND nipguru = '$nipsel' 
				   AND ($sqljam)";
		$res = QueryDb($sql);
		
		if (mysqli_num_rows($res) > 0) 
		{
			$ket = "";
			while ($row = mysqli_fetch_array($res))
			{
				if (strlen($ket) > 0)
					$ket .= "\\r\\n";
				$ket .= $row['departemen'] . " " . $row['kelas'] . ", " . $row['deskripsi'] . ", " . $row['nama'] . ", " .  $row['pelajaran'] . ", " . $dayname[$row['hari']] . " jam " . $row['jam1'] . " s/d "  . $row['jam2'];  	
			}
			CloseDb();		
			$ERROR_MSG = "Jadwal guru di kelas lain yang bentrok:\\r\\n$ket";
		} 
		else
		{
			// Cek bentrok di kelas yang sama
			$sqljam = "";
			for($i = $jam; $i <= $jam2; $i++)
			{
				if (strlen($sqljam) != 0)
					$sqljam .= " OR ";
					
				if ($i >= $jamorig1 && $i <= $jamorig2)	
					$sqljam .= "($i >= jamke AND $i <= jamke + njam - 1 AND nipguru <> '$nipsel')";
				else			
					$sqljam .= "($i >= jamke AND $i <= jamke + njam - 1)";
			}
		
			$sql = "SELECT ij.deskripsi, pg.nama, p.nama AS pelajaran, k.kelas, j.departemen, j.hari, j.jamke AS jam1, j.jamke + j.njam - 1 AS jam2
					  FROM infojadwal ij, jadwal j, jbssdm.pegawai pg, pelajaran p, kelas k 
					 WHERE ij.replid = j.infojadwal AND j.nipguru = pg.nip AND j.idpelajaran = p.replid 
					   AND j.idkelas = k.replid AND ij.replid IN ($idinfo_aktif) 
					   AND hari = '$hari' AND idkelas = '$kelas' 
					   AND ($sqljam)";
			$res = QueryDb($sql);
			$ket = "";
			
			if (mysqli_num_rows($res) > 0)
			{
				while ($row = mysqli_fetch_array($res))
				{
					if (strlen($ket) > 0)
						$ket .= "\\r\\n";
					$ket .= $row['departemen'] . " " . $row['kelas'] . ", " . $row['deskripsi'] . ", " . $row['nama'] . ", " .  $row['pelajaran'] . ", " . $dayname[$row['hari']] . " jam " . $row['jam1'] . " s/d "  . $row['jam2'];  	
				}
				CloseDb();		
				$ERROR_MSG = "Jadwal bentrok di kelas yang sama:\\r\\n$ket";
			}
		}
		
		if (strlen($ERROR_MSG) == 0)
		{
			$sql1 = "SELECT replid, TIME_FORMAT(jam1, '%H:%i') AS jam1 FROM jam WHERE departemen = '$departemen' AND jamke = '".$jam."'";	
			$result1 = QueryDb($sql1);
			$row1 = mysqli_fetch_array($result1);
			$rep1 = $row1['replid'];
			$jm1 = $row1['jam1'];
			
			$sql2 = "SELECT replid, TIME_FORMAT(jam2, '%H:%i') AS jam2 FROM jam WHERE departemen = '$departemen' AND jamke = '$jam2'";	
			$result2 = QueryDb($sql2);
			$row2 = mysqli_fetch_array($result2);
			$rep2 = $row2['replid'];
			$jm2 = $row2['jam2'];
			
			$jum = $jam2 - $jam + 1;
		
			$sql = "UPDATE jadwal SET idkelas='$kelas', nipguru='$nip', idpelajaran = '$pelajaran', departemen = '$departemen', 
					infojadwal = '$info', hari = '$hari', jamke = '$jam', njam = '$jum', sifat = 1, status = '$status', keterangan='$keterangan', 
					jam1 = '$jm1', jam2 = '$jm2', idjam1 = '$rep1', idjam2 = '$rep2' WHERE replid = '".$replid."'";
			$res = QueryDb($sql);
			CloseDb();
		
			if ($res) { ?>
				<script language="javascript">
					opener.parent.footer.refresh();
					window.close();
				</script> 
	<?php 	}
		}
	} else {
		$ERROR_MSG = "Tidak ada Info jadwal yang aktif, silakan aktifkan salah satu Info Jadwal\\r\\n";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Ubah Jadwal Kelas]</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function pegawai() {
	var departemen = document.getElementById('departemen').value;
	var pelajaran = document.getElementById('pelajaran').value;
	newWindow('../library/guru.php?flag=0&departemen='+departemen+'&pelajaran='+pelajaran, 'Guru','600','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}


function acceptPegawai(nip, nama, flag, dep, pel) 
{
	document.getElementById('nip').value = nip;
	document.getElementById('nama').value = nama;
}

function showPelajaran(x) {
	document.getElementById("InfoPelajaran").innerHTML = x;
}

function validate() {
	var nip = document.getElementById('nip').value; 	
	var jam2 = document.getElementById('jam2').value; 
	var jam1 = document.getElementById('jam1').value; 
	var maxJam = document.getElementById('maxJam').value; 
	var ket = document.getElementById('keterangan').value; 
	
	if (nip.length == 0) {
		alert("NIP guru harus dimasukkan");
		return false;
	} else if (jam2.length == 0) {
		alert("Jam akhir harus dimasukkan");
		document.getElementById('jam2').focus();
		return false;
	} else if (ket.length > 255) {
		alert("Panjang keterangan tidak boleh dari 255 karakter");		
		document.getElementById('keterangan').focus();
		return false;
	} else if (isNaN(jam2)) {
		alert ('Data isian anak ke harus berupa bilangan');
		document.getElementById('jam2').focus();
		return false;
	} else if (parseInt(jam1) > parseInt(jam2)) {
		alert ('Jam akhir tidak boleh kurang dari jam awal');
		document.getElementById('jam2').focus();
		return false;
	} else if (parseInt(jam2) > parseInt(maxJam)) {
		alert ('Jam akhir tidak boleh lebih dari jumlah jam jadwal kelas');
		document.getElementById('jam2').focus();
		return false;
	}
	return true;
	
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function panggil(elem){
	var lain = new Array('pelajaran','jam2','status','keterangan');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

function changepel()
{
	document.getElementById('nip').value = "";
	document.getElementById('nama').value = "";
}

</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('jam2').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Jadwal Kelas :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
<form name="main" onSubmit="return validate()">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>"/>
<input type="hidden" name="info" id="info" value="<?=$info ?>"/>
<input type="hidden" name="hari" id="hari" value="<?=$hari ?>"/>
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>"/>
<input type="hidden" name="maxJam" id="maxJam" value="<?=$maxJam ?>"/>
<input type="hidden" name="replid" id="replid" value="<?=$replid ?>"/>
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td><strong>Departemen</strong></td>
    <td width="30%"><input type="text" name="dept" id="dept" size="10" value="<?=$departemen ?>" class="disabled" readonly/>
        <input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>"/></td>    
</tr>
<tr>
	<td width="100"><strong>Tahun Ajaran</strong></td>
    <td><input type="text" name="tahun" size="10" value="<?=$tahun ?>" readonly class="disabled"/>
    	<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>">    </td>
       
</tr>
<tr>
	<td width="100"><strong>Kelas</strong></td>
    <td><input type="text" name="kls" size="10" value="<?=$kls ?>" readonly class="disabled"/>
    	<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>">    </td>
       
</tr>
<tr>
	<td align="left"><strong>Pelajaran</strong></td>
 	<td><div id ="InfoPelajaran">
      	<select name="pelajaran" id="pelajaran" onChange="changepel()" onKeyPress="return focusNext('jam2', event)" onFocus="panggil('pelajaran')">
   	<?php OpenDb();
		$sql = "SELECT replid,nama FROM pelajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY nama";
		$result = QueryDb($sql);
		CloseDb();
		while ($row = @mysqli_fetch_array($result)) {
			if ($pelajaran == "") 				
				$pelajaran = $row['replid'];			
		?>
        
    	<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $pelajaran)?> ><?=$row['nama']?></option>
                  
    <?php } ?>
    	</select></div>		</td>  
</tr>
<tr>
	<td><strong>Guru</strong></td>
    <td><strong>
    	<input type="text" name="nip" id="nip" size="10" class="disabled" value="<?=$nipsel ?>" readonly onKeyPress="pegawai();" onFocus="panggil('nip')" onClick="pegawai()"/>
        <input type="hidden" name="nipguru" id="nipguru" value="<?=$nipguru ?>"/>
    	<input type="text" name="nama" id="nama" size="20" class="disabled" value="<?=$namasel ?>" readonly onClick="pegawai()" onFocus="panggil('nama')" />
        <input type="hidden" name="namaguru" id="namaguru" value="<?=$nama ?>"/>
		</strong>
        <a href="JavaScript:pegawai()"><img src="../images/ico/cari.png" border="0" /></a></td>
</tr>
<tr>
	<td><strong>Hari </strong></td>
    <td><strong><input type="text" name="namahari" id ="namahari" size="10" readonly class="disabled" value = "<?=NamaHari($hari)?>" /></strong> 
</tr>
<tr>
	<td><strong>Jam ke</strong></td>
    <td>   
    	<input type="text" name="jam1" id ="jam1" size="2" readonly class="disabled" value = "<?=$jam?>" />
        <input type="hidden" name="jam" id="jam" value="<?=$jam ?>"/> s/d 
    	<input type="text" name="jam2" id ="jam2" size="2" value="<?=$jam2 ?>" onKeyPress="return focusNext('status', event)" onFocus="panggil('jam2')"/>
</tr>
<tr>
	<td><strong>Status</strong></td> 
    <td><select name="status" id="status" onkeypress="return focusNext('keterangan', event)" onFocus="panggil('status')">     
     	<option value=0 <?=IntIsSelected(0, $status)?>>Mengajar</option>
        <option value=1 <?=IntIsSelected(1, $status)?>>Asistensi</option>
        <option value=2 <?=IntIsSelected(2, $status)?>>Tambahan</option>
     	</select>
    </td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td>
    	<textarea name="keterangan" id="keterangan" rows="3" cols="40" onKeyPress="return focusNext('Simpan', event)" onFocus="panggil('keterangan')"><?=$keterangan ?></textarea>
    </td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but" onFocus="panggil('Simpan')"/>&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />
    </td>
</tr>
<!-- END OF TABLE CONTENT -->
</table>
</form>
</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>

<!-- Tamplikan error jika ada -->
<?php 
if (strlen($ERROR_MSG) > 0) 
{ ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

</body>
</html>