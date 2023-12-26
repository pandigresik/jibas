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

OpenDb();
require_once("pincs.updatedb.php");

if (isset($_REQUEST['idkelompok']))
	$idkelompok = $_REQUEST['idkelompok'];

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$op = $_REQUEST['op'];
if ($op == "dw8dxn8w9ms8zs22")
{
	$pin = random(5);
   
	$sql = "UPDATE jbsakad.calonsiswa SET pinsiswa = '$pin' WHERE nopendaftaran = '".$_REQUEST['nopendaftaran']."'";
	QueryDb($sql);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Pendataan PIN Calon Siswa]</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function gantipin(nopendaftaran)
{
	if (confirm("Apakah anda yakin akan mengganti PIN ini?"))
   {
		document.location.href = "pincs.content.php?op=dw8dxn8w9ms8zs22&idkelompok=<?= $idkelompok ?>&urut=<?=$urut?>&urutan=<?=$urutan?>&nopendaftaran="+nopendaftaran;
	}	
}

function refresh() {
	document.location.reload;
}

function cetak()
{	
	newWindow('pincs.cetak.php?idkelompok=<?=$idkelompok?>&urut=<?=$urut?>&urutan=<?=$urutan?>','CetakPendataanPINCS','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	var kelas = document.getElementById('kelas').value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "pin_footer.php?kelas="+kelas+"&urut="+urut+"&urutan="+urutan;
	
}
</script>
</head>
<body leftmargin="0" topmargin="0">
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>"/>
<input type="hidden" name="urut" id="urut" value="<?=$urut?>"/>
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan?>"/>

<table width="100%" border="0" height="100%">
<tr>
	<td>	
<?php $sql = "SELECT *
             FROM jbsakad.calonsiswa 
            WHERE idkelompok = '$idkelompok'
              AND aktif = 1
            ORDER BY $urut $urutan ";
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0)
   {  ?>

	<table width="100%" border="0" align="center">          
	<tr>
	<td align="right">            
    	<a href="#" onClick="document.location.reload();">
         <img src="../images/ico/refresh.png" border="0" name="refresh" id="refresh"
              onMouseOver="showhint('Refresh!', this, event, '50px');"/>&nbsp;Refresh
      </a>&nbsp;&nbsp;
      <a href="JavaScript:cetak()" >
         <img src="../images/ico/print.png" border="0" name="cetak" id="cetak"
              onMouseOver="showhint('Cetak!', this, event, '50px');"/>&nbsp;Cetak
      </a>&nbsp;&nbsp;   	
   </td>
	</tr>          
   </table>
   
   <br />

	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
<!-- TABLE CONTENT -->
    <tr height="30" class="header" align="center">
        <td width="4%">No</td>        
        <td width="15%" onMouseOver="background='../style/formbg2agreen.gif'; height=30;"
            onMouseOut="background='../style/formbg2.gif'; height=30;" background="../style/formbg2.gif" style="cursor:pointer;"
            onClick="change_urut('nis','<?=$urutan?>')">No Pendaftaran <?=change_urut('nopendaftaran',$urut,$urutan)?>
        </td>    
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif'; height=30;"
            onMouseOut="background='../style/formbg2.gif'; height=30;" background="../style/formbg2.gif" style="cursor:pointer;"
            onClick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?>
        </td>
        <td width="20%" onMouseOver="background='../style/formbg2agreen.gif'; height=30;"
            onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;"
            onClick="change_urut('pinsiswa','<?=$urutan?>')">PIN Calon Siswa <?=change_urut('pinsiswa',$urut,$urutan)?>
         </td>
    </tr>
<?php  while ($row = @mysqli_fetch_array($result))
    { ?>
    <tr height="25">   	
        <td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nopendaftaran']?></td>
        <td><?=$row['nama'] ?></td>      
        <td align="center"><?=$row['pinsiswa'] ?>&nbsp;
        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
        <a href="JavaScript:gantipin('<?=$row['nopendaftaran']?>')" ><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Ganti PIN!', this, event, '50px')"/></a>
        <?php } ?>
        </td>      
    </tr>
<?php  } ?>
    </table>
   
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script></div>

<?php } else { ?>

<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.      
        </b></font>
	</td>
</tr>
</table>  
<?php } ?> 
</td>
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>