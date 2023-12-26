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
require_once('include/mainconfig.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Education Community <?=$G_VERSION?></title>
<link href="script/vtip.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/jibas2015.ico" />
<link rel="stylesheet" href="script/bgstretcher.css" />
<script language="javascript" src="script/jquery.min.js"></script>
<script language="javascript" src="interaksi.js"></script>
<script language="javascript" src="script/bgstretcher.js"></script>
<script language="javascript" src="script/jquery.elevatezoom.js"></script>
</head>

<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0">
<div style="position:relative; z-index:2">
	
<center>
<br>		
<font style="font-family:Tahoma; font-size:20px; color:#fff; ">
	ALUR INTERAKSI SISTEM INFORMASI SEKOLAH JIBAS
</font><br>
<font style="font-family:Tahoma; font-size:10px; color:#fff; ">
<?= $G_BUILDDATE ?>	
</font><br>
</center>

<iframe id="dvMain" style='position:absolute; height:500px; width: 800px; border-width: 0px;'
		scrolling='no' src='interaksi.image.php' frameBorder='0'  allowtransparency='true'>	
</iframe>

<div id="dvCopy" style="color:#fff; width:300px; font-size:11px; font-family:Tahoma; position:absolute; background-image:url(images/bgdiv_black.png);">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
<td align="right" valign="middle">
	versi <?=$G_VERSION." - ".$G_BUILDDATE?><br />
	<a href="http://www.jibas.net" target="_blank" style="color:#fff; text-decoration:none;">
	&nbsp;&nbsp;<strong>JIBAS</strong>: Jaringan Informasi Bersama Antar Sekolah</a><br />
	&nbsp;&nbsp;Hak cipta &copy; 2009 <a href="http://www.indonesiamembaca.net" target="_blank" style="color:#00f6f3; text-decoration:underline;">Yayasan Indonesia Membaca</a><br>
</td>
<td>
	<a href="http://www.jibas.net" target="_blank">
	<img src="images/jibas.png" border="0" title="JIBAS">
	</a>	
</td>	
</tr>	
</table>
</div>

<div id="dvPartner" style="color:#fff; width:120px; font-size:11px; font-family:Tahoma; position:absolute; background-image:url(images/bgdiv_black.png);">

</div>

</div>	
</body>
</html>