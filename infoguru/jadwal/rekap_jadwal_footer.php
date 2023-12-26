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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once("../include/sessionchecker.php");

if (isset($_REQUEST['info']))
	$info=$_REQUEST['info'];

OpenDb();	
$sql1 = "SELECT i.deskripsi, t.replid, t.departemen, t.tglmulai, t.tglakhir FROM infojadwal i, tahunajaran t  WHERE i.replid = '$info' AND t.replid = i.idtahunajaran";
$result1 = QueryDb($sql1);
$row1 = mysqli_fetch_array($result1); 
$info_jadwal = $row1['deskripsi'];
$departemen = $row1['departemen'];
$tahunajaran = $row1['replid'];
$periode = TglTextLong($row1['tglmulai']).' s/d '. TglTextLong($row1['tglakhir']); 
 	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rekap Jadwal Guru</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function change(){
	var info_jadwal = document.getElementById('info').value;
	document.location.href="rekap_jadwal_footer.php?info_jadwal="+info_jadwal;
	parent.footer.location.href="blank_rekapjadwal.php";
}

function cetak() {
	var info = document.getElementById('info').value;		
	newWindow('rekap_jadwal_cetak.php?info='+info, 'CetakRekapJadwal', '790', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

</script>
</head>

<body>
<input type="hidden" name="info" id="info" value="<?=$info ?>" />
<table border="0" width="100%" align="center" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
<?php 	OpenDb();
		
	$sql = "SELECT p.nip, p.nama, SUM(IF(j.status = 0, 1, 0)), SUM(IF(j.status = 1, 1, 0)), SUM(IF(j.status = 2, 1, 0)), SUM(j.njam), COUNT(DISTINCT(j.idkelas)), COUNT(DISTINCT(j.hari)) FROM jadwal j, jbssdm.pegawai p WHERE j.nipguru = p.nip AND j.infojadwal = '$info' GROUP BY j.nipguru ORDER BY p.nama";	
	
	$result = QueryDb($sql);
	$jum = mysqli_num_rows($result);
	if ($jum > 0) { 
?>	
    <table width="100%" border="0" align="center">
    <!-- TABLE LINK -->
    <tr>
    	<td><strong>Periode <?=$periode?></strong></td>
		<!--<input type="text" class="disabled" readonly value="<?=format_tgl($row['tglmulai'])?> s/d <?=format_tgl($row['tglakhir'])?>" size="40">	-->	        

    	<td align="right">
        	<a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    		<a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;		 </td>
   	</tr>
    </table>
   	<br />
    <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="center">
    <!-- TABLE CONTENT -->
    <tr height="15">
    	<td width="4%" rowspan="2 "class="header" align="center">No</td>
        <td width="10%"rowspan="2" class="header" align="center">NIP</td>
        <td width="*"rowspan="2" class="header" align="center">Nama</td>
        <td colspan="6" width="60%" class="header" align="center">Jumlah</td>
	</tr>
    <tr height="15">
        <td width="8%" class="header" align="center">Mengajar</td>
        <td width="8%" class="header" align="center">Asistensi</td>
        <td width="8%" class="header" align="center">Tambahan</td>
        <td width="8%" class="header" align="center">Jam</td>
        <td width="8%" class="header" align="center">Kelas</td>
        <td width="8%" class="header" align="center">Hari</td>
    </tr>
    <?php $cnt = 0;
		while ($row = mysqli_fetch_row($result)) {
	?>
    <tr height="25">
    	<td align="center"><?=++$cnt?></td>
        <td align="center"><?=$row[0]?></td>        
        <td><?=$row[1]?></td>        
        <td align="center"><?=$row[2]?></td>        
        <td align="center"><?=$row[3]?></td>        
        <td align="center"><?=$row[4]?></td>        
        <td align="center"><?=$row[5]?></td>        
        <td align="center"><?=$row[6]?></td> 
        <td align="center"><?=$row[7]?></td>        
    </tr>
    <?php } ?>
     <!-- END TABLE CONTENT -->
    </table>
       
<?php CloseDb() ?>    
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>	
<?php 	} else { ?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="250">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data jadwal mengajar. <br /><br />Tambah data pada jadwal mengajar untuk setiap guru atau jadwal mengajar untuk setiap kelas <br> pada <?=$info_jadwal?>  di menu Penyusunan Jadwal Setiap Guru atau Penyusunan Jadwal Setiap Kelas <br /> pada bagian Jadwal & Kalender.</b></font>
		</td>
	</tr>
	</table>
<?php } ?>  
    	
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    
</body>
</html>
<?php
CloseDb();
?>