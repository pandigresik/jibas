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
require_once('../include/config.php');
require_once('../include/db_functions.php');
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
<TITLE> New Document </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script language="javascript" src="../script/tools.js"></script>
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style4 {font-size: 12}
.style5 {color: #FFFFFF; font-weight: bold; font-size: 12; }
.style6 {
	font-size: 12px;
	font-weight: bold;
}
-->
</style>
<script language="javascript" src="../script/tables.js"></script>
</HEAD>
<BODY>

<table width="100%" border="0">
<tr><td>

  <table width="100%" border="0" bordercolor="#666666" cellpadding="0" cellspacing="0" >
  <tr>
      <td width="6%" height="50" style="background-color: #4a88cc" >
          <span class="style5">&nbsp;NIS </span><span class="style4"><br>
          <span class="style1">&nbsp;Nama </span></span>
      </td>
      <td width="46%" height="40" style="background-color: #4a88cc">
          <span class="style5">: <?=$nis?></span>
          <span class="style4"><br>
	      <span class="style1">:
<?php          $sql_get_nama="SELECT nama FROM jbsakad.siswa WHERE nis='$nis'";
	        $result_get_nama=QueryDb($sql_get_nama);
	        $row_get_nama=@mysqli_fetch_array($result_get_nama);
	        echo $row_get_nama['nama'];?>
	      </span>
          </span>
      </td>
      <td width="47%" align="right" valign="bottom">
          <div align="right"><a href="laporan_nilai_content.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nis=<?=$nis?>&harian=<?=$harian?>&prespel=<?=$prespel?>">
          <img src="../images/ico/refresh.png" border="0">Refresh</a>
          <a href="#" onClick="newWindow('laporan_nilai_cetak.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nis=<?=$nis?>&harian=<?=$harian?>&prespel=<?=$prespel?>&tglmulai=<?=$tglawal?>&tglakhir=<?=$tglakhir?>','Cetak',820,636,'resizable=1,scrollbars=1,status=1,toolbar=0')">
          <img src="../images/ico/print.png" border="0">Cetak</a>
          <a href="#" onClick="document.location.href='lap_cetak_word.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nis=<?=$nis?>&harian=<?=$harian?>&prespel=<?=$prespel?>&tglmulai=<?=$tglawal?>&tglakhir=<?=$tglakhir?>'">
          <img src="../images/ico/word.png" border="0">Cetak Word</a><br>
          </div>
      </td>
  </tr>
</table>
    <br>
</td></tr>

<tr><td>
<?php require_once("rapor.content.komentar.php"); ?>
</td></tr>

<tr><td>
<?php require_once("rapor.content.nilai.php"); ?>
</td></tr>

<tr><td>
<?php require_once("rapor.content.nilai.deskripsi.php"); ?>
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
<script language='JavaScript'>
    Tables('table', 1, 0);
</script>
</BODY>
</HTML>
<?php
CloseDb();
?>