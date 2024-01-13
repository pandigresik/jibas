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
require_once('HitungRata.php');

OpenDb();
BeginTrans();
$success = true;	

$sql_get_nau_per_nis = 
	"SELECT nilaiAU, replid, keterangan 
	   FROM jbsakad.nau WHERE idkelas = '".$_REQUEST['kelas']."' AND idsemester = '".$_REQUEST['semester']."' 
	    AND idaturan = '".$_REQUEST['idaturan']."'";
$result_nau = QueryDb($sql_get_nau_per_nis);
if (mysqli_num_rows($result_nau) > 0) 
{	
	$sql_hapus_nau = "DELETE FROM jbsakad.nau 
					   WHERE idkelas = '".$_REQUEST['kelas']."' AND idsemester = '".$_REQUEST['semester']."' 
					     AND idaturan = '".$_REQUEST['idaturan']."'";
	QueryDbTrans($sql_hapus_nau,$success);	
}

$tanggal = TglDb($_REQUEST['tanggal']);	
$rpp = "";
if ($_REQUEST['idrpp'] != '') 
	$rpp = " ,idrpp= ".$_REQUEST['idrpp'];

$sql1 = "INSERT INTO ujian SET idpelajaran = '".$_REQUEST['pelajaran']."', idkelas = '".$_REQUEST['kelas']."', 
			idsemester = '".$_REQUEST['semester']."', idjenis = '".$_REQUEST['jenis']."', deskripsi = '".CQ($_REQUEST['deskripsi'])."', 
			tanggal = '$tanggal', idaturan = '".$_REQUEST['idaturan']."', kode = '".$_REQUEST['kode']."' $rpp";
QueryDbTrans($sql1,$success);

$sql2 = "SELECT LAST_INSERT_ID()";
$result1 = QueryDb($sql2);
$row = mysqli_fetch_row($result1);
$id = $row[0];

$a = $_REQUEST['nilaiujian'];	
foreach($a as $key => $value) 
{	
	if ($success)
	{
		$sql = "INSERT INTO nilaiujian SET nilaiujian=$value[0], nis='$key',idujian = $id, keterangan='".CQ($value[1])."'";
		QueryDbTrans($sql, $success);
	}

	if ($success)
		HitungRataSiswa($_REQUEST['kelas'], $_REQUEST['semester'], $_REQUEST['idaturan'], $key, $success);
}

if ($success)
	HitungRataKelasUjian($_REQUEST['kelas'], $_REQUEST['semester'], $_REQUEST['idaturan'], $id, $success);
	
if ($success) 
{ 
	CommitTrans();
	CloseDb();	?>
	<script language="javascript">
		parent.opener.refresh();		
		window.close();
	</script>
<?php 	
} 
else 
{ 
	RollbackTrans();
	CloseDb(); ?>
	<script language="javascript">
		alert ('Data gagal disimpan');
	</script>
<?php 
}		
?>