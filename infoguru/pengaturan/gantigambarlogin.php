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
require_once('../include/common.php');
require_once('../include/sessioninfo.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once("../include/sessionchecker.php");

OpenDb();
function delete($file) {
 if (file_exists($file)) {
   chmod($file,0777);
   if (is_dir($file)) {
     $handle = opendir($file); 
     while($filename = readdir($handle)) {
       if ($filename != "." && $filename != "..") {
         delete($file."/".$filename);
       }
     }
     closedir($handle);
     rmdir($file);
   } else {
     unlink($file);
   }
 }
}
$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op == "dw8dxn8w9ms8zs22") {
	$sql = "UPDATE jbsvcr.gambarlogin SET aktif = 1 WHERE replid = {$_REQUEST['replid']} ";
	QueryDb($sql);
	$sql2 = "UPDATE jbsvcr.gambarlogin SET aktif = 0 WHERE replid <> {$_REQUEST['replid']} ";
	QueryDb($sql2);
} 
if ($op == "fckgwrhqq2yxrkt8tg6w2b7q8") {
	$sql = "SELECT * FROM jbsvcr.gambarlogin WHERE replid = {$_REQUEST['replid']} ";
	$res = QueryDb($sql);
	$row = @mysqli_fetch_array($res);
	if ($row['aktif']==1){
		$sql2 = "SELECT replid FROM jbsvcr.gambarlogin ORDER BY replid ASC LIMIT 1 ";
		//echo $sql2;
		$res2 = QueryDb($sql2);
		$row2 = @mysqli_fetch_array($res2);
		$akt2 = $row2['replid'];
		$sql3 = "UPDATE jbsvcr.gambarlogin SET aktif=1 WHERE replid = $akt2";
		//echo $sql3;
		$res3 = QueryDb($sql3);
	}
	$filename = "../".$row['direktori'].$row['namafile'];
	//echo $filename ;
	if ($row['direktori']!="" && $row['namafile']!=""){
	delete($filename);
	$sql4 = "DELETE FROM jbsvcr.gambarlogin WHERE replid = {$_REQUEST['replid']}";
	$res4 = QueryDb($sql4);
	} else {
	exit;
	}
}
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tools.js" type="text/javascript"></script>
<script language="javascript" src="../script/ajax.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function tambah() {
	newWindow('gambarlogin_add.php', 'TambahGambar','500','210','resizable=1,scrollbars=0,status=0,toolbar=0')
}
function view(source) {
	show_wait("viewcolumn");
	sendRequestText("getimage.php", show_preview, "source="+source);
	//newWindow('gambarlogin_add.php', 'TambahGambar','500','210','resizable=1,scrollbars=0,status=0,toolbar=0')
}
function get_fresh() {
	document.location.href="gantigambarlogin.php";
}
function setaktif(replid) {
	var msg;
	msg = "Apakah anda yakin akan mengubah gambar ini menjadi AKTIF,\nDan gambar yang lain menjdai tidak aktif?";
	if (confirm(msg)) 
		document.location.href = "gantigambarlogin.php?op=dw8dxn8w9ms8zs22&replid="+replid;
}
function show_preview(x) {
	document.getElementById("viewcolumn").innerHTML = x;
}
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}
function hapus(replid) {
	var msg;
	msg = "Apakah anda yakin akan menghapus gambar ini?";
	if (confirm(msg)) 
		document.location.href = "gantigambarlogin.php?op=fckgwrhqq2yxrkt8tg6w2b7q8&replid="+replid;
}
</script>
</head>
<body leftmargin="0" topmargin="0">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/ico/loading.gif" border="0" />&nbsp;Silakan&nbsp;tunggu...
</div>

<div align="left"><a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')" />&nbsp;Tambah Gambar</a></div><br />
<table width="950" border="0" cellspacing="0">
  <tr>
    <th width="400" scope="row" valign="top"><table width="400" border="1" cellspacing="0" class="tab" align="left">
  <tr>
    <th width="3%" height="30" align="center" class="header">No.</th>
    <td width="62%" height="30" align="center" class="header">Gambar</td>
    <td width="18%" height="30" align="center" class="header">Aktif</td>
    <td width="17%" height="30" align="center" class="header">&nbsp;</td>
  </tr>
  <?php
  OpenDb();
  $sql="SELECT * FROM jbsvcr.gambarlogin ORDER BY replid";
  $result=QueryDb($sql);
  if (@mysqli_num_rows($result)>0){
  $cnt=1;
  while ($row=@mysqli_fetch_array($result)){
  $aktif=0;
  $aktif=$row['aktif'];
  ?>
  <tr>
    <th align="center" valign="top" width="5"><?=$cnt?></th>
    <td align="center" width="150"><img title="Klik untuk Preview, Dobel Klik untuk mengaktifkan Gambar" src="../<?=$row['direktori'].$row['namafile']?>" width="80" height="60" onclick="view('<?=$row['direktori'].$row['namafile']?>')" ondblclick="setaktif('<?=$row['replid'] ?>')" style="cursor:pointer;"/></td>
    <td align="center" valign="top" width="7"><?php if ($aktif == 1) { ?>
				<img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '50px')"/>
<?php 		} else { ?>
				<a href="JavaScript:setaktif('<?=$row['replid'] ?>')"><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '50px')"/></a>
<?php 		} ?></td>
    <td align="center" valign="top" width="8"><img onclick="hapus('<?=$row['replid'] ?>')" title="Hapus gambar ini" src="../images/ico/hapus.png" width="16" height="16" style="cursor:pointer;"/></td>
  </tr>
  <?php $cnt++; } } else { ?>
  <tr>
    <th align="center" scope="row" colspan="4">Tidak ada Gambar Login</th>
  </tr>
  <?php } ?>
</table></th>
    <td width="450" valign="top"><div align="left" id="viewcolumn"></div></td>
  </tr>
</table>

</body>
</html>