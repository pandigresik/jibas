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
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');
require_once('include/errorhandler.php');

if (getLevel() == 2) { ?>
	<script language="javascript">
        alert('Maaf, anda tidak berhak mengakses halaman ini!');
        document.location.href = "penerimaan.php";
    </script>
<?php exit();
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

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$op = $_REQUEST['op'];
if ($op == "12134892y428442323x423") {
	$sql = "DELETE FROM datapengeluaran WHERE replid = '".$_REQUEST['id']."'";
	OpenDb();
	QueryDb($sql);
	CloseDb();
	
	header("Location: jenispengeluaran.php?departemen=$departemen");
}

if ($op == "d28xen32hxbd32dn239dx") {
	$sql = "UPDATE datapengeluaran SET aktif='".$_REQUEST['newaktif']."' WHERE replid='".$_REQUEST['id']."'";
	
	OpenDb();
	QueryDb($sql);
	CloseDb();
	
	header("Location: jenispengeluaran.php?departemen=$departemen");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function change_dep() {
	var departemen = document.getElementById('departemen').value;
	document.location.href = "jenispengeluaran.php?departemen="+departemen;
}

function refresh() {
	var departemen = document.getElementById('departemen').value;
	document.location.href = "jenispengeluaran.php?departemen="+departemen;
}

function set_aktif(id, aktif) {
	var newaktif;
	var msg;
	
	if (aktif == 1) {
		newaktif = 0;	
		msg = "Apakah anda yakin akan mengganti status data ini menjadi TIDAK AKTIF?";
	} else {
		newaktif = 1;	
		msg = "Apakah anda yakin akan mengganti status data ini menjadi AKTIF?";
	}
	
	if (confirm(msg)) {
		var departemen=document.getElementById('departemen').value;
		document.location.href="jenispengeluaran.php?op=d28xen32hxbd32dn239dx&departemen="+departemen+"&id="+id+"&newaktif="+newaktif;
	}
}

function hapus(id) {
	if (confirm("Apakah anda yakin akan menghapus data ini?")) {
		var departemen=document.getElementById('departemen').value;
		document.location.href = "jenispengeluaran.php?op=12134892y428442323x423&departemen="+departemen+"&id="+id+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
	}
}

function cetak() {
	var dept = document.getElementById('departemen').value;
	var total=document.getElementById("total").value;
	
	var addr = "jenispengeluaran_cetak.php?departemen="+dept+"&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'CetakJenisPengeluaran','780','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah() {
	var departemen = document.getElementById('departemen').value;
	newWindow('jenispengeluaran_add.php?departemen='+departemen,'DataPengeluaran', '450', '340', 'resizable=1,scrollbars=1,status=0,toolbar=0')

}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function change_page(page) {
	var departemen = document.getElementById('departemen').value;
	var varbaris=document.getElementById("varbaris").value;
		
	document.location.href = "jenispengeluaran.php?page="+page+"&varbaris="+varbaris+"&hal="+page+"&departemen="+departemen;
}

function change_hal() {
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	var departemen = document.getElementById('departemen').value;
		
	document.location.href="jenispengeluaran.php?page="+hal+"&hal="+hal+"&varbaris="+varbaris+"&departemen="+departemen;
}

function change_baris() {
	var departemen = document.getElementById('departemen').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="jenispengeluaran.php?varbaris="+varbaris+"&departemen="+departemen;
}
</script>
</head>

<body onLoad="document.getElementById('departemen').focus()">
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="images/jnspengeluaran_trans.png" style="background-repeat:no-repeat">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">
    <!-- BOF CONTENT -->
	<table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
		<td align="right">
		<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Jenis Pengeluaran</font>
      	</td>
  	</tr>
    <tr>
    	<td align="right">
    	<a href="pengeluaran.php">
      	<font size="1" color="#000000"><b>Pengeluaran</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Jenis Pengeluaran</b></font>
       	</td>
  	</tr>
	<tr>
      	<td align="left">&nbsp;</td>
    </tr>
	</table><br />
    <table border="0" width="95%" cellpadding="0" cellspacing="0" align="center">
    <tr>
    	<td align="right" width="35%">
        <strong>Departemen&nbsp;</strong> 
        <select name="departemen" id="departemen" onChange="change_dep()">
<?php 	OpenDb();
		$dep = getDepartemen(getAccess());
		foreach($dep as $value) {
			if ($departemen == "")
				$departemen = $value; ?>
        	<option value="<?=$value ?>" <?=StringIsSelected($departemen, $value) ?>  > <?=$value ?></option>
<?php 	} ?>            
        </select>
        </td>
<?php 		
	$sql_tot = "SELECT * FROM datapengeluaran WHERE departemen='$departemen' ORDER BY replid";
	
	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	
	$sql = "SELECT * FROM datapengeluaran WHERE departemen='$departemen' ORDER BY replid LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	$akhir = ceil($jumlah/5)*5;
	
	$request = QueryDb($sql);
	if (mysqli_num_rows($request) > 0) {
?>   
       <input type="hidden" name="total" id="total" value="<?=$total?>"/>
        <td align="right">
        <a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
   	    <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
        <a href="#" onClick="JavaScript:tambah()">
            <img src="images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')">&nbsp;Tambah Data Pengeluaran</a>
        </td>
    </tr>
	</table>
    <br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
	<tr height="30" align="center">
        <td class="header" width="5%">No</td>
        <td class="header">Nama</td>
        <td class="header" width="25%">Kode Rekening</td>
        <td class="header" width="*">Keterangan</td>
        <td class="header" width="100">&nbsp;</td>
	</tr>
<?php 
	if ($page==0)
		$cnt = 0;
	else 
		$cnt = (int)$page*(int)$varbaris;
		
	while ($row = mysqli_fetch_array($request)) { ?>
    <tr height="25">
    	<td align="center"><?=++$cnt?></td>
        <td><?=$row['nama'] ?></td>
        <td>
<?php 		$sql = "SELECT nama FROM rekakun WHERE kode = '".$row['rekkredit']."'";
			$result = QueryDb($sql);
			$row2 = mysqli_fetch_row($result);
			$namarekkredit = $row2[0];
	
			$sql = "SELECT nama FROM rekakun WHERE kode = '".$row['rekdebet']."'";
			$result = QueryDb($sql);
			$row2 = mysqli_fetch_row($result);
			$namarekdebet = $row2[0]; ?>
		<strong>Rek. Kas:</strong> <?=$row['rekkredit'] . " " . $namarekkredit ?><br />
        <strong>Rek. Beban:</strong> <?=$row['rekdebet'] . " " . $namarekdebet ?>        </td>
        <td><?=$row['keterangan'] ?></td>
        <td align="center">
        <?php
		$img = "aktif.png";
		$pesan = "Status Aktif!"; 
		if ($row['aktif'] == 0) {
			$img = "nonaktif.png";
			$pesan = "Status Tidak Aktif!";
		}
		?>
        <a href="#" onClick="set_aktif(<?=$row['replid'] ?>, <?=$row['aktif'] ?>)"><img src="images/ico/<?=$img ?>" border="0" onMouseOver="showhint('<?=$pesan?>', this, event, '80px')"/></a>&nbsp;|&nbsp;
        <a href="#" onClick="newWindow('jenispengeluaran_edit.php?id=<?=$row['replid']?>&departemen=<?=$row['departemen']?>', 'UbahJenisPengeluaran','450', '340','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Pengeluaran!', this, event, '80px')"/></a>&nbsp;|&nbsp;
        <a href="#" onClick="hapus(<?=$row['replid'] ?>)"><img src="images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Pengeluaran!', this, event, '80px')"/></a>        </td>
    </tr>
    <?php
	}
	CloseDb();
	?>
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
		
		<?php 
     // Navigasi halaman berikutnya dan sebelumnya
        ?>
        </td>
    	<td align="center">
    <!--input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<?php
		for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo  "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo  "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
			}
				 
	    }
		?>
	     <input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')"-->
 		</td>
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
<!-- EOF CONTENT -->
</td></tr>
</table>
<?php } else { ?>
	<td width="65%"></td>
</tr>
</table>
<table width="95%" border="0" align="center">          
<tr>
	<td width="17%"></td>
	<td><hr style="border-style:dotted" color="#000000"/></td>
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