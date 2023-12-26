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
require_once('../include/errorhandler.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/sessioninfo.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawian</title>
<link rel="stylesheet" href="../style/style.css" />
<script language="javascript" src="../script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
    <td align="center"><h3>PRESENSI PEGAWAI</h3></td>
</tr>
<tr>
    <td>

    <table id="Table_01" width="423" height="450" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><img src="../images/presensi_01.jpg" width="89" height="118" alt=""></td>
		<td>
            <a href="inputpresensi.main.php">
            <img src="../images/presensi_02.jpg" width="104" height="118" alt="Input Presensi Pegawai" border="0">
            </a>
        </td>
		<td><img src="../images/presensi_03.jpg" width="86" height="118" alt=""></td>
		<td>
            <a href="http://www.jibas.net/content/sptfgr/sptfgr.php" target="_blank">
            <img src="../images/presensi_04.jpg" width="144" height="118" alt="JIBAS SPT Fingerprint" border="0">
            </a>
        </td>
	</tr>
	<tr>
		<td><img src="../images/presensi_05.jpg" width="89" height="49" alt=""></td>
		<td><img src="../images/presensi_06.jpg" width="104" height="49" alt=""></td>
		<td><img src="../images/presensi_07.jpg" width="86" height="49" alt=""></td>
		<td><img src="../images/presensi_08.jpg" width="144" height="49" alt=""></td>
	</tr>
	<tr>
		<td>
            <a href="lembur.main.php">
            <img src="../images/presensi_09.jpg" width="89" height="118" alt="Presensi Lembur Pegawai" border="0">
            </a>
        </td>
		<td><img src="../images/presensi_10.jpg" width="104" height="118" alt=""></td>
		<td><img src="../images/presensi_11.jpg" width="86" height="118" alt=""></td>
		<td><img src="../images/presensi_12.jpg" width="144" height="118" alt=""></td>
	</tr>
	<tr>
		<td><img src="../images/presensi_13.jpg" width="89" height="41" alt=""></td>
		<td><img src="../images/presensi_14.jpg" width="104" height="41" alt=""></td>
		<td><img src="../images/presensi_15.jpg" width="86" height="41" alt=""></td>
		<td><img src="../images/presensi_16.jpg" width="144" height="41" alt=""></td>
	</tr>
	<tr>
		<td><img src="../images/presensi_17.jpg" width="89" height="124" alt=""></td>
		<td>
            <a href="rekappresensi.main.php">
            <img src="../images/presensi_18.jpg" width="104" height="124" alt="" border="0">
            </a>
        </td>
		<td><img src="../images/presensi_19.jpg" width="86" height="124" alt=""></td>
		<td>
            <a href="rekapall.main.php">
            <img src="../images/presensi_20.jpg" width="144" height="124" alt="" border="0">
            </a>
        </td>
	</tr>
</table>
    
    
    </td>
</tr>
</table>
</body>
</html>