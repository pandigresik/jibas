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
require_once("../include/sessionchecker.php");
require_once('../include/config.php');
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table id="Table_01" width="800" height="416" border="0" cellpadding="0" cellspacing="0" align="center">
<tr height="10">
	<td colspan="5" align="center">
    	<h3>KEPEGAWAIAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
    </td>
</tr>
<tr>
	<td><img src="../images/bkpegawai_01.jpg" width="219" height="149" alt=""></td>
	<td><a href="pegawaiinput.php" title="Input Pegawai"><img src="../images/bkpegawai_02.jpg" width="156" height="149" alt="" border="0"></a></td>
	<td><img src="../images/bkpegawai_03.jpg" width="105" height="149" alt=""></td>
	<td><img src="../images/bkpegawai_04.jpg" width="62" height="149" alt=""></td>
	<td><img src="../images/bkpegawai_05.jpg" width="257" height="149" alt=""></td>
	<td rowspan="3"><img src="../images/bkpegawai_06.jpg" width="1" height="416" alt=""></td>
</tr>
<tr>
	<td><a href="daftar.php" title="Mencari, mengubah dan menentukan jadwal kepegawaian"><img src="../images/bkpegawai_07.jpg" width="219" height="130" alt="" border="0"></a></td>
	<td><img src="../images/bkpegawai_08.jpg" width="156" height="130" alt=""></td>
	<td><a href="statistik.php" title="Statistik Kepegawaian"><img src="../images/bkpegawai_09.jpg" width="105" height="130" alt="" border="0"></a></td>
	<td><img src="../images/bkpegawai_10.jpg" width="62" height="130" alt=""></td>
	<td><img src="../images/bkpegawai_11.jpg" width="257" height="130" alt=""></td>
</tr>
<tr>
	<td><a href="jadwal.php" title="Menentukan jadwal kepegawaian"><img src="../images/bkpegawai_12.jpg" width="219" height="137" alt="" border="0"></a><br />
    <a href="dagenda.php">Daftar Agenda Kepegawaian</a>
    </td>
	<td><img src="../images/bkpegawai_13.jpg" width="156" height="137" alt=""></td>
	<td><img src="../images/bkpegawai_14.jpg" width="105" height="137" alt=""></td>
	<td colspan="2" valign="top">
		<br><br>
		<a href="struktur.php">Struktur Organisasi</a><br>
        <a href="dukpangkat.php">Daftar Urut Kepangkatan</a><br>
    </td>
</tr>
</table>
</body>
</html>