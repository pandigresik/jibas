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
require_once("../../include/config.php");
require_once("../../include/common.php");
require_once("../../include/sessioninfo.php");
require_once("../../include/db_functions.php");
require_once("../../include/sessionchecker.php");
require_once("../../include/fileutil.php");

$iddir = (int)$_REQUEST['iddir'];
if (($iddir == 0))
	exit();

OpenDb();

$op = $_REQUEST['op'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../../style/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../../script/tools.js"></script>
<script language="javascript" src="../../script/tables.js"></script>
<script language="javascript">
function tambahfile(iddir){
	//alert (' '+fullpath+' '+dirfullpath);
	newWindow('addfile.php?iddir='+iddir,'InputFile','556','189','resizable=1,scrollbars=0,status=0,toolbar=0');
}
function openfile(addr){
	//alert ('Address '+addr);
	newWindow(addr,'OpenFile','556','189','resizable=1,scrollbars=0,status=0,toolbar=0');
}
function get_fresh(){
	document.location.href="files.php?iddir=<?=$iddir?>";
	//parent.dirs.get_fresh();
}
function cek_all() {
	var x;
	var jum = document.getElementById("numfile").value;
	var ceked = document.getElementById("cek").checked;
	for (x=1;x<=jum;x++){
		if (ceked==true){
			document.getElementById("cekfile"+x).checked=true;
		} else {
			document.getElementById("cekfile"+x).checked=false;
		}
	}
}
function cek() {
	var x;
	var jum = document.getElementById("numfile").value;
	var jumfilechecked = parseInt(document.getElementById("jumchecked").value);
	for (x=1;x<=jum;x++){
		var ceked = document.getElementById("cekfile"+x).checked;
		if (ceked==true){
			document.getElementById("jumchecked").value=jumfilechecked+1;
		}
	}
	if (jumfilechecked>0){
		return true;
	} else {
		alert ('Minimal harus ada satu file terpilih yang akan dihapus !');
		return false;
	}

}
</script>
</head>
<body>
	<?= $FILESHARE_UPLOAD_ADDR ?>
<form name="file_list">
<input type="hidden" name="iddir" id="iddir" value="<?=$iddir?>">
<br /><br />
<?php
$sql = "SELECT dirfullpath FROM jbsvcr.dirshare WHERE idroot=0";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$rootname = $row[0];

$sql = "SELECT dirfullpath, idguru FROM jbsvcr.dirshare WHERE replid='$iddir'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$idguru = $row[1];
$dfullpath = $row[0];
$fullpath = str_replace($rootname, "", (string) $dfullpath);
?>
<font size="3" color="#000033">f i l e s</font>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td width="46%" align="left">Content of: <font size="2" color="#990000"><em><strong>&nbsp;<?="(root)/".$fullpath; ?></strong></em></font></td>
   <td width="54%" align="right">&nbsp;</td>
</tr>
<tr>
  <td align="left">&nbsp;</td>
  <td align="right"></td>
</tr>
</table>
<br />
<table border="1" style="border-collapse:collapse" cellpadding="2" cellspacing="2" width="100%" class="tab" id="table" bordercolor="#000000">
<tr height="30">
	<td width="2%" align="center" class="header">No</td>
   <td width="60%" align="center" class="header">Name</td>
   <td width="12%" align="center" class="header">Size</td>
   <td width="22%" align="center" class="header">Date</td>
</tr>
<?php
$sql = "SELECT replid,filename, filesize, date_format(filetime, '%d-%b-%Y %h:%i:%s') as filetime FROM jbsvcr.fileshare WHERE iddir='$iddir' ORDER BY filename";
$result = QueryDb($sql);
$numfile = @mysqli_num_rows($result);
$cnt = 1;
if ($numfile>0)
{
	while ($row = mysqli_fetch_array($result))
	{
		$file_addr = "$FILESHARE_UPLOAD_DIR/fileshare/$fullpath/{$row['filename']}";
	?>
		<tr height="25">
			<td align="center"><?=$cnt ?></td>
			<td align="left">
				<a title='<?= "$FILESHARE_ADDR/fileshare/$fullpath/{$row['filename']}" ?>'
				   href='<?= "$FILESHARE_ADDR/fileshare/$fullpath/{$row['filename']}" ?>' target="_blank">
					<?= $row['filename'] ?>
				</a>
			</td>
			<td align="right">
	<?php 		$filesize = $row['filesize'];
			echo fileSizeInByte($filesize);	?>
			</td>
			<td align="center">
				<?=$row['filetime'] ?>
			</td>
		</tr>
	<?php
		$cnt++;
	} // while
}
else
{
?>
<tr><td colspan="5" align="center"><div class="divNotif">Tidak ada file dalam folder ini</div></td></tr>
<?php
} // if
CloseDb();
?>
<input type="hidden" name="numfile" id="numfile" value="<?=$numfile?>">
<input type="hidden" name="listdel" id="listdel">
<input type="hidden" name="numdel" id="numdel">
<input type="hidden" name="jumchecked" id="jumchecked">
</table>
</form>
<script language='JavaScript'>
	    Tables('table', 1, 0);
</script>
</body>
</html>