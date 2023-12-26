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
include('cek.php');
require_once('include/sessioninfo.php');
?>
<html>
<head>
<title>Untitled-1</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script type="text/javascript" src="script/tooltips.js"></script>
<script type="text/javascript">
function get_fresh(){
	document.location.reload();
}
function change_theme(theme){
	parent.topcenter.location.href="topcenter.php?theme="+theme;
	parent.topleft.location.href="topleft.php?theme="+theme;
	parent.topright.location.href="topright.php?theme="+theme;
	parent.midleft.location.href="midleft.php?theme="+theme;
	get_fresh();
	parent.midright.location.href="midright.php?theme="+theme;
	parent.bottomleft.location.href="bottomleft.php?theme="+theme;
	parent.bottomcenter.location.href="bottomcenter.php?theme="+theme;
	parent.bottomright.location.href="bottomright.php?theme="+theme;
}
</script>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- ImageReady Slices (Untitled-1) -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana" color="Gray"><strong>REFERENSI</strong></font></td>
  </tr>
</table>
<br>
<table id="Table_01" width="491" height="434" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3" rowspan="6">
			<img src="images/referensi_01.jpg" width="75" height="229" alt=""></td>
		<td rowspan="2">
			<a href="referensi/pegawai.php" onMouseOver="showhint('Pendataan Pegawai', this, event, '100px')"><img src="images/referensi_02.jpg" width="67" height="83" alt="" border="0"></a></td>
		<td colspan="11">
			<img src="images/referensi_03.jpg" width="348" height="35" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="35" alt=""></td>
	</tr>
	<tr>
		<td colspan="7" rowspan="2">
			<img src="images/referensi_04.jpg" width="250" height="57" alt=""></td>
		<td colspan="2" rowspan="3"><a href="referensi/identitas.php" onMouseOver="showhint('Identitas Sekolah', this, event, '100px')"><img src="images/referensi_05.jpg" width="56" height="91" alt="" border="0"></a></td>
		<td colspan="2" rowspan="5">
			<img src="images/referensi_06.jpg" width="42" height="194" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="48" alt=""></td>
	</tr>
	<tr>
		<td rowspan="8">
			<img src="images/referensi_07.jpg" width="67" height="350" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="9" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="3">
			<img src="images/referensi_08.jpg" width="59" height="137" alt=""></td>
		<td colspan="3" rowspan="2">
			<a href="referensi/departemen.php" onMouseOver="showhint('Pendataan Departemen', this, event, '100px')"><img src="images/referensi_09.jpg" width="78" height="71" alt="" border="0"></a></td>
		<td colspan="2" rowspan="3">
			<img src="images/referensi_10.jpg" width="113" height="137" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="34" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			<img src="images/referensi_11.jpg" width="56" height="103" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="37" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="images/referensi_12.jpg" width="78" height="66" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="66" alt=""></td>
	</tr>
	<tr>
		<td rowspan="4">
			<img src="images/referensi_13.jpg" width="10" height="204" alt=""></td>
		<td>
			<a href="referensi/angkatan.php" onMouseOver="showhint('Pendataan Angkatan', this, event, '100px')"><img src="images/referensi_14.jpg" width="53" height="76" alt="" border="0"></a></td>
		<td rowspan="4">
			<img src="images/referensi_15.jpg" width="12" height="204" alt=""></td>
		<td rowspan="4">
			<img src="images/referensi_16.jpg" width="4" height="204" alt=""></td>
		<td colspan="2">
			<a href="referensi/tingkat.php" onMouseOver="showhint('Pendataan Tingkat', this, event, '100px')"><img src="images/referensi_17.jpg" width="59" height="76" alt="" border="0"></a></td>
		<td colspan="2" rowspan="2">
			<img src="images/referensi_18.jpg" width="74" height="130" alt=""></td>
		<td>
			<a href="referensi/tahunajaran.php" onMouseOver="showhint('Pendataan Tahun Ajaran', this, event, '100px')"><img src="images/referensi_19.jpg" width="71" height="76" alt="" border="0"></a></td>
		<td colspan="2" rowspan="4">
			<img src="images/referensi_20.jpg" width="54" height="204" alt=""></td>
		<td colspan="2">
			<a href="referensi/semester.php" onMouseOver="showhint('Pendataan Semester', this, event, '100px')"><img src="images/referensi_21.jpg" width="61" height="76" alt="" border="0"></a></td>
		<td rowspan="4">
			<img src="images/referensi_22.jpg" width="25" height="204" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="76" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="images/referensi_23.jpg" width="53" height="128" alt=""></td>
		<td colspan="2">
			<img src="images/referensi_24.jpg" width="59" height="54" alt=""></td>
		<td rowspan="3">
			<img src="images/referensi_25.jpg" width="71" height="128" alt=""></td>
		<td colspan="2" rowspan="3">
			<img src="images/referensi_26.jpg" width="61" height="128" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="54" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<a href="referensi/kelas.php" onMouseOver="showhint('Pendataan Kelas', this, event, '100px')"><img src="images/referensi_27.jpg" width="68" height="71" alt="" border="0"></a></td>
		<td rowspan="2">
			<img src="images/referensi_28.jpg" width="65" height="74" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="71" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="images/referensi_29.jpg" width="68" height="3" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="3" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/spacer.gif" width="10" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="53" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="12" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="67" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="4" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="55" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="4" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="9" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="65" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="71" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="42" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="12" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="44" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="17" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="25" height="1" alt=""></td>
		<td></td>
	</tr>
</table>
<div style="right:5px; bottom:5px; position:absolute;" align="right">
</div>
<!-- End ImageReady Slices -->
</body>
</html>