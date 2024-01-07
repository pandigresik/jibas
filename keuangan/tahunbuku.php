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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');

$from = $_REQUEST['from'];
$sourcefrom = $_REQUEST['sourcefrom'];

if (getLevel() == 2) { ?>
<script language="javascript">
	alert('Maaf, anda tidak berhak mengakses halaman ini!');
	document.location.href = "<?=$sourcefrom ?>";
</script>
<?php 	exit();
} // end if

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "tahunbuku";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];	

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$op = $_REQUEST['op'];
if ($op == "12134892y428442323x423") {
	$sql = "DELETE FROM tahunbuku WHERE replid = '".$_REQUEST['id']."'";
	OpenDb();
	QueryDb($sql);
	CloseDb();
	
	header("Location: tahunbuku.php?departemen=$departemen&from=$from&sourcefrom=$sourcefrom");
}

if ($op == "d28xen32hxbd32dn239dx") {
	OpenDb();	
	$sql = "UPDATE tahunbuku SET aktif = 0 WHERE departemen = '".$departemen."'";
	QueryDb($sql);
	
	$sql = "UPDATE tahunbuku SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['id']."'";
	QueryDb($sql);
	CloseDb();
	
	header("Location: tahunbuku.php?departemen=$departemen&from=$from&sourcefrom=$sourcefrom");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Pembayaran DSP</title>
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function refresh() {
	var departemen = document.getElementById('departemen').value;
	document.location.href = "tahunbuku.php?departemen="+departemen+"&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
}

function set_aktif(id, aktif) {
	var newaktif;
	var msg;
	var departemen = document.getElementById('departemen').value;
	
	if (aktif == 1) {
		newaktif = 0;	
		msg = "Apakah anda yakin akan mengganti status tahun buku ini menjadi TIDAK AKTIF?\r\nPERINGATAN: Hanya ada satu tahun buku yang aktif dalam satu masa!";
	} else {
		newaktif = 1;	
		msg = "Apakah anda yakin akan mengganti status tahun buku ini menjadi AKTIF?\r\nPERINGATAN: Hanya ada satu tahun buku yang aktif dalam satu masa!";
	}
	
	if (confirm(msg)) 
		document.location.href = "tahunbuku.php?op=d28xen32hxbd32dn239dx&id="+id+"&newaktif="+newaktif+"&departemen="+departemen+ "&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function hapus(id) {
	if (confirm("Apakah anda yakin akan menghapus data ini?")) {
		var departemen = document.getElementById('departemen').value;
		document.location.href = "tahunbuku.php?op=12134892y428442323x423&id="+id+"&departemen="+departemen+"&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
	}
}

function change_dep() {
	var departemen = document.getElementById('departemen').value;
	document.location.href = "tahunbuku.php?departemen="+departemen+"&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
}

function cetak() {
	var departemen = document.getElementById('departemen').value;
	var total=document.getElementById("total").value;
	var addr = "tahunbuku_cetak.php?departemen="+departemen+"&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total+"&urut=<?=$urut?>&urutan=<?=$urutan?>";
	
	newWindow(addr, 'CetakTahunBuku','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah() {
	var departemen = document.getElementById('departemen').value;
	newWindow('tahunbuku_add.php?departemen='+departemen,'TambahTahunBuku','480','340','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function change_page(page) {
	var departemen = document.getElementById('departemen').value;
	var varbaris=document.getElementById("varbaris").value;
		
	document.location.href = "tahunbuku.php?page="+page+"&varbaris="+varbaris+"&hal="+page+"&departemen="+departemen+"&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
}

function change_hal() {
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	var departemen = document.getElementById('departemen').value;
		
	document.location.href="tahunbuku.php?page="+hal+"&hal="+hal+"&varbaris="+varbaris+"&departemen="+departemen+"&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
}

function change_baris() {
	var departemen = document.getElementById('departemen').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="tahunbuku.php?varbaris="+varbaris+"&departemen="+departemen+"&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
}

function change_urut(urut,urutan) {			
	var departemen = document.getElementById('departemen').value;
	var varbaris=document.getElementById("varbaris").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "tahunbuku.php?departemen="+departemen+"&urutan="+urutan+"&urut="+urut+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris+"&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
}
</script>
</head>

<body onLoad="document.getElementById('departemen').focus();">
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="images/tgl_trans.png" style="background-repeat:no-repeat">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">
	<table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
	<tr>
    	<td align="right">
		<!-- BOF CONTENT -->
		
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Tahun Buku</font>
    	 </td>
    </tr>
    <tr>
    	<td align="right">
        <a href="<?=$sourcefrom ?>">
      	<font size="1" color="#000000"><b><?=$from ?></b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Tahun Buku</b></font>
        </td>
    </tr>
    <tr>
      	<td align="left">&nbsp;</td>
    </tr>
	</table><br />
	<table border="0" width="95%" cellpadding="0" cellspacing="0" align="center">
    <tr>
    	<td width="15%" rowspan="2">&nbsp;</td>
        <td align="left" width="30%"><strong>Departemen&nbsp;</strong>
    	<select name="departemen" id="departemen" onChange="change_dep()">
<?php 	OpenDb();
		$dep = getDepartemen(getAccess());
		foreach ($dep as $value) { 
			if ($departemen == "")
				$departemen = $value ?>
	    	<option value="<?=$value ?>" <?=StringIsSelected($departemen, $value) ?> > <?=$value ?></option>
<?php  	} ?>     
</select>
        </td>
<?php 	$sql_tot = "SELECT * FROM tahunbuku WHERE departemen='$departemen' ORDER BY replid"; 

	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$sql = "SELECT * FROM tahunbuku WHERE departemen='$departemen' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	$result = QueryDb($sql);
	
	if (@mysqli_num_rows($result) > 0){       
 ?>       
        <input type="hidden" name="total" id="total" value="<?=$total?>"/>
        <td align="right" width="80%">
        <a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>
        </td>
    </tr>
	</table>
    <br />
    <table id="table" class="tab" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
	<tr height="30" class="header" align="center">
        <td width="5%">No</td>
        <td width="12%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" onClick="change_urut('tahunbuku','<?=$urutan?>')" style="cursor:pointer;">Tahun Buku <?=change_urut('tahunbuku',$urut,$urutan)?></td>
        <td width="15%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" onClick="change_urut('tanggalmulai','<?=$urutan?>')" style="cursor:pointer;">Tanggal Mulai <?=change_urut('tanggalmulai',$urut,$urutan)?></td>
        <td width="15%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" onClick="change_urut('awalan','<?=$urutan?>')" style="cursor:pointer;">Awalan Kuitansi <?=change_urut('awalan',$urut,$urutan)?></td>
        <td width="40%">Keterangan</td>
        <td width="12%">&nbsp;</td>
	</tr>
    <?php

	if ($page==0)
		$cnt = 0;
	else 
		$cnt = (int)$page*(int)$varbaris;
		
	while($row = mysqli_fetch_array($result)) {
	?>
    <tr height="25">
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['tahunbuku'] ?></td>
        <td align="center"><?=LongDateFormat($row['tanggalmulai']) ?></td>
        <td align="center"><?=$row['awalan'] ?></td>
        <td><?=$row['keterangan'] ?></td>
        <td align="center">
        <?php if ($row['aktif'] == 1) { ?>
            <a href="#" onClick="newWindow('tahunbuku_edit.php?id=<?=$row['replid']?>', 'EditTahunBuku','480','340','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Tahun Buku!', this, event, '75px')" /></a>
		<?php } else { ?>
        	&nbsp;
        <?php } ?>            
        </td>
    </tr>
<?php  }
	CloseDb(); ?>
    
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
    <?php if ($page==0){ 
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page<$total && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page==$total-1 && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:hidden;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:hidden;'";
		}
	?>
     </td>
</tr> 
<tr>
    <td>
    <table border="0"width="95%" align="center" cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left">Halaman
            <select name="hal" id="hal" onChange="change_hal()">
            <?php for ($m=0; $m<$total; $m++) {?>
                 <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
            <?php } ?>
            </select>
            dari <?=$total?> halaman
        </td>
        <td width="30%" align="right">Jumlah baris per halaman
            <select name="varbaris" id="varbaris" onChange="change_baris()">
            <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
                <option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
            <?php 	} ?>
            </select>
        </td>
    </tr>
    </table>
<!-- EOF CONTENT -->
</td></tr>
</table>
<?php } else { ?>
	<td width = "60%"></td>
</tr>
</table>
<table width="95%" border="0" align="center">          
<tr>
	<td width="14%"></td>
	<td><hr style="border-style:dotted" color="#000000" /></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">    
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.        
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru.         
        </b></font>
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