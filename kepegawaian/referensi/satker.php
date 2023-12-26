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
require_once("../include/sessionchecker.php");
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

OpenDb();
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op == "xm8r389xemx23xb2378e23") {
	$sql = "DELETE FROM satker WHERE replid = '".$_REQUEST['replid']."'";
	$result = QueryDb($sql);
	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style<?=GetThemeDir2()?>.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function tambah() {
	newWindow('satker_add.php', 'TambahDepartemen','381','127','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() {
	//document.location.reload();
	document.location.href="satker.php";
}

function edit(replid) {
	newWindow('satker_edit.php?replid='+replid, 'UbahSatuanKerja','381','127','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid) {
	if (confirm("Apakah anda yakin akan menghapus satuan kerja ini?"))
		document.location.href = "satker.php?op=xm8r389xemx23xb2378e23&replid="+replid;
}

function cetak() {
	newWindow('satker_cetak.php', 'CetakJenisJabatan','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>

<body>
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
  <td width="100%" align="right" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
    <font style="background-color:#FFCC33; font-size:24px">&nbsp;&nbsp;</font>
    <font class="subtitle">Satuan Kerja</font><br />
    <a href="referensi.php">Referensi</a> &gt; Satuan Kerja<br />
    </td>
    
</tr>
<tr><td>
	<br />
    <?php
	$sql = "SELECT replid,satker,nama,isdefault FROM satker ORDER BY satker";
	//echo $sql;    
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0){
	?>
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE CONTENT -->
    <tr>
      <td align="right">
    
    <a href="#" onClick="refresh()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
	<a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')" />&nbsp;Tambah Satuan Kerja</a>
<?php } ?>	
    </td>
    </tr>
    </table>
    
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center">
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="15%" class="header" align="center">Satuan Kerja</td>
        <td width="15%" class="header" align="center">Nama</td>
        <td width="8%" class="header">&nbsp;</td>
    </tr>
<?php 	
	
	$cnt = 0;
	while ($row = mysqli_fetch_row($result))
	{
		$isdefault = (int)$row[3];
		?>
    <tr height="25">
    	<td align="center"><?= ++$cnt ?></td>
        <td><?= $row[1] ?></td>
        <td><?= $row[2] ?></td>
        <td align="center">
			&nbsp;
<?php 		if ($isdefault == 0 && SI_USER_LEVEL() != $SI_USER_STAFF)
			{ ?>		
				<a href="JavaScript:edit('<?= $row[0] ?>')"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Satuan Kerja!', this, event, '80px')" /></a>&nbsp;
				<a href="JavaScript:hapus('<?= $row[0] ?>')"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Satuan Kerja!', this, event, '80px')"/></a>        
<?php 		} ?>
		</td>
    </tr>
<?php } ?>
    <!-- END TABLE CONTENT -->
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>

	</td></tr>
<!-- END TABLE CENTER -->    
</table>
<?php } else { ?>

<table width="100%" border="0" align="center">

<tr>
	<td align="center" valign="middle" height="250" colspan="2">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
       <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru.
        <?php } ?>
        </p></b></font>
	</td>
</tr>
</table>  
<?php } ?> 
 </table>
    
</td></tr>
</table>   

</body>
</html>
<?php CloseDb();?>