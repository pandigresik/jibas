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
require_once('../include/getheader.php');
require_once('rapor.content.func.php');

OpenDb();

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];

if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];

if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];

if (isset($_REQUEST['pelajaran'])) 
	$pelajaran = $_REQUEST['pelajaran'];

if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];

if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];

if (isset($_REQUEST['prespel']))
	$prespel = $_REQUEST['prespel'];

if (isset($_REQUEST['harian']))
	$harian = $_REQUEST['harian'];

if (isset($_REQUEST['tglmulai']))
    $tglawal = $_REQUEST['tglmulai'];

if (isset($_REQUEST['tglakhir']))
    $tglakhir = $_REQUEST['tglakhir'];

$sql_get_ta = "SELECT tahunajaran FROM jbsakad.tahunajaran WHERE replid='$tahunajaran'";
$result_get_ta = QueryDb($sql_get_ta);
$row_get_ta = @mysqli_fetch_array($result_get_ta);
$nmtahunajaran = $row_get_ta['tahunajaran'];

$sql_get_nama="SELECT nama, nisn FROM jbsakad.siswa WHERE nis='$nis'";
$result_get_nama=QueryDb($sql_get_nama);
$row_get_nama=@mysqli_fetch_array($result_get_nama);
$nmsiswa = $row_get_nama['nama'];
$nisn = $row_get_nama['nisn'];

$sql_get_kls="SELECT kelas FROM jbsakad.kelas WHERE replid='$kelas'";
$result_get_kls=QueryDb($sql_get_kls);
$row_get_kls=@mysqli_fetch_array($result_get_kls);
$nmkelas = $row_get_kls['kelas'];

$sql_get_sem="SELECT semester FROM jbsakad.semester WHERE replid='$semester'";
$result_get_sem=QueryDb($sql_get_sem);
$row_get_sem=@mysqli_fetch_array($result_get_sem);
$nmsemester = $row_get_sem['semester'];

$sql_get_w_kls="SELECT p.nama as namawalikelas, p.nip as nipwalikelas FROM jbssdm.pegawai p, jbsakad.kelas k WHERE k.replid='$kelas' AND k.nipwali=p.nip";
//echo $sql_get_w_kls;
$rslt_get_w_kls=QueryDb($sql_get_w_kls);
$row_get_w_kls = @mysqli_fetch_array($rslt_get_w_kls);
$nmwalikelas = $row_get_w_kls['namawalikelas'];
$nipwalikelas = $row_get_w_kls['nipwalikelas'];

$sql_get_kepsek="SELECT d.nipkepsek as nipkepsek,p.nama as namakepsek FROM jbssdm.pegawai p, jbsakad.departemen d WHERE  p.nip=d.nipkepsek AND d.departemen='$departemen'";
$rslt_get_kepsek=QueryDb($sql_get_kepsek);
$row_get_kepsek=@mysqli_fetch_array($rslt_get_kepsek);
$nmkepsek = $row_get_kepsek['namakepsek'];
$nipkepsek = $row_get_kepsek['nipkepsek'];

header('Content-Type: application/vnd.ms-word'); //IE and Opera  
header('Content-Type: application/w-msword'); // Other browsers  
header('Content-Disposition: attachment; filename=Nilai_Pelajaran.doc');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 11">
<meta name=Originator content="Microsoft Word 11">
<link rel=File-List href="Doc1_files/filelist.xml">
<?php
require_once("rapor.word.style.php");
require_once("rapor.word.header.php");
?>
</head>

<body lang=EN-US style='tab-interval:36.0pt'>

<div class=Section1>
<?=getHeader($departemen)?>

<table width="100%" border="0">
<tr><td>
<?php
$title = "LAPORAN HASIL BELAJAR";
require("rapor.word.title.php");
?>
</td></tr>

<tr><td>
<br>
<?php require_once("rapor.content.komentar.php"); ?>
</td></tr>

<tr><td>
<br>
<?php
require_once("rapor.content.nilai.php");
?>
</td></tr>

<tr><td>
<br>
<?php
require_once("rapor.content.nilai.deskripsi.php");
?>
</td></tr>

<tr><td>
<?php
require("rapor.word.ttd.php");
?>
</td></tr>
</table>

</div> <!-- Section2 //-->

<?php
if ($prespel != "false")
{   ?>

<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'></span>

<div class=Section3>
<table width="100%" border="0">
<tr><td>
<?php
$title = "PRESENSI PELAJARAN";
require("rapor.word.title.php");
?>
</td></tr>

<tr><td>
<br>
<?php
require_once("rapor.content.presensi.pelajaran.php");
?>
</td></tr>

<tr><td>
<?php
require("rapor.word.ttd.php");
?>
</td></tr>
</table>

</div>
<?php
}
?>

<?php
if ($harian != "false")
{   ?>

<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'></span>
<div class=Section4>
<table width="100%" border="0">

<tr><td>
<?php
$title = "PRESENSI HARIAN";
require("rapor.word.title.php");
?>
</td></tr>

<tr><td>
<br>
<?php
require_once("rapor.content.presensi.harian.php");
?>
</td></tr>

<tr><td>
<?php
require("rapor.word.ttd.php");
?>
</td></tr>

</table>
</div>
<?php
}
?>

</body>
</html>
<?php
CloseDb();
?>