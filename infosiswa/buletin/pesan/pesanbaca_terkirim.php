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
require_once('../../include/getheader.php');
require_once('../../include/db_functions.php');
require_once('../../include/sessionchecker.php');
$replid="";
if (isset($_REQUEST['replid']))
	$replid=$_REQUEST['replid'];
OpenDb();

$sql2="SELECT DATE_FORMAT(pg.tanggalpesan, '%Y-%m-%j') as tanggal, pg.judul as judul, pg.pesan as pesan, p.nama as nama FROM jbsvcr.pesan pg, jbssdm.pegawai p WHERE pg.idguru=p.nip AND pg.replid='$replid'";
$result2=QueryDb($sql2);
if (@mysqli_num_rows($result2)>0){
$row2=@mysqli_fetch_array($result2);
} else {
$sql4="SELECT DATE_FORMAT(pg.tanggalpesan, '%Y-%m-%j') as tanggal, pg.judul as judul, pg.pesan as pesan, p.nama as nama, pg.replid as replid FROM jbsvcr.pesan pg, jbsakad.siswa p WHERE pg.nis=p.nis AND pg.replid='$replid'";
//echo $sql4;
$result4=QueryDb($sql4);
$row2=@mysqli_fetch_array($result4);
}
$sql3="SELECT * FROM jbsvcr.lampiranpesan WHERE idpesan='$replid'";
$result3=QueryDb($sql3);
CloseDb();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" type="text/javascript" src="../../script/tools.js"></script>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-weight: bold}
.style4 {
	color: #666666;
	font-weight: bold;
	font-size: 14px;
}
.style5 {color: #006633}
.style6 {color: #006666}
-->
</style>
</head>

<body>
<table id="Table_01" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
    <th height="39" colspan="3" scope="row">
      <input name="button" type="submit" class="but" id="button" value="Kembali" title="Kembali ke Halaman sebelumnya" onclick="window.self.history.back();" />    </th>
   </tr>
    <tr>
		<td>
			<img src="../../images_slice/BGNews_01.png" width="12" height="11" alt=""></td>
		<td background="../../images_slice/BGNews_02.png" width="*" height="11">			</td>
		<td>
			<img src="../../images_slice/BGNews_03.png" width="18" height="11" alt=""></td>
	</tr>
	<tr>
	  <td background="../../images_slice/BGNews_04.png"></td>
	  <td background="../../images_slice/BGNews_05.png" align="left"><div align="left" style="padding-bottom:10px"><span style="color:#339900; font-size:20px; font-weight:bold">.:</span><span style="color:#FF6600; font-family:Calibri; font-size:16px; font-weight:bold;">Pesan Terkirim</span></div></td>
	  <td background="../../images_slice/BGNews_06.png"></td>
  </tr>
	<tr>
		<td background="../../images_slice/BGNews_04.png" width="12">			</td>
		<td width="*" background="../../images_slice/BGNews_05.png">
            <table width="95%" border="0" cellspacing="2" cellpadding="2" align="center">
              <tr>
                <td width="11%" valign="top"><span class="style1"><span class="style5">Dari</span></span></td>
                <td width="2%" valign="top"><span class="style5">:</span></td>
                <td width="87%"><span class="style1">
                  <?=$row2['nama']?>
                </span></td>
              </tr>
              <tr>
                <td valign="top"><span class="style6">Judul</span></td>
                <td valign="top"><span class="style5">:</span></td>
                <td><span class="style1">
                  <?=$row2['judul']?>
                </span></td>
              </tr>
              <tr>
                <td valign="top"><span class="style5">Pesan</span></td>
                <td valign="top"><span class="style5">:</span></td>
                <td><font style="font-size: 11px; line-height: 18px">
                  <?= str_replace("`", "'", (string) $row2['pesan'])  ?>
                </span></td>
              </tr>
            </table></td>
		<td background="../../images_slice/BGNews_06.png" width="18">			</td>
	</tr>
	<tr>
		<td>
			<img src="../../images_slice/BGNews_07.png" width="12" height="20" alt=""></td>
		<td background="../../images_slice/BGNews_08.png" width="*" height="20">			</td>
		<td>
			<img src="../../images_slice/BGNews_09.png" width="18" height="20" alt=""></td>
	</tr>
</table>

</body>
</html>