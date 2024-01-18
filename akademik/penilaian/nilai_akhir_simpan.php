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

$idaturan = $_REQUEST['idaturan'];
$idkelas = $_REQUEST['kelas'];
$idsemester = $_REQUEST['semester'];
	
OpenDb();
BeginTrans();
$success = true;	

if ($_REQUEST['action'] <> "manual") 
{	
	for ($i = 1; $i <= $_REQUEST['jumujian']; $i++ && $success) 
	{
		$ujian = trim((string) $_REQUEST['ujian'.$i]);
		$bobot = trim((string) $_REQUEST['bobot'.$i]);
		$id = trim((string) $_REQUEST['replid'.$i]);
		$cek = $_REQUEST['jenisujian'.$i];
		
		if ($cek)
		{
			if ($id == "")
				$sql = "INSERT INTO bobotnau SET idujian = '$ujian', bobot = '$bobot', idaturan = '".$idaturan."'";
			else
				$sql = "UPDATE bobotnau SET bobot = '$bobot' WHERE replid = '".$id."'";
			QueryDbTrans($sql, $success);
		}
		else
		{
			if ($id != "")
			{
				$sql = "DELETE FROM bobotnau WHERE replid = '".$id."'";
				QueryDbTrans($sql, $success);
			}
		}
	}
}

// ambil idjenisujian dan idpelajaran
$sql = "SELECT idpelajaran, idjenisujian 
		FROM jbsakad.aturannhb 
		WHERE replid = '".$idaturan."'";
$res = QueryDb($sql);
$row = mysqli_fetch_array($res);
$jenis = $row['idjenisujian'];
$pelajaran = $row['idpelajaran'];
	
$sql = "SELECT nis 
	 	FROM jbsakad.siswa 
		WHERE idkelas = '$idkelas' AND aktif = 1 
		ORDER BY nama ASC ";
$result_get_nis_siswa = QueryDb($sql);

while ($success && ($row_get_nis_siswa = @mysqli_fetch_array($result_get_nis_siswa)))
{
	$nis = $row_get_nis_siswa['nis'];
	
	if ($_REQUEST['action'] <> "manual") 
	{	
		$sql = "SELECT replid 
				FROM jbsakad.ujian 
			    WHERE idkelas = '$idkelas' AND idsemester = '$idsemester' AND idaturan = '".$idaturan."'";
		$result_get_ujian = QueryDb($sql);
		
		$ujian_culip = 0;
		$nilai = 0.0;
		$bobot = 0.0;
		while ($row_get_ujian = @mysqli_fetch_array($result_get_ujian))
		{	
			$idujian = $row_get_ujian['replid'];
			
			//Ambil bobot
			$sql = "SELECT bobot FROM jbsakad.bobotnau WHERE idujian = '".$idujian."'";
			$result_get_bobot = QueryDb($sql);
			$row_get_bobot = @mysqli_fetch_array($result_get_bobot);
			$b = (float)$row_get_bobot['bobot'];
			
			//Ambil nilai ujian
			$sql = "SELECT nilaiujian FROM jbsakad.nilaiujian WHERE idujian = '$idujian' AND nis = '".$nis."'";
			$result_get_nilai = QueryDb($sql);
			$row_get_nilai = @mysqli_fetch_array($result_get_nilai);
			$nu = (float)$row_get_nilai['nilaiujian'];
			
			//Hitung NA
			$nilai = $nilai + $b * $nu;
			$bobot = $bobot + $b;
			$ujian_culip++;
		}		
		$ratabulat = round(($nilai / $bobot), 2);	
	
		$sql = 	"SELECT nilaiAU, replid, keterangan 
				 FROM jbsakad.nau 
				 WHERE nis = '$nis' AND idkelas = '$idkelas' AND idsemester ='$idsemester' AND idaturan = '".$idaturan."'";
		$result_nau = QueryDb($sql);
	
		if (mysqli_num_rows($result_nau) > 0) 
		{	
			$row_nau = mysqli_fetch_row($result_nau);
			$id_nau  = $row_nau[1];
			
			$sql_insert_nau = "UPDATE jbsakad.nau SET nilaiAU = '$ratabulat' WHERE replid = '$id_nau'";
		}
		else
		{
			$sql_insert_nau = 
				"INSERT INTO jbsakad.nau 
				 SET nis = '$nis', idkelas = '$idkelas', idsemester = '$idsemester', idjenis = '$jenis', 
				     idpelajaran = '$pelajaran', nilaiAU = '$ratabulat', idaturan = '".$idaturan."'";
		}
	} 
	else 
	{
		// if from manual 
		foreach($_REQUEST['nau'] as $key => $value) 
		{			
			if ($key == $nis) 
			{
				if ($value[1]) 
					$sql_insert_nau = "UPDATE jbsakad.nau SET nilaiAU = '$value[0]', keterangan = 'Manual' WHERE replid = '$value[1]'";
				else 
					$sql_insert_nau = 
						"INSERT INTO jbsakad.nau 
						 SET nis = '$key', idkelas = '$idkelas', idsemester = '$idsemester',
						     idjenis = '$jenis', idpelajaran = '$pelajaran', nilaiAU = '$value[0]',
							 idaturan = '$idaturan', keterangan = 'Manual'";				
			}		
		}
	}	
	
	QueryDbTrans($sql_insert_nau, $success);
}
	
if ($success) 
{
	CommitTrans();			
	CloseDb(); ?>
	<script language="javascript">
		parent.nilai_pelajaran_content.location.href="nilai_pelajaran_content.php?kelas=<?=$_REQUEST['kelas']?>&semester=<?=$_REQUEST['semester']?>&idaturan=<?=$_REQUEST['idaturan']?>";						
	</script>
<?php 	
} 
else 
{		
	RollbackTrans();		
	CloseDb();?>
	<script language="javascript">
		alert ('Data gagal disimpan');
	</script>
<?php 
}	
?>