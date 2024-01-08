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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
require_once('../cek.php');

OpenDb();
function delete($file) 
{
	if (file_exists($file)) 
 	{
   		chmod($file, 0777);
   		if (is_dir($file)) 
		{
			$handle = opendir($file); 
			while($filename = readdir($handle)) 
			{
				if ($filename != "." && $filename != "..") 
				{
					delete($file."/".$filename);
       			}
     		}
			closedir($handle);
			rmdir($file);
		} 
   		else 
   		{
			unlink($file);
   		}
 	}
}

$departemen = $_REQUEST['departemen']; 
if (isset($_REQUEST['simpan']))
{
	$tmp_path = realpath(".") . "/../../temp";
	$filename = "$tmp_path/ad-logo-tmp.jpg";
	
	$fh = fopen($filename,"r");
	$foto_data = addslashes(fread($fh, filesize($filename)));
	fclose($fh);
	
	$sql = "SELECT * FROM jbsumum.identitas WHERE departemen='$departemen'";
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0)
	{
		$sql="UPDATE jbsumum.identitas SET foto='$foto_data' WHERE departemen='$departemen'";
		QueryDb($sql);
	} else {
		$sql="INSERT INTO jbsumum.identitas SET foto='$foto_data', departemen='$departemen'";
		QueryDb($sql);
	} 
	?>
    <script language="javascript">
		parent.opener.getfresh();
		window.close();
    </script>
  <?php
}
$sql = "SELECT replid FROM jbsumum.identitas WHERE departemen='$departemen'";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$replid = $row[0];

$gbrbaru = 0;
if (isset($_REQUEST['gbrbaru']))
	$gbrbaru = 1;
?>
<?php
if ($gbrbaru==1){
	$ol = "reffoto();";
} else {
	$ol = "getfoto();";
}
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Input Logo Sekolah]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" type="text/javascript">

function simpanlogo(){
	var departemen = document.getElementById("departemen").value;
	var ftbaru = document.getElementById("ftbaru").value;
	var replid = document.getElementById("replid").value;
	document.location.href="logo2.php?simpan=1&ftbaru="+ftbaru+"&replid="+replid+"&departemen="+departemen;
}

function ganti() {	
	var file = document.getElementById("foto").value;
	if (file.length>0){
		var ext = "";
		var i = 0;
		var string4split='.';

		z = file.explode(string4split);
		ext = z[z.length-1];
		
		if (ext!='JPG' && ext!='jpg' && ext!='Jpg' && ext!='JPg' && ext!='JPEG' && ext!='jpeg'){
			alert ('Format Gambar harus ber-extensi jpg atau JPG !');
			document.getElementById("foto").value='';
			document.form1.foto.focus();
    		document.form1.foto.select();
			return false;
		} 
	}
	document.getElementById("main").submit();
}
function showFoto(x) {
	document.getElementById("fotoInfo").innerHTML = x;
}

function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}
function reffoto(){
	show_wait('fotoInfo');	
	var departemen = document.getElementById("departemen").value;
	sendRequestText("refreshfoto2.php", showFoto, "&gbrbaru=1&departemen"+departemen);
}
function getfoto(){
	show_wait('fotoInfo');	
	var departemen = document.getElementById("departemen").value;
	sendRequestText("refreshfoto2.php", showFoto, "&departemen"+departemen);
}
function wait_foto() {
	show_wait("fotoInfo"); //lihat div id 
}
/**/
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="<?=$ol?>">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
		.: Logo Sekolah :.
		</div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="215">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" valign="top">
    <!-- CONTENT GOES HERE //--->    
		<form name="main" id="main" method="post" enctype="multipart/form-data" action="getfoto2.php">
			<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>"/>
			<table width="100%" border="0" cellspacing="0">
			  <tr>
				<td align="center">
					<div id="fotoInfo">
					<!--<img src="../library/gambar.php?replid=<?=$replid?>&table=jbsumum.identitas" border="0"/>-->
					</div>
				</td>
			  </tr>
			  <tr>
				<td align="center">
					<input type="hidden" name="replid" id="replid" value="<?=$replid?>"/>
					<input type="hidden" name="ftbaru" id="ftbaru" value="<?=$gbrbaru?>"/>
					<input type="file" name="foto" id="foto" onChange="ganti()" /></td>
			  </tr>
			  <tr>
				<td align="center" >
					<input name="Simpan" type="button" class="but" onClick="simpanlogo()" value="Simpan" />&nbsp;
					<input name="tutup" type="button" class="but" onClick="window.close()" value="Tutup" /></td>
			  </tr>
			</table>
		</form>
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
<?php
CloseDb();
?>
