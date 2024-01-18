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
require_once('include/common.php');
require_once('include/sessioninfo.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessionchecker.php');

$replid = "";
if (isset($_REQUEST['replid']))
	$replid = $_REQUEST['replid'];
	
OpenDb();

$sql = "SELECT YEAR(b.tanggal) as thn, MONTH(b.tanggal) as bln, DAY(b.tanggal) as tgl,
			   b.replid as replid, b.judul as judul, b.abstrak as abstrak, b.isi as berita, p.nama as nama
		  FROM jbsvcr.beritaguru b, jbssdm.pegawai p
		 WHERE p.nip=b.idguru
           AND b.replid=$replid";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
CloseDb();

$tglberita = $row['tgl'] . " " . NamaBulan($row['bln'] - 1) . " " . $row['thn'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Berita Guru</title>
<script language="javascript" src="script/tools.js"></script>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<table id="Table_01" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<img src="images/BGNews_01.png" width="12" height="11" alt=""></td>
		<td height="11" colspan="2" background="images/BGNews_02.png">			</td>
		<td>
			<img src="images/BGNews_03.png" width="18" height="11" alt=""></td>
	</tr>
	<tr>
	  <td background="images/BGNews_04.png"></td>
	  <td width="759" align="left" background="images/BGNews_05.png">
      <div align="left" style="padding-bottom:10px"><span style="color:#339900; font-size:20px; font-weight:bold">.:</span> <span style="color:#FF6600; font-family:Calibri; font-size:16px; font-weight:bold; ">Berita Guru</span></div>
      </td>
	  <td width="425" align="right" valign="top" background="images/BGNews_05.png">&nbsp;</td>
	  <td background="images/BGNews_06.png"></td>
  </tr>
	<tr>
		<td background="images/BGNews_04.png" width="12"></td>
		<td colspan="2" background="images/BGNews_05.png">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr>
				<td align="left">
					<font style="color:#a20707; font-size:9px;"><i><?=$tglberita?></i></font>
				</td>
			</tr>
			<tr>
				<td align="left">
					<font style="font-size:16px;"><strong><?=$row['judul']?></strong></font>
				</td>
			</tr>
			<tr>
				<td align="left">
					<font style="color: #757575; font-size:11px;"><?=$row['nama']?></font>
					<br><br>
				</td>
			</tr>
			<tr>
				<td align="left">
					<font style="font-size:11px; line-height: 18px;">
					<?php
					$berita = $row['berita'];
					$berita = str_replace("#sq;", "'", (string) $berita);
					echo $berita;
					?>
					</font>
				</td>
			</tr>
            </table>
		</td>
		<td background="images/BGNews_06.png" width="18"></td>
	</tr>
	<tr>
		<td>
			<img src="images/BGNews_07.png" width="12" height="20" alt=""></td>
		<td height="17" colspan="2" background="images/BGNews_08.png">			</td>
		<td>
			<img src="images/BGNews_09.png" width="18" height="20" alt=""></td>
	</tr>
</table>
</body>
</html>