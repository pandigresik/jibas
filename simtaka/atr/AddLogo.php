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
require_once("../inc/session.checker.php");
require_once("../inc/config.php");
require_once("../inc/db_functions.php");
require_once("../inc/common.php");//imageresizer.php
require_once("../inc/imageresizer.php");
require_once("../inc/fileinfo.php");
OpenDb();
$op = $_REQUEST['op'];
$perpustakaan = $_REQUEST['perpustakaan']; 
$logo = $_FILES['filegambar'];
$uploadedfile = $logo['tmp_name'];
$uploadedfile_name = $logo['name'];
$OL='';
$dis='';
if (strlen((string) $uploadedfile)!=0){
	$filename='temp'.GetFileExt($uploadedfile_name);
	ResizeImage($logo, 100, 80, 70, $filename);
	$handle = fopen($filename, "r");
	$fn = $filename;
	$OL = "1";
	$dis = '';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pengaturan Logo Header</title>
<script language="javascript" src="../scr/ajax.js"></script>
<script language="javascript">
function ShowWait(id){
	var x = document.getElementById('WaitBox').innerHTML;
	document.getElementById(id).innerHTML = x;
}
function Load(){
	var perpustakaan = document.getElementById('perpustakaan').value;
	var op = document.getElementById('op').value;
	document.getElementById('notification').value=1;
	ShowWait('ImageArea');
	sendRequestText("GetImage.php",ShowImage,"perpustakaan="+perpustakaan+"&op="+op);
}
function ShowImage(x){
	document.getElementById('ImageArea').innerHTML = x;
}
function SaveSuccess(){
	parent.opener.Fresh();
	window.close();
}
function Close(){
	var x = document.getElementById('notification').value;
	if (x.length>0){
		if (confirm('Gambar belum disimpan!\nAnda yakin akan menutup jendela ini?'))
			window.close();
	} else {
			window.close();
	}
}
function SimpanGambar(){
	var fn = '<?=$fn?>';
	var x  = document.getElementById('perpustakaan').value;
	if (fn!='')
		parent.HiddenFrame.location.href='CopyTmpToFix.php?op=CopyTempImage&perpustakaan='+x+'&filename='+fn;
	else
		alert ('Silakan Browse gambar terlebih dahulu!');
}
function formSubmit(){
	var file = document.getElementById("filegambar").value;
	if (file.length>0){
		var ext = "";
		var i = 0;
		var string4split='.';

		z = file.explode(string4split);
		ext = z[z.length-1];
		
		if (ext!='JPG' && ext!='jpg' && ext!='Jpg' && ext!='JPg' && ext!='JPEG' && ext!='jpeg'){
			alert ('Format Gambar harus ber-extensi jpg atau JPG !');
			//document.getElementById("foto").value='';
			//document.form1.foto.focus();
    		//document.form1.foto.select();
			return false;
		} 
	}
	document.getElementById('FrmLogo').submit();
}
</script>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
</head>
<body >
<div id="WaitBox" style="position:absolute; visibility:hidden">
	<img src="../img/loading.gif" />
</div>
<div id="title" align="right">
	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
	<font style="font-size:18px; color:#999999">Logo Header</font><br />
</div>
<form id="FrmLogo" action="AddLogo.php" method="post" enctype="multipart/form-data" >
<input type="hidden" name="op" id="op" value="<?=$op?>" />
<input type="hidden" id="notification" value="<?=$OL?>" />
<input type="hidden" name="perpustakaan" id="perpustakaan" value="<?=$perpustakaan?>" />
<table width="100%" border="0">
  <tr>
    <td align="center" id="ImageArea" height="150">
    	<?php
		if ($op=='Edit'){
			$sql = "SELECT replid FROM ".$db_name_umum.".identitas WHERE status='1' AND perpustakaan='$perpustakaan'";
			$result = QueryDb($sql);
			$row  =@mysqli_fetch_array($result);
			echo "<img src='../lib/gambar.php?replid=".$row['replid']."&table=".$db_name_umum.".identitas&field=foto'><br>Logo Lama";
		}
		?>
    </td>
    </tr>
  <tr>
    <td>File Gambar : <input type="file" id="filegambar" name="filegambar" onchange="formSubmit()" /></td>
    </tr>
  <tr>
    <td align="center">
    <input name="Simpan2" type="submit" value="Simpan2" class="btnfrm2" style="display:none" />
    <input name="Simpan" <?=$dis?> type="button" value="Simpan" class="btnfrm2" onclick="SimpanGambar()" />
    &nbsp;&nbsp;<input type="button" value="Batal" onClick="Close()" class="btnfrm2" /></td>
  </tr>
</table>
</form>
<?php if ($OL!=""){ ?>
<script language="javascript">
	Load('<?=$fn?>');
</script>
<?php } ?>
<iframe name="HiddenFrame" style="display:none"></iframe>
</body>
</html>