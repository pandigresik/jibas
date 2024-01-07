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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Laporan Hasil Belajar</TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<style type="text/css">
.style1{
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF
}

.style6 {
	font-size: 12px;
	font-weight: bold;
}
.style13 {
	font-size: 14px;
	font-weight: bold;
}
.style14 {color: #FFFFFF}
</style>
<script language="javascript" src="../script/tables.js"></script>
</HEAD>
<BODY>
<table width="780" border="0">
<tr>
    <td><?=getHeader($departemen)?></td>
</tr>
<tr><td>

<table width="100%" border="0">
<tr>
    <td>

    <table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#666666">
    <tr>
        <td height="16" colspan="2" bgcolor="#FFFFFF"><div align="center" class="style13">LAPORAN HASIL BELAJAR</div></td>
    </tr>
    <tr>
        <td height="20">Departemen</td>
        <td height="20">:&nbsp;<?=$departemen?></td>
    </tr>
    <tr>
        <td height="20">Tahun Ajaran</td>
    <?php  $sql_get_ta="SELECT tahunajaran FROM jbsakad.tahunajaran WHERE replid='$tahunajaran'";
        $result_get_ta=QueryDb($sql_get_ta);
        $row_get_ta=@mysqli_fetch_array($result_get_ta);?>
        <td height="20">:&nbsp;<?=$row_get_ta['tahunajaran']?></td>
    </tr>
    <tr>
        <td width="6%" height="20">NIS</td>
        <td width="93%" height="20">:&nbsp;<?=$nis?></td>
    </tr>
    <tr>
        <td height="20">Nama</td>
<?php      $sql_get_nama="SELECT nama FROM jbsakad.siswa WHERE nis='$nis'";
	    $result_get_nama=QueryDb($sql_get_nama);
	    $row_get_nama=@mysqli_fetch_array($result_get_nama);?>
	    <td height="20">:&nbsp;<?=$row_get_nama['nama']?></td>
    </tr>
    <tr>
        <td height="20">Kelas/Semester&nbsp;</td>
<?php      $sql_get_kls="SELECT kelas FROM jbsakad.kelas WHERE replid='$kelas'";
        $result_get_kls=QueryDb($sql_get_kls);
        $row_get_kls=@mysqli_fetch_array($result_get_kls);
	
        $sql_get_sem="SELECT semester FROM jbsakad.semester WHERE replid='$semester'";
        $result_get_sem=QueryDb($sql_get_sem);
        $row_get_sem=@mysqli_fetch_array($result_get_sem);?>
        <td height="20">:&nbsp;<?=$row_get_kls['kelas']."/".$row_get_sem['semester']?></td>
    </tr>
</table>

</td></tr>

<tr><td><br>
<?php
require_once("rapor.content.komentar.php");
?>
</td></tr>

<tr><td>
<?php
require_once("rapor.content.nilai.php");
?>
</td></tr>

<tr><td>
<?php
require_once("rapor.content.nilai.deskripsi.php");
?>
</td></tr>


<tr><td>
<?php
if ($harian != "false") require_once("rapor.content.presensi.harian.php");
?>
</td></tr>

<tr><td>
<?php
if ($prespel != "false") require_once("rapor.content.presensi.pelajaran.php");
?>
</td></tr>
</table>

</BODY>
<script language="javascript">
window.print();
</script>
</HTML>
<?php
CloseDb();
?>