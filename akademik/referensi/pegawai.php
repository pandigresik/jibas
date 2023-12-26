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
require_once('../cek.php');

OpenDb();

$bagian = "-1";
if (isset($_REQUEST["bagian"]))
	$bagian=$_REQUEST["bagian"];

$varbaris=20;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op == "dw8dxn8w9ms8zs22") {
	$sql = "UPDATE jbssdm.pegawai SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql);
} else if ($op == "xm8r389xemx23xb2378e23") {
	$sql = "DELETE FROM jbssdm.pegawai WHERE replid = '".$_REQUEST['replid']."'";
	$result = QueryDb($sql);
	$page=0;
	$hal=0;
}

if ($op == "fdgfde342ft45tgwer34rfwef") {
	$pin = random(5);
	$sql = "UPDATE jbssdm.pegawai SET `{$_REQUEST['field']}` = '$pin' WHERE nip = '".$_REQUEST['nip']."'";
	QueryDb($sql);
}

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Kepegawaian]</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh(){
	var bagian=document.getElementById("bagian").value;
	//document.location.href="pegawai.php?bagian="+bagian;
	document.location.href = "pegawai.php?bagian="+bagian+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>"
}

function change_bagian(){
	var bagian=document.getElementById("bagian").value;
	document.location.href="pegawai.php?bagian="+bagian+"&varbaris=<?=$varbaris?>";
}

function setaktif(replid, aktif) {
	var bagian=document.getElementById("bagian").value;
	var msg;
	var newaktif;
	
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah status pegawai ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin akan mengubah status pegawai ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
		document.location.href = "pegawai.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&bagian="+bagian+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>";
	
}

function hapus(replid) {
	var bagian=document.getElementById("bagian").value;
	if (confirm("Apakah anda yakin akan menghapus pegawai ini?"))
		document.location.href = "pegawai.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&bagian="+bagian+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>";
}

function change_urut(urut,urutan) {	
	var bagian=document.getElementById("bagian").value;
	var varbaris=document.getElementById("varbaris").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href="pegawai.php?bagian="+bagian+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function tambah() {
	var bagian=document.getElementById("bagian").value;
	newWindow('pegawai_add.php?bagian='+bagian, 'TambahPegawai','500','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function lihat(replid) {	
	newWindow('pegawai_view.php?replid='+replid, 'LihatPegawai','790','610','resizable=0,scrollbars=1,status=0,toolbar=0')
}

function edit(replid) {
	newWindow('pegawai_edit.php?replid='+replid, 'UbahPegawai','535','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak(urut,urutan) {
	var bagian=document.getElementById("bagian").value;
	var total=document.getElementById("total").value;
	
	newWindow('pegawai_cetak.php?bagian='+bagian+'&urut='+urut+'&urutan='+urutan+'&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakPegawai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak_detail(replid) {
	newWindow('pegawai_cetak_detail.php?replid='+replid, 'CetakDetailCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function change_page(page) {
	var bagian=document.getElementById("bagian").value;
	var varbaris=document.getElementById("varbaris").value;
	document.location.href="pegawai.php?bagian="+bagian+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var bagian = document.getElementById("bagian").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	document.location.href="pegawai.php?bagian="+bagian+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var bagian = document.getElementById("bagian").value;
	var varbaris=document.getElementById("varbaris").value;
	document.location.href="pegawai.php?bagian="+bagian+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function gantipin(field, nip) {
	if (confirm("Apakah anda yakin akan mengganti PIN ini?")) {
		var bagian = document.getElementById("bagian").value;
		var hal = document.getElementById("hal").value;
		var varbaris=document.getElementById("varbaris").value;
		//document.location.href = "pegawai.php?op=fdgfde342ft45tgwer34rfwef&bagian="+bagian+"&urut=<?=$urut?>&urutan=<?=$urutan?>&field="+field+"&nip="+nip+"&hal="+hal+"&varbaris="+varbaris;
		document.location.href = "pegawai.php?op=fdgfde342ft45tgwer34rfwef&bagian="+bagian+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>&field="+field+"&nip="+nip;
	}	
}

function exel()
{
	newWindow('pegawai_excel.php', 'ExcelPegawai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

</script>

</head>
<body onload="document.getElementById('bagian').focus()">
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/ico/b_pegawai.png" style="margin:0;padding:0;background-repeat:no-repeat;background-attachment:fixed;margin-left:10">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
  	<td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <tr>
        <td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Kepegawaian</font></td>
    </tr>
    <tr>
        <td align="right"><a href="../referensi.php" target="content">
          <font size="1" color="#000000"><b>Referensi</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Kepegawaian</b></font>        </td>
    </tr>
    <tr>
      <td align="left">&nbsp;</td>
      </tr>
	</table>
	<br /><br />
  
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center" style="padding-left:100px">
    <!-- TABLE CONTENT -->
    <tr>
      	<td width="38%" align="right">
      	<strong>Bagian&nbsp;</strong>
      	<select name="bagian" id="bagian" onchange="change_bagian()" >
        <option value="-1" <?=StringIsSelected($row_bag['bagian'], $bagian)?>>Semua Bagian </option>
	<?php
        $sql_bag = "SELECT bagian FROM jbssdm.bagianpegawai ORDER BY urutan";    
		$result_bag = QueryDB($sql_bag);
		while ($row_bag = @mysqli_fetch_array($result_bag)){
	?>
        <option value="<?=$row_bag['bagian']?>" <?=StringIsSelected($row_bag['bagian'], $bagian)?>>
        <?=$row_bag['bagian']?>
        </option>
    <?php
		}
	?>
    	</select></td>  
	<?php
		if ($bagian != "-1"){
			$sql_tot = "SELECT * FROM jbssdm.pegawai WHERE bagian='$bagian' ORDER BY replid";
			$result_tot = QueryDb($sql_tot);
			$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
			$jumlah = mysqli_num_rows($result_tot);
						
			$sql_pegawai="SELECT * FROM jbssdm.pegawai WHERE bagian='$bagian' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		} else {
			$sql_tot = "SELECT * FROM jbssdm.pegawai ORDER BY replid";
			$result_tot = QueryDb($sql_tot);
			$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
			$jumlah = mysqli_num_rows($result_tot);
			
			$sql_pegawai="SELECT * FROM jbssdm.pegawai ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		}
		
		$akhir = ceil($jumlah/5)*5;
		$result_pegawai=QueryDb($sql_pegawai);
		if (@mysqli_num_rows($result_pegawai) > 0){ ?>
		<input type="hidden" name="total" id="total" value="<?=$total?>"/>
    	<td width="60%" align="right">
        	<a href="#" onClick="refresh()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
          
		    <a href="#" onClick="JavaScript:exel()"><img src="../images/ico/excel.png" border="0" onMouseOver="showhint('Excel!', this, event, '80px')"/>&nbsp;Excel</a>&nbsp;&nbsp;
            <a href="JavaScript:cetak('<?=$urut?>','<?=$urutan?>')"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')" />&nbsp;Cetak</a>&nbsp;&nbsp;
     
     	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        	<a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')" />&nbsp;Tambah Pegawai</a>
        <?php } ?>        </td>
    </tr>
    </table>
    <br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000" />
    <tr height="30" align="center" class="header">
    	<td width="20" align="center" background="../style/formbg2.gif" >No</td>
        <td width="80" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nip','<?=$urutan?>')">N I P <?=change_urut('nip',$urut,$urutan)?></td>
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="250" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('tmplahir','<?=$urutan?>')">Tempat Tanggal Lahir <?=change_urut('tmplahir',$urut,$urutan)?></td>
        <td width="103" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('pinpegawai','<?=$urutan?>')">PIN&nbsp;Pegawai&nbsp;<?=change_urut('pinpegawai',$urut,$urutan)?></td>
        <td width="65" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('aktif','<?=$urutan?>')">Status <?=change_urut('aktif',$urut,$urutan)?></td>
        <td width="115">&nbsp;</td>
    </tr>
	<?php 	
	if ($page==0)
		$cnt = 1;
	else 
		$cnt = (int)$page*(int)$varbaris+1;
	
	while ($row_pegawai = mysqli_fetch_array($result_pegawai)) { ?>
    <tr height="25">
    	<td width="20" align="center"><?=$cnt ?></td>
        <td align="center"><?=$row_pegawai['nip'] ?></td>
        <td><?=$row_pegawai['nama'] . " " . $row['nama'] ?></td>
        <td><?=$row_pegawai['tmplahir'] ?>, <?=format_tgl($row_pegawai['tgllahir']) ?></td>
        <td width="103" align="center"><?=$row_pegawai['pinpegawai'] ?>&nbsp;
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <a href="JavaScript:gantipin('pinpegawai','<?=$row_pegawai['nip']?>')" ><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Ganti PIN!', this, event, '70px')"/></a>
        <?php } ?>        </td>    
        <td align="center">
        
<?php 	if (SI_USER_LEVEL() == $SI_USER_STAFF) {  
			if ($row_pegawai['aktif'] == 1) { ?> 
            	<img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/>
<?php 		} else { ?>                
				<img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/>
<?php 		}
		} else { 
			if ($row_pegawai['aktif'] == 1) { ?>
				<a href="JavaScript:setaktif(<?=$row_pegawai['replid'] ?>, <?=$row_pegawai['aktif'] ?>)"><img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/></a>
<?php 		} else { ?>
				<a href="JavaScript:setaktif(<?=$row_pegawai['replid'] ?>, <?=$row_pegawai['aktif'] ?>)"><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/></a>
<?php 		} //end if
		} //end if ?>        </td>
        <td align="center"><a href="JavaScript:lihat(<?=$row_pegawai['replid'] ?>)"><img src="../images/ico/lihat.png" border="0" onMouseOver="showhint('Detail Data Pegawai!', this, event, '50x')"/></a>&nbsp;
        
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?> 
			<a href="JavaScript:cetak_detail(<?=$row_pegawai['replid'] ?>)" onMouseOver="showhint('Cetak Detail Data Pegawai!', this, event, '100px')"><img src="../images/ico/print.png" border="0" /></a>&nbsp; 
            <a href="JavaScript:edit(<?=$row_pegawai['replid'] ?>)"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Data Pegawai!', this, event, '80px')" /></a>&nbsp;
            <a href="JavaScript:hapus(<?=$row_pegawai['replid'] ?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Data Pegawai!', this, event, '80px')"/></a>
<?php 	} ?>        </td>
    </tr>
<?php $cnt++; } 
CloseDb(); ?>	
    
    <!-- END TABLE CONTENT -->
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
    <table border="0"width="95%" align="center"cellpadding="0" cellspacing="0">	
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
    	<!--td align="center">
    <input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<?php
		/*for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
			}
				 
	    }*/
		?>
	     <input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
 		</td-->
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=10; $m <= 100; $m=$m+10) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
</td></tr>
<!-- END TABLE CENTER -->    
</table>
	
<?php } else { ?>
<td width = "60%"></td>
</tr>
</table>
<table width="95%" border="0" align="center">          
<tr>
	<td width="19%"></td>
	<td><hr style="border-style:dotted" color="#000000"/></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF ) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        <?php } ?>
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
	var spryselect1 = new Spry.Widget.ValidationSelect("bagian");
	var spryselect1 = new Spry.Widget.ValidationSelect("hal");
	var spryselect1 = new Spry.Widget.ValidationSelect("varbaris");
</script>