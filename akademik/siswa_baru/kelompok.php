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

$proses = "";
if (isset($_REQUEST['proses']))
	$proses = $_REQUEST['proses'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "kelompok";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
	
$op = $_REQUEST['op'];

if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql = "DELETE FROM kelompokcalonsiswa WHERE replid = '".$_REQUEST['replid']."'";
	QueryDb($sql);
	CloseDb();	
$page=0;
$hal=0;
}	
OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kelompok Calon Siswa</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function tambah() {
	var departemen = document.getElementById('departemen').value;
	var id = document.getElementById('proses').value;
	newWindow('kelompok_add.php?departemen='+departemen+'&id='+id, 'TambahKelompokCalonSiswa','500','340','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() {
	var departemen = document.getElementById('departemen').value;
	var proses= document.getElementById('proses').value;

	document.location.href = "kelompok.php?departemen="+departemen+"&proses="+proses;	
}

function tampil() {
	var departemen = document.getElementById('departemen').value;
	document.location.href = "kelompok.php?departemen="+departemen+"&varbaris=<?=$varbaris?>";
}

function edit(replid) {
	newWindow('kelompok_edit.php?replid='+replid, 'UbahKelompokCalonSiswa','500','340','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid) {
	var departemen = document.getElementById('departemen').value;
	var proses = document.getElementById('proses').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	if (confirm("Apakah anda yakin akan menghapus kelompok ini?"))
		document.location.href = "kelompok.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen+"&proses="+proses+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function lihat(replid) {
	var departemen = document.getElementById('departemen').value;
	var proses = document.getElementById('proses').value;
	newWindow('kelompok_detail.php?replid='+replid+'&departemen='+departemen+'&proses='+proses, 'DaftarCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak(urut,urutan) {
	var departemen = document.getElementById('departemen').value;
	var proses = document.getElementById('proses').value;
	var total=document.getElementById("total").value;
		
	newWindow('kelompok_cetak.php?departemen='+departemen+'&proses='+proses+'&urut='+urut+'&urutan='+urutan+'&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakKelompokCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function change_urut(urut,urutan) {		
	var departemen = document.getElementById('departemen').value;
	var proses= document.getElementById('proses').value;
	var varbaris=document.getElementById("varbaris").value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "kelompok.php?departemen="+departemen+"&proses="+proses+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function change_page(page) {
	var departemen=document.getElementById("departemen").value;
	var proses= document.getElementById('proses').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="kelompok.php?departemen="+departemen+"&proses="+proses+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var departemen = document.getElementById("departemen").value;
	var proses= document.getElementById('proses').value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="kelompok.php?departemen="+departemen+"&proses="+proses+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen = document.getElementById("departemen").value;
	var proses= document.getElementById('proses').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="kelompok.php?departemen="+departemen+"&proses="+proses+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>

<body onload="document.getElementById('departemen').focus()">
<input type="hidden" name="urut" id="urut" value="<?=$urut ?>" />
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan ?>" />
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/b_kelompok.png" style="margin:0;padding:0;background-repeat:no-repeat;background-attachment:fixed">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
  	<td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Kelompok Calon Siswa</font></td>
    </tr>
    <tr>
        <td align="right"><a href="../siswa_baru.php" target="content">
          <font size="1" color="#000000"><b>Penerimaan Siswa Baru</b></font></a>&nbsp>&nbsp 
          <font size="1" color="#000000"><b>Kelompok Calon Siswa</b></font></td>
    </tr>
    <tr>
        <td align="left">&nbsp;</td>
    </tr>
	</table>
    <br />
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
      <!-- TABLE LINK -->
      <tr>
        <td width="15%" rowspan="2">&nbsp;</td>
        <td width="16%"><strong>Departemen &nbsp;</strong></td>
        <td width="20%"><select name="departemen" id="departemen" onchange="tampil()" style="width:155px;">
          <?php $dep = getDepartemen(SI_USER_ACCESS());    
	foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
          <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
            <?=$value ?>
            </option>
          <?php } ?>
        </select></td>
      </tr>
      <tr>
        <td><strong>Proses Penerimaan &nbsp;</strong></td>
        <td><?php $sql = "SELECT replid,proses FROM prosespenerimaansiswa WHERE aktif=1 AND departemen='$departemen'";				
				$result = QueryDb($sql);
				if (@mysqli_num_rows($result)>0){
					$row = mysqli_fetch_array($result);
					$proses = $row['replid'];
				} 
			?>
          <input type="text" name="nama_proses" id="nama_proses" style="width:150px;" class="disabled" value="<?=$row['proses']?>" readonly />
          <input type="hidden" name="proses" id="proses"  value="<?=$proses?>" /></td>
        <?php
OpenDb();
if ($proses!="") {
	$sql_tot = "SELECT replid,kelompok,kapasitas,keterangan FROM kelompokcalonsiswa WHERE idproses='$proses'";
	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
						
	$sql = "SELECT replid,kelompok,kapasitas,keterangan FROM kelompokcalonsiswa WHERE idproses='$proses' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 						
	$akhir = ceil($jumlah/5)*5;
	  
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0) {
?>
        <input type="hidden" name="total" id="total" value="<?=$total?>"/>
        <td align="right"><a href="#" onclick="refresh()"><img src="../images/ico/refresh.png" border="0" onmouseover="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp; <a href="JavaScript:cetak('<?=$urut?>','<?=$urutan?>')"><img src="../images/ico/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
          <?php 	//if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
          <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onmouseover="showhint('Tambah Kelompok!', this, event, '50px')"/>&nbsp;Tambah Kelompok</a>
          <?php 	//} ?></td>
      </tr>
    </table>
    <br />   	

    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
      <!-- TABLE CONTENT -->
    <tr height="30" class="header" align="center">
        <td width="4%">No</td>       
        <td width="25%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kelompok','<?=$urutan?>')">Kelompok <?=change_urut('kelompok',$urut,$urutan)?></td>
        <td width="12%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kapasitas','<?=$urutan?>')" >Kapasitas <?=change_urut('kapasitas',$urut,$urutan)?></td>
        <td width="8%">Terisi</td>
        <td width="*" >Keterangan</td>
        <?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <td width="8%" >&nbsp;</td>
        <?php //} ?>
      </tr>
   	<?php    
		if ($page==0)
			$i = 0;
		else 
			$i = (int)$page*(int)$varbaris;
			
		while ($row = @mysqli_fetch_array($result)) {
	?>
      <tr>
        <td height="25" align="center"><?=++$i?></td>
        <td height="25"><?=$row['kelompok']?></td>
        <td height="25" align="center"><?=$row['kapasitas']?></td>
        <td height="25" align="center">
		<?php $sql1 = "SELECT COUNT(*) FROM calonsiswa WHERE idkelompok='".$row['replid']."' AND aktif = 1";
			$result1 = QueryDb($sql1);
			$row1 = @mysqli_fetch_row($result1);
			echo $row1[0];
			if ($row1[0] > 0 ) {
		?>  
        	&nbsp;<a href="JavaScript:lihat(<?=$row['replid']?>)"><img src="../images/ico/lihat.png" border="0" onMouseOver="showhint('Lihat Calon Siswa!', this, event, '65px')"/></a>
        <?php 	} ?>
        </td>        
        <td height="25"><?=$row['keterangan']?></td>
        

        <td height="25" align="center">    
        	<a href="JavaScript:edit(<?=$row['replid'] ?>)" ><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Kelompok!', this, event, '80px')"/></a>&nbsp; 
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>
            <a href="JavaScript:hapus(<?=$row['replid'] ?>)" ><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Kelompok!', this, event, '80px')"/></a>
    	</td>
<?php 	}?>   
  	</tr>
<?php } CloseDb(); ?>
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
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>

<?php } else { ?>
<td width = "65%"></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td width="16%"></td>
	<td><hr style="border-style:dotted" color="#000000" /></td>
</tr>
</table>
<?php //if ($proses!=""){ ?>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data 
        <?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        <?php //} ?>
        </b></font>
	</td>
</tr>
</table>  
<?php } 
	} else {?>
<td width = "65%"></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td width="16%"></td>
	<td><hr style="border-style:dotted" color="#000000" /></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
    <?php if ($departemen != "") {	?>  
        <font size = "2" color ="red"><b>Belum ada Proses Penerimaan Siswa Baru untuk Departemen <?=$departemen?> <br> Silahkan isi  terlebih dahulu di menu Proses Penerimaan Siswa Baru pada bagian PSB</font>
    <?php } else { ?>
		<font size = "2" color ="red"><b>Belum ada data Departemen.
        <br />Silahkan isi terlebih dahulu di menu Departemen pada bagian Referensi.
        </b></font>
    <?php } ?>    
	</td>
</tr>
</table>  
<?php } 

	?>
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    

</body>
</html>
<script language="javascript">
var spryselect = new Spry.Widget.ValidationSelect("departemen");
</script>