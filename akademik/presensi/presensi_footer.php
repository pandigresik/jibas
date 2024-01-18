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

$id = 0;
$status = 0;
$st = ['Hadir', 'Ijin', 'Sakit', 'Alpa', 'Cuti', '(Belum ada data)'];
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];

if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
//if (isset($_REQUEST['tahunajaran']))
//	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['pelajaran'])) 
	$pelajaran = $_REQUEST['pelajaran'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['jam'])) 
	$jam = $_REQUEST['jam'];
if (isset($_REQUEST['menit']))
	$menit = $_REQUEST['menit'];
if (isset($_REQUEST['tanggal'])) 
	$tanggal = $_REQUEST['tanggal'];
	//$tanggal = TglDb($_REQUEST['tanggal']);
if (isset($_REQUEST['jenis'])) 
	$jenis = $_REQUEST['jenis'];
if (isset($_REQUEST['nip']))
	$nip = $_REQUEST['nip'];	
if (isset($_REQUEST['nama']))
	$nama = $_REQUEST['nama'];
$waktu = $_REQUEST['jam'].":".$_REQUEST['menit'];

$op = $_REQUEST['op'];

if ($op == "dw8dxn8w9ms8zs22") {
	$id=(int)$_REQUEST['id'];
	OpenDb();
	$sql = "UPDATE pelajaran SET aktif = '$newaktif' WHERE replid = '$replid' ";
	QueryDb($sql);
	CloseDb();
} else if ($op == "xm8r389xemx23xb2378e23") {
	$replid=(int)$_REQUEST['replid'];
	OpenDb();
	$sql = "DELETE FROM ppsiswa WHERE idpp = '".$replid."'";
	//echo $sql;
	QueryDb($sql);
	$sql = "DELETE FROM presensipelajaran WHERE replid = '".$replid."'";
	//echo $sql;exit;
	QueryDb($sql);
	if(mysqli_affected_rows($conn) > 0) {
	?>
    <script language = "javascript" type = "text/javascript">
        alert('Data presensi pelajaran berhasil dihapus');
		parent.footer.location.href = "../blank2.php";
	</script>
<?php } else { ?>
    <script language = "javascript" type = "text/javascript">
        alert('Data presensi pelajaran gagal dihapus, Periksalah apakah data sudah terpakai');	
	</script>
<?php CloseDb();
	}
}

OpenDb();
if ($_REQUEST['replid']<> "") {
	$sql1 = "SELECT k.idtingkat, s.departemen, p.idkelas FROM kelas k, semester s, presensipelajaran p WHERE p.replid = '".$_REQUEST['replid']."' AND p.idkelas = k.replid AND p.idsemester = s.replid"; 
	$result1 = QueryDb($sql1);
	$row1 = mysqli_fetch_array($result1);
	$departemen = $row1['departemen'];
	$tingkat = $row1['idtingkat'];
		
	$sql = "SELECT p.replid, p.gurupelajaran, p.keterangan, p.materi, p.objektif, p.refleksi, p.rencana, p.keterlambatan, p.jumlahjam, p.jenisguru, p.idkelas, p.idsemester, p.idpelajaran, p.tanggal, g.nama, k.idtahunajaran, p.jam FROM presensipelajaran p, jbssdm.pegawai g, kelas k WHERE p.replid = '".$_REQUEST['replid']."' AND g.nip = p.gurupelajaran AND p.idkelas = k.replid";	
} else {
	$tanggal = MySqlDateFormat($tanggal);
	$sql = "SELECT p.replid, p.gurupelajaran, p.keterangan, p.materi, p.objektif, p.refleksi, p.rencana, p.keterlambatan, p.jumlahjam, p.jenisguru, p.idkelas, p.idsemester, p.idpelajaran, p.tanggal, g.nama, k.idtahunajaran, p.jam FROM presensipelajaran p, jbssdm.pegawai g, kelas k WHERE k.replid = '$kelas' AND p.idsemester='$semester' AND p.idpelajaran='$pelajaran' AND p.tanggal = '$tanggal' AND p.jam = '$waktu' AND g.nip = p.gurupelajaran AND p.idkelas = k.replid";	   	
}

$result = QueryDb($sql);
$jml = @mysqli_num_rows($result);
$row = @mysqli_fetch_array($result);
if ($jml > 0) {
	$id = $row['replid'];
	//$tahunajaran = $row['idtahunajaran'];	
	$nip=$row['gurupelajaran'];
	$nama=$row['nama'];
	$keterangan=$row['keterangan'];
	$materi=$row['materi'];
	$objektif=$row['objektif'];
	$refleksi=$row['refleksi'];
	$materi_lanjut=$row['rencana'];
	$telat=$row['keterlambatan']; 
	$jumlah=$row['jumlahjam'];
	$jenis = $row['jenisguru'];
	$kelas = $row['idkelas'];
	$semester = $row['idsemester'];
	$pelajaran = $row['idpelajaran'];
	$tanggal = $row['tanggal'];	
	$jam=substr((string) $row['jam'],0,2);
	$menit=substr((string) $row['jam'],3,2);
	
}	

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pendataan Calon Siswa</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function refresh() {	
	document.location.reload();
}

function hapus(replid) {
	if (confirm("Apakah anda yakin akan menghapus data presensi pelajaran ini?"))
		document.location.href = "presensi_footer.php?op=xm8r389xemx23xb2378e23&replid="+replid;
}
function tambah(id) {	
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
	//var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	newWindow('presensi_tambah_siswa.php?id='+id+'&departemen='+departemen+'&tingkat='+tingkat+'&kelas='+kelas+'&total=1', 'TambahSiswa','800','450','resizable=1,scrollbars=1,status=0,toolbar=0')
}
	
function pegawai() {
	var departemen = document.getElementById('departemen').value;
	var pelajaran = document.getElementById('pelajaran').value;
	
	newWindow('../library/guru.php?flag=0&departemen='+departemen+'&pelajaran='+pelajaran, 'Guru','600','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(nip, nama, flag, d, p) {
	var departemen = document.getElementById("departemen").value;
	var kelas = document.getElementById("kelas").value;
	var semester = document.getElementById("semester").value;
	var pelajaran = document.getElementById("pelajaran").value;	
	var jam = document.getElementById("jam").value;
	var menit = document.getElementById("menit").value;
	var tanggal = document.getElementById("tanggal").value;
	var replid = document.getElementById("replid").value;
	
	document.getElementById('nip').value = nip;
	document.getElementById('nipguru').value = nip;
	document.getElementById('nama').value = nama;
	document.getElementById('namaguru').value = nama;
	
	//alert ("nip="+nip+"&nama="+nama);
	sendRequestText("../library/getguru.php", show, "nip="+nip+"&nama="+nama+"&pelajaran="+pelajaran);
	document.getElementById('jenis').focus();	
}

function show(x) {
	document.getElementById("InfoGuru").innerHTML = x;
}
	
function validate() {
	var nip = document.getElementById("nip").value;
	var materi = document.getElementById("materi").value;
	var jenis = document.getElementById("jenis").value;	
	var nama = document.getElementById("nama").value;	
	var departemen = document.getElementById("departemen").value;	
	var telat = document.getElementById("telat").value;	
	var jumlah = document.getElementById("jumlah").value;	
	
	if (nip.length == 0 ) {	
		alert ('NIP Guru tidak boleh kosong !');	
		document.getElementById('nip').focus();
		return false;	
	} else if (jenis.length == 0 ) {	
		alert('Tambah status data guru '+nama+' \n di menu Pendataan Guru pada bagian Guru & Pelajaran!');		
		document.getElementById('jenis').focus();
		return false;	
	} else if (materi.length == 0) {	
		alert ('Materi tidak boleh kosong!');	
		document.getElementById('materi').focus();
		return false;	
	} else if (isNaN(telat)) {	
		alert ('Keterlambatan harus berupa bilangan!');	
		document.getElementById('telat').focus();
		return false;
	} else if (isNaN(jumlah)) {	
		alert ('Jumlah jam mengajar harus berupa bilangan!');	
		document.getElementById('jumlah').focus();
		return false;			
	}
	document.getElementById('main').submit();	
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
</script>
</head>

<body onLoad="document.getElementById('jenis').focus()">

<form name="main" id="main" method="post" action="presensi_simpan.php" enctype="multipart/form-data">

<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="semester" id="semester" value="<?=$semester ?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat ?>" />
<!--<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran ?>" />-->
<input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran ?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>" />
<input type="hidden" name="tanggal" id="tanggal" value="<?=$tanggal ?>" />
<input type="hidden" name="jam" id="jam" value="<?=$jam	?>" />
<input type="hidden" name="menit" id="menit" value="<?=$menit ?>" />

<input type="hidden" name="replid" id="replid" value="<?=$id ?>" />

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="25">	
	<td colspan="2" class="header" align="center">Data Presensi Pelajaran</td>
</tr>
<tr>
	<td colspan="2">
<div id ="InfoGuru">
<table cellpadding="0" width="100%">
<tr>
	<td width="17%"><strong>Guru yang Mengajar</strong></td>
    <td>
        <input type="text" name="nipguru" id="nipguru" size="15" class="disabled" value="<?=$nip ?>" readonly onClick="pegawai()"/>        
    	<input type="text" name="namaguru" id="namaguru" size="30" class="disabled" value="<?=$nama ?>" readonly onClick="pegawai()"/>        
		<input type="hidden" name="nip" id="nip" value="<?=$nip ?>" />
		<input type="hidden" name="nama" id="nama" value="<?=$nama ?>" />
        <a href="JavaScript:pegawai()"><img src="../images/ico/cari.png" border="0" /></a>	
        
   	</td>
</tr>
<tr>
	<td><strong>Status Guru</strong></td>
    <td>
   	<select name="jenis" id="jenis" style="width:150px;" onKeyPress="return focusNext('keterangan', event)">
<?php 	OpenDb();
		$sql = "SELECT s.replid, s.status FROM statusguru s ORDER BY status";
		$result = QueryDb($sql);
		CloseDb();
		while($row = mysqli_fetch_array($result)) 
		{
			if ($jenis == "")
				$jenis = $row['replid'];	?>
			<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $jenis) ?>>
            <?=$row['status']?>
            </option>
<?php 	} //while	?>
	</select>    
	</td>
</tr>
</table>
</div>
	</td>
</tr>
<tr>
	<td valign="top" width="17%">Keterangan Kehadiran Guru</td>
    <td><textarea name="keterangan" id="keterangan" rows="2" cols="80%" onKeyPress="return focusNext('materi', event)"><?=$keterangan ?></textarea></td>
</tr>
<tr>
	<td valign="top"><strong>Materi</strong></td>
    <td><textarea name="materi" id="materi" rows="2" cols="80%" onFocus="showhint('Materi tidak boleh kosong!', this, event, '120px')" onKeyPress="return focusNext('materi_lanjut', event)"><?=$materi ?></textarea></td>
</tr>
<tr>
	<td valign="top">Materi Selanjutnya</td>
    <td><textarea name="materi_lanjut" id="materi_lanjut" rows="2" cols="80%" onKeyPress="return focusNext('telat', event)"><?=$materi_lanjut ?></textarea></td>
</tr>
<tr>
	<td>Keterlambatan</td>
    <td><input type="text" name="telat" id="telat" size="3" maxlength="3" value="<?=$telat ?>" onKeyPress="return focusNext('jumlah', event)"/> menit</td>
</tr>
<tr>
	<td>Jumlah Jam Mengajar</td>
    <td><input type="text" name="jumlah" id="jumlah" size="3" maxlength="3" value="<?=$jumlah ?>" onKeyPress="return focusNext('status1', event)"/> jam</td>
</tr>
<tr>
    <td align="left" valign="top" colspan="2">       
	 <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="center" bordercolor="#000000">
		<tr>		
			<td width="3%" height="30" align="center" class="header">No</td>
			<td width="10%" height="30" align="center" class="header">N I S</td>
			<td width="*" height="30" align="center" class="header">Nama</td>
            <td width="8%" height="30" align="center" class="header">Presensi</td>
            <td width="51%" height="30" align="center" class="header">Catatan</td>
		</tr>
		<?php 
		OpenDb();
			
		$sql = "SELECT s.nis, s.nama, s.idkelas, k.kelas, s.aktif FROM siswa s, kelas k WHERE s.idkelas = '$kelas' AND s.aktif = 1 AND s.alumni = 0 AND k.replid = s.idkelas UNION SELECT s.nis, s.nama, s.idkelas, k.kelas, s.aktif FROM siswa s, ppsiswa p, kelas k WHERE p.idpp = '$id' AND p.nis = s.nis AND s.idkelas = k.replid ORDER BY nama";
		
		$result = QueryDb($sql);		
		$cnt = 1;
		$jum = @mysqli_num_rows($result);
		
		while ($row = @mysqli_fetch_array($result)) {		
			if ($id) {
				$sql1 = "SELECT * FROM ppsiswa WHERE idpp = '$id' AND nis='".$row['nis']."'";
				
				$result1 = QueryDb($sql1);
				$jml = mysqli_num_rows($result1);
				$row1 = mysqli_fetch_array($result1);
				if ($jml <> 0) {									
					$status=$row1['statushadir'];
					$catatan=$row1['catatan'];				
				} else {
					$status=5;
					$catatan="";					
				}
			}
		?>	
        <tr>        			
			<td height="25" align="center"><?=$cnt?></td>
			<?php if ($row['idkelas'] <> $kelas) { ?>
            <td align="center" onMouseOver="showhint('Kelas tetap di <?=$row['kelas']?>', this, event, '80px')">
            <font color="#FF0000"><?=$row['nis']?></font></td>
            <td onMouseOver="showhint('Kelas tetap di <?=$row['kelas']?>', this, event, '80px')">
            <font color="#FF0000"><?=$row['nama']?></font></td>			
		<?php } else if ($row['aktif'] == 0) { ?>			
            <td align="center" onMouseOver="showhint('Status siswa tidak aktif lagi!', this, event, '80px')">
            <font color="#FF0000"><?=$row['nis']?></font></td>
            <td onMouseOver="showhint('Status siswa tidak aktif lagi!', this, event, '80px')">
            <font color="#FF0000"><?=$row['nama']?></font></td>		
		<?php } else {	?>
            <td align="center"><?=$row['nis']?></td>
            <td><?=$row['nama']?></td>
       	<?php } ?>
            
            <!--<td height="25" align="center"><?=$row['nis']?></td>-->
            <input type="hidden" name="nis<?=$cnt?>" value="<?=$row['nis']?>">
  			<!--<td height="25"><?=$row['nama']?></td>-->
           	<td height="25"><select name="status<?=$cnt?>" id="status<?=$cnt?>" onKeyPress="return focusNext('catatan<?=$cnt?>', event)"> 
                <?php for ($i=0;$i<count($st);$i++){ ?>
           		<option value=<?=$i?> <?=IntIsSelected($i, $status) ?>><?=$st[$i]?></option>
            	<?php } ?>
           		</select>            </td>
            <td height="25" align="center"><input type="text" name="catatan<?=$cnt?>" id="catatan<?=$cnt?>" size="75" value="<?=$catatan?>" <?php if ($cnt == $jum) {?> onKeyPress="focusNext('simpan',event)" <?php } else { ?> onKeyPress="focusNext('status<?=$cnt+1 ?>',event)" <?php } ?>/></td>
    	</tr>
 	<?php 	$cnt++;
		} 
		CloseDb();	?>
    	<input type="hidden" name="jum" id="jum" value="<?=$jum?>">			
		</table>   	  </td>
    <!-- END TABLE CONTENT -->
      
    <script language='JavaScript'>
	   	Tables('table', 1, 0);
    </script>
	</tr>
    <tr>    
    	<td align="right" colspan="2">
        <input type="button" name="simpan" id="simpan" value="Simpan" class="but" style="width:100px; " onClick="return validate();"/>
        <?php
			if($id){
				$action = "Update";
		?>
        	<input type="button" value="(+) Tambah Siswa" class="but" onClick="tambah(<?=$id ?>)">
			<input type="button" value="Hapus Data" class="but" onClick="hapus(<?=$id ?>)" style="width:100px; ">
			
			<?php
			}else{
				$action = "Simpan"; 
			}
			?>
        	<!--<input type="submit" name="simpan" value="<?=$action?>" class="but" style="width:100px; " />-->
            <input type="hidden" name="action" id="action" value="<?=$action?>">
            </td>
	</tr>
    
<!-- END TABLE CENTER -->    
</table>
</form>
</body>
</html>
<script language="javascript">
	var jum = document.getElementById("jum").value;
	var x;
	for (x=1;x<=jum;x++){
		var spryselect = new Spry.Widget.ValidationSelect("status"+x);
		var sprytextfield = new Spry.Widget.ValidationTextField("catatan"+x);
	}
	var spryselect = new Spry.Widget.ValidationSelect("jenis");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("telat");
	var sprytextfield2 = new Spry.Widget.ValidationTextField("jumlah");
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
	var sprytextarea2 = new Spry.Widget.ValidationTextarea("materi");
	var sprytextarea3 = new Spry.Widget.ValidationTextarea("materi_lanjut");
	 
</script>