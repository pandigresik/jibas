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
require_once("../include/sessionchecker.php");
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/dpupdate.php');

if (isset($_REQUEST['id_tingkat']))
	$id_tingkat = $_REQUEST['id_tingkat'];
if (isset($_REQUEST['nip_guru']))
	$nip_guru = $_REQUEST['nip_guru'];
if (isset($_REQUEST['id_pelajaran']))
	$id_pelajaran = $_REQUEST['id_pelajaran'];	
if (isset($_REQUEST['aspek']))
	$aspek = $_REQUEST['aspek'];	
if (isset($_REQUEST['jum']))
	$jum = $_REQUEST['jum'];	

OpenDb();
if ($_REQUEST['action'] == 'Add') 
{
	$sql = "SELECT * FROM guru g, pelajaran j, dasarpenilaian d, tingkat t, aturannhb a 
			WHERE a.nipguru=g.nip AND a.idpelajaran = j.replid AND a.dasarpenilaian = d.dasarpenilaian 
			AND a.idtingkat = t.replid AND a.idpelajaran = '$id_pelajaran' AND a.nipguru = '$nip_guru' 
			AND a.idtingkat = '$id_tingkat' AND a.dasarpenilaian = '".$aspek."'"; 
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) 
	{
		CloseDb(); ?>
		<script language="javascript">
			alert ('Aspek <?=$aspek?> sudah digunakan!');
			window.self.history.back();
		</script>
<?php 	exit;
	} 
}

BeginTrans();	
$success=0;
for ($i = 1; $i <= $jum; $i++) 
{
	$jenis = $_REQUEST['ujian'.$i];
	$bobot = $_REQUEST['bobot'.$i];
	$id = $_REQUEST['replid'.$i];
	$cek = $_REQUEST['cek'.$i];

	if ($jenis && $cek == 1 && $bobot >= 0) 
	{
		if ($id != "") 
			$sql1 = "UPDATE aturannhb SET bobot='$bobot' WHERE replid = $id";				
		else 
			$sql1 = "INSERT INTO aturannhb SET nipguru='$nip_guru',
					 idtingkat='$id_tingkat', idpelajaran='$id_pelajaran',
					 dasarpenilaian='$aspek', idjenisujian='$jenis', bobot='$bobot'";	
		QueryDbTrans($sql1,$success);
	} 
}
	
if ($success) 
{ 
	CommitTrans();
	CloseDb(); ?>
	<script language="javascript">
		opener.document.location.href="perhitungan_rapor_content.php?id_pelajaran=<?=$id_pelajaran?>&nip_guru=<?=$nip_guru?>";
		window.close();
	</script>
<?php 
} 
else 
{ 
	RollbackTrans();
	CloseDb(); ?>
	<script language="javascript">
        alert ('Data gagal disimpan !');
        window.close();
    </script>
<?php
}
?>