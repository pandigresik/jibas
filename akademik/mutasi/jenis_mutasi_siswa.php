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
require_once('../cek.php');

OpenDb();
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op=="gu7jkds894h98uj32uhi9d8"){
	$sql_hapus="DELETE FROM jbsakad.jenismutasi WHERE replid='".$_REQUEST['replid']."'";
	$result_hapus=QueryDb($sql_hapus);
}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Tambah Jenis Mutasi</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<SCRIPT type="text/javascript" language="text/javascript" src="../script/tables.js"></SCRIPT>
<SCRIPT type="text/javascript" language="javascript" src="../script/tools.js"></script>
<link href="../style/style.css" rel="stylesheet" type="text/css">
<script language="javascript">

function tambah() {
	newWindow('tambah_jenis_mutasi.php','TambahJenisMutasi','400','260','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function edit(replid) {
	newWindow('ubah_jenis_mutasi.php?replid='+replid, 'UbahJenisMutasi','400','260','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid){
	if (confirm('Anda yakin akan menghapus jenis mutasi ini?'))
		document.location.href="jenis_mutasi_siswa.php?op=gu7jkds894h98uj32uhi9d8&replid="+replid;
}

function cetak() {
	newWindow('jenis_mutasi_cetak.php', 'CetakJenisMutasi','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() {
	document.location.reload();
}
</script>
</head>
<body>

<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/ico/b_jenismutasi.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
  <td align="left" valign="top">
	<table border="0"width="95%" align="center">
    <tr>
    	<td align="right">
       	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Jenis-Jenis Mutasi Siswa</font>
        </td>
	</tr>
    <tr>
    	<td align="right"><a href="../mutasi.php" target="content"> 
        	<font size="1" face="Verdana" color="#000000"><b>Mutasi</b></font></a>&nbsp>&nbsp <font size="1" face="Verdana" color="#000000"><b>Jenis-Jenis Mutasi Siswa</b></font></td>
	</tr>
   	<tr>
    	<td align="left">&nbsp;</td>
    </tr>
	</table>
	<br /><br />
    <?php OpenDb();
    	$queryJenis="SELECT * FROM jbsakad.jenismutasi ORDER BY jenismutasi";
		$resultJenis=queryDb($queryJenis);
		if (@mysqli_num_rows($resultJenis) > 0){
	?>
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE CONTENT -->
    <tr><td align="right">
    
    <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')" />&nbsp;Cetak</a>&nbsp;&nbsp;    
<?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
	    <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')" />&nbsp;Tambah Jenis Mutasi</a>
<?php //} ?>    
    	</td></tr>
    </table><br />
  	<table width="95%" border="1" class="tab" align="center" cellpadding="0" cellspacing="0" id="table" bordercolor="#000000">
  	<tr class="header">
    	<td width="4%" height="30"><div align="center">No</div></td>
    	<td width="35%" height="30"><div align="center">Jenis Mutasi </div></td>
     	<td width="*" height="30"><div align="center">Keterangan </div></td>
    	 <?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <td width="8%" height="30">&nbsp;</td>
        <?php //} ?>
 	</tr>
<?php 
	$a=0;
  	while($fetchJenis=mysqli_fetch_array($resultJenis)){ ?>
  	<tr height="25">
        <td align="center"><?=++$a; ?></td>
        <td><?=$fetchJenis['jenismutasi']; ?></td>
        <td><?=$fetchJenis['keterangan']; ?></td>
        <?php //	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>         
		<td align="center">
            <a href="JavaScript:edit(<?=$fetchJenis['replid'] ?>)"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Jenis Mutasi!', this, event, '80px')" /></a>&nbsp;
            <a href="JavaScript:hapus(<?=$fetchJenis['replid'] ?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Jenis Mutasi!', this, event, '80px')"/></a>
        </td>
<?php 	//} ?>  

        
        <!--<td><img title="Ubah" src="../images/ico/ubah.png" width="16" height="16" onClick="newWindow('ubah_jenis_mutasi.php?replid=<?=$fetchJenis['replid']?>','',410,248,'')" style="cursor:pointer"> <img title="Hapus" src="../images/ico/hapus.png" width="16" height="16" onClick="hapus(<?=$fetchJenis['replid']?>)" style="cursor:pointer"></td>-->
	</tr>
<?php } ?>
	</table>  
	<script language="javascript">
		Tables('table', 1, 0);
	</script>	
    </td></tr>
</table><?php } else { ?>

<table width="100%" border="0" align="center">

<tr>
	<td align="center" valign="middle" height="250" colspan="2">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
       <?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru.
        <?php //} ?>
        </p></b></font>
	</td>
</tr>
</table>  
<?php } ?> 
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    

</body>
</html>
<?php CloseDb();?>