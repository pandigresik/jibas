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

if (getLevel() == 2)
{ ?>
	<script language="javascript">
        alert('Maaf, anda tidak berhak mengakses halaman ini!');
        document.location.href = "penerimaan.php";
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

$idkategori = "";
if (isset($_REQUEST['idkategori']))
	$idkategori = $_REQUEST['idkategori'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$op = $_REQUEST['op'];
if ($op == "12134892y428442323x423")
{
	$sql = "DELETE FROM datapenerimaan WHERE replid = '".$_REQUEST['id']."'";
	OpenDb();
	QueryDb($sql);
	CloseDb();
	//header("Location: jenispenerimaan.php?idkategori=$idkategori&departemen=$departemen");
}

if ($op == "d28xen32hxbd32dn239dx")
{
	$sql = "UPDATE datapenerimaan SET aktif = '".$_REQUEST['newaktif']."' WHERE replid= '".$_REQUEST['id']."'";
	
	OpenDb();
	QueryDb($sql);
	CloseDb();
	
	header("Location: jenispenerimaan.php?idkategori=$idkategori&departemen=$departemen");
}
getAccess();
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

function change_jenis() {
	var idkategori = document.getElementById('idkategori').value;
	var departemen = document.getElementById('departemen').value;
	document.location.href = "jenispenerimaan.php?idkategori="+idkategori+"&departemen="+departemen;
}

function change_dep() {
	change_jenis();
}

function refresh() {
	var idkategori = document.getElementById('idkategori').value;
	var departemen = document.getElementById('departemen').value;
	document.location.href = "jenispenerimaan.php?idkategori="+idkategori+"&departemen="+departemen;
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
		var idkategori = document.getElementById('idkategori').value;
		var departemen = document.getElementById('departemen').value;
		document.location.href = "jenispenerimaan.php?op=d28xen32hxbd32dn239dx&idkategori="+idkategori+"&departemen="+departemen+"&id="+id+"&newaktif="+newaktif;
	}
}

function hapus(id) {
	if (confirm("Apakah anda yakin akan menghapus data ini?")) {
		var idkategori = document.getElementById('idkategori').value;
		var departemen = document.getElementById('departemen').value;
		document.location.href = "jenispenerimaan.php?op=12134892y428442323x423&idkategori="+idkategori+"&departemen="+departemen+"&id="+id+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
	}
}

function cetak() {
	var idkategori = document.getElementById('idkategori').value;
	var departemen = document.getElementById('departemen').value;
	var total=document.getElementById("total").value;
	
	var addr = "jenispenerimaan_cetak.php?idkategori="+idkategori+"&departemen="+departemen+"&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'CetakJenisPenerimaan','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah() {
	var idkategori = document.getElementById('idkategori').value;
	var departemen = document.getElementById('departemen').value;
	newWindow('jenispenerimaan_add.php?idkategori='+idkategori+'&departemen='+departemen, 'JenisPenerimaan','500','395','resizable=1,scrollbars=1,status=0,toolbar=0');
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
	var idkategori = document.getElementById('idkategori').value;
	var departemen = document.getElementById('departemen').value;
	var varbaris=document.getElementById("varbaris").value;
		
	document.location.href = "jenispenerimaan.php?page="+page+"&varbaris="+varbaris+"&hal="+page+"&idkategori="+idkategori+"&departemen="+departemen;
}

function change_hal()
{
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	var idkategori = document.getElementById('idkategori').value;
	var departemen = document.getElementById('departemen').value;
		
	document.location.href="jenispenerimaan.php?page="+hal+"&hal="+hal+"&varbaris="+varbaris+"&departemen="+departemen+"&idkategori="+idkategori;
}

function change_baris()
{
	var idkategori = document.getElementById('idkategori').value;
	var departemen = document.getElementById('departemen').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="jenispenerimaan.php?varbaris="+varbaris+"&departemen="+departemen+"&idkategori="+idkategori;
}
</script>
</head>

<body onLoad="document.getElementById('idkategori').focus();">
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="images/bulu1.png" style="background-repeat:no-repeat">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">
	<table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
		<td align="right">
		<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Jenis Penerimaan</font>
        </td>
    </tr>
    <tr>
    	<td align="right"><a href="penerimaan.php">
      	<font size="1" color="#000000"><b>Penerimaan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Jenis Penerimaan</b></font></td>
    </tr>
    <tr>
      	<td align="left">&nbsp;</td>
    </tr>
	</table><br />
    <table border="0" width="95%" cellpadding="0" cellspacing="0" align="center">
    <tr>
    	<td width="15%" rowspan="2">&nbsp;</td>
        <td width="12%"><strong>Kategori&nbsp;</strong></td>
        <td width="20%">
        <select name="idkategori" id="idkategori" onChange="change_jenis();" style="width:200px" onKeyPress="return focusNext('departemen', event);">
<?php 	$sql = "SELECT kode, kategori FROM kategoripenerimaan ORDER BY urutan";
		OpenDb();
		$result = QueryDb($sql);
		while ($row = mysqli_fetch_row($result)) {
			if ($idkategori == "")
				$idkategori = $row[0];	?>
          <option value="<?=$row[0]?>" <?=StringIsSelected($idkategori, $row[0])?> >
          <?=$row[1]?>
            </option>
          <?php 	} ?>
        </select></td>
   	</tr>
    <tr>
        <td><strong>Departemen</strong></td>
        <td>
        <select name="departemen" id="departemen" onChange="change_dep()" style="width:200px">
        <?php 	$dep = getDepartemen(getAccess());
		foreach($dep as $value) { 
			if ($departemen == "")
				$departemen = $value; ?>
        <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
        <?=$value ?>
        </option>
        <?php 	} ?>
      	</select></td> 
<?php 
	$sql_tot = "SELECT * FROM datapenerimaan WHERE idkategori = '$idkategori' AND departemen = '$departemen' ORDER BY replid";         
	
	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	
	$sql = "SELECT * FROM datapenerimaan WHERE idkategori = '$idkategori' AND departemen = '$departemen' ORDER BY replid LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		
	
	$akhir = ceil($jumlah/5)*5;
	$request = QueryDb($sql);
	
	if (@mysqli_num_rows($request) > 0){
?>          
         <input type="hidden" name="total" id="total" value="<?=$total?>"/>
        <td align="right">
        <a href="#" onClick="refresh()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')" />&nbsp;Cetak</a>&nbsp;&nbsp;       
        <a href="JavaScript:tambah()">
        <img src="images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')">&nbsp;Tambah Jenis Penerimaan</a>        
        </td>
    </tr>   
	</table><br />
    
    <table id="table" class="tab" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
	<tr height="30" align="center">
        <td class="header" width="5%">No</td>
        <td class="header" width="15%">Nama</td>        
        <td class="header" width="30%">Kode Rekening</td>
        <td class="header" width="*">Keterangan</td>
		<td class="header" width="120">Notif SMS | TGRAM | JS</td>
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
<?php 	$sql = "SELECT nama FROM rekakun WHERE kode = '".$row['rekkas']."'";
		$result = QueryDb($sql);
		$row2 = mysqli_fetch_row($result);
		$namarekkas = $row2[0];
	
		$sql = "SELECT nama FROM rekakun WHERE kode = '".$row['rekpendapatan']."'";
		$result = QueryDb($sql);
		$row2 = mysqli_fetch_row($result);
		$namarekpendapatan = $row2[0];
	
		$sql = "SELECT nama FROM rekakun WHERE kode = '".$row['rekpiutang']."'";
		$result = QueryDb($sql);
		$row2 = mysqli_fetch_row($result);
		$namarekpiutang = $row2[0];
		
		$sql = "SELECT nama FROM rekakun WHERE kode = '".$row['info1']."'";
		$result = QueryDb($sql);
		$row2 = mysqli_fetch_row($result);
		$namarekdiskon = $row2[0];
		?>
		<strong>Kas:</strong> <?=$row['rekkas'] . " " . $namarekkas ?><br />
        <strong>Pendapatan:</strong> <?=$row['rekpendapatan'] . " " . $namarekpendapatan ?><br />
        <strong>Piutang:</strong> <?=$row['rekpiutang'] . " " . $namarekpiutang ?><br />
		<strong>Diskon:</strong> <?=$row['info1'] . " " . $namarekdiskon ?><br />
        </td>
        <td><?=$row['keterangan'] ?></td>
		<td align="center">
		<?php if ($row['info2'] == 1)
				echo "<img src='images/ico/checka.png' title='kirim'>";
			else
				echo "&nbsp;"; ?>
		</td>
        <td align="center">
<?php      
		$img = "aktif.png"; 
		$pesan = "Status Aktif!";
		if ($row['aktif'] == 0) {
			$img = "nonaktif.png";
			$pesan = "Status Tidak Aktif!"; 
		} 
?>		
        	<a href="#" onClick="set_aktif(<?=$row['replid'] ?>, <?=$row['aktif'] ?>)"><img src="images/ico/<?=$img ?>" border="0" onMouseOver="showhint('<?=$pesan?>', this, event, '80px')"/></a>&nbsp;
        	<a href="#" onClick="newWindow('jenispenerimaan_edit.php?id=<?=$row['replid']?>&departemen=<?=$row['departemen'] ?>&idkategori=<?=$row['idkategori']?>', 'UbahJenisPenerimaan','500','395','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Penerimaan!', this, event, '80px')"/></a>&nbsp;
        	<a href="#" onClick="hapus(<?=$row['replid'] ?>)"><img src="images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Penerimaan!', this, event, '80px')"/></a>   	
        </td>
    </tr>
<?php } CloseDb();?>
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
       
      	</select></td>
    </tr>
    </table>
<!-- EOF CONTENT -->
</td></tr>
</table>
<?php } else { ?>
	<td width = "50%"></td>
</tr>
</table>
<table width="95%" border="0" align="center">          
<tr>
	<td width="14%"></td>
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
	var spryselect1 = new Spry.Widget.ValidationSelect("idkategori");
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
</script>