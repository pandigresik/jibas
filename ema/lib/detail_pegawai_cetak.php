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
require_once('../inc/rupiah.php');

$replid=$_REQUEST['replid'];
$departemen = "yayasan";
OpenDb();

$sql="SELECT * FROM $db_name_sdm.pegawai WHERE replid='$replid'";
$result=QueryDB($sql);
$row = mysqli_fetch_array($result); 
CloseDb();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<title>JIBAS [Cetak Data Pegawai]</title>
</head>
<body>
<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">

<?php getHeader($departemen) ?>

<center>
  <font size="4" class="nav_title"><strong>DATA PEGAWAI</strong></font><br />
 </center><br /><br />
    <table width="100%">
    <tr>    	
    	<td align="center" width="150" valign="top">
        <img src="gambar.php?nip=<?=$row['nip']?>" width="120" height="120" /> 
        <div align="center"><br /><br /><br />Tanda Tangan<br /><br /><br /><br /><br />
        <strong>(<?=$row['nama']?>)</strong></div>
        </td>
        <td>
    <table border="1" class="tab" id="table" width="100%" cellpadding="0" style="border-collapse:collapse" cellspacing="0" >
    <tr>
    	<td valign="top">
        <table border="0" cellpadding="0" style="border-collapse:collapse" cellspacing="0" width="100%">
          <tr class="header" height="30">
            <td align="center"><strong>A. </strong></td>
            <td colspan="4"><strong>KETERANGAN PRIBADI</strong></td>
          </tr>
          <tr height="20">
            <td rowspan="10"></td>
            <td width="4%">1.</td>
            <td colspan="2">Nama Pegawai </td>           
            <td width="5%" rowspan="10">&nbsp;</td>
          </tr>
          <tr height="20">
            <td>&nbsp;</td>
            <td width="21%">a. Lengkap</td>
            <td width="67%">:
              <?=$row['nama']?> <?php if ($row['gelar']!="") echo ", ".$row['gelar']; ?></td>
          </tr>
          <tr height="20">
            <td>&nbsp;</td>
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
            <td>7.</td>
            <td >Suku</td>
            <td>:
              <?=$row['suku']?></td>
          </tr>
          <tr height="20">
            <td>8.</td>
            <td >No. Identitas</td>
            <td>:
              <?=$row['noid']?></td>
          </tr>
          
          
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr class="header" height="30">
            <td width="3%" align="center"><strong>B. </strong></td>
            <td colspan="5"><strong>KETERANGAN TEMPAT TINGGAL</strong></td>
          </tr>
          <tr height="20">
            <td rowspan="5"></td>
            <td>9.</td>
            <td>Alamat</td>
            <td colspan="2">:
              <?=$row['alamatsiswa']?></td>
          </tr>
          <tr height="20">
            <td>10.</td>
            <td>Telepon</td>
            <td colspan="2">:
              <?=$row['telponsiswa']?></td>
          </tr>
          <tr height="20">
            <td>11.</td>
            <td>Handphone</td>
            <td colspan="2">:
              <?=$row['hpsiswa']?></td>
          </tr>
          <tr height="20">
            <td>12.</td>
            <td>Email</td>
            <td colspan="2">:
              <?=$row['emailsiswa']?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          
          
          <tr height="30" class="header">
            <td align="center"><strong>C.</strong></td>
            <td colspan="5"><strong>KETERANGAN LAINNYA</strong></td>
          </tr>
          <tr height="20">
            <td>13.</td>
            <td >Keterangan</td>
            <td colspan="2">: <?=$row['keterangan']?></td>
          </tr>        
        </table></td>
  	</tr>
	</table>
    </td>
    </tr>
    </table>
  	</td>
</tr>
</table>
<script language="javascript">
	window.print();
</script>
</body>
</html>