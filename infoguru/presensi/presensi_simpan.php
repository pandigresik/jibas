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
require_once('../include/db_functions.php');
require_once('../include/rupiah.php');

$nip=$_REQUEST['nipguru'];
$keterangan=$_REQUEST['keterangan'];
$materi=$_REQUEST['materi'];
$objektif=$_REQUEST['objektif'];
$refleksi=$_REQUEST['refleksi'];
$materi_lanjut=$_REQUEST['materi_lanjut'];
$telat=$_REQUEST['telat']; 
if ($telat == "") 
	$telat = 0;
$jumlah=$_REQUEST['jumlah'];
if ($jumlah == "")
	$jumlah = 0; 
$jum=$_REQUEST['jum'];
$waktu = $_REQUEST['jam'].":".$_REQUEST['menit'];
$jenis = $_REQUEST['jenis'];
$departemen=$_REQUEST['departemen'];
$semester=$_REQUEST['semester'];
$kelas=$_REQUEST['kelas'];
$pelajaran=$_REQUEST['pelajaran'];
$replid = $_REQUEST['replid'];
$tanggal = $_REQUEST['tanggal'];

?>
<html>
<head>

</head>
<body>
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>" />
<input type="hidden" name="semester" id="semester" value="<?=$semester ?>" />
<input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran ?>" />
<input type="hidden" name="jam" id="jam" value="<?=$_REQUEST['jam'] ?>" />
<input type="hidden" name="menit" id="menit" value="<?=$_REQUEST['menit'] ?>" />
<input type="hidden" name="tanggal" id="tanggal" value="<?=$tanggal ?>" />


<?php
OpenDb();
if ($_REQUEST['action'] == 'Update') {
	$sql = "DELETE FROM ppsiswa WHERE idpp = '".$replid."'";
	QueryDb($sql);
	$sql = "DELETE FROM presensipelajaran WHERE replid = '".$replid."'";
	QueryDb($sql);
	
}

BeginTrans();
$success=0;
$sql = "INSERT INTO presensipelajaran
			  SET idkelas='$kelas', idsemester='$semester', idpelajaran='$pelajaran', tanggal='$tanggal',
			      jam='$waktu', gurupelajaran='$nip', jenisguru='$jenis',
					keterangan='".CQ($keterangan)."', materi='".CQ($materi)."',
					objektif='".CQ($objektif)."', refleksi='".CQ($refleksi)."',
					rencana='".CQ($materi_lanjut)."', keterlambatan='$telat', jumlahjam='$jumlah'";
QueryDbTrans($sql,$success);
//$result = QueryDb($sql);
//echo 'sql1'.$sql.' '.$success;		
if ($success) {
	$sql1 = "SELECT LAST_INSERT_ID() FROM presensipelajaran";		
	$result1 = QueryDb($sql1);		
	$row1 = mysqli_fetch_row($result1);
	$id = $row1[0];		
	//echo '<br>sql2'.$sql1.' '.$success;	
}

for ($i=1;$i<=$jum;$i++) {
	$nis = $_REQUEST['nis'.$i];
	$status = $_REQUEST['status'.$i];
	$catatan = $_REQUEST['catatan'.$i];
		
	if ($status <> 5) {
		$sql2 = "INSERT INTO ppsiswa SET idpp=$id, nis='$nis', statushadir=$status, catatan='$catatan' ";
	//echo '<br>'.$i.' '.$sql2.' '.$success;
		QueryDbTrans($sql2,$success);
	}	
}

if ($success) { 
	CommitTrans();?>

<script language="javascript">
	alert ('Data telah tersimpan');
	parent.header.show();
</script> 
<?php   
} else {
	RollbackTrans();
?>
<script language="javascript">
	alert ('Data gagal disimpan');
</script>
<?php }
CloseDb();?> 

</body>
</html>