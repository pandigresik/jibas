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
 
$id = $_REQUEST['id'];
$nama_dep = $_REQUEST['nama_dep'];
$nama_pel = $_REQUEST['nama_pel'];
$op = $_REQUEST['op'];

if ($op == "xm8r389xemx23xb2378e23") {
$replid=$_REQUEST['replid'];
$id=$_REQUEST['id'];
	OpenDb();
	$sql = "DELETE FROM jenisujian WHERE replid = '".$replid."'";
	QueryDb($sql);
	$result=QueryDb($sql);
	if ($result) { 
	CloseDb();
				 }
	
}	
OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Status Guru</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh() {	
	document.location.reload();
}

function cetak(id) {
	newWindow('jenis_pengujian_cetak.php?id='+id, 'CetakSemester','790','650','resizable=1,scrollbars=1,status=1,toolbar=0')
}
</script>
</head>

<body topmargin="0" leftmargin="0">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<input type="hidden" name="nama_pel" id="nama_pel" value="<?=$nama_pel?>">
<input type="hidden" name="nama_dep" id="nama_dep" value="<?=$nama_dep?>">
<tr height="300">
	
	<td align="left" valign="top" background="../images/ico/b_jenisujian.png" style="background-repeat:no-repeat">
    <input type="hidden" name="preplid" id="preplid" value="<?=$id?>">
<table width="100%" border="0">
  <tr>
    <td>
     <table border="0"width="100%">
    <!-- TABLE TITLE -->
    <tr>     
      <td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Jenis Pengujian</font></td>
    </tr>
    
    <tr>
      <td align="right"><a href="../pelajaran.php" target="framecenter">
        <font size="1" color="#000000"><b>Guru & Pelajaran</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Jenis
        Pengujian</b></font></td>
    </tr>    
	</table><br /><br />
    </td>
  </tr>
</table>
<?php  OpenDb();
	$sql = "SELECT j.replid,j.jenisujian,j.idpelajaran,j.keterangan,p.replid,p.nama,p.departemen,j.info1 FROM jenisujian j, pelajaran p WHERE j.idpelajaran = '$id' AND j.idpelajaran = p.replid ORDER BY j.jenisujian";   
	$result = QueryDb($sql); 
	if (@mysqli_num_rows($result) > 0){ 
?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
    <!-- TABLE LINK -->
    
    <tr>
    <td align="right">
    <a href="#" onclick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    <a href="JavaScript:cetak(<?=$id?>)"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')" />&nbsp;Cetak</a>&nbsp;&nbsp;
	</td>
    </tr>
    </table>
    <br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
    <!-- TABLE CONTENT -->
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="20%" class="header" align="center">Singkatan</td>
		<td width="20%" class="header" align="center">Jenis Pengujian</td>
        <td width="*" class="header" align="center">Keterangan</td>
    </tr>
    
     <?php
		
		$cnt = 0;
		while ($row = @mysqli_fetch_row($result)) {
		?>
    <tr height="25">   	
       	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row[7]?></td>
		<td align="center"><?=$row[1]?></td>
		<td><?=$row[3]?></td>
    </tr>
<?php } 
	CloseDb(); ?>	
    
    <!-- END TABLE CONTENT -->
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
<?php } else { ?>

<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. Untuk menambah data jenis pengujian, hubungi bagian Akademik atau kordinator guru mata pelajaran.
        </b></font>
	</td>
</tr>
</table>  
<?php } ?> 

	</td></tr>
<!-- END TABLE CENTER -->    
</table>
</body>
</html>