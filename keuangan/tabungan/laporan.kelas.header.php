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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../include/sessioninfo.php');
require_once('laporan.kelas.header.func.php');

OpenDb();
ReadRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>    
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<!-- TABLE TITLE -->
<tr>
    <td rowspan="3" width="52%">
    <table width = "100%" border = "0">
	<tr>
        <td width="18%"><strong>Departemen </strong></td>
        <td>
<?php      SelectDepartemen(); ?>
        <strong>Angkatan </strong>
        </td>
        <td>
<?php      SelectAngkatan(); ?>        
        </td>
    </tr>
     <tr>
    	<td><strong>Kelas </strong></td>
        <td>
<?php      SelectTingkat(); ?>        
<?php      SelectKelas(); ?>
        <strong>Tabungan </strong>
        </td>
        <td>
<?php      SelectTabungan(); ?>        
    	</td>
    </tr>
    </table>
	</td>
 	<td width="*" rowspan="2" valign="middle">
    	<a href="#" onclick="show_pembayaran()">
            <img src="../images/view.png" border="0" height="48" width="48"
                 onmouseover="showhint('Klik untuk menampilkan data laporan tabungan per kelas!', this, event, '180px')"/>
        </a>
    </td>
	<td width="45%" colspan="3" align="right" valign="top">
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Laporan Tabungan Per Kelas</font><br />
        <a href="tabungan.php" target="_parent">
        <font size="1" color="#000000"><b>Tabungan Siswa</b></font>
        </a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Laporan Tabungan Per Kelas</b></font>
	</td>
</tr>
<tr>	
    <td align="right" valign="top">
   	</td>
</tr>
</table>
<?php CloseDb() ?>
</body>
</html>
<script language="javascript" src="laporan.kelas.header.js"></script>