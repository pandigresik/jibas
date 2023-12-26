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
include('sessionchecker.php');
require_once('include/sessioninfo.php');
$inputcatatan="<a href='infosiswa/catatansiswamain.php'><img src='images/catatankejadian_07.jpg' alt='' width='92' height='106' border='0'></a>";
$img="";
if (SI_USER_LEVEL()==0){
	$inputcatatan="<img src='images/catatankejadian_07a.jpg' alt='' width='92' height='106' border='0'>";
	$img="a";
}
?>
<html>
<head>
<title>Untitled-3</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0">
  <tr>
	<td id="up"><p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Arial" color="Gray"><strong>CATATAN KEJADIAN SISWA</strong></font></p></td>
  </tr>
  <tr>
    <td>
<!-- ImageReady Slices (Untitled-3) -->
<table id="Table_01" width="215" height="250" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="5">
			<img src="images/catatankejadian_01.jpg" width="214" height="24" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="24" alt=""></td>
	</tr>
	<tr>
		<td rowspan="4">
			<img src="images/catatankejadian_02.jpg" width="11" height="226" alt=""></td>
		<td>
			<a href="infosiswa/catatankategori.php"><img src="images/catatankejadian_03<?=$img?>.jpg" alt="" width="80" height="83" border="0"></a></td>
<td colspan="3" rowspan="2"><img src="images/catatankejadian_04<?=$img?>.jpg" width="123" height="97" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="83" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="images/catatankejadian_05.jpg" width="80" height="143" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="14" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/catatankejadian_06.jpg" width="16" height="129" alt=""></td>
		<td>
			<?=$inputcatatan?></td>
<td rowspan="2">
			<img src="images/catatankejadian_08<?=$img?>.jpg" width="15" height="129" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="106" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/catatankejadian_09.jpg" width="92" height="23" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="23" alt=""></td>
	</tr>
</table>
<!-- End ImageReady Slices -->
    </td>
  </tr>
</table>

</body>
</html>