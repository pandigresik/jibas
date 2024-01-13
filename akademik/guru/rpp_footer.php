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
require_once('../cek.php');

//$departemen=$_REQUEST['departemen'];
$tingkat=$_REQUEST['tingkat'];
$semester=$_REQUEST['semester'];
$pelajaran=$_REQUEST['pelajaran'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];
	
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];

$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];
	
$urut = "koderpp";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

OpenDb();
$op = $_REQUEST['op'];
if ($op == "dw8dxn8w9ms8zs22") {
	OpenDb();
	$sql = "UPDATE rpp SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql);
	CloseDb();			
} else if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql = "DELETE FROM rpp WHERE replid = '".$_REQUEST['replid']."'";
	$result = QueryDb($sql);
	if ($result) { 
		CloseDb();
	$page=0;
	$hal=0;
	?>
    	<script language="javascript">
			refresh_all();
    	</script>
	<?php }
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rencana Program Pembelajaran</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function tambah() {	
	var semester = document.getElementById('semester').value;
	var tingkat = document.getElementById('tingkat').value;
	var pelajaran = document.getElementById('pelajaran').value;
	
	newWindow('rpp_add.php?tingkat='+tingkat+'&semester='+semester+'&pelajaran='+pelajaran, 'TambahRPP','660','568','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh(aktif) {
	document.location.reload();
}

function refresh_all() {
	var semester = document.getElementById('semester').value;
	var tingkat = document.getElementById('tingkat').value;
	var pelajaran = document.getElementById('pelajaran').value;
	
	document.location.href = "rpp_footer.php?semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat;
}


function setaktif(replid, aktif) {
	var semester = document.getElementById('semester').value;
	var tingkat = document.getElementById('tingkat').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	var msg;
	var newaktif;
	
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah rencana program pembelajaran ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin akan mengubah rencana program pembelajaran ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
		document.location.href = "rpp_footer.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+'&semester='+semester+'&tingkat='+tingkat+'&pelajaran='+pelajaran+'&urut='+urut+'&urutan='+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";;
}

function edit(replid) {
	newWindow('rpp_edit.php?replid='+replid, 'UbahRPP','660','568','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid) {
	var tingkat = document.getElementById('tingkat').value;
	var semester = document.getElementById('semester').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	
	if (confirm("Apakah anda yakin akan menghapus rencana program pembelajaran ini?"))
		document.location.href = "rpp_footer.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function cetak(urut,urutan) {
	var semester = document.getElementById('semester').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var total = document.getElementById('total').value;
	newWindow('rpp_cetak.php?tingkat='+tingkat+'&semester='+semester+'&pelajaran='+pelajaran+'&urut='+urut+'&urutan='+urutan+'&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakRPP','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
	
}

function change_urut(urut,urutan) {		
	var semester = document.getElementById('semester').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "rpp_footer.php?semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
	
}

function change_page(page) {
	var semester = document.getElementById('semester').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="rpp_footer.php?semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var semester = document.getElementById('semester').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="rpp_footer.php?semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var semester = document.getElementById('semester').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="rpp_footer.php?semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

</script>
</head>
<body leftmargin="0" topmargin="0">
<input type="hidden" name="urut" id="urut" value="<?=$urut?>"/>
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan?>"/>
<input type="hidden" name="semester" id="semester" value="<?=$semester?>"/>
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>"/>
<input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran?>"/>
<table width="100%" border="0" width="100%" align="center">
<tr><td>
	<!--<td background="../images/ico/b_kelas.png" style="background-repeat:no-repeat; background-attachment:fixed; margin-left:10">-->

<?php 	
OpenDb();
$sql_tot = "SELECT replid, koderpp, rpp, deskripsi, aktif FROM rpp WHERE idtingkat='$tingkat' AND idsemester='$semester' AND idpelajaran='$pelajaran'";
$result_tot = QueryDb($sql_tot);
$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
$jumlah = mysqli_num_rows($result_tot);
$akhir = ceil($jumlah/5)*5;

$sql = "SELECT replid, koderpp, rpp, deskripsi, aktif FROM rpp WHERE idtingkat='$tingkat' AND idsemester='$semester' AND idpelajaran='$pelajaran' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
$result = QueryDb($sql);

if (@mysqli_num_rows($result) > 0){ 
?>
<input type="hidden" name="total" id="total" value="<?=$total?>"/>
<table width="100%" border="0" align="center">          
<tr>
	<td align="right">            
    	<a href="JavaScript:refresh_all()" ><img src="../images/ico/refresh.png" border="0" name="refresh" id="refresh" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak('<?=$urut?>','<?=$urutan?>')" ><img src="../images/ico/print.png" border="0" name="cetak" id="cetak" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
        <a href="JavaScript:tambah()" ><img src="../images/ico/tambah.png" border="0" name="tambah" id="tambah" onMouseOver="showhint('Tambah RPP!', this, event, '50px')"/>&nbsp;Tambah RPP</a>
    </td>
</tr>
</table>
<br />

<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
<!-- TABLE CONTENT -->
<tr height="30" class="header" align="center">
	<td width="4%">No</td>        
    <td width="8%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('koderpp','<?=$urutan?>')">Kode <?=change_urut('koderpp',$urut,$urutan)?></td>    
	<td width="25%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('rpp','<?=$urutan?>')">Materi <?=change_urut('rpp',$urut,$urutan)?></td>
    <td width="*">Deskripsi</td>
    <td width="8%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('aktif','<?=$urutan?>')">Status <?=change_urut('aktif',$urut,$urutan)?></td>	    
    <td width="8%">&nbsp;</td>
</tr>
<?php 
	if ($page==0)
		$cnt = 0;
	else 
		$cnt = (int)$page*(int)$varbaris;
	while ($row = @mysqli_fetch_row($result)) {		
?>
<tr height="25">   	
	<td align="center"><?=++$cnt ?></td>
    <td><?=$row[1]?></td>
    <td><?=$row[2]?></td>
	<td><?=$row[3]?></td>
    <td align="center">  
<?php if ($row[4] == 1) { ?>
        <a href="JavaScript:setaktif(<?=$row[0] ?>, <?=$row[4] ?>)"><img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/></a>
<?php } else { ?>
        <a href="JavaScript:setaktif(<?=$row[0] ?>, <?=$row[4] ?>)"><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/></a>
<?php } //end if ?>        
	</td>
    <td align="center">
    	<a href="JavaScript:edit(<?=$row[0] ?>)"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah RPP!', this, event, '50px')"/></a>&nbsp;
 <?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>
        <a href="JavaScript:hapus(<?=$row[0] ?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus RPP!', this, event, '50px')"/></a>
 <?php } ?>
	</td>
</tr>
<?php } ?>
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script></div>
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
    <table border="0"width="100%" align="center" >	
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

<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="250">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
       <?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru.
        <?php //} ?>
        </p></b></font>
	</td>
</tr>
</table>  
<?php } ?> 
</td>
</tr>
</table>

</body>

</html>