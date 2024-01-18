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
require_once('include/theme.php');
require_once('include/sessioninfo.php');

$kategori = "";
if (isset($_REQUEST['kategori']))
	$kategori = $_REQUEST['kategori'];

$flag = $_REQUEST['flag'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "kode";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
	
$option = "";
if (isset($_REQUEST['option']))
	$option = $_REQUEST['option'];
	
OpenDb();	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Cari Rekening Perkiraan]</title>
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function change_kategori()
{
	var kate = document.getElementById('kategori').value;
	document.location.href = "carirek.php?option=<?=$option?>&kategori="+kate+"&flag=<?=$flag?>";
}

function pilih(kode, nama)
{
	opener.accept_rekening(kode, nama, <?=$flag ?>);
	window.close();
}

function focusNext(elemName, evt)
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13)
	{
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function change_urut(urut,urutan)
{			
	var kate = document.getElementById('kategori').value;

	if (urutan =="ASC")
		urutan="DESC"
	else 
		urutan="ASC"
	
	document.location.href = "carirek.php?option=<?=$option?>&kategori="+kate+"&flag=<?=$flag?>&urutan="+urutan+"&urut="+urut+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" style='background-color:#dfdec9' onLoad="document.getElementById('kategori').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Cari Rekening Perkiraan :.
    </div>
	</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" valign="top" height="350">
    <!-- CONTENT GOES HERE //--->
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>	
	<td align="left" valign="top">
	<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- TABLE LINK -->
    <tr>
    	<td align="left" width="50%"><strong>Kategori&nbsp;</strong>
<?php 		if ($option == "ro") { ?>

			<input type="text" name="kategori" id="kategori" readonly="readonly" style="background-color:#DDD" value="<?= strtoupper((string) $kategori) ?>">

<?php 	} else { ?>

			<select name="kategori" id="kategori" onChange="change_kategori()" onKeyPress="return focusNext('pilih1', event)">
			<?php
			OpenDb();
			$sql = "SELECT * FROM katerekakun ORDER BY urutan";
			$result = QueryDb($sql);
			while ($row = mysqli_fetch_array($result))
			{
				if ($kategori == "")
					$kategori = $row['kategori']; ?>
				<option value="<?=$row['kategori']?>" <?=StringIsSelected($kategori, $row['kategori'])?> ><?=$row['kategori']?></option>
			<?php
			}
			CloseDb();
			?>
			</select>
		
<?php 		} ?>
        
        </td>
        <!--<td align="right" width="50%">
        <input type="button" class="but" value="Tutup" onclick="window.close()" />
        </td>-->
    </tr>
	</table>
    </td>
</tr>
<tr>
	<td>
<?php OpenDb();
	$sql_tot = "SELECT * FROM rekakun WHERE kategori='$kategori' ORDER BY kode";
	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$sql = "SELECT * FROM rekakun WHERE kategori='$kategori' ORDER BY $urut $urutan "; 	
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0)
	{
		$tot = mysqli_num_rows($result); ?>
		<br />
		<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
		<tr height="30" align="center" class="header">
			<td width="8%" >No</td>
			<td width="15%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" onClick="change_urut('kode','<?=$urutan?>')" style="cursor:pointer;">Kode <?=change_urut('kode',$urut,$urutan)?></td>
			<td width="30%"  onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" onClick="change_urut('nama','<?=$urutan?>')" style="cursor:pointer;">Nama <?=change_urut('nama',$urut,$urutan)?></td>
			<td>Keterangan</td>
			<td width="6%">&nbsp;</td>
		</tr>
<?php 		if ($page==0)
			$no = 0;
		else 
			$no = (int)$page*(int)$varbaris;
		
		while ($row = mysqli_fetch_array($result))
		{ ?>
			<tr onclick="pilih('<?=$row['kode'] ?>','<?=$row['nama'] ?>')">
				<td align="center"><?=++$no ?></td>
				<td align="center"><?=$row['kode'] ?></td>
				<td><?=$row['nama'] ?></td>
				<td><?=$row['keterangan'] ?></td>
				<td align="center">
				<input type="button" class="but" value="pilih" name="pilih<?=$no ?>" id="pilih<?=$no ?>" onclick="pilih('<?=$row['kode'] ?>','<?=$row['nama'] ?>')"  />
				</td>
			</tr>
<?php 		} ?>
		</table>
		<script language='JavaScript'>
			Tables('table', 1, 0);
		</script>
<?php 	if ($page==0)
		{ 
			$disback="style='visibility:hidden;'";
			$disnext="style='visibility:visible;'";
		}
		if ($page<$total && $page>0)
		{
			$disback="style='visibility:visible;'";
			$disnext="style='visibility:visible;'";
		}
		if ($page==$total-1 && $page>0)
		{
			$disback="style='visibility:visible;'";
			$disnext="style='visibility:hidden;'";
		}
		if ($page==$total-1 && $page==0)
		{
			$disback="style='visibility:hidden;'";
			$disnext="style='visibility:hidden;'";
		}	?>
    </td>
</tr> 
<tr>
    <td>
<?php }
	else
	{	?>	
		<table width="100%" border="0" align="center">
		<tr><td><hr style="border-style:dotted" /></td></tr>          
		<tr>
			<td align="center" valign="middle" height="200">    
				<font size = "2" color ="red"><b>Tidak ditemukan adanya data.         
				<br />Tambah data kode rekening pada kategori <?=$kategori?> di menu Kode Rekening Perkiraan pada bagian Penerimaan.        
				</b></font>
			</td>
		</tr>
		</table>  
<?php
}
?>    
	</td>
</tr>
<tr height="35">
	<td align="center">
       <input class="but" type="button" value="Tutup" onClick="window.close()">
   	</td>
</tr>  
</table>

	<!-- END OF CONTENT //--->
    </td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("kategori");
</script>
<?php
CloseDb();
?>