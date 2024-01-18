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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
$replid=$_REQUEST['replid'];
$sender=$_REQUEST['sender'];
OpenDb();
$sql = "SELECT * FROM pustaka WHERE replid='$replid'";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$judul = stripslashes((string) $row['judul']);
$harga = $row['harga'];
$katalog = $row['katalog'];
$penerbit = $row['penerbit'];
$penulis = $row['penulis'];
$tahun = $row['tahun'];
$format = $row['format'];
$keyword = stripslashes((string) $row['keyword']);
$keteranganfisik = stripslashes((string) $row['keteranganfisik']);
$abstraksi = stripslashes((string) $row['abstraksi']);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Detail Pustaka</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../scr/tools.js"></script>
<script language="javascript" src="pustaka.daftar.js"></script>
<style type="text/css">
<!--
.style1 {
	color: #666666;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<div id="title" align="right">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td  align="left">
			<?php if($sender!='opac'){ ?>
            <a href="javascript:cetak(<?=$replid?>)"><img src="../img/ico/print1.png" width="16" height="16" border="0" />&nbsp;Cetak</a>&nbsp;&nbsp;
            <?php } ?>
			<!--<a href="javascript:cetak_nomor(<?=$replid?>)"><img src="../img/ico/print1.png" width="16" height="16" border="0" />&nbsp;Cetak&nbsp;Label</a>&nbsp;&nbsp;-->
			<a href="javascript:window.close()"><img src="../img/ico/hapus.png" border="0" />&nbsp;Tutup</a>
		</td>
        <td  align="right">
       	  <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
    		<font style="font-size:18px; color:#999999">Detail Pustaka</font><br />
   		  <br /><br />
        </td>
      </tr>
    </table>
</div>
<table width="100%" border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td width="8%" align="right" valign="top"><span class="style1">Judul</span></td>
    <td colspan="2"><?=$judul?>    </td>
  </tr>
  <tr>
    <td align="right" valign="top"><?php if($sender!='opac'){ ?><span class="style1">Harga&nbsp;Satuan</span><?php } ?></td>
    <td width="92%"><?php if($sender!='opac'){ ?><?=FormatRupiah($harga)?><?php } ?></td>
    <td width="92%" rowspan="7" valign="top">
    	<div style="margin-left:10px">
            <table width="125" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="125" align="center" valign="middle" bgcolor="#CCCCCC"><img src="../inc/gambar.php?replid=<?=$replid?>&table=pustaka" /></td>
              </tr>
            </table>
   	    </div>    </td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong class="style1">Katalog</strong></td>
    <td>
	<?php 
		$sql = "SELECT kode,nama FROM katalog WHERE replid='$katalog'";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_row($result);
		echo $row[0]." - ".$row[1];
	?>    </td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong class="style1">Penerbit</strong></td>
    <td>
	<?php 
		$sql = "SELECT kode,nama FROM penerbit WHERE replid='$penerbit'";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_row($result);
		echo $row[0]." - ".$row[1];
	?>    </td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong class="style1">Penulis</strong></td>
    <td>
	<?php 
		$sql = "SELECT kode,nama FROM penulis WHERE replid='$penulis'";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_row($result);
		echo $row[0]." - ".$row[1];
	?>    </td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong class="style1">Tahun&nbsp;Terbit</strong></td>
    <td><?=$tahun?></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong class="style1">Format</strong></td>
    <td>
	<?php 
		$sql = "SELECT kode,nama FROM format WHERE replid='$format'";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_row($result);
		echo $row[0]." - ".$row[1];
	?>    </td>
  </tr>
  <tr>
    <td align="right" valign="top" class="style1">Keyword</td>
    <td><?=$keyword?></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong class="style1">Keterangan&nbsp;Fisik</strong></td>
    <td colspan="2"><?=$keteranganfisik?></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong class="style1">Abstraksi</strong></td>
    <td colspan="2"><?=chg_p_to_div($abstraksi)?></td>
  </tr>
  <tr>
    <td align="right" valign="top"><strong class="style1">Alokasi&nbsp;Jumlah</strong></td>
<td>
        <?php
		$sql = "SELECT p.nama,COUNT(d.replid) FROM daftarpustaka d, perpustakaan p WHERE d.pustaka='$replid' AND d.perpustakaan=p.replid GROUP BY d.perpustakaan ORDER BY p.nama";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		if ($num>0){
		?>
        <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab">
          <tr>
            <td height="25" align="center" class="header">Perpustakaan</td>
            <td height="25" align="center" class="header">Jumlah</td>
          </tr>
          <?php
		  while ($row = @mysqli_fetch_row($result)){
		  ?>
          <tr>
            <td height="20" align="center"><?=$row[0]?></td>
            <td height="20" align="center"><?=$row[1]?></td>
          </tr>
          <?php
		  }
		  ?>
      </table>
        <?php
		}
		?>    </td>
    <td>&nbsp;</td>
  </tr>
</table>

</body>
</html>