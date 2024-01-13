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
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once("../include/sessionchecker.php");

if (SI_USER_ID()!="landlord" && SI_USER_ID()!="LANDLORD"){
?>
<script language="javascript">
alert ('Maaf Anda Tidak berhak mengakses halaman ini!');
parent.framecenter.location.href="../center.php";
</script>
	<?php
}

OpenDb();
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op == "dw8dxn8w9ms8zs22") {
	$sql = "UPDATE departemen SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = {$_REQUEST['replid']} ";
	QueryDb($sql);

} 
if ($op == "xm8r389xemx23xb2378e23") {
	$sql0 = "SELECT * FROM jbsuser.hakakses WHERE login = '".$_REQUEST['nip']."' AND modul <> 'INFOGURU'";
	$result0 = QueryDb($sql0);
	if (@mysqli_num_rows($result0)==0){
	$sql = "DELETE FROM jbsuser.login WHERE login = '".$_REQUEST['nip']."'";
	$result = QueryDb($sql);
	}
	$sql1 = "DELETE FROM jbsuser.hakakses WHERE login = '".$_REQUEST['nip']."' AND modul='INFOGURU'";
	$result1 = QueryDb($sql1);
	//echo $sql."<br>".$sql1;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function tambah() {
	newWindow('user_add.php', 'TambahUser','411','231','resizable=1,scrollbars=0,status=0,toolbar=0')
}

function refresh() {
	document.location.reload();
}

function setaktif(replid, aktif) {
	var msg;
	var newaktif;
	
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah departemen ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin akan mengubah departemen ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
		document.location.href = "departemen.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif;
}

function edit(replid) {
	newWindow('user_edit.php?replid='+replid, 'UbahUser','434','364','resizable=1,scrollbars=0,status=0,toolbar=0')
}

function hapus(nip) {
	if (confirm("Apakah anda yakin akan menghapus pengguna ini?"))
		document.location.href = "user.php?op=xm8r389xemx23xb2378e23&nip="+nip;
}

function cetak() {
	newWindow('user_cetak.php', 'CetakUser','613','528','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>

<body>

<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/ico/b_user_lock.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
  <td align="left" valign="top">

	<table border="0"width="100%" align="center">
    <tr>
        <td align="right">
         <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Manajemen Pengguna</font><br />
            <font size="1" color="#000000"><b>Pengguna</b></font>&nbsp>&nbsp <font size="1" color="#000000"><b>Manajemen Pengguna</b></font>
        </td>
    </tr>
	</table>
	<br /><br />
    <?php
	$sql = "SELECT * FROM jbsuser.hakakses WHERE modul='INFOGURU' GROUP BY login ORDER BY login";
	$result = QueryDB($sql);
	?>
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE CONTENT -->
    <tr>
      <td align="right">
    
    <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    <?php if (@mysqli_num_rows($result)>0){
	?>
    <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')" />&nbsp;Cetak</a>&nbsp;&nbsp;
    <?php } ?>
<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
	    <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')" />&nbsp;Tambah
	    User</a>
<?php } ?>    
    </td>
    </tr>
    </table><br />
    <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="95%" align="center">
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="15%" class="header" align="center">Login</td>
        <td width="20%" class="header" align="center">Nama</td>
       <td width="*" class="header" align="center">Keterangan</td>
        <td width="10%" class="header" align="center">Last Login</td>
        <td width="*" class="header">&nbsp;</td>
    </tr>
<?php 	
	if (@mysqli_num_rows($result)>0){
	$cnt = 0;
	while ($row = mysqli_fetch_array($result)) { ?>
    <tr height="25">
    	<td align="center"><?=++$cnt ?></td>
        <td><?=$row['login'] ?></td>
        <td>
		<?php
		$sql_get_lvl="SELECT DATE_FORMAT(lastlogin,'%d-%m-%Y') as tanggal, TIME(lastlogin) as jam FROM jbsuser.hakakses WHERE login='".$row['login']."' AND modul='INFOGURU'";
		//echo $sql_get_lvl;
		$result_get_lvl=QueryDb($sql_get_lvl);
		$row_get_lvl=@mysqli_fetch_array($result_get_lvl);
		$sql_get_nama="SELECT nama FROM jbssdm.pegawai WHERE nip='".$row['login']."'";
		$result_get_nama=QueryDb($sql_get_nama);
		$row_get_nama=@mysqli_fetch_array($result_get_nama);
		echo $row_get_nama['nama'];
		?></td>
        <td><?=$row['keterangan'] ?></td>
        <td align="center"><?=$row_get_lvl['tanggal']?><br><?=$row_get_lvl['jam']?></td>
        <td align="center">
        
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?> 
            
            <a href="JavaScript:hapus('<?=$row['login'] ?>')"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Pengguna!', this, event, '75px')"/></a>
<?php 	} ?>        </td>
    </tr>
<?php } } else {
	 ?>
     <tr><td colspan="6" align="center">Tidak ada data User</td></tr>	
	<?php
	}
	?>
    
    <!-- END TABLE CONTENT -->
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>

	</td></tr>
<!-- END TABLE CENTER -->    
</table>

</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    

</body>
</html>
<?php CloseDb();?>