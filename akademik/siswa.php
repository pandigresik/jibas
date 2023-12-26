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
    <td>&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Arial" color="Gray"><strong>KESISWAAN</strong></font></td>
  </tr>
</table>
<br>
<table id="Table_01" width="633" height="463" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="11">
			<img src="images/siswa_01.jpg" width="452" height="4" alt=""></td>
		<td rowspan="12" align="center" valign="top" width="180">
            <a href="referensi/tambahandata.php?from=Kesiswaan" style="text-decoration:none;" onMouseOver="showhint('Konfigurasi Tambahan Data', this, event, '100px')">
                <img src="images/tambahandata.png" height="60" alt="" border="0"><br>
                <font style="font-family:Verdana, Geneva, sans-serif; font-size:11px; color:#000; font-weight:bold">Kolom Tambahan<br>Data Siswa</font>
            </a>
        </td>
	</tr>
	<tr>
		<td rowspan="11">
			<img src="images/siswa_02.jpg" width="24" height="458" alt=""></td>
		<td colspan="2" rowspan="2">
			<img src="images/siswa_03.jpg" width="83" height="104" alt="" style="cursor:pointer" onClick="alert ('Gunakan menu Tahun Ajaran di bagian referensi \nuntuk mendata Tahun Ajaran');"></td>
		<td colspan="8">
			<img src="images/siswa_04.jpg" width="345" height="77" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="images/siswa_05.jpg" width="47" height="156" alt=""></td>
		<td colspan="4" rowspan="2">
			<a href="siswa/siswa_main.php" onMouseOver="showhint('Pendataan Siswa', this, event, '100px')"><img src="images/siswa_06.jpg" width="114" height="135" alt="" border="0"></a></td>
		<td colspan="3" rowspan="3">
			<img src="images/siswa_07.jpg" width="184" height="156" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			<img src="images/siswa_08.jpg" width="83" height="129" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="images/siswa_09.jpg" width="114" height="21" alt=""></td>
	</tr>
	<tr>
		<td rowspan="7">
			<img src="images/siswa_10.jpg" width="18" height="225" alt=""></td>
		<td colspan="3">
			<a href="siswa/siswa_cari_main.php" onMouseOver="showhint('Pencarian Siswa', this, event, '100px')"><img src="images/siswa_11.jpg" width="146" height="71" alt="" border="0"></a></td>
		<td colspan="4" rowspan="2">
			<img src="images/siswa_12.jpg" width="104" height="79" alt=""></td>
		<td rowspan="3">
			<a href="siswa/siswa_pindah_main.php" onMouseOver="showhint('Pendataan Siswa Yang Akan Pindah Kelas', this, event, '100px')">
			<img src="images/siswa_13.jpg" width="101" height="117" alt="" style="cursor:pointer"  border="0" >
			</a>
        </td>
		<td rowspan="7">
			<img src="images/siswa_14.jpg" width="59" height="225" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="images/siswa_15.jpg" width="146" height="8" alt=""></td>
	</tr>
	<tr>
		<td colspan="4" rowspan="2">
			<!--<a href="siswa/statistik/siswa_statistik_main.php" onMouseOver="showhint('Statistik Kesiswaan', this, event, '100px')">--><a href="siswa/siswa_statistik_main.php" onMouseOver="showhint('Statistik Kesiswaan', this, event, '100px')">
            <img src="images/siswa_16.jpg" width="173" height="67" alt="" border="0"></a></td>
		<td colspan="3" rowspan="3">
			<img src="images/siswa_17.jpg" width="77" height="77" alt=""></td>
	</tr>
	<tr>
		<td rowspan="4">
			<img src="images/siswa_18.jpg" width="101" height="108" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="images/siswa_19.jpg" width="173" height="10" alt=""></td>
	</tr>
	<tr>
		<td colspan="5">
			<a href="siswa/pin_main.php"><img src="images/siswa_20.jpg" width="208" height="57" alt="" border="0"></a></td>
		<td colspan="2" rowspan="2">
			<img src="images/siswa_21.jpg" width="42" height="69" alt=""></td>
	</tr>
	<tr>
		<td colspan="5">
			<img src="images/siswa_22.jpg" width="208" height="12" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/spacer.gif" width="24" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="18" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="65" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="47" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="34" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="27" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="35" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="18" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="24" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="101" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="59" height="1" alt=""></td>
		<td></td>
	</tr>
</table>
<div style="right:5px; bottom:5px; position:absolute;" align="right">
</div>
<!-- End ImageReady Slices -->
</body>
</html>