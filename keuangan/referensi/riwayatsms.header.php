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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../library/departemen.php');
require_once('riwayatsms.header.func.php');

OpenDb();

if (!isset($_REQUEST['tgl']))
{
    $sql = "SELECT DAY(NOW()), MONTH(NOW()), YEAR(NOW())";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    $seltgl = $row[0];
    $selbln = $row[1];
    $selthn = $row[2];    
}
else
{
    $seltgl = $_REQUEST['tgl'];
    $selbln = $_REQUEST['bln'];
    $selthn = $_REQUEST['thn'];
}

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Riwayat SMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css" />
    <script language="javascript" src="riwayatsms.header.js"></script>
	<script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
</head>

<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus();">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td width="40%">
        
    <table width="100%" border="0">
    <tr>
    	<td align="left" width = "15%"><strong>Departemen&nbsp;</strong></td>
      	<td width="*">
<?php      ShowSelectDept(); ?>
        &nbsp;
        <strong>Pembayaran</strong>&nbsp;
        <select name='kate' id='kate' onchange="change_kate()">
            <option value='SISPAY'>Pembayaran Siswa</option>
            <option value='SISTUNG'>Tunggakan Siswa</option>
            <option value='SISTAB'>Tabungan Siswa</option>
            <option value='CSISPAY'>Pembayaran Calon Siswa</option>
            <option value='CSISTUNG'>Tunggakan Calon Siswa</option>
        </select>
    	</td>
	</tr>
    <tr>
    	<td align="left" width = "15%"><strong>Tanggal&nbsp;</strong></td>
      	<td width="*">
<?php      ShowSelectTanggal(); ?>          
    	</td>
	</tr>
	</table>
    
    </td>		
    <td width="*" valign="middle">
		<a href="#" onclick="ShowRiwayatSms()">
        <img src="../images/view.png" border="0" height="48" width="48"/>
		</a>    
    </td>
    <td width="30%" align="right" valign="top">
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Riwayat Pengiriman SMS</font><br />
        <a href="../referensi.php" target="_parent">
        <font size="1" color="#000000"><b>Referensi</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Riwayat Pengiriman SMS</b></font>
    </td>  
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>