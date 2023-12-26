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
require_once('../cek.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$op = $_REQUEST['op'];

if ($op == "dw8dxn8w9ms8zs22") {
	OpenDb();
	$sql = "UPDATE tingkat SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql);
	CloseDb();
} else if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql = "DELETE FROM tingkat WHERE replid = '".$_REQUEST['replid']."'";
	QueryDb($sql);
	CloseDb();
	   
}	
OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tingkat</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function tambah() {
	var departemen = document.getElementById('departemen').value;
	newWindow('tingkat_add.php?departemen='+departemen, 'TambahTingkat','500','310','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() {	
	document.location.reload();
}

function tampil() {
	var departemen = document.getElementById('departemen').value;
	document.location.href = "tingkat.php?departemen="+departemen;
}

function setaktif(replid, aktif) {
	var msg;
	var newaktif;
	var departemen = document.getElementById('departemen').value;
	
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah tingkat ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin akan mengubah tingkat ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
		document.location.href = "tingkat.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&departemen="+departemen;
}

function edit(replid) {
	newWindow('tingkat_edit.php?replid='+replid, 'UbahTingkat','500','310','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid) {
	var departemen = document.getElementById('departemen').value;
	if (confirm("Apakah anda yakin akan menghapus tingkat ini?"))
		document.location.href = "tingkat.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen;
		
}

function cetak() {
	var departemen = document.getElementById('departemen').value;
	newWindow('tingkat_cetak.php?departemen='+departemen, 'CetakTingkat','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>
<body onload="document.getElementById('departemen').focus()">

<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/b_tingkat.png" style="background-repeat:no-repeat">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	<td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Tingkat</font></td>
        </tr>
    <tr>
        <td align="right"><a href="../referensi.php" target="content">
          <font size="1" color="#000000"><b>Referensi</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Tingkat</b></font></td>
        </tr>
 	<tr>
      <td align="left">&nbsp;</td>
      </tr>
	</table><br /><br />
    
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE LINK -->
    <tr>
    <td align="right" width="35%">
      <strong>Departemen&nbsp;</strong>
            <select name="departemen" id="departemen" onChange="tampil()">
              <?php $dep = getDepartemen(SI_USER_ACCESS());    
	foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
                <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
                  <?=$value ?> 
                  </option>
              <?php } ?>
              </select>
      </td>
    
   
    <?php
    OpenDb();
		$sql = "SELECT replid,tingkat,keterangan,aktif,urutan FROM tingkat WHERE departemen='$departemen' ORDER BY urutan";    
		$result = QueryDb($sql);
		if (@mysqli_num_rows($result) > 0){
	
		?>
        <td align="right" width="60%"> 
        <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onmouseover="showhint('Refresh!', this, event, '50px')" />&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
	    <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onmouseover="showhint('Tambah!', this, event, '50px')"/>&nbsp;Tambah Tingkat</a>
<?php } ?>    </td></tr>
    </table><br />
    
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
    <!-- TABLE CONTENT -->
    <tr height="30">
    	<td width="4%" class="header" align="center">No</td>
        <td width="15%" class="header" align="center">Tingkat</td>
        <td width="*" class="header" align="center">Keterangan</td>
        <td width="10%" class="header" align="center">Status</td>
		<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>	
        <td width="8%" class="header">&nbsp;</td>
        <?php } ?>
    </tr>
    
     <?php
		
		$cnt = 0;
		while ($row = @mysqli_fetch_array($result)) {
	?>
    <tr height="25">   	
       	<td align="center"><?=++$cnt ?></td>
        <td><?=$row['tingkat']?></td>
        <td><?=$row['keterangan']?></td>        
        <td align="center">
<?php 	if (SI_USER_LEVEL() == $SI_USER_STAFF) {  
			if ($row['aktif'] == 1) { ?> 
            	<img src="../images/ico/aktif.png" border="0" onmouseover="showhint('Status Aktif!', this, event, '80px')"/>
<?php 		} else { ?>                
				<img src="../images/ico/nonaktif.png" border="0" onmouseover="showhint('Status Tidak Aktif!', this, event, '80px')"/>
<?php 		}
		} else { 
			if ($row['aktif'] == 1) { ?>
				<a href="JavaScript:setaktif(<?=$row['replid'] ?>, <?=$row['aktif'] ?>)"><img src="../images/ico/aktif.png" border="0" onmouseover="showhint('Status Aktif!', this, event, '80px')"/></a>
<?php 		} else { ?>
				<a href="JavaScript:setaktif(<?=$row['replid'] ?>, <?=$row['aktif'] ?>)"><img src="../images/ico/nonaktif.png" border="0" onmouseover="showhint('Status Tidak Aktif!', this, event, '80px')"/></a>
<?php 		} //end if
		} //end if ?>        </td>
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>         
		<td align="center">
            <a href="JavaScript:edit(<?=$row['replid'] ?>)"><img src="../images/ico/ubah.png" border="0" onmouseover="showhint('Ubah Tingkat!', this, event, '80px')"/></a>&nbsp;
            <a href="JavaScript:hapus(<?=$row['replid'] ?>)"><img src="../images/ico/hapus.png" border="0" onmouseover="showhint('Hapus Tingkat!', this, event, '80px')"/></a>
       </td>
<?php 	} ?> 
    </tr>
<?php } 
	CloseDb(); ?>	
    
    <!-- END TABLE CONTENT -->
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>	</td></tr>
<!-- END TABLE CENTER -->    
</table>
<?php } else { ?>
<td width = "60%"></td>
</tr>
</table>
<table width="95%" border="0" align="center">          
<tr>
	<td width="18%"></td>
	<td><hr style="border-style:dotted" color="#000000"/></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200"> 
	<?php if ($departemen != "") {	?>   
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        <?php } ?>
        </b></font>
     <?php } else { ?>
        <font size = "2" color ="red"><b>Belum ada data Departemen.
        <br />Silahkan isi terlebih dahulu di menu Departemen pada bagian Referensi.
        </b></font>
    <?php } ?> 
	</td>
</tr>
</table>
<?php } ?>  
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    

</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
</script>