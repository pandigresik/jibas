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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../include/rupiah.php");

$varkolom=4;
OpenDb();
$idkelompok = $_REQUEST['idkelompok'];
if ($idkelompok=="")
	exit;
$sql = "SELECT kelompok FROM jbsfina.kelompokbarang WHERE replid='$idkelompok'";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$namakelompok = $row[0];
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op=="EraseBarang"){
	$sql = "DELETE FROM jbsfina.barang WHERE replid='".$_REQUEST['idbarang']."'";
	QueryDb($sql);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function TambahBarang(idkelompok){
	var addr="AddBarang.php?idkelompok="+idkelompok;
	newWindow(addr,'AddBarang',432,420,'');
}

function GetFresh(){
	document.location.reload();
}

function Hover(id,state){
	if (state=='1'){
		document.getElementById(id).style.background='#fffcca';
		document.getElementById(id).style.border='2px #d8d277 solid';
	} else {
		document.getElementById(id).style.background='';
		document.getElementById(id).style.border='2px #eaf4ff solid';
	}
}

function ubah(idbarang, evt){
	if(evt.target.nodeName=='IMG') {
		var addr="EditBarang.php?idbarang="+idbarang+"&idkelompok=<?=$_REQUEST['idkelompok']?>";
		newWindow(addr,'EditBarang',450,420,'resizable=1');		
		return false;
	}
}

function hapus(idbarang, evt){
	var e = evt.target;
	if(e.nodeName=='DIV') {
		return false;
	} else {
		var msg = "Anda yakin akan menghapus barang ini?";
		if (confirm(msg))
			document.location.href="Inventory.Right.php?op=EraseBarang&idbarang="+idbarang+"&idkelompok=<?=$_REQUEST['idkelompok']?>";	
	}
}

function ViewDetail(idbarang,evt){
	if(evt.target.nodeName !='IMG'){
			var addr="ViewDetailBarang.php?idbarang="+idbarang;
			newWindow(addr,'ViewDetail',480,324,'resizable=1');
	}
}

function Cetak(idkelompok)
{
	var addr = "Inventory.Cetak.php?idkelompok=" + idkelompok;
	newWindow(addr, 'CetakInventory','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function Excel(idkelompok)
{
	var addr = "Inventory.Excel.php?idkelompok=" + idkelompok;
	newWindow(addr, 'ExcelInventory','780','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body>
<fieldset style="border:#336699 1px solid; background-color:#FFFFFF" >
<legend style="background-color:#336699; color:#FFFFFF; font-size:10px; font-weight:bold; padding:5px">&nbsp;Kelompok&nbsp;<?=stripslashes((string) $namakelompok)?>&nbsp;</legend>
<div align="right">
  <a href="javascript:TambahBarang('<?=$idkelompok?>')"><img src="../images/ico/tambah.png" border="0" />&nbsp;Tambah Barang</a>&nbsp;&nbsp;|&nbsp;
  <a href="javascript:Cetak('<?=$idkelompok?>')"><img src="../images/ico/print.png" border="0" />&nbsp;Cetak</a>&nbsp;&nbsp;|&nbsp;
  <a href="javascript:Excel('<?=$idkelompok?>')"><img src="../images/ico/excel.png" border="0" />&nbsp;Excel</a>
</div>
<?php
$sql = "SELECT * FROM jbsfina.barang WHERE idkelompok='$idkelompok'";
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
$total = ceil(mysqli_num_rows($result)/(int)$varkolom);
if ($num>0){
?>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<?php
$cnt=1;
while ($row = @mysqli_fetch_array($result))
{
	if ($cnt==1 || $cnt%(int)$varkolom==1){
	?><tr><?php
	}
	
	$jumlah = (int)$row['jumlah'];
	$satuan = $row['satuan'];
	$harga = (int)$row['info1'];
	$total = $jumlah * $harga;	
?>
<td valign="top" align="center">
<div id="div<?=$row['replid']?>" style="padding:5px; width:200px; margin:5px; border:2px solid #eaf4ff; cursor:default" onmouseover="Hover('div<?=$row['replid']?>','1')" onmouseout="Hover('div<?=$row['replid']?>','0')" title="<?=$row['keterangan']?>" onclick="ViewDetail('<?=$row['replid']?>',event)">
<div align="left">
<span style="font-family:Arial; font-size:14px; font-weight:bold; color:#990000"><?=$row['kode']?></span><br />
<span style="font-family:Arial; font-size:12px; font-weight:bold; color:#006600; cursor:pointer"><?=$row['nama']?></span><br />
</div>
<img src="gambar.php?table=jbsfina.barang&replid=<?=$row['replid']?>"  style="padding:2px" />
<div align="left">
Jumlah: <?=$jumlah?>&nbsp;<?=$satuan?>&nbsp;@<?=FormatRupiah($harga)?><br />
Total: <?=FormatRupiah($total)?><br>
Tanggal: <?=substr((string) $row['tglperolehan'],8,2)."-".substr((string) $row['tglperolehan'],5,2)."-".substr((string) $row['tglperolehan'],0,4)?><br />
<img src="../images/ico/ubah.png" border="0" onclick="ubah('<?=$row['replid']?>', event)" title="Ubah" style="cursor:pointer; z-index:100" />&nbsp;<img src="../images/ico/hapus.png" border="0" onclick="hapus('<?=$row['replid']?>', event)" title="Hapus" style="cursor:pointer; z-index:100" />
</div>
</div>
</td>
<?php
if ($num<$varkolom){
	if ($num==1)
		echo  "<td width='157'>&nbsp;</td><td width='157'>&nbsp;</td><td width='157'>&nbsp;</td><td width='157'>&nbsp;</td>";
	elseif ($num==2)
		echo  "<td width='157'>&nbsp;</td><td width='157'>&nbsp;</td><td width='157'>&nbsp;</td>";	
	elseif ($num==3)
		echo  "<td width='157'>&nbsp;</td><td width='157'>&nbsp;</td>";
	elseif ($num==4)
		echo  "<td width='157'>&nbsp;</td>";
}
if ($cnt%(int)$varkolom==0){
?></tr><?php
}
$cnt++;
}
?>
</table>
<?php } else { ?>
<div align="center"><span style="font-family:verdana; font-size:12px; font-style:italic; color:#666666">Tidak ada Data Barang Untuk Kelompok <?=stripslashes((string) $namakelompok)?></span></div>
<?php } ?>
</fieldset>
</body>
<?php
CloseDb();
?>
</html>