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

$aktif = 0;
$dep = $_REQUEST['departemen'];
$guru = $_REQUEST['departemen'];
$query ="AND j.departemen = '".$guru."'";

$urut = "p.nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

if ($_REQUEST['aktif']) { 	
	$aktif = 1;
	$id = $_REQUEST['id'];
	OpenDb();
	$sql = "SELECT nama FROM pelajaran WHERE replid ='$id'";
	$result = QueryDb($sql); 
	CloseDb();
	$row = mysqli_fetch_array($result);
	$guru = $row['nama'];
	$query = "AND g.idpelajaran=$id";
}

$op = $_REQUEST['op'];

if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql = "DELETE FROM guru WHERE replid = '".$_REQUEST['replid']."'";
	QueryDb($sql);
	CloseDb();
	?>
    <script>
    	refresh_add();
    </script> 
	<?php
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
function tambah() {
	var departemen = document.getElementById('departemen').value;
	var guru = document.getElementById('guru').value;
	var aktif = document.getElementById('aktif').value;
	var id = document.getElementById('id').value;
	
	newWindow('guru_add.php?departemen='+departemen+'&guru='+guru+'&aktif='+aktif+'&id='+id, 'TambahGuru','500','340','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() {
	var departemen = document.getElementById('departemen').value;
	var aktif = document.getElementById('aktif').value;
	var id = document.getElementById('id').value;
	var guru = document.getElementById('guru').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	document.location.href = "guru_content.php?guru="+guru+"&aktif="+aktif+"&id="+id+"&departemen="+departemen;
	//document.location.reload();
}

function refresh_add() {	
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	var departemen = document.getElementById('departemen').value;	
	var aktif = document.getElementById('proses').value;	
	var id = document.getElementById('kelompok').value;
	var guru = document.getElementById('guru').value;
	
	document.location.href = "guru_content.php?urut="+urut+"&urutan="+urutan+"&guru="+guru+"&aktif="+aktif+"&id="+id+"&departemen="+departemen+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

/*function tampil() {	
	var departemen = document.getElementById('departemen').value;
	var aktif = document.getElementById('aktif').value;
	var guru = document.getElementById('guru').value;
	
	document.location.href = "guru_content.php?departemen="+departemen+"&guru="+guru+'&aktif='+aktif;
}*/

function edit(replid) {
	var aktif = document.getElementById('aktif').value;	
	newWindow('guru_edit.php?replid='+replid+'&aktif='+aktif, 'UbahGuru','500','340','resizable=1,scrollbars=1,status=0,toolbar=0')
	
}

function hapus(replid) {
	var departemen = document.getElementById('departemen').value;
	var aktif = document.getElementById('aktif').value;	
	var id = document.getElementById('id').value;	
	var guru = document.getElementById('guru').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	if (confirm("Apakah anda yakin akan menghapus status guru ini?"))
		document.location.href = "guru_content.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen+"&aktif="+aktif+"&guru="+guru+"&id="+id+"&urut="+urut+"&urutan="+urutan;
}

function cetak(urut, urutan) {
	var departemen = document.getElementById('departemen').value;
	var aktif = document.getElementById('aktif').value;	
	var id = document.getElementById('id').value;	
	var guru = document.getElementById('guru').value;
	
	newWindow('guru_cetak.php?departemen='+departemen+"&aktif="+aktif+"&guru="+guru+"&id="+id+"&urut="+urut+"&urutan="+urutan, 'CetakGuru','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function change_urut(urut,urutan) {		
	var departemen = document.getElementById('departemen').value;
	var aktif = document.getElementById('aktif').value;
	var id = document.getElementById('id').value;
	var guru = document.getElementById('guru').value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "guru_content.php?guru="+guru+"&aktif="+aktif+"&id="+id+"&departemen="+departemen+"&urut="+urut+"&urutan="+urutan;
	
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<input type="hidden" name="aktif" id="aktif" value="<?=$aktif ?>" />
<input type="hidden" name="departemen" id="departemen" value="<?=$dep?>" /> 
<input type="hidden" name="guru" id="guru" value="<?=$guru ?>" />
<input type="hidden" name="id" id="id" value="<?=$id ?>" />
<input type="hidden" name="urut" id="urut" value="<?=$urut ?>" />
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan ?>" />
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">	
<td valign="top" background="../images/ico/b_guru.png" style="background-repeat:no-repeat; background-attachment:scroll">
<table width="100%" border="0">
<tr>
    <td>
    <table border="0"width="100%">
    <!-- TABLE TITLE -->
    <tr>
    	<td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pendataan Guru</font></td>
    </tr>
    <tr>
      	<td align="right"><a href="../guru.php?page=g" target="content">
        <font size="1" color="#000000"><b>Guru & Pelajaran</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Guru</b></font> 		</td>
    </tr>   
	</table> 
    <br /><br />
    
    <?php 
	
		OpenDb();
		$sql = "SELECT g.replid,g.nip,p.nama,g.statusguru,g.keterangan,j.nama FROM guru g, jbssdm.pegawai p, pelajaran j, statusguru s WHERE g.nip=p.nip AND g.idpelajaran = j.replid AND g.statusguru = s.status $query ORDER BY $urut $urutan";
		$result = QueryDb($sql);
 		if (@mysqli_num_rows($result) > 0){ 
	?>
   
   	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<!-- TABLE LINK -->
    <tr>
    	<td align="center"><font size="4"><strong>Guru <?=$guru?> </strong></font><br /><br /></td>
    </tr>
	<tr> 
    	<td align="right">
    	<a href="#" onClick="refresh()" ><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    	<a href="JavaScript:cetak('<?=$urut?>','<?=$urutan?>')" ><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
	    <a href="JavaScript:tambah()" ><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')"/>&nbsp;Tambah Guru</a><br /><br />
    	</td>
    </tr>
    </table>
	</td>
</tr>
<tr>
	<td>
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <!-- TABLE CONTENT -->
    <tr height="30" class="header" align="center">
    	<td width="4%" class="header" align="center">No</td>
        <td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nip','<?=$urutan?>')">NIP  <?=change_urut('nip',$urut,$urutan)?></td>
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('p.nama','<?=$urutan?>')">Guru <?=change_urut('p.nama',$urut,$urutan)?></td>
     <?php if (!$aktif) { ?>
       	<td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('j.nama','<?=$urutan?>')">Pelajaran <?=change_urut('j.nama',$urut,$urutan)?></td>
     <?php } ?>
        <td width="17%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('statusguru','<?=$urutan?>')">Status Guru <?=change_urut('statusguru',$urut,$urutan)?></td>
        <td width="*" >Keterangan</td>
        <td width="8%" class="header" align="center">&nbsp;</td>
    </tr>
    <?php 	
		$cnt = 0;
		while ($row = @mysqli_fetch_row($result)) {
	?>
    <tr height="25">   	
       	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row[1]?></td>
        <td><?=$row[2]?></td>
        <?php if (!$aktif) { ?>
        	<td><?=$row[5]?></td>
        <?php } ?>        
        <td><?=$row[3]?></td>        
        <td><?=$row[4]?></td>        
        
 		<td align="center">
            <a href="JavaScript:edit(<?=$row[0] ?>)" onMouseOver="showhint('Ubah Guru!', this, event, '50px')"><img src="../images/ico/ubah.png" border="0" /></a>&nbsp;
            <a href="JavaScript:hapus(<?=$row[0] ?>)" onMouseOver="showhint('Hapus Guru!', this, event, '50px')"><img src="../images/ico/hapus.png" border="0" /></a>
 		</td>

    </tr>
<?php } 
	CloseDb(); ?>	
    
    <!-- END TABLE CONTENT -->
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
<?php
	} else { ?>

	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">

    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.   
        <br />Klik <a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a> untuk mengisi data baru. 
        </b></font>
		</td>
	</tr>
	</table>
<?php } ?> 
</td></tr>
</table>
</td></tr>
<!-- END TABLE CENTER -->    
</table>
</body>
</html>