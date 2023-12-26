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
require_once('../include/sessioninfo.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../cek.php');

OpenDb();

$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op == "dw8dxn8w9ms8zs22") 
{
	$sql = "UPDATE departemen SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql);
} 

if ($op == "xm8r389xemx23xb2378e23") 
{
	$sql = "DELETE FROM jbsakad.departemen WHERE replid = '".$_REQUEST['replid']."'";
	$result = QueryDb($sql);
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
function tambah() 
{
	newWindow('departemen_add.php', 'TambahDepartemen','505','308','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() 
{
	document.location.href = "departemen.php";
}

function setaktif(replid, aktif) 
{
	var msg;
	var newaktif;
	
	if (aktif == 1) 
	{
		msg = "Apakah anda yakin akan mengubah departemen ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} 
	else	
	{	
		msg = "Apakah anda yakin akan mengubah departemen ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
		document.location.href = "departemen.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif;
}

function edit(replid) 
{
	newWindow('departemen_edit.php?replid='+replid, 'UbahDepartemen','505','308','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid) 
{
	if (confirm("Apakah anda yakin akan menghapus departemen ini?"))
		document.location.href = "departemen.php?op=xm8r389xemx23xb2378e23&replid="+replid;
}

function cetak() 
{
	newWindow('departemen_cetak.php', 'CetakDepartemen','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>

<body>

<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/b_departemen.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
  <td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <tr>
        <td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Departemen</font></td>
    </tr>
    <tr>
        <td align="right"><a href="../referensi.php" target="content">
          <font size="1" face="Verdana" color="#000000"><b>Referensi</b></font></a>&nbsp>&nbsp <font size="1" face="Verdana" color="#000000"><b>Departemen</b></font>
        </td>
    </tr>
     <tr>
      <td align="left">&nbsp;</td>
      </tr>
	</table>
	<br /><br />
<?php $sql = "SELECT d.replid,d.departemen,d.nipkepsek,p.nama,d.keterangan,d.aktif 
			FROM departemen d, jbssdm.pegawai p 
			WHERE d.nipkepsek = p.nip ORDER BY d.urutan";
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0)
	{	?>
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE CONTENT -->
    <tr><td align="right">
    
    <a href="#" onClick="refresh()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')" />&nbsp;Cetak</a>&nbsp;&nbsp;    
<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) 
	{ ?>
	    <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')" />&nbsp;Tambah Departemen</a>
<?php } ?>    
    </td></tr>
    </table><br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="15%" class="header" align="center">Departemen</td>
        <td width="*" class="header" align="center">Kepala Sekolah</td>
        <td width="*" class="header" align="center">Keterangan</td>
        <td width="10%" class="header" align="center">Status</td>
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <td width="8%" class="header">&nbsp;</td>
        <?php } ?>
    </tr>
<?php 	$cnt = 0;
	while ($row = mysqli_fetch_row($result)) 
	{ ?>
    <tr height="25">
    	<td align="center"><?=++$cnt ?></td>
        <td><?=$row[1] ?></td>
        <td><?=$row[2] . " - " . $row[3] ?></td>
        <td><?=$row[4] ?></td>
        <td align="center">
        
<?php 	if (SI_USER_LEVEL() == $SI_USER_STAFF) {  
			if ($row[5] == 1) { ?> 
            	<img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/>
<?php 		} else { ?>                
				<img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/>
<?php 		}
		} else { 
			if ($row[5] == 1) { ?>
				<a href="JavaScript:setaktif(<?=$row[0] ?>, <?=$row[5] ?>)"><img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/></a>
<?php 		} else { ?>
				<a href="JavaScript:setaktif(<?=$row[0] ?>, <?=$row[5] ?>)"><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/></a>
<?php 		} //end if
		} //end if ?>        
        
        </td>
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>         
		<td align="center">
            <a href="JavaScript:edit(<?=$row[0] ?>)"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Departemen!', this, event, '80px')" /></a>&nbsp;
            <a href="JavaScript:hapus(<?=$row[0] ?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Departemen!', this, event, '80px')"/></a>
        </td>
<?php 	} ?>  
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
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    

</body>
</html>
<?php CloseDb();?>