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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/getheader.php');
OpenDb();
$departemen='yayasan';
$replid=$_REQUEST['replid'];

$sql_pegawai="SELECT * FROM jbssdm.pegawai WHERE replid='$replid'";
$result_pegawai=QueryDb($sql_pegawai);
$row_pegawai=@mysqli_fetch_array($result_pegawai);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<title>JIBAS SIMAKA [Cetak Data Pegawai]</title>
</head>
<body leftmargin="0">
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">

<?=getHeader($departemen)?>
<center>
  <font size="4"><strong>DATA PEGAWAI</strong></font><br />
 </center><br /><br />
<br />
<strong>Bagian : <?=$row_pegawai['bagian']?></strong></font>
<br /><br /> 	
	
    <table width="100%">
    <tr>    	
    	<td align="center" width="150" valign="top">
        <img src="../library/gambar.php?replid=<?=$row_pegawai['replid']?>&table=jbssdm.pegawai" border="0"/>
        <div align="center"><br /><br />Tanda Tangan<br /><br /><br /><br /><br /><br /><br />
        <strong>(<?=$row_pegawai['nama']?><?php if ($row_pegawai['gelar'] <> "")
			  		echo ", ".$row_pegawai['gelar'];
			  	?>)</strong></div>
        </td>
        <td>
    
    <table border="1" class="tab" id="table" width="100%" cellpadding="0" style="border-collapse:collapse" cellspacing="0" >
    <tr>
    	<td valign="top">
        <table border="0" cellpadding="0" style="border-collapse:collapse" cellspacing="0" width="100%">
          <tr class="header" height="30">
            <td align="center"><strong>A. </strong></td>
            <td colspan="4"><strong>DATA PRIBADI PEGAWAI</strong></td>
          </tr>
          <tr height="20">
          	<td rowspan="12"></td>
            <td width="5%">1.</td>
            <td>NIP</td>
            <td>: 
				<?=$row_pegawai['nip']?></td>
          </tr>
          
          <tr height="20">
            <td>2.</td>
            <td colspan="2">Nama Pegawai</td>           
            <td rowspan="11">&nbsp;</td>
            
          </tr>
          <tr height="20">
            <td>&nbsp;</td>
            <td width="20%">a. Lengkap</td>
            <td>:
              <?=$row_pegawai['nama']?>
              <?php if ($row_pegawai['gelar'] <> "")
			  		echo ", ".$row_pegawai['gelar'];
			  ?></td>
          </tr>
          <tr height="20">
            <td>&nbsp;</td>
            <td>b. Panggilan</td>
            <td>:
              <?=$row_pegawai['panggilan']?></td>
          </tr>
          <tr height="20">
            <td >3.</td>
            <td>Jenis Kelamin</td>
            <td >:
              <?php 	if ($row_pegawai['kelamin']=="l")
				echo "Laki-laki"; 
			if ($row_pegawai['kelamin']=="p")
				echo "Perempuan"; 
		?></td>
          </tr>
          <tr height="20">
            <td>4.</td>
            <td>Tempat Lahir</td>
            <td>:
              <?=$row_pegawai['tmplahir']?></td>
          </tr>
          <tr height="20">
            <td>5.</td>
            <td>Tanggal Lahir</td>
            <td>:
              <?=format_tgl($row_pegawai['tgllahir']) ?></td>
          </tr>
          <tr height="20">
            <td>6.</td>
            <td >Agama</td>
            <td>:
              <?=$row_pegawai['agama']?></td>
          </tr>
          <tr height="20">
            <td>7.</td>
            <td>Suku</td>
            <td>:
              <?=$row_pegawai['suku']?></td>
            
          </tr>
          <tr height="20">
            <td>8.</td>
            <td>Nomor Identitas</td>
            <td>:
              <?=$row_pegawai['noid']?></td>
            
          </tr>
          <tr height="20">
            <td>9.</td>
            <td>Status</td>
            <td>:
              <?php 	if($row_pegawai['nikah']=="menikah")
					echo "Menikah";
				if($row_pegawai['nikah']=="belum")
					echo "Belum Menikah";
				if($row_pegawai['nikah']=="tak_ada")
					echo "";?></td>
           
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr class="header" height="30">
            <td width="5%" align="center"><strong>B. </strong></td>
            <td colspan="5"><strong>KETERANGAN TEMPAT TINGGAL</strong></td>
          </tr>
          <tr height="20">
            <td rowspan="5"></td>
            <td>10.</td>
            <td>Alamat</td>
            <td colspan="2">:
              <?=$row_pegawai['alamat']?></td>
           
          </tr>
          <tr height="20">
            <td>11.</td>
            <td>Telepon</td>
            <td colspan="2">:
              <?=$row_pegawai['telpon']?></td>
            
          </tr>
          <tr height="20">
            <td>12.</td>
            <td>Handphone</td>
            <td colspan="2">:
              <?=$row_pegawai['handphone']?></td>
            
          </tr>
          <tr height="20">
            <td>13.</td>
            <td>Email</td>
            <td colspan="2">:
              <?=$row_pegawai['email']?></td>
           
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr height="30" class="header">
            <td align="center"><strong>C.</strong></td>
            <td colspan="5"><strong>KETERANGAN LAINNYA</strong></td>
          </tr>
          <tr height="20">
          	<td></td>
            <td>14.</td>
            <td>Keterangan</td>
            <td colspan="2">: <?=$row_pegawai['keterangan']?></td>
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