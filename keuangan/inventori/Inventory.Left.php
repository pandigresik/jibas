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
//require_once("../include/errorhandler.php");
OpenDb();
$op="";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];
if ($op=="EraseGroup"){
	$sql = "DELETE FROM jbsfina.groupbarang WHERE replid='".$_REQUEST['idgroup']."'";
	$result = mysqli_query($mysqlconnection, $sql);
	$mysqli_ERRNO = mysqli_errno($mysqlconnection);
	if ($mysqli_ERRNO>0){
	?>
    <script language="javascript">
		alert('Data sedang digunakan \nsehingga tidak dapat dihapus!');
    </script>
    <?php
	//echo  "<span style='font-family:Verdana; color:red;'>Gagal Menghapus Data<br>Data Sedang Digunakan!</span>";
	//exit;
	}
}

if ($op=="EraseKelompok"){
	$sql = "DELETE FROM jbsfina.kelompokbarang WHERE replid='".$_REQUEST['idkelompok']."'";
	$result = mysqli_query($mysqlconnection, $sql);
	$mysqli_ERRNO = mysqli_errno($mysqlconnection);
	if ($mysqli_ERRNO>0){
	?>
    <script language="javascript">
		alert('Data sedang digunakan \nsehingga tidak dapat dihapus!');
    </script>
    <?php
	//echo  "<span style='font-family:Verdana; color:red;'>Gagal Menghapus Data<br>Data Sedang Digunakan!</span>";
	//exit;
	}
}

function getNSubDir($idroot) {
	global $idvolume;
	
	$sql = "SELECT count(*) FROM jbsfina.kelompokbarang WHERE idgroup='$idroot'";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	return $row[0];
}

function spacing($count) {
	$str = "";
	for ($i = 0; $i < $count * 2; $i++) 
		$str = $str . " ";
	return $str;
}
?>
<html>
<head>
<link rel="stylesheet" href="../style/style.css" />
<link rel="stylesheet" href="../script/mktree.css" />
<script language="javascript" src="../script/mktree.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function AddGroup(){
	var addr="AddGroup.php";
	newWindow(addr,'AddGroup',360,207,'');
}
function EditGroup(idgroup){
	var addr="EditGroup.php?idgroup="+idgroup;
	newWindow(addr,'EditGroup',360,207,'');
}
function GetFresh(){
	document.location.href="Inventory.Left.php";
}
function EraseGroup(idgroup){
	var msg = "Anda yakin akan menghapus Group ini?";
	if (confirm(msg))
		document.location.href="Inventory.Left.php?op=EraseGroup&idgroup="+idgroup;
}
function EraseKelompok(idkelompok){
	var msg = "Anda yakin akan menghapus Kelompok ini?";
	if (confirm(msg))
		document.location.href="Inventory.Left.php?op=EraseKelompok&idkelompok="+idkelompok;
}
function AddKelompok(idgroup){
	var addr="AddKelompok.php?idgroup="+idgroup;
	newWindow(addr,'AddKelompok',360,207,'');
}
function EditKelompok(idkelompok){
	var addr="EditKelompok.php?idkelompok="+idkelompok;
	newWindow(addr,'EditKelompok',360,207,'');
}
function OnLoad(){
	parent.Right.location.href="Inventory.Right.php";
}
function Hover(id,state){
	if (state=='1')
		document.getElementById(id).style.background='#fffcca';
	else
		document.getElementById(id).style.background='';
}
function SelectKelompok(idkelompok){
	parent.Right.location.href="Inventory.Right.php?idkelompok="+idkelompok;
}
</script>
</head>
<body onLoad="OnLoad()">
<!--
<a href="#" onClick="document.location.reload()"><img src="../../images/ico/refresh.png" border="0" /></a>&nbsp;|&nbsp;<a href="#" onClick="expandTree('tree1'); return false;">Expand All</a>&nbsp;|&nbsp;
<a href="#" onClick="collapseTree('tree1'); return false;">Collapse All</a><br /><br /><br />
-->
<fieldset style="border:#336699 1px solid; background-color:#ffffff" >
<legend style="background-color:#336699; color:#FFFFFF; font-size:10px; font-weight:bold; padding:5px">&nbsp;Group&nbsp;Barang&nbsp;</legend>
<div align="right">
<a href="javascript:AddGroup()"><img src="../images/ico/tambah.png" border="0">Tambah Group</a>
</div>
<?php
$sql = "SELECT replid,namagroup FROM jbsfina.groupbarang ORDER BY namagroup";
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
if ($num>0){
?>
<ul class='mktree' id='tree1'>
<?php
while ($row = @mysqli_fetch_row($result)){
$class="liOpen";
if (getNSubDir($row[0])==0)
	$class="liClose";
?>
<li class="liOpen" style="cursor:default">&nbsp;<img src='../images/ico/folder.gif' border='0'>&nbsp;<strong><?=stripslashes((string) $row[1])?></strong>&nbsp;<a href="javascript:AddKelompok('<?=$row[0]?>')"><img src="../images/ico/tambah.png" border='0' title="Tambah Kelompok"></a><a href="javascript:EditGroup('<?=$row[0]?>')"><img src="../images/ico/ubah.png" border='0' title="Ubah Group"></a><a href="javascript:EraseGroup('<?=$row[0]?>')"><img src="../images/ico/hapus.png" border='0' title="Hapus Group"></a>
<?php
$sql2 = "SELECT replid,kelompok FROM jbsfina.kelompokbarang WHERE idgroup='".$row[0]."'"; 
$result2 = QueryDb($sql2);
$num2 = @mysqli_num_rows($result2);
if ($num2>0){
echo  "<ul>";
while ($row2 = @mysqli_fetch_row($result2)){
?>
<li class="liOpen" id="liOpen<?=$row2[0]?>" onMouseOver="Hover('liOpen<?=$row2[0]?>','1')" onMouseOut="Hover('liOpen<?=$row2[0]?>','0')" onClick="SelectKelompok('<?=$row2[0]?>')">
<!--<span >-->
<span ><img src='../images/ico/page.gif' border='0'>&nbsp;<?=stripslashes((string) $row2[1])?></span>&nbsp;<img src="../images/ico/ubah.png" border='0' onClick="EditKelompok('<?=$row2[0]?>')" title="Ubah Kelompok" style="cursor:pointer"><img src="../images/ico/hapus.png" border='0' onClick="EraseKelompok('<?=$row2[0]?>')" title="Hapus Kelompok" style="cursor:pointer">
<!--</span>-->
</li>
<?php
}
echo  "</ul>";
} 
}
?>
</li>
</ul>
<script language="javascript">
collapseTree('tree1');
</script>
<?php
} else {
echo  "<div align='center'><br><em>Tidak ada Group Barang</em><br><br></div>";
}
?>
</fieldset>
</body>
<?php
CloseDb();
?>
</html>