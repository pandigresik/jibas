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
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('../../include/sessionchecker.php');

$source="";
if (isset($_REQUEST["source"]))
	$source = $_REQUEST["source"];

$pagesource = $_REQUEST["pagesource"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tambah Foto</title>
<script src="../../script/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../../script/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
function chg(y)
{
	var x = document.getElementById("file" + y).value;
	if (x.length > 0)
	{
		document.getElementById("nama" + y).style.visibility = "visible";
		document.getElementById("keterangan" + y).style.visibility = "visible";
		document.getElementById("jumlah").value = "1";
		var result = '';
		var string4split='.';

		z = x.explode(string4split);
		result = z[z.length-1];
		document.getElementById("ext" + y).value = result;
		document.getElementById("tr" + y).style.background = "#FFFFFF" ;
		document.getElementById("nama" + y).focus();
	}
}

function validate()
{
	var x=document.getElementById("jumlah").value;
	var y=1;
	if (x.length==0)
	{
		alert ('Minimal harus ada 1 gambar yang akan di Upload!');
		return false;
	}
	else
	{
		while (y<=3)
		{
			var ext=document.getElementById("ext"+y).value;
			var file=document.getElementById("file"+y).value;
			if (file.length>0)
			{
				ext = ext.toLowerCase();
				if (ext!='jpg' && ext!='jpeg' && ext!='gif' && ext!='bmp' && ext!='png')
				{
					alert ('Format Gambar harus ber-extensi jpg, gif, bmp atau png!');
					document.getElementById("file"+y).value='';
					document.getElementById("tr"+y).style.background = "#FF8080" ;
					return false;
				} 
			}
			y++;
		}
	}
	return true;
}
</script>
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {color: #000000}
.style5 {color: #FFFFFF}
-->
</style>
</head>
<body topmargin="0" leftmargin="0" style="background-image:url(../../images/bgpop.jpg); background-repeat:repeat-x">
<form name="foto" id="foto" action="simpanfoto.php" method="POST" onSubmit="return validate()" enctype="multipart/form-data">
<input name="pagesource" id="pagesource" type="hidden" size="20" value="<?=$pagesource?>" />
<input name="source" id="source" type="hidden" size="20" value="<?=$source?>" />
<table width="100%" border="0" cellspacing="0" >
  <tr>
    <td colspan="3" scope="row"><span class="style3"><strong><font size="2">Foto Baru :</font></strong><br />
        <br />
    </span></td>
  </tr>
  <tr>
    <th width="21%" height="25" align="center" bgcolor="#CCCC99" class="headerlong" scope="row"><div align="left">File Gambar</div></th>
    <th width="39%" height="25" align="center" bgcolor="#CCCC99" class="headerlong" scope="row">Nama</th>
    <th width="40%" align="center" bgcolor="#CCCC99" class="headerlong" scope="row"><div align="left">Keterangan</div></th>
  </tr>
<?php 	for ($i = 1; $i <= 3; $i++)
	{  ?>
	<tr id="tr<?= $i ?>">
		<th scope="row" align="center"><div align="left">
			<input name="file<?=$i?>" id="file<?=$i?>" type="file" size="20" onChange="chg('<?=$i?>')" />
		</div></th>
		<th align="center" scope="row">
		<div align="center">
			<input type="text" id="nama<?=$i?>" name="nama<?=$i?>" style="visibility:hidden; " />
		</div></th>
		<th align="center" scope="row"><input type="text" id="keterangan<?=$i?>" name="keterangan<?=$i?>" style="visibility:hidden; " />
		<input type="hidden" id="ext<?=$i?>" name="ext<?=$i?>"/>
		</th>
	</tr>
<?php 	} ?>
  <tr>
    <th colspan="3" align="center" scope="row">* Format Gambar harus jpg, gif, bmp atau png</th>
  </tr>
  <tr>
    <th colspan="3" align="center" scope="row">
    <input title="Simpan foto !" type="submit" class="but" name="simpan" id="simpan" value="Simpan" />&nbsp;&nbsp;
    <input type="hidden" id="jumlah" name="jumlah"/>
    <input title="Tutup !" type="button" class="but" onClick="window.close();" name="tutup" id="tutup" value="Tutup" /></th>
  </tr>
</table>
</form>

</body>
</html>
<script language="javascript">
var sprytextfielda = new Spry.Widget.ValidationTextField("nama1");
var sprytextfieldb = new Spry.Widget.ValidationTextField("nama2");
var sprytextfieldc = new Spry.Widget.ValidationTextField("nama3");
var sprytextfield1 = new Spry.Widget.ValidationTextField("keterangan1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("keterangan2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("keterangan3");
</script>