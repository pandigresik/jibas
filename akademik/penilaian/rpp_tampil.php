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

if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];
if(isset($_REQUEST["tingkat"]))
	$tingkat = $_REQUEST["tingkat"];
if(isset($_REQUEST["pelajaran"]))
	$pelajaran = $_REQUEST["pelajaran"];
if(isset($_REQUEST["idrpp"]))
	$idrpp = $_REQUEST["idrpp"];
	
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
$sql="SELECT t.tingkat, t.departemen, p.nama, s.semester FROM tingkat t, pelajaran p, semester s WHERE t.replid = '$tingkat' AND s.replid = '$semester' AND p.replid = '".$pelajaran."'";

$result=QueryDb($sql);
$row=@mysqli_fetch_array($result);
$departemen = $row['departemen'];
$namatingkat = $row['tingkat'];
$namasemester = $row['semester'];
$namapelajaran = $row['nama'];

$op = $_REQUEST['op'];
if ($op == "dw8dxn8w9ms8zs22") {
	//OpenDb();
	$sql_upd = "UPDATE jbsakad.rpp SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	$result_upd = QueryDb($sql_upd);
	if ($result_upd) { 
		//CloseDb();
	?>
    	<script language="javascript">
    	document.location.href="rpp_tampil.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&semester=<?=$semester?>&pelajaran=<?=$pelajaran?>";
    	</script>
<?php }
	//CloseDb();			
} else if ($op == "xm8r389xemx23xb2378e23") {
	//OpenDb();
	$sql_del = "DELETE FROM jbsakad.rpp WHERE replid = '".$_REQUEST['replid']."'";
	$result_del = QueryDb($sql_del);
	if ($result_del) { 
		//CloseDb();
	?>
    	<script language="javascript">
    	document.location.href="rpp_tampil.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&semester=<?=$semester?>&pelajaran=<?=$pelajaran?>";
    	</script>
<?php }
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Rencana Program Pembelajaran]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript">

function tambah() {			
	newWindow('../guru/rpp_add.php?tingkat=<?=$tingkat?>&semester=<?=$semester?>&pelajaran=<?=$pelajaran?>', 'TambahRPP1','700','540','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refresh(idrpp) {
	document.location.href="../penilaian/rpp_tampil.php?semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&idrpp="+idrpp+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";	
}

function refresh_all() {
	var semester = document.getElementById('semester').value;
	var tingkat = document.getElementById('tingkat').value;
	var pelajaran = document.getElementById('pelajaran').value;
	
	document.location.href = "rpp_tampil.php?semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat;
}

function setaktif(replid, aktif) {
	var msg;
	var newaktif;
	
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah rencana program pengajaran ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin akan mengubah rencana program pengajaran ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
		document.location.href = "rpp_tampil.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&departemen=<?=$departemen?>";
}

function edit(replid) {
	newWindow('../guru/rpp_edit.php?replid='+replid,'UbahRPP','700','540','resizable=1,scrollbars=1,status=0,toolbar=0')
}

/*function tutup() {	
	opener.refresh_rpp(<?=$idrpp?>);
	window.close();
}*/

function tutup() {
	var idrpp= document.getElementById('idrpp').value;	
	
	if (idrpp.length==0){	
		opener.refresh_rpp(0);
		window.close();
	}else{
		parent.opener.kirim_rpp(idrpp);	
		window.close();		
	}
}
/*
function tutup() {
	var departemen = document.getElementById('departemen').value;
	var proses=document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
		
	if (kelompok.length == 0)
		parent.opener.change_kelompok(0);
	else
		parent.opener.kelompok_kiriman(kelompok,proses,departemen);
		
	window.close();
	
}
*/
function hapus(replid) {
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	
	if (confirm("Apakah anda yakin akan menghapus rencana program pengajaran ini?"))
		document.location.href = "rpp_tampil.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&departemen=<?=$departemen?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function cetak() {
	newWindow('../guru/rpp_cetak.php?tingkat=<?=$tingkat?>&semester=<?=$semester?>&pelajaran=<?=$pelajaran?>', 'CetakRPP','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
	
}

function take(idrpp,rpp) {
	opener.accept_rpp(idrpp,rpp);
	window.close();	
}

function change_urut(urut,urutan) {		
	var semester = document.getElementById('semester').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris = document.getElementById('varbaris').value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "rpp_tampil.php?semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
	
}

function change_page(page) {
	var semester = document.getElementById('semester').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="rpp_tampil.php?semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var semester = document.getElementById('semester').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="rpp_tampil.php?semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var semester = document.getElementById('semester').value;
	var pelajaran = document.getElementById('pelajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="rpp_tampil.php?semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

locnm=location.href;
pos=locnm.indexOf("indexb.htm");
locnm1=locnm.substring(0,pos);
function ByeWin() {
	windowIMA=opener.refresh_rpp(<?=$idrpp?>);
//alert ('<?=$semester?>,<?=$tingkat?>,<?=$pelajaran?>,<?=$departemen?>');
}
</script>

</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4"  onUnload="ByeWin()">
<input type="hidden" name="semester" id="semester" value="<?=$semester ?>" />
<input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran ?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat ?>" />
<input type="hidden" name="urut" id="urut" value="<?=$urut ?>" />
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan ?>" />


<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Rencana Program Pembelajaran :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" height="360" valign="top">
     <!-- CONTENT GOES HERE //--->

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- TABLE LINK -->
    <tr>
    	<td width="13%"><strong>Departemen</strong></td>
    	<td><input type="text" readonly value="<?=$departemen?>" class="disabled" size="15"/></td>
    	<td width="10%"><strong>Semester</strong></td>
    	<td><input type="text" readonly value="<?=$namasemester?>" class="disabled" size="27" /></td>
        
   	</tr>
    <tr>
        <td><strong>Tingkat</strong></td>
        <td><input type="text" readonly value="<?=$namatingkat?>" class="disabled" size="15" /></td>
        <td><strong>Pelajaran</strong></td>
        <td><input type="text" readonly value="<?=$namapelajaran?>" class="disabled"  size="27"/></td>
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
                
        <td align="right">
        <a href="#" onClick="refresh_all()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png"onMouseOver="showhint('Tambah RPP!', this, event, '50px')" border="0" />&nbsp;Tambah RPP</a>&nbsp;</td>
  	</tr>
  	</table>
	</td>
</tr>
<tr>
	<td>
    <br />  	
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left">    
      <!-- TABLE CONTENT -->
	<tr height="30" align="center" class="header">
        <td width="4%">No</td>        
    	<td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('koderpp','<?=$urutan?>')">Kode <?=change_urut('koderpp',$urut,$urutan)?></td>    
		<td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('rpp','<?=$urutan?>')">Materi <?=change_urut('rpp',$urut,$urutan)?></td>
    	<td width="*">Deskripsi</td>
    	<td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('aktif','<?=$urutan?>')">Status <?=change_urut('aktif',$urut,$urutan)?></td>	    
		<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <td width="10%">&nbsp;</td>
        <?php } ?>
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
    <?php 	if (SI_USER_LEVEL() == $SI_USER_STAFF) {  
                if ($row[4] == 1) { ?> 
            <img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/>
    <?php 		} else { ?>                
            <img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/>
    <?php 		}
            } else { 
                if ($row[4] == 1) { ?>
            <a href="JavaScript:setaktif(<?=$row[0] ?>, <?=$row[4] ?>)"><img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '80px')"/></a>
    <?php 		} else { ?>
            <a href="JavaScript:setaktif(<?=$row[0] ?>, <?=$row[4] ?>)"><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '80px')"/></a>
    <?php 		} //end if
            } //end if ?>        
        </td>
        
    <?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?> 
        <td align="center">
            <a href="JavaScript:edit(<?=$row[0] ?>)"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah RPP!', this, event, '50px')"/></a>&nbsp;
            <a href="JavaScript:hapus(<?=$row[0] ?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus RPP!', this, event, '50px')"/></a>
        </td>
    <?php 	} ?>  
    </tr>
<?php } ?>
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
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
    <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
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
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
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
<?php } else { ?>
</td><td>&nbsp;</td>
</tr>
<tr>
  	<td colspan="3"><hr style="border-style:dotted" color="#000000"/>
    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="250">
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data.
           <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru.
            <?php } ?>
            </p></b></font>
        </td>
    </tr>
    </table	
    ></td>
</tr>
</table> 
<?php } ?> 
 	</td>
</tr>
<tr height="35">
	<td align="center">    
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="tutup()" />
    <input type="hidden" name="idrpp" id="idrpp" value="<?=$idrpp?>" />
    </td>
</tr>
<!-- END TABLE CENTER -->    
</table>
<!-- END OF CONTENT //---> 
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