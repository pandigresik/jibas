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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
OpenDb();
if(isset($_REQUEST["departemen"]))//1
	$departemen = $_REQUEST["departemen"];
if(isset($_REQUEST["tingkat"]))//2
	$tingkat = $_REQUEST["tingkat"];
if(isset($_REQUEST["tahun"]))//3
	$tahun = $_REQUEST["tahun"];
if(isset($_REQUEST["semester"]))//4
	$semester = $_REQUEST["semester"];
if(isset($_REQUEST["kelas"]))//5
	$kelas = $_REQUEST["kelas"];
if(isset($_REQUEST["pelajaran"]))//6
	$pelajaran = $_REQUEST["pelajaran"];
if(isset($_REQUEST["jenis_penilaian"]))//7
	$jenis_penilaian = $_REQUEST["jenis_penilaian"];
if(isset($_REQUEST["dasar_penilaian"]))//8
	$dasar_penilaian = $_REQUEST["dasar_penilaian"];
if(isset($_REQUEST["idaturan"]))//9
	$idaturan = $_REQUEST["idaturan"];
$jumnis = $_REQUEST["jumnis"];
$hit=1;
while ($hit<=$jumnis){
$nis=$_REQUEST["nis_".$hit];
$nau=$_REQUEST["nau_".$hit];
$idnau=$_REQUEST["idnau_".$hit];
$sql_cek_nau="SELECT * FROM jbsakad.nau WHERE idpelajaran='$pelajaran' AND idkelas='$kelas' AND idsemester='$semester' AND idjenis='$jenis_penilaian' AND nis='$nis' AND replid='$idnau'";
//echo $sql_cek_nau;
$result_cek_nau=QueryDb($sql_cek_nau);
$nilai_nau_tetap=@mysqli_fetch_array($result_cek_nau);
if (@mysqli_num_rows($result_cek_nau)>0){
	if ($nau==$nilai_nau_tetap['nilaiAU']){
	$sql_simpan="UPDATE jbsakad.nau SET nilaiAU='$nau' WHERE replid='$idnau'";
	} else {
	$sql_simpan="UPDATE jbsakad.nau SET nilaiAU='$nau',keterangan='Manual' WHERE replid='$idnau'";
	}
} else {
	$sql_simpan="INSERT INTO jbsakad.nau SET nilaiAU='$nau',idpelajaran='$pelajaran',idkelas='$kelas',idsemester='$semester',idjenis='$jenis_penilaian',nis='$nis',keterangan='Manual',idaturan='$idaturan' ";
}
//echo $sql_simpan."<br>".$idaturan;
//exit;
$result_simpan=QueryDb($sql_simpan);
$hit++;
}
if(@mysqli_affected_rows($conn)($conn) >= 0) {
?>
<script language="javascript">
document.location.href="nilai_pelajaran_content.php?dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>&manual=1";
</script>
<?php
} else {
?>
<script language="javascript">
alert ('Gagal mengisi data');
//document.location.href="nilai_pelajaran_content.php?dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>&manual=1";
</script>
<?php
}
?>