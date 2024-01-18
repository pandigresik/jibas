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
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('../../include/sessionchecker.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../../script/tools.js"></script>
<script language="javascript">
function tampil(replid) {
	newWindow('../../library/pegawai_view.php?replid='+replid, 'DetailPegawai','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tampilsiswa(replid) {
	newWindow('../../library/detail_siswa.php?replid='+replid, 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function go(content) {
	parent.kanan.location.href=content+".php";
}


</script>
<style type="text/css">
<!--
.style3 {font-family: Verdana; color: #666666; font-size: 12px;}
.style6 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color:#FF6633;}
.style7 {
	font-family: Calibri;
	font-weight: bold;
	font-size: 24px;
	color: #996600;
}
.style8 {
	font-family: Verdana;
	font-size: 14px;
	font-weight: bold;
}
.style11 {font-size: 12px}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" ><!--onload="MM_preloadImages('inbox_on.png','compose_on.png','sent_on.png','draft_on.png','inbox_off.png')">-->
<table width="150" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td colspan="2"><span class="style7">.: Pesan</span><br />
    <br /></td>
  </tr>
  <tr>
	<td width="31"><a href="#" onclick="go('pesan_inbox');" title="Kotak Masuk Anda" ><img src="../../images/ico/inbox.png" border="0" /></a></td>
    <td width="119" class="style8"><a style="text-decoration:none; color:#666666" href="#" title="Kotak Masuk Anda" onclick="go('pesan_inbox');" >Kotak Masuk</a></td>
  </tr>
  <tr>
    <td><a href="#" onclick="go('pesanguru_add_main');" title="Tulis Pesan ke Guru" ><img src="../../images/ico/compose.png" width="31" height="25" border="0" /></a></td>
    <td class="style8"><a style="text-decoration:none; color:#666666" href="#" title="Tulis Pesan Baru" class="style11" onclick="go('pesanguru_add_main');" >Tulis Pesan Guru</a></td>
  </tr>
  <tr>
    <td><a href="#" onclick="go('pesansiswa_add_main');" title="Tulis Pesan ke Siswa" ><img src="../../images/ico/compose.png" width="31" height="25" border="0" /></a></td>
    <td class="style8"><a style="text-decoration:none; color:#666666" href="#" title="Tulis Pesan Baru" class="style11" onclick="go('pesansiswa_add_main');" >Tulis Pesan Siswa</a></td>
  </tr>
  <tr>
    <td><a href="#" onclick="go('pesan_terkirim');" title="Daftar Pesan Terkirim" ><img src="../../images/ico/sent.png" width="31" height="25" border="0" /></a></td>
    <td class="style8"><a style="text-decoration:none; color:#666666" href="#" title="Daftar Pesan Terkirim" onclick="go('pesan_terkirim');" >Pesan Terkirim</a></td>
  </tr>
</table>
<?php
$bulan=date("m");
$tanggal=date("j");
OpenDb();
$sql="SELECT replid,nip,nama FROM jbssdm.pegawai WHERE DAY(tgllahir)='$tanggal' AND MONTH(tgllahir)='$bulan' ORDER BY nama";
$result=QueryDb($sql);
$sql2="SELECT replid,nis,nama FROM jbsakad.siswa WHERE DAY(tgllahir)='$tanggal' AND MONTH(tgllahir)='$bulan' ORDER BY nama";
$result2=QueryDb($sql2);
if (@mysqli_num_rows($result)>0 || @mysqli_num_rows($result2)>0){
?>
<table width="150" border="0" cellspacing="0">
  <tr>
    <th  scope="row">&nbsp;</th>
  </tr>
  <tr>
    <td><span class="style3">Ulang&nbsp;Tahun&nbsp;Hari&nbsp;ini</span></td>
  </tr>
  <tr>
    <th valign="top" scope="row">
    <div style="overflow:aotu; overflow-x:hidden; height:200px">
    <table width="95%" border="0" cellspacing="2" cellpadding="2">
  
  <?php

	//echo $tanggal;
	//exit;

	while ($row=@mysqli_fetch_array($result)){
	echo "<tr>
    <td class=\"style8\"><a class=\"style11\" style=\"text-decoration:none; color:#666666\" href='#' onclick=tampil('".$row['replid']."')>".$row['nip']."-".substr((string) $row['nama'],0,20)."</a></td>
  </tr><tr>
    <td background=\"../../images/box_hr1.gif\" style=\"background-repeat:repeat-x; background-position:center\">&nbsp;</td>
  </tr>";
	}
	while ($row2=@mysqli_fetch_array($result2)){
	echo "<tr>
    <td class=\"style8\"><a class=\"style11\" style=\"text-decoration:none; color:#666666\" href='#' onclick=tampilsiswa('".$row2['replid']."')>".$row2['nis']."-".$row2['nama']."</a></td>
  </tr><tr>
    <td background=\"../../images/box_hr1.gif\" style=\"background-repeat:repeat-x; background-position:center\">&nbsp;</td>
  </tr>";
	}
	?>
    </table>
    <?php
	} 
	?>
</div>
</th>
  </tr>
</table>
</body>
</html>