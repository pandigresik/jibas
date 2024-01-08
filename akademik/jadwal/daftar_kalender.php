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

$kalender = $_REQUEST['kalender'];

$urut = "kalender";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$op = $_REQUEST['op'];

OpenDb();

if ($op == "dw8dxn8w9ms8zs22")
{	
	$sql = "UPDATE jbsakad.kalenderakademik SET aktif = '".$_REQUEST['newaktif']."' WHERE replid = '".$_REQUEST['replid']."' ";
	QueryDb($sql);
	$kalender = $_REQUEST['replid'];
}
else if ($op == "xm8r389xemx23xb2378e23")
{
	$sql = "DELETE FROM jbsakad.kalenderakademik WHERE replid = '".$_REQUEST['replid']."'";	
	QueryDb($sql);		
	$kalender = $_REQUEST['replid'];
}
else if ($op == "c234907nxdhnqhwflqf23f3f33")
{
	$sql = "UPDATE jbsakad.kalenderakademik
				  SET aktif = 0
				WHERE replid <> '".$_REQUEST['replid']."'
				  AND departemen = '".$_REQUEST['departemen']."'";	
	QueryDb($sql);
	
	$sql = "UPDATE jbsakad.kalenderakademik
				  SET aktif = 1
				WHERE replid = '".$_REQUEST['replid']."'";	
	QueryDb($sql);
	
	$kalender = $_REQUEST['replid'];
}
else if ($op == "vmt489tukd9fcmf92kd2309scm2323rc3")
{
	$sql = "SELECT COUNT(replid)
				 FROM jbsakad.kalenderakademik
				WHERE departemen = '".$_REQUEST['departemen']."'";
	$res = QueryDb($sql);
	$row = mysqli_fetch_row($res);
	$ncal = (int)$row[0];
	
	if ($ncal > 1)
	{
		$sql = "UPDATE jbsakad.kalenderakademik SET aktif = 0 WHERE replid = '".$_REQUEST['replid']."'";	
		QueryDb($sql);
	}
	
	$kalender = $_REQUEST['replid'];
}	
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
	document.location.href = "daftar_kalender.php?departemen="+departemen;
}

function tambah() {
	var departemen=document.getElementById("departemen").value;
	newWindow('kalender_add.php?departemen='+departemen,'TambahKalenderAkademik','440','280','resizable=1,scrollbars=1,status=0,toolbar=0')	
}


function refresh(kalender) {	
	var departemen = document.getElementById('departemen').value;
	
	document.location.href = "daftar_kalender.php?kalender="+kalender+"&departemen="+departemen+"&urut=<?=$urut?>&urutan=<?=$urutan?>";
}

function hapus(replid) {
	var departemen = document.getElementById('departemen').value;
		
	if (confirm("Apakah anda yakin akan menghapus kalender akademik ini?"))
		document.location.href = "daftar_kalender.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen+"&urut=<?=$urut?>&urutan=<?=$urutan?>";
		
}

function aktif(replid)
{
	var departemen = document.getElementById('departemen').value;		
	if (confirm("Apakah anda akan mengaktifkan kalender akademik ini?"))
		document.location.href = "daftar_kalender.php?op=c234907nxdhnqhwflqf23f3f33&replid="+replid+"&departemen="+departemen+"&urut=<?=$urut?>&urutan=<?=$urutan?>";		
}

function nonaktif(replid)
{
	var departemen = document.getElementById('departemen').value;		
	if (confirm("Apakah anda akan menonaktifkan kalender akademik ini?"))
		document.location.href = "daftar_kalender.php?op=vmt489tukd9fcmf92kd2309scm2323rc3&replid="+replid+"&departemen="+departemen+"&urut=<?=$urut?>&urutan=<?=$urutan?>";		
}

function tutup() {	
	var kalender= document.getElementById('kalender').value;
	var departemen = document.getElementById('departemen').value;
				
	if (kalender.length != 0)	
		parent.opener.refresh_change(kalender,departemen);		
	window.close();
}

function change_urut(urut,urutan) {		
	var departemen = document.getElementById('departemen').value;	
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "daftar_kalender.php?departemen="+departemen+"&urut="+urut+"&urutan="+urutan;
	
}

locnm=location.href;
pos=locnm.indexOf("indexb.htm");
locnm1=locnm.substring(0,pos);
function ByeWin() {

windowIMA=parent.opener.refresh_change(0,0);
}
</script>
<title>JIBAS SIMAKA [Daftar Kalender Akademik]</title>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onUnload="ByeWin()" onload="document.getElementById('departemen').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Kalender Akademik :.
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
    	<td><strong>Departemen </strong>    	
        <select name="departemen" id="departemen" onChange="change_departemen()" >
        <?php $dep = getDepartemen(SI_USER_ACCESS());    
		foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
          <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
            <?=$value ?> 
            </option>
              <?php } ?>
        </select>  		
        </td>  
        <td align="right">
        	
         <?php 
		if ($departemen <> "") {	
			OpenDb();
			$sql = "SELECT * FROM jbsakad.kalenderakademik i, jbsakad.tahunajaran t WHERE t.departemen ='$departemen' AND i.idtahunajaran = t.replid";
			//$sql = "SELECT COUNT(t.replid) FROM jbsakad.tahunajaran t WHERE t.departemen = '$departemen' UNION SELECT COUNT(k.replid) FROM jbsakad.kalenderakademik k WHERE k.departemen = '".$departemen."'";
			//echo $sql;
			$result = QueryDb($sql);
			$row = mysqli_fetch_row($result);
			$jumlah = $row[0];
			//echo 'ada row '.$row[0];
		 ?> 
         	<a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '80px')">&nbsp;Refresh</a>&nbsp;&nbsp;  
				<a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah Info Jadwal!', this, event, '80px')">&nbsp;Tambah Kalender Akademik</a>
         <?php //} 
		} 
		?>   
            
         </td>
    </tr>
	</table>
	</td>
</tr>
<tr>
	<td> 
     
<?php 
if ($departemen <> "") {	
	OpenDb();	
	$sql = "SELECT i.kalender, i.aktif, i.replid, i.terlihat, i.idtahunajaran, t.tglmulai, t.tglakhir FROM jbsakad.kalenderakademik i, jbsakad.tahunajaran t WHERE t.departemen ='$departemen' AND i.idtahunajaran = t.replid ORDER BY $urut $urutan";
	
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0) {
	?>
    <br /> 
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left">
    <tr class="header" height="30" align="center">
        <td width="10%">No</td>
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('kalender','<?=$urutan?>')">Kalender Akademik <?=change_urut('kalender',$urut,$urutan)?></td>        
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('tglmulai','<?=$urutan?>')">Periode <?=change_urut('tglmulai',$urut,$urutan)?></td>                
        <td width="*">&nbsp;</td>
	</tr>
    <?php
	$cnt=1;
	while ($row = @mysqli_fetch_array($result)) {				
		$replid=$row['replid'];
	?>
    <tr height="25">
        <td align="center"><?=$cnt?></td>
        <td><?=$row['kalender']?></td>
        <td><?=LongDateFormat($row['tglmulai']).' s/d '.LongDateFormat($row['tglakhir'])?></td>
        <td align="center">
<?php 		if ($row['aktif'] == 1) { ?>
			<a href="JavaScript:nonaktif(<?=$row['replid']?>)" ><img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Set Aktif Kalender Akademik!', this, event, '80px')"></a>
<?php 		} else { ?>
			<a href="JavaScript:aktif(<?=$row['replid']?>)" ><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Set Non Aktif Kalender Akademik!', this, event, '80px')"></a>
<?php 		} ?>
        	<a href="#" onClick="newWindow('kalender_edit.php?replid=<?=$row['replid']?>',     'UbahKalender','440','280','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Kalender Akademik!', this, event, '80px')"></a>&nbsp;
        	<a href="JavaScript:hapus(<?=$row['replid']?>)" ><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Kalender Akademik!', this, event, '80px')"></a>        </td>
        
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
	<table width="100%" border="0" align="center">
   	<tr>
    	<td colspan="3"><hr style="border-style:dotted" color="#000000"/> 
       	</td>
   	</tr>
	<tr>
		<td align="center" valign="middle" height="150">
    	<?php if ($jumlah == 0) { ?>
        	<font size = "2" color ="red"><b>Belum ada data Tahun Ajaran.
        	<br />Silahkan isi terlebih dahulu di menu Tahun Ajaran pada bagian Referensi.
        	</b></font>
        
        <?php } else { ?>
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
            <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk mengisi data baru. 
            <?php } ?>
            </b></font>  
   		<?php } ?>
        </td>
   	</tr>
   	</table>

<?php }
} else { ?>

	<table width="100%" border="0" align="center">
   	<tr>
    	<td colspan="3"><hr style="border-style:dotted" color="#000000"/> 
       	</td>
   	</tr>
	<tr>
		<td align="center" valign="middle" height="150">
    
    <?php if ($departemen == "") { ?>
		<font size = "2" color ="red"><b>Belum ada data Departemen.
        <br />Silahkan isi terlebih dahulu di menu Departemen pada bagian Referensi.
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
       <input type="hidden" name="kalender" id="kalender" value="<?=$kalender?>" />
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
</script>