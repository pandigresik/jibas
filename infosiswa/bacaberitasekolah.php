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
$replid="";
if (isset($_REQUEST['replid']))
	$replid=$_REQUEST['replid'];
OpenDb();
$sql="SELECT YEAR(b.tanggal) as thn,MONTH(b.tanggal) as bln,DAY(b.tanggal) as tgl,b.replid as replid,b.judul as judul,b.abstrak as abstrak ,b.isi as berita, b.jenisberita as jenis, p.nama as nama FROM jbsvcr.beritasekolah b, jbsakad.siswa p WHERE p.nis=b.idpengirim AND b.replid='$replid'";
$result=QueryDb($sql);
$num = @mysqli_num_rows($result);
if ($num>0){
	$row=@mysqli_fetch_array($result);
} else {
	$sql="SELECT YEAR(b.tanggal) as thn,MONTH(b.tanggal) as bln,DAY(b.tanggal) as tgl,b.replid as replid,b.judul as judul,b.abstrak as abstrak ,b.isi as berita, b.jenisberita as jenis, p.nama as nama FROM jbsvcr.beritasekolah b, jbssdm.pegawai p WHERE p.nip=b.idpengirim AND b.replid='$replid'";
	$result=QueryDb($sql);
	$row=@mysqli_fetch_array($result);
}
CloseDb();
$namabulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];	
$tglberita=$row['tgl']." ".$namabulan[$row['bln']-1]." ".$row['thn'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if ($row['jenis']==0){ ?>Rubrik Gaul <?php } elseif ($row['jenis']==1) { ?> Berita Penting !!! <?php } else { ?> Rubrik Sekolah <?php } ?></title>
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
		<td width="*" height="11" colspan="2" background="images/BGNews_02.png">			</td>
		<td>
			<img src="images/BGNews_03.png" width="18" height="11" alt=""></td>
	</tr>
	<tr>
	  <td background="images/BGNews_04.png"></td>
	  <td background="images/BGNews_05.png">
      <div align="left" style="padding-bottom:10px"><span style="color:#339900; font-size:20px; font-weight:bold">.:</span><span style="color:#FF6600; font-family:Calibri; font-size:16px; font-weight:bold; "><?php if ($row['jenis']==0){ ?>Rubrik Gaul <?php } elseif($row['jenis']==1) { ?> Berita Penting !!! <?php } else { ?> Rubrik Sekolah <?php } ?></span></div>      </td>
	  <td align="right" valign="top" background="images/BGNews_05.png"><img src="images/ico/hapus.png" width="16" height="16" style="cursor:pointer" onclick="window.close();" /></td>
	  <td background="images/BGNews_06.png"></td>
  </tr>
	<tr>
		<td background="images/BGNews_04.png" width="12">			</td>
		<td width="*" colspan="2" background="images/BGNews_05.png">
            <table width="100%" border="0" cellspacing="0" >
              <tr>
                <td width="62" scope="row" align="left"><strong>Dari </strong></td>
                <td width="17" scope="row" align="left"><strong>:</strong></td>
                <td width="853" scope="row" align="left"><?=$row['nama']?></td>
              </tr>
              <tr>
                <td align="left" scope="row"><strong>Tanggal </strong></td>
                <td align="left" scope="row"><strong>:</strong></td>
                <td scope="row" align="left"><?=$tglberita?></td>
              </tr>
              <tr>
                <td align="left" scope="row"><strong>Judul </strong></td>
                <td align="left" scope="row"><strong>:</strong></td>
                <td scope="row" align="left"><?=$row['judul']?></td>
              </tr>
              <tr>
                <td align="left" valign="top" scope="row"><strong>Abstrak </strong></td>
                <td align="left" valign="top" scope="row"><strong>:</strong></td>
                <td scope="row" align="left" style="background-image:url(images/box_hr1.gif); background-repeat:repeat-x"><?=$row['abstrak']?></td>
              </tr>
              <tr>
                <td align="left" valign="top" scope="row"><strong>Berita </strong></td>
                <td align="left" valign="top" scope="row"><strong>:</strong></td>
                <td scope="row" align="left" style="background-image:url(images/box_hr1.gif); background-repeat:repeat-x"><?php
                    $brt = str_replace("../../","",(string) $row['berita']);
                    echo($brt);
                ?></td>
              </tr>
              
        	</table>		</td>
		<td background="images/BGNews_06.png" width="18">			</td>
	</tr>
	<tr>
		<td>
			<img src="images/BGNews_07.png" width="12" height="20" alt=""></td>
		<td width="*" height="17" colspan="2" background="images/BGNews_08.png">			</td>
		<td>
			<img src="images/BGNews_09.png" width="18" height="20" alt=""></td>
	</tr>
</table>
</body>
</html>