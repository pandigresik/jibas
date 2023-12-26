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

$replid=$_REQUEST['replid'];
OpenDb();
$sql_pegawai="SELECT * FROM jbssdm.pegawai WHERE replid='$replid'";
$result_pegawai=QueryDb($sql_pegawai);
$row_pegawai=@mysqli_fetch_array($result_pegawai);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<title>JIBAS SIMAKA [Data Pegawai]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
</head>
<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="center">
<tr>
	<td valign="top" colspan="2">

<?php include("../library/headercetak.php") ?>
<br />
    <table border="0" cellpadding="0" style="border-collapse:collapse" cellspacing="0" width="100%">
    <tr height="30">
    	<td colspan="6" align="left" bgcolor="#FFFFFF">
        <font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Pribadi Pegawai</strong></font>
        <hr width="300" style="line-height:1px; border-style:solid; border-color:#000000" align="left" />        </td>
    	</tr>
  	<tr height="20">
    	<td width="5%" rowspan="12" bgcolor="#FFFFFF">&nbsp;</td>
    	<td width="5%">1.</td>
    	<td width="15%">NIP</td>
    	<td height="25">:&nbsp;<?=$row_pegawai['nip']?></td>
    	<td width="200" rowspan="11" align="center" bgcolor="#FFFFFF">
    	<img src="../library/gambar.php?replid=<?=$row_pegawai['replid']?>&table=jbssdm.pegawai" width="113" height="170" border="0"/>        </td>
  	</tr>
  	<tr height="20">
    	<td>2.</td>
    	<td colspan="2">Nama Pegawai</td>
 	</tr>
  	<tr height="20" >    
    	<td bgcolor="#FFFFFF" >&nbsp;</td>    
    	<td >a. Lengkap </td>
    	<td>:&nbsp;<?=$row_pegawai['nama']?>, <?=$row_pegawai['gelar']?></td>
    </tr>
  	<tr height="20">
	    <td bgcolor="#FFFFFF">&nbsp;</td>
    	<td>b. Panggilan </td>
    	<td>:&nbsp;<?=$row_pegawai['panggilan']?></td>
    </tr>
    <tr height="20">
        <td>3.</td>
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
    	<td>:&nbsp;<?=$row_pegawai['tmplahir']?></td>
    </tr>
  	<tr height="20">    	
    	<td>5.</td>
    	<td>Tanggal Lahir</td>
    	<td>:&nbsp;<?=format_tgl($row_pegawai['tgllahir'])?></td>
  	</tr>
  	<tr height="20">    	
	    <td>6.</td>
    	<td>Agama</td>
    	<td height="25" >:&nbsp;<?=$row_pegawai['agama']?></td>
    </tr>
  	<tr height="20" >    	
    	<td>7.</td>
    	<td>Suku</td>
    	<td>:&nbsp;<?=$row_pegawai['suku']?></td>
  	</tr>
  	<tr height="20" >    	
	    <td>8.</td>
    	<td>Nomor Identitas</td>
    	<td>:&nbsp;<?=$row_pegawai['noid']?></td>
  	</tr>
  	<tr height="20">
    	<td>9.</td>
    	<td>Status</td>
    	<td>:&nbsp;
			<?php 	if($row_pegawai['nikah']=="menikah")
					echo "Menikah";
				if($row_pegawai['nikah']=="belum")
					echo "Belum Menikah";
				if($row_pegawai['nikah']=="tak_ada")
					echo "";?></td>
  	</tr>
  	<tr>
    	<td bgcolor="#FFFFFF" >&nbsp;</td><td bgcolor="#FFFFFF" >&nbsp;</td><td bgcolor="#FFFFFF" >&nbsp;</td><td bgcolor="#FFFFFF" >&nbsp;</td>    	
  	</tr>
  	<tr height="30" >
    	<td colspan="5" align="left" bgcolor="#FFFFFF">
        <font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Tempat Tinggal</strong></font>
        <hr width="300" style="line-height:1px; border-style:solid; border-color:#000000" align="left" />        </td>
    	</tr>
  	<tr height="20">
    	<td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>    	
    	<td>10.</td>
    	<td>Alamat</td>
    	<td colspan="2">:&nbsp;<?=$row_pegawai['alamat']?></td>
 	</tr>
 	<tr height="20" >    	
    	<td>11.</td>
    	<td>Telepon</td>
    	<td colspan="2">:&nbsp;<?=$row_pegawai['telpon']?></td>
  	</tr>
  	<tr height="20" >    	
	    <td>12.</td>
    	<td>HP</td>
    	<td colspan="2">:&nbsp;<?=$row_pegawai['handphone']?></td>
  	</tr>
  	<tr height="20">    	
    	<td>13.</td>
    	<td>Email</td>
   	 	<td colspan="2">:&nbsp;<?=$row_pegawai['email']?></td>
  	</tr>
  	<tr >
    	<td  colspan="2" bgcolor="#FFFFFF">&nbsp;</td><td bgcolor="#FFFFFF" colspan="2">&nbsp;</td>
  	</tr>
  	<tr height="30">
    	<td colspan="5" align="left" bgcolor="#FFFFFF">
        <font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Lainnya</strong></font>
        <hr width="300" style="line-height:1px; border-style:solid; border-color:#000000" align="left" />
        </td>
    	</tr>
   	<tr height="20">
    	<td bgcolor="#FFFFFF">&nbsp;</td>
    	<td>14.</td>
    	<td>Keterangan</td>
    	<td colspan="2" rowspan="2">:&nbsp;<?=$row_pegawai['keterangan']?></td>
  	</tr>
    <tr>
    	<td></td>
    </tr>
	</table>
    </td>
</tr>
</table>

</body>
</html>