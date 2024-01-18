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
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

if (isset($_REQUEST['replid']))
	$replid = $_REQUEST['replid'];

OpenDb();

$sql = "SELECT *, j.keterangan AS keterangan, idtahunajaran, idtingkat, t.tahunajaran, p.nama AS guru
		FROM jadwal j, kelas k, tahunajaran t, jbssdm.pegawai p
		WHERE j.replid = '$replid' AND j.idkelas = k.replid AND k.idtahunajaran = t.replid AND p.nip = j.nipguru";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$pelajaran = $row['idpelajaran'];
$departemen = $row['departemen'];
$tahunajaran = $row['idtahunajaran'];
$tahun = $row['tahunajaran'];
$tingkat = $row['idtingkat'];
$info = $row['infojadwal'];
$hari = $row['hari'];
$kelas = $row['idkelas'];
$nip = $row['nipguru'];

$nama = $row['guru'];
$jam = $row['jamke'];
$jam2 = $row['njam'] + $jam-1;
$jum = $jam2 - $jam + 1;
$status = $row['status'];
$keterangan = CQ($row['keterangan']);
$jamorig1 = $row['jamke'];
$jamorig2 = $row['njam'] + $row['jamke'] - 1;

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
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
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];	
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];	
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];	
if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);	
if (isset($_REQUEST['jam2']))
	$jam2 = (int)$_REQUEST['jam2'];		
if (isset($_REQUEST['status']))
	$status = $_REQUEST['status'];	

$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) 
{		
	$sql = "SELECT replid FROM infojadwal WHERE aktif=1";
	$res = QueryDb($sql);
	$num = mysqli_num_rows($res);
	if ($num>0)
	{
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
		
		// cek jadwal guru bentrok di kelas lain
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
				   AND hari = '$hari' AND nipguru = '$nip' 
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
			$ERROR_MSG = "Jadwal guru yang bentrok:\\r\\n$ket";
		} 
		else
		{
			// cek bentrok di kelas yang sama
			$sqljam = "";
			for($i = $jam; $i <= $jam2; $i++)
			{
				if (strlen($sqljam) != 0)
					$sqljam .= " OR ";
				
				if ($i >= $jamorig1 && $i <= $jamorig2)
					$sqljam .= "($i >= jamke AND $i <= jamke + njam - 1 AND nipguru <> '$nip')";
				else
					$sqljam .= "($i >= jamke AND $i <= jamke + njam - 1)";
			}
		
			$sql = "SELECT ij.deskripsi, pg.nama, p.nama AS pelajaran, k.kelas, j.departemen, j.hari, j.jamke AS jam1, j.jamke + j.njam - 1 AS jam2
					  FROM infojadwal ij, jadwal j, jbssdm.pegawai pg, pelajaran p, kelas k 
					 WHERE ij.replid = j.infojadwal AND j.nipguru = pg.nip AND j.idpelajaran = p.replid 
					   AND j.idkelas = k.replid AND ij.replid IN ($idinfo_aktif)
					   AND idkelas = '$kelas' AND hari = '$hari'
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
			$sql = "UPDATE jadwal SET idkelas='$kelas', idpelajaran = '$pelajaran', departemen = '$departemen', infojadwal = '$info', 
					hari = $hari, jamke = $jam, njam = $jum, sifat = 1, status = '$status', keterangan='$keterangan', jam1 = '$jm1', 
					jam2 = '$jm2', idjam1 = '$rep1', idjam2 = '$rep2' WHERE replid = '".$replid."'";
			$result = QueryDb($sql);
		
			if ($result)
			{
				CloseDb();
				?>
				<script language="javascript">
					opener.parent.footer.refresh();
					window.close();
				</script> 
	<?php 	}
		}
	}
	else
	{
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
<title>JIBAS SIMAKA [Ubah Jadwal Guru]</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function validate() {
	var jam1 = document.getElementById('jam1').value; 
	var jam2 = document.getElementById('jam2').value; 
	var maxJam = document.getElementById('maxJam').value; 
	var ket = document.getElementById('keterangan').value; 
	var kelas = document.getElementById('kelas').value; 
		
	if (kelas.length == 0) {
		alert("Kelas tidak boleh kosong!");
		document.getElementById('kelas').focus();
		return false;
	} else if (jam2.length == 0 || jam2 == "0") {
		alert("Jam akhir harus dimasukkan!");
		document.getElementById('jam2').focus();
		return false;
	} else if (ket.length > 255) {
		alert("Panjang keterangan tidak boleh dari 255 karakter!");		
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
		alert ('Jam akhir tidak boleh lebih dari jumlah jam jadwal kelas!');
		document.getElementById('jam2').focus();
		return false;
	}
	return true;	
}

function change() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;
	var tingkat = document.getElementById("tingkat").value;
	var hari = document.getElementById("hari").value;
	var jam = document.getElementById("jam").value;
	var jam2 = document.getElementById("jam2").value;
	var status = document.getElementById("status").value;		
	var keterangan = document.getElementById("keterangan").value;
	var maxJam = document.getElementById("maxJam").value;	
	var info = document.getElementById("info").value;
	var replid = document.getElementById("replid").value;
		
	document.location.href = "jadwal_guru_edit.php?departemen="+departemen+"&pelajaran="+pelajaran+"&tahunajaran="+tahunajaran+"&kelas="+kelas+"&tingkat="+tingkat+"&hari="+hari+"&jam="+jam+"&jam2="+jam2+"&status="+status+"&keterangan="+keterangan+"&maxJam="+maxJam+"&info="+info+"&replid="+replid;		
}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var pelajaran = document.getElementById("pelajaran").value;	
	var tingkat = document.getElementById("tingkat").value;
	var hari = document.getElementById("hari").value;
	var jam = document.getElementById("jam").value;
	var jam2 = document.getElementById("jam2").value;
	var status = document.getElementById("status").value;		
	var keterangan = document.getElementById("keterangan").value;
	var maxJam = document.getElementById("maxJam").value;
	var nip = document.getElementById("nip").value;
	var info = document.getElementById("info").value;
	
	document.location.href = "jadwal_guru_edit.php?replid=<?=$replid?>&departemen="+departemen+"&pelajaran="+pelajaran+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&hari="+hari+"&jam="+jam+"&jam2="+jam2+"&status="+status+"&keterangan="+keterangan+"&maxJam="+maxJam+"&nip="+nip+"&info="+info;		
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
	var lain = new Array('tingkat','kelas','pelajaran','jam2','status','keterangan');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('jam2').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Jadwal Guru :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->

<form name="main" onSubmit="return validate()">
<input type="hidden" name="info" id="info" value="<?=$info ?>"/>
<input type="hidden" name="hari" id="hari" value="<?=$hari ?>"/>
<input type="hidden" name="maxJam" id="maxJam" value="<?=$maxJam ?>"/>
<input type="hidden" name="replid" id="replid" value="<?=$replid ?>"/>

<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
    <td><strong>Guru</strong></td>
    <td>
   	<input type="text" name="nipguru" id="nipguru" size="10" class="disabled" readonly value="<?=$nip?>"  /> 
    <input type="hidden" name="nip" id="nip" value="<?=$nip?>" /> 
    <input type="text" name="nama" id="nama" size="25" class="disabled" readonly value="<?=$nama?>" />    
  	</td>
</tr>
<tr>
	<td><strong>Departemen</strong></td>
    <td><input type="text" name="departemen" id="departemen" size="10" class="disabled" value="<?=$departemen ?>" readonly/>
        <input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>"/></td>
</tr>
<tr>
	<td width="100"><strong>Tahun Ajaran</strong></td>
    <td><input type="text" name="tahun" size="10" value="<?=$tahun ?>" readonly class="disabled"/>
    	<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>">
    </td>
</tr>
<tr>
    <td><strong>Tingkat</strong> </td>
    <td>
		<select name="tingkat" id="tingkat" onChange="change_tingkat()" style="width:80px;" onKeyPress="return focusNext('kelas', event)" onFocus="panggil('tingkat')">
<?php 		$sql = "SELECT replid,tingkat FROM tingkat WHERE aktif=1 AND departemen='$departemen' ORDER BY urutan";	
			$result = QueryDb($sql);
				
			while($row = mysqli_fetch_array($result))
			{
				if ($tingkat == "")
					$tingkat = $row['replid'];	?>
			<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tingkat) ?>>
            <?=$row['tingkat']?>
            </option>
<?php 		} ?>
        </select>
	</td>
</tr>
<tr>
   	<td><strong>Kelas</strong> </td>
    <td>
       	<select name="kelas" id="kelas" onChange="change()" style="width:180px;" onKeyPress="return focusNext('pelajaran', event)" onFocus="panggil('kelas')">
<?php 		$sql = "SELECT replid,kelas FROM kelas WHERE aktif=1 AND idtahunajaran = '$tahunajaran' AND idtingkat = '$tingkat' ORDER BY kelas";	
			$result = QueryDb($sql);
		
			while($row = mysqli_fetch_array($result))
			{
				if ($kelas == "")
					$kelas = $row['replid'];	?>
				<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelas) ?>><?=$row['kelas']?></option>
<?php 		} ?>
    	</select>        
	</td>    
</tr>
<tr>
	<td align="left"><strong>Pelajaran</strong></td>
 	<td>
      	<select name="pelajaran" id="pelajaran" onChange="change()" style="width:180px;" onKeyPress="return focusNext('jam2', event)" onFocus="panggil('pelajaran')">
<?php 	$sql = "SELECT l.replid,l.nama FROM pelajaran l, guru g WHERE g.nip = '$nip' AND g.idpelajaran = l.replid AND l.aktif=1 AND departemen = '$departemen' ORDER BY l.nama";
		$result = QueryDb($sql);
		
		while ($row = @mysqli_fetch_array($result))
		{
			if ($pelajaran == "") 				
				$pelajaran = $row['replid'];	?>
	    	<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $pelajaran)?> ><?=$row['nama']?></option>                 
<?php 	} ?>
    	</select>
	</td>  
</tr>
<tr>
	<td><strong>Hari</strong> </td>
    <td><input type="text" name="namahari" id ="namahari" size="10" readonly class="disabled" value = "<?=NamaHari($hari)?>" /></td> 
</tr>
<tr>
	<td><strong>Jam ke</strong></td>
    <td>    
    	<input type="text" name="jam1" id ="jam1" size="2" readonly class="disabled" value = "<?=$jam?>" /><input type="hidden" name="jam" id="jam" value="<?=$jam ?>"/> s/d 
    	<input type="text" name="jam2" id ="jam2" size="2" value="<?=$jam2 ?>" onKeyPress="return focusNext('status', event)" onFocus="panggil('jam2')"/></td>
</tr>
<tr>
	<td><strong>Status</strong></td> 
    <td><select name="status" id="status" onKeyPress="return focusNext('keterangan', event)" onFocus="panggil('status')">     
     	<option value=0 <?=IntIsSelected(0, $status)?>>Mengajar</option>
        <option value=1 <?=IntIsSelected(1, $status)?>>Asistensi</option>
        <option value=2 <?=IntIsSelected(2, $status)?>>Tambahan</option>
     	</select>
    </td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td>
    	<textarea name="keterangan" id="keterangan" rows="3" cols="45" onKeyPress="return focusNext('Simpan', event)" onFocus="panggil('keterangan')"><?=$keterangan ?></textarea>
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
<!-- END OF CONTENT //--->
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
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

<?php
CloseDb();
?>
</body>
</html>