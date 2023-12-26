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
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

$replid=$_REQUEST['replid'];

OpenDb();

$sql="SELECT * FROM $db_name_sdm.pegawai WHERE replid='$replid'";
//echo $sql; exit;
$result=QueryDb($sql);
$row = mysqli_fetch_array($result); 
CloseDb();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">

<title>DATA PEGAWAI</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function cetak(replid) {
	//var replid = document.getElementById('replid').value;
	newWindow('detail_pegawai_cetak.php?replid='+replid, 'CetakDetailPegawai','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')
	//newWindow('cetak_detail_siswa.php?replid='+replid, 'CetakDetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>

<body>

<table width="100%"> 
<tr height="50">
	<td><div align="center" class="nav_title"><font size="4"><strong>DATA PEGAWAI</strong></font></div>
	<br /></td>
</tr>
<tr>
	<td align="right"><input type="hidden" name="replid" id="replid" value="<?=$replid?>" />
      <a href="#" onclick="cetak('<?=$replid?>')"><img src="../img/print.png" border="0"/>&nbsp;Cetak</a>&nbsp;&nbsp;
      <a href="#" onclick="window.close();"><img src="../img/hapus.png" width="16" height="16" border="0" />&nbsp;Tutup</a>      </div>	</td>
  </tr>
</table>  
<br />
<table border="0" width="100%" class="tab2" >
  <tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Pribadi Pegawai</strong></font>
      <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
  </tr>
  <tr height="20">
    <td width="0" rowspan="8" bgcolor="#FFFFFF" ></td>
    <td width="2%">1.</td>
    <td colspan="2">Nama Pegawai</td>
    <td width="46%" rowspan="10" bgcolor="#FFFFFF"><div align="center"><img src="gambar.php?nip=<?=$row['nip']?>" width="120" height="139" /></div></td>
  </tr>
  <tr height="20">
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td width="16%">a. Lengkap</td>
    <td width="36%">:
      <?=$row['nama']?><?php if ($row['gelar']!="") echo ", ".$row['gelar']; ?></td>
  </tr>
  <tr height="20">
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td>b. Panggilan</td>
    <td>:
      <?=$row['panggilan']?></td>
  </tr>
  <tr height="20">
    <td >2.</td>
    <td>Jenis Kelamin</td>
    <td >:
      <?php 	if ($row['kelamin']=="l")
				echo "Laki-laki"; 
			if ($row['kelamin']=="p")
				echo "Perempuan"; 
		?></td>
  </tr>
  <tr height="20">
    <td>3.</td>
    <td>Tempat Lahir</td>
    <td>:
      <?=$row['tmplahir']?></td>
  </tr>
  <tr height="20">
    <td>4.</td>
    <td>Tanggal Lahir</td>
    <td>:
      <?=LongDateFormat($row['tgllahir']) ?></td>
  </tr>
  <tr height="20">
    <td>5.</td>
    <td>Menikah</td>
    <td>:
      <?php 	if ($row['nikah']=="menikah")
				echo "Sudah Menikah"; 
			if ($row['nikah']=="belum")
				echo "Belum Menikah"; 
		?></td>
  </tr>
  <tr height="20">
    <td>6.</td>
    <td >Agama</td>
    <td>:
      <?=$row['agama']?></td>
  </tr>
  <tr height="20">
    <td bgcolor="#FFFFFF" ></td>
    <td>7.</td>
    <td >Suku</td>
    <td>:
    <?=$row['suku']?></td>
  </tr>
  <tr height="20">
    <td bgcolor="#FFFFFF" ></td>
    <td>8.</td>
    <td >No. Identitas</td>
    <td>:
    <?=$row['noid']?></td>
  </tr>
  
  <tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Tempat Tinggal</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
  </tr>
  <tr height="20">
    <td rowspan="5" bgcolor="#FFFFFF"></td>
    <td>9.</td>
    <td>Alamat</td>
    <td colspan="2">:
      <?=$row['alamat']?></td>
  </tr>
  <tr height="20">
    <td>10.</td>
    <td>Telepon</td>
    <td colspan="2">:
      <?=$row['telpon']?></td>
  </tr>
  <tr height="20">
    <td>11.</td>
    <td>Handphone</td>
    <td colspan="2">:
      <?=$row['handphone']?></td>
  </tr>
  <tr height="20">
    <td>12.</td>
    <td>Email</td>
    <td colspan="2">:
      <?=$row['email']?></td>
  </tr>
  <tr>
    <td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  
  
  <tr height="30">
    <td align="left" bgcolor="#FFFFFF" colspan="5"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Lainnya</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
  </tr>
  <tr height="20">
    <td></td>
    <td>13.</td>
    <td >Keterangan</td>
    <td colspan="2">:
      <?=$row['keterangan']?></td>
  </tr>
</table>
<script language='JavaScript'>
	    Tables('table', 0, 0);
	</script>
</body>
</html>