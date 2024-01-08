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
require_once('../include/theme.php'); 
require_once('../library/departemen.php');
require_once('../cek.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];

$jadwal = $_REQUEST['jadwal'];
$urut = "deskripsi";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$op = $_REQUEST['op'];

if ($op == "dw8dxn8w9ms8zs22") {	
	OpenDb();
	$sql = "UPDATE infojadwal SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql);
	CloseDb();
	$jadwal = $_REQUEST['replid'];
} else if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql = "DELETE FROM infojadwal WHERE replid = '".$_REQUEST['replid']."'";	
	QueryDb($sql);		
	CloseDb();	
	$jadwal = $_REQUEST['replid'];
}	
OpenDb();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript">
function change_departemen() {	
	var departemen=document.getElementById("departemen").value;
	document.location.href = "info_jadwal.php?departemen="+departemen;
}

function change_tahunajaran() {
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	document.location.href = "info_jadwal.php?departemen="+departemen+"&tahunajaran="+tahunajaran;
}

function tambah() {
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	
	newWindow('info_jadwal_add.php?tahunajaran='+tahunajaran,'TambahInfoJadwal','390','250','resizable=1,scrollbars=1,status=0,toolbar=0')	
}


function refresh(jadwal) {	
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	document.location.href = "info_jadwal.php?jadwal="+jadwal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&urut=<?=$urut?>&urutan=<?=$urutan?>";
}

function setaktif(aktif, replid) {
	var msg;
	var newaktif;
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah jadwal ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin akan mengubah jadwal ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
		document.location.href = "info_jadwal.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&departemen="+departemen+"&tahunajaran="+tahunajaran;
}

function hapus(replid) {

	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	
	
	if (confirm("Apakah anda yakin akan menghapus info jadwal ini?"))
		document.location.href = "info_jadwal.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&urut=<?=$urut?>&urutan=<?=$urutan?>";
		
}

function tutup() {
	var jadwal= document.getElementById('jadwal').value;
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	
	if (jadwal.length > 0)
		parent.opener.change(jadwal,tahunajaran,departemen);		
	
	window.close();
}

function change_urut(urut,urutan) {		
	var departemen = document.getElementById('departemen').value;	
	var tahunajaran = document.getElementById('tahunajaran').value;	
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "info_jadwal.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&urut="+urut+"&urutan="+urutan;
	
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

locnm=location.href;
pos=locnm.indexOf("indexb.htm");
locnm1=locnm.substring(0,pos);
function ByeWin() {
	windowIMA=parent.opener.change(0);
}
</script>
<title>JIBAS SIMAKA [Daftar Info Jadwal]</title>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onUnload="ByeWin()" onload="document.getElementById('departemen').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Info Jadwal :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" height="335" valign="top">
    
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>	
	<td align="left" valign="top">


   
    
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- TABLE LINK -->
    <tr>
    	<td width="20%"><strong>Departemen </strong></td>
    	<td width="20%">
        <select name="departemen" id="departemen" onChange="change_departemen()" style="width:150px;">
        <?php $dep = getDepartemen(SI_USER_ACCESS());    
		foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
          <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
            <?=$value ?> 
            </option>
              <?php } ?>
        </select>  		</td>
    </tr>
    <tr>
    	<td><strong>Tahun Ajaran</strong></td>
        <td>  
        <select name="tahunajaran" id="tahunajaran" onChange="change_tahunajaran()" style="width:150px;">
   		 	<?php
			OpenDb();
			$sql = "SELECT replid,tahunajaran,aktif FROM tahunajaran where departemen='$departemen' ORDER BY aktif DESC, replid DESC";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
				if ($tahunajaran == "") 
					$tahunajaran = $row['replid'];
				if ($row['aktif']) 
					$ada = '(Aktif)';
				else 
					$ada = '';			 
			?>
            
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tahunajaran)?> ><?=$row['tahunajaran'].' '.$ada?></option>
    		<?php
			}
    		?>
    	</select>   
        </td>
<?php if ($tahunajaran <> "" && $departemen <> "") { 
	OpenDb();	
	$sql = "SELECT i.deskripsi, i.aktif, i.replid FROM jbsakad.infojadwal i, jbsakad.tahunajaran t WHERE t.departemen ='$departemen' AND i.idtahunajaran = '$tahunajaran' AND i.idtahunajaran = t.replid ORDER BY $urut $urutan";
	
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0) {
?>
        <td align="right">
        	<a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '80px')">&nbsp;Refresh</a>&nbsp;&nbsp;
            <a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah Info Jadwal!', this, event, '80px')">&nbsp;Tambah Info Jadwal</a></td>
    </tr>
	</table>
	</td>
</tr>
<tr>
	<td> 
    <br /> 
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left">
	<tr class="header" height="30" align="center">
        <td width="10%">No</td>
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('deskripsi','<?=$urutan?>')">Info
          Jadwal <?=change_urut('deskripsi',$urut,$urutan)?></td>        
        <td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('i.aktif','<?=$urutan?>')">Status <?=change_urut('i.aktif',$urut,$urutan)?></td>
        <td width="15%">&nbsp;</td>
	</tr>
    <?php
	$cnt=1;
	while ($row = @mysqli_fetch_array($result)) {				
		$replid=$row['replid'];
	?>
    <tr height="25">
        <td align="center"><?=$cnt?></td>
        <td><?=$row['deskripsi']?></td>
        <td align="center">
        <?php if ($row['aktif']==1){ ?>
            <img src="../images/ico/aktif.png" onClick="setaktif(<?=$row['aktif']?>,<?=$row['replid']?>)" onMouseOver="showhint('Status Aktif', this, event, '80px')">
        <?php } else { ?>
            <img src="../images/ico/nonaktif.png" onClick="setaktif(<?=$row['aktif']?>,<?=$row['replid']?>)" onMouseOver="showhint('Status Tidak Aktif', this, event, '80px')">
        <?php } ?> 
		</td>
        <td align="center">
        	<a href="#" onClick="newWindow('info_jadwal_edit.php?replid=<?=$row['replid']?>',     'UbahInfoJadwal','390','250','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Info Jadwal!', this, event, '80px')"></a>&nbsp;
        	<a href="JavaScript:hapus(<?=$row['replid']?>)" ><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Info Jadwal!', this, event, '80px')"></a>        </td>
        
	</tr> 
     
    <?php
	$cnt++;	
	} //while
	CloseDb();
	?>
    
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
  
	</table>
  
<?php } else { ?>
	<td width = "48%"></td>
	</tr>
	</table>
	<table width="100%" border="0" align="center">
   	<tr>
    	<td colspan="3"><hr style="border-style:dotted" color="#000000"/> 
       	</td>
   	</tr>
	<tr>
		<td align="center" valign="middle" height="150">
    	
        <font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        
        <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
        
        </b></font>  
   
        </td>
   	</tr>
   	</table>

<?php }
} else { ?>
	<td width = "48%"></td>
	</tr>
	</table>
    <table width="100%" border="0" align="center">
   	<tr>
    	<td colspan="3"><hr style="border-style:dotted" color="#000000" /> 
       	</td>
   	</tr>
	<tr>
		<td align="center" valign="middle" height="150">
    
    <?php if ($departemen == "") { ?>
		<font size = "2" color ="red"><b>Belum ada data Departemen.
        <br />Silahkan isi terlebih dahulu di menu Departemen pada bagian Referensi.
        </b></font>
    <?php } elseif ($tahunajaran == "") {?>
    	<font size = "2" color ="red"><b>Belum ada data Tahun Ajaran.
        <br />Silahkan isi terlebih dahulu di menu Tahun Ajaran pada bagian Referensi.
        </b></font>
    <?php } ?>
        </td>
   	</tr>
   	</table>
<?php } ?> 
	</td>
</tr>
<tr height="35">
	<td colspan="3" align="center">   	
       <input class="but" type="button" value="Tutup" onClick="tutup()">
       <input type="hidden" name="jadwal" id="jadwal" value="<?=$jadwal?>" />
   	</td>
</tr>  
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
	var spryselect1 = new Spry.Widget.ValidationSelect("tahunajaran");
</script>