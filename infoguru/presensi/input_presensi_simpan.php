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

$semester=$_REQUEST['semester'];
$kelas=$_REQUEST['kelas'];
$replid = $_REQUEST['replid'];
$tgl1 = $_REQUEST['tgl1'];
$bln = $_REQUEST['bln'];
$th = $_REQUEST['th'];
$tgl2 = $_REQUEST['tgl2'];
$jum=$_REQUEST['jum'];
$status = ["hadir", "ijin", "sakit", "cuti", "alpa"];

$tglawal = "$th-$bln-$tgl1";
$tglakhir = "$th-$bln-$tgl2";
$hariaktif = $_REQUEST['hariaktif'];
?>
<html>
<head>
</head>
<body>

<input type="hidden" name="semester" id="semester" value="<?=$semester ?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>" />

<?php
OpenDb();

/*$sql_cek = "SELECT t.tglmulai, t.tglakhir FROM tahunajaran t, kelas k WHERE k.idtahunajaran = t.replid AND k.replid = $kelas AND (((t.tglmulai BETWEEN '$tglawal' AND '$tglakhir') OR (t.tglakhir BETWEEN '$tglawal' AND '$tglakhir')) OR (('$tglawal' BETWEEN t.tglmulai AND t.tglakhir) OR ('$tglakhir' BETWEEN t.tglmulai AND t.tglakhir))) ";

$result_cek = QueryDb($sql_cek);

if (mysqli_num_rows($result_cek) > 0) {*/
	$filter ="";
	if ($_REQUEST['action'] == 'Update') 
		$filter = "AND replid <> $replid";
    
	$sql_action = "SELECT tanggal1, tanggal2 FROM presensiharian WHERE (((tanggal1 BETWEEN '$tglawal' AND '$tglakhir') OR (tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) OR (('$tglawal' BETWEEN tanggal1 AND tanggal2) OR ('$tglakhir' BETWEEN tanggal1 AND tanggal2))) AND idkelas = '$kelas' AND idsemester = '$semester' $filter";	
		
	$result_action = QueryDb($sql_action);
	$sum = mysqli_num_rows($result_action);
	$row = mysqli_fetch_array($result_action);
	if ($sum > 0) {
	?>
		<script language="javascript">
			alert ('Ada presensi antara tanggal <?=LongDateFormat($row[\TANGGAL1])." s/d ".LongDateFormat($row[\TANGGAL2])?>!');		
			parent.isi.location.href = "blank_presensi.php?tipe='harian'";
			//window.self.history.back();
			//window.history.back();
			
		</script>
	<?php exit;
		}	
	//}
/*} else {
	 
?>
	<script language="javascript">
	//alert ('Pastikan presensi <?=$tgl1." ".NamaBulan($bln1)." ".$th1." s/d ".$tgl2." ".NamaBulan($bln2)." ".$th2?>');		
	//alert ('Pastikan waktu presensi berada dalam batas <?=TglTextLong($awal)?>-<?=TglTextLong($akhir)?> periode Tahun Ajaran <?=$tahunajaran?>');
	 //Waktu data presensi tidak boleh melebihi batas <br /><?=TglTextLong($awal)?>-<?=TglTextLong($akhir)?> pada tahun ajaran <?=$tahunajaran?>.</b></font>
		alert ('Pastikan waktu presensi berada dalam periode tahun ajaran!');
		window.history.back();
	
	</script>
	<?php exit;
}*/	


BeginTrans();
$success=0;
if ($_REQUEST['action'] == 'Update') {
	$sql_action = "DELETE FROM phsiswa WHERE idpresensi = '".$replid."'";		
	QueryDbTrans($sql_action,$success);
	$sql_action = "DELETE FROM presensiharian WHERE replid = '".$replid."'";		
	QueryDbTrans($sql_action,$success);
} 

$sql = "INSERT INTO presensiharian SET idkelas='$kelas', idsemester='$semester', tanggal1='$tglawal', tanggal2 = '$tglakhir', hariaktif='$hariaktif'";
QueryDbTrans($sql,$success);
//echo 'sql1'.$sql.' '.$success;;
if ($success) {
	$sql1 = "SELECT LAST_INSERT_ID(replid) FROM presensiharian ORDER BY replid DESC LIMIT 1";	
	//echo '<br>sql2'.$sql1.' '.$success;
	$result1 = QueryDb($sql1);		
	$row1 = mysqli_fetch_row($result1);
	$id = $row1[0];
	if ($sum > 0) {
	?>
		<script language="javascript">
			alert ('Ada presensi antara tanggal <?=LongDateFormat($row[\TANGGAL1])." s/d ".LongDateFormat($row[\TANGGAL2])?>!');		
			parent.isi.location.href = "blank_presensi.php?tipe='harian'";
			//window.self.history.back();
			//window.history.back();
			
		</script>
	<?php //exit;
		}	
}

for ($i=1;$i<=$jum;$i++) {
	$nis = $_REQUEST['nis'.$i];
	$hadir = $_REQUEST['hadir'.$i];
	$ijin = $_REQUEST['ijin'.$i];
	$sakit = $_REQUEST['sakit'.$i];
	$cuti = $_REQUEST['cuti'.$i];
	$alpa = $_REQUEST['alpa'.$i];
	$keterangan = $_REQUEST['ket'.$i];
	
	$total = $hadir+$ijin+$sakit+$cuti+$alpa;
	if ($total > 0) 
		$sql2 = "INSERT INTO phsiswa SET idpresensi=$id, nis='$nis', hadir=$hadir, ijin = $ijin, sakit = $sakit, cuti = $cuti, alpa = $alpa, keterangan='".CQ($keterangan)."' ";
	//echo '<br>'.$i.' '.$sql2.' '.$success;
	if ($success)
		QueryDbTrans($sql2,$success);	
}
if ($success) { 
	CommitTrans();
	?>

<script language="javascript">
	alert ('Data telah tersimpan');
	parent.menu.location.href="input_presensi_menu.php?semester=<?=$semester?>&kelas=<?=$kelas?>&bln=<?=$bln?>&th=<?=$th?>";
	parent.isi.location.href = "blank_presensi.php?tipe='harian'";
	
</script> 
<?php  
} else {
	RollbackTrans();
?>
<script language="javascript">
	alert ('Data gagal disimpan');
</script>
<?php 
}
CloseDb();?>

</body>
</html>