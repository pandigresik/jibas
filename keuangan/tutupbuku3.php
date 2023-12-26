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
require_once('include/errorhandler.php');
require_once('include/sessioninfo.php');

if (getLevel() == 2) 
{ ?>
<script language="javascript"> 
	alert('Maaf, anda tidak berhak mengakses halaman ini!'); 
	window.history.go(-1);
</script>
<?php 	
	exit();
} // end if
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tutup Buku</title>
</head>
<body onLoad="document.getElementById('kategori').focus();">
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="images/bgtutupbuku.jpg" style="background-repeat:no-repeat">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	<td align="left" valign="top">
    
    <table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right">
		<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Tutup Buku</font>	
        </td>
  	</tr>
    <tr>
    	<td align="right"><a href="referensi.php">
      	<font size="1" color="#000000"><b>Referensi</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Tutup Buku</b></font>
        </td>
   	</tr>
    <tr>
      	<td align="left">&nbsp;</td>
    </tr>
	</table><br />
    
  	<table width="70%" align="center" border="1" cellpadding="7" cellspacing="0" style="border-color:#306">
    <tr>
    	<td align="left" width="27%" style="background-color:#306">
        <font style="font-size:20px;">Langkah 3 dari 3</font>
        </td>
        <td align="left" valign="middle" style="background-color:#306">
        <font style="font-size:11px;">Selesai</font>
        </td>
    </tr>
    <tr>
    	<td colspan="2" align="left" height="300" valign="middle" style="background-color:#F9F2FF">
    
        <table style="background-color:#DFEFFF; border-color:#006" width="80%" align="center">
        <tr>
            <td align="center" height="80" valign="middle">
            <font style="color:#003"><strong>Selesai memproses tutup buku dan membuat tahun buku baru!</strong></font>
            </td>
        </tr>
        </table>
        
        </td>
    </tr>
    </table>
            
    </td>
</tr>
</table>
   
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table> 
</body>
</html>