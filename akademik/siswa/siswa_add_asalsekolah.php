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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$nama_sekolah = $_REQUEST['sekolah'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$op = $_REQUEST['op'];
if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql = "DELETE FROM jbsakad.asalsekolah WHERE replid = '".$_REQUEST['replid']."'";
	QueryDb($sql);
	CloseDb();
	$page=0;
	$hal=0;
	?>
	<script language="javascript">
		document.location.href = "siswa_add_asalsekolah.php?page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
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
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<title>JIBAS SIMAKA [Daftar Asal Sekolah]</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />

<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript">
function tambah() {
	var departemen = document.getElementById('departemen').value;
	newWindow('siswa_add_asalsekolah_tambah.php?departemen='+departemen, 'TambahSekolah','400','240','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh(departemen) {	
	//alert (departemen);
	//var departemen = document.getElementById('departemen').value;
	document.location.href = "siswa_add_asalsekolah.php?departemen="+departemen+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

//function refresh() {
//	var departemen = document.getElementById('departemen').value;	
//	document.location.href = "siswa_add_asalsekolah.php?departemen="+departemen;
//}

function change_departemen() {
	var departemen = document.getElementById('departemen').value;
	document.location.href = "siswa_add_asalsekolah.php?departemen="+departemen;
}

function edit(replid) {
//var sekolah= document.getElementById('
	newWindow('siswa_add_asalsekolah_edit.php?replid='+replid, 'UbahNamaSekolah','400','240','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid) {
	var departemen = document.getElementById('departemen').value;
	if (confirm("Apakah anda yakin akan menghapus sekolah ini?"))
		document.location.href = "siswa_add_asalsekolah.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_page(page) {
	var departemen=document.getElementById("departemen").value;	
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="siswa_add_asalsekolah.php?departemen="+departemen+"&page="+page+"&hal="+page+"&varbaris="+varbaris;
}

function change_hal() {
	var departemen = document.getElementById("departemen").value;	
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="siswa_add_asalsekolah.php?departemen="+departemen+"&page="+hal+"&hal="+hal+"&varbaris="+varbaris;
}

function change_baris() {
	var departemen = document.getElementById("departemen").value;	
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="siswa_add_asalsekolah.php?departemen="+departemen+"&varbaris="+varbaris;
}

function tutup() {
	var departemen = document.getElementById('departemen').value;
	//var nama_sekolah=document.getElementById('nama_sekolah').value;
	
	//if (nama_sekolah.length==0){
		//alert ('namasekolah kosong');
	//	opener.refresh_delete_sekolah();
	//	window.close();
	//} else{	
		//alert ('namasekolah adaan');
		//parent.opener.sekolah_kiriman(nama_sekolah, departemen);
		parent.opener.sekolah_kiriman2(departemen);
		window.close();
	//}
}

locnm=location.href;
pos=locnm.indexOf("indexb.htm");
locnm1=locnm.substring(0,pos);
function ByeWin() {
	//windowIMA=opener.refresh_delete_sekolah();
	var departemen = document.getElementById('departemen').value;
	windowIMA=opener.get_dep_asal(departemen);
}
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onUnload="ByeWin()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Asal Sekolah :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" height="335" valign="top">
    <!-- CONTENT GOES HERE //--->
<table border="0" width="100%" align="center">
<tr>
	<td valign="top" align="center">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- TABLE LINK -->
    <tr>
    <td width="40%" align="left"><strong>Departemen</strong>
    <select name="departemen" id="departemen" onChange="change_departemen()">
    <?php 
	$sql = "SELECT DISTINCT departemen FROM asalsekolah";
	$result = QueryDb($sql);
	while ($row = @mysqli_fetch_row($result)){
       if ($departemen=="")
		   $departemen=$row[0];
			
	   echo "<option value='".$row[0]."' ".StringIsSelected($row[0], $departemen)."  >".$row[0]."</option>";
    }
	?>
	</select>
    
    </td>
    <?php
    	OpenDb();
		$sql_tot = "SELECT * FROM jbsakad.asalsekolah WHERE departemen='$departemen'";
		$result_tot = QueryDb($sql_tot);
		$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
		$jumlah = mysqli_num_rows($result_tot);
						
		$sql = "SELECT replid,sekolah FROM jbsakad.asalsekolah WHERE departemen='$departemen' ORDER BY sekolah LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 						
		$akhir = ceil($jumlah/5)*5;
	  
		$result = QueryDb($sql);
		if (@mysqli_num_rows($result) > 0) {
	?>
    
    <td align="right" width="60%">
    <a href="#" onClick="refresh('<?=$departemen?>')"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah Sekolah!', this, event, '50px')"/>&nbsp;Tambah Sekolah</a>

    </td>
    </tr>
    </table>
</td>
</tr>
<tr>
	<td>  
  <br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left">
    <!-- TABLE CONTENT -->
    <tr height="30">
    	<td width="10%" class="header" align="center">No</td>
        <td width="70%" class="header" align="center">Sekolah</td>
        <td width="*" class="header" align="center">&nbsp;</td>
    </tr>
    
     <?php
		if ($page==0)
			$cnt = 0;
		else 
			$cnt = (int)$page*(int)$varbaris;
		
		while ($row = @mysqli_fetch_array($result)) {
	?>
    <tr height="25">   	
       	<td align="center"><?=++$cnt ?></td>
        <td><?=$row['sekolah']?></td>
        <td align="center">
		<?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?> 
            <a href="JavaScript:edit(<?=$row['replid'] ?>)"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Sekolah!', this, event, '50px')"/></a>&nbsp;
            <a href="JavaScript:hapus(<?=$row['replid'] ?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Sekolah!', this, event, '50px')" /></a>
<?php 	//} ?>
		</td> 
    </tr>
<?php } 
	CloseDb(); ?>	
    
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
	</table>
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
    <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="35%" align="left">Hal
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> hal
		
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
        <td width="35%" align="right">Jml baris per hal
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
<?php } else { ?>
</td><td>&nbsp;</td>
</tr>

<table width="100%" border="0" align="center">
   	<tr>
    	<td colspan="3"><hr style="border-style:dotted" color="#000000" /> 
       	</td>
   	</tr>
	<tr>
		<td align="center" valign="middle" height="200">
    <?php //if ($departemen != "") {	?>
        <font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        <?php } ?>
        </b></font>
    <?php //} else { ?>
		<!--<font size = "2" color ="red"><b>Belum ada data Departemen.
        <br />Silahkan isi terlebih dahulu di menu Departemen pada bagian Referensi.
        </b></font>-->
    <?php //} ?>
        
        </td>
   	</tr>
</table>
<?php } ?>  
<tr height="35">
	<td colspan="3" align="center">
    	<!--<input class="but" type="button" value="Tutup" onClick="window.close();">-->
        <input class="but" type="button" value="Tutup" onClick="tutup()">
        <input type="hidden" name="nama_sekolah" id="nama_sekolah" value="<?=$nama_sekolah?>" />
	</td>
</tr>
    
    <!-- END TABLE CONTENT -->
</table>
</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
   

</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
</script>