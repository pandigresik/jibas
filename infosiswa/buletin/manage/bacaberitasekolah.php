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
$replid="";
if (isset($_REQUEST['replid']))
	$replid=$_REQUEST['replid'];
if (isset($_REQUEST['idpengirim']))
    $idpengirim=$_REQUEST['idpengirim'];
OpenDb();
$sql="SELECT YEAR(b.tanggal) as thn,MONTH(b.tanggal) as bln,DAY(b.tanggal) as tgl,b.replid as replid,b.judul as judul,b.abstrak as abstrak ,b.isi as berita, p.nama as nama FROM jbsvcr.beritasekolah b, jbsakad.siswa p WHERE p.nis=b.idpengirim AND b.replid='$replid'";
$result=QueryDb($sql);
$row=@mysqli_fetch_array($result);
CloseDb();
$namabulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];	
$tglberita=$row['tgl']." ".$namabulan[$row['bln']-1]." ".$row['thn'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../../script/tools.js"></script>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {
	font-size: 14px;
	font-weight: bold;
}
-->
img{
  width:75%;
  height:75%;
  text-align:left;
}
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <td height="39" colspan="3" scope="row"><div align="center">
      <label>
      <input name="button" type="submit" class="but" id="button" value="Tutup" onclick="window.close();" />
      </label>
    </div></td>
  </tr>
  <tr>
    <td height="39" style="background-image:url(../../images/buat_berita_01.jpg); background-repeat:no-repeat;" scope="row">&nbsp;</td>
    <td style="background-image:url(../../images/buat_berita_03.jpg); background-repeat:repeat-x;"><div align="left"><span class="style3">Berita Guru</span></div></td>
    <td width="21" style="background-image:url(../../images/buat_berita_05.jpg); background-repeat:no-repeat;">&nbsp;</td>
  </tr>
  <tr>
    <td width="23" scope="row" style="background-image:url(../../images/buat_berita_06.jpg); background-repeat:repeat-y;">&nbsp;</td>
    <td bgcolor="#F1F1F1">
        <table width="100%" border="0" cellspacing="0" style="background-color:#f1f1f1;">
          <tr>
            <td width="65" scope="row" align="left"><strong>Dari </strong></td>
            <td width="8" scope="row" align="left">:</td>
            <td width="859" scope="row" align="left"><?=$row['nama']?></td>
          </tr>
          <tr>
            <td align="left" scope="row"><strong>Tanggal </strong></td>
            <td align="left" scope="row">:</td>
            <td scope="row" align="left"><?=$tglberita?></td>
          </tr>
          <tr>
            <td align="left" scope="row"><strong>Judul </strong></td>
            <td align="left" scope="row">:</td>
            <td scope="row" align="left"><?=$row['judul']?></td>
          </tr>
          <tr>
            <td align="left" valign="top" scope="row"><strong>Abstrak </strong></td>
            <td align="left" valign="top" scope="row">:</td>
            <td scope="row" align="left"><?=$row['abstrak']?><br><hr></td>
          </tr>
          <tr>
            <td align="left" valign="top" scope="row"><strong>Berita </strong></td>
            <td align="left" valign="top" scope="row">:</td>
            <td scope="row" align="left"><?=$row['berita']?><br><hr></td>
          </tr>
    </table>    </td>
    <td style="background-image:url(../../images/buat_berita_08.jpg); background-repeat:repeat-y;">&nbsp;</td>
  </tr>
  <tr>
    <td height="16" style="background-image:url(../../images/buat_berita_11.jpg); background-repeat:no-repeat;" scope="row">&nbsp;</td>
    <td style="background-image:url(../../images/buat_berita_12.jpg); background-repeat:repeat-x;">&nbsp;</td>
    <td style="background-image:url(../../images/buat_berita_14.jpg); background-repeat:no-repeat">&nbsp;</td>
  </tr>
</table>
</body>
</html>