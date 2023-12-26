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
<title>PSB</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script type="text/javascript" src="script/tooltips.js"></script>
<script type="text/javascript">
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
<!-- ImageReady Slices (PSB.psd) -->
<table width="100%" border="0">
<tr>
	<td width="53%"><p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Arial" color="Gray"><strong>PENERIMAAN SISWA BARU</strong></font></p>
    </td>
    <td width="47%"><div id="hint">&nbsp;</div></td>
</tr>
<tr>
    <td>
    
    <table id="Table_01" width="540" height="460" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="13">
			<img src="images/siswa_baru_01.gif" width="539" height="14" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="14" alt=""></td>
	</tr>
	<tr>
		<td rowspan="12">
			<img src="images/siswa_baru_02.gif" width="12" height="445" alt=""></td>
		<td>
			<a href="siswa_baru/proses.php" onMouseOver="showhint('Pendataan Proses Penerimaan Siswa Baru', this, event, '100px')"><img src="images/siswa_baru_03.gif" alt="" width="90" height="87" border="0"></a></td>
		<td colspan="6" rowspan="2">
			<img src="images/siswa_baru_04.gif" width="175" height="126" alt=""></td>
		<td>
			<a href="siswa_baru/kelompok.php" onMouseOver="showhint('Pendataan Kelompok Penerimaan Siswa Baru', this, event, '100px')"><img src="images/siswa_baru_05.gif" alt="" width="67" height="87" border="0"></a></td>
		<td colspan="4" rowspan="3">
			<img src="images/siswa_baru_06.gif" width="195" height="206" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="87" alt=""></td>
	</tr>
	<tr>
		<td rowspan="5">
			<img src="images/siswa_baru_07.gif" width="90" height="211" alt=""></td>
		<td rowspan="6">
			<img src="images/siswa_baru_08.gif" width="67" height="226" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="39" alt=""></td>
	</tr>
	<tr>
		<td rowspan="4">
			<img src="images/siswa_baru_09.gif" width="11" height="172" alt=""></td>
		<td colspan="3" rowspan="2">
			<a href="siswa_baru/calon_main.php" onMouseOver="showhint('Pendataan Calon Siswa', this, event, '100px')"><img src="images/siswa_baru_10.gif" alt="" width="134" height="119" border="0"></a></td>
		<td colspan="2" rowspan="5">
			<img src="images/siswa_baru_11.gif" width="30" height="187" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="80" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="4">
			<img src="images/siswa_baru_12.gif" width="54" height="107" alt=""></td>
		<td rowspan="2">
			<img src="images/siswa_baru_13.gif" width="132" height="66" alt="" style="cursor:pointer" onClick="alert ('Gunakan menu Tahun Ajaran di bagian referensi \nuntuk mendata Tahun Ajaran');"></td>
		<td rowspan="9">
			<img src="images/siswa_baru_14.gif" width="9" height="239" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="39" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="2"><img src="images/siswa_baru_15.gif" width="134" height="53" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="27" alt=""></td>
	</tr>
	<tr>
		<td rowspan="7">
			<img src="images/siswa_baru_16.gif" width="132" height="173" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="26" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="2">
			<a href="siswa_baru/cari_main.php" onMouseOver="showhint('Pencarian Calon Siswa', this, event, '100px')"><img src="images/siswa_baru_17.gif" alt="" width="139" height="59" border="0"></a></td>
		<td colspan="2" rowspan="3">
			<img src="images/siswa_baru_18.gif" width="96" height="72" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="15" alt=""></td>
	</tr>
	<tr>
		<td rowspan="5">
			<img src="images/siswa_baru_19.gif" width="25" height="132" alt=""></td>
		<td colspan="3" rowspan="3">
			<a href="siswa_baru/penempatan_main.php" onMouseOver="showhint('Penempatan Calon Siswa', this, event, '100px')">
			<img style="cursor:pointer" src="images/siswa_baru_20.gif" alt="" width="121" height="113" border="0">
			</a>
        </td>
		<td rowspan="5">
			<img src="images/siswa_baru_21.gif" width="5" height="132" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="44" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="images/siswa_baru_22.gif" width="139" height="13" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="13" alt=""></td>
	</tr>
	<tr>
		<td colspan="4" rowspan="2">
			<a href="siswa_baru/statistik_main.php" onMouseOver="showhint('Statistik Calon Siswa', this, event, '100px')"><img src="images/siswa_baru_23.jpg" alt="" width="160" height="64" border="0"></a></td>
		<td rowspan="3">
			<img src="images/siswa_baru_24.gif" width="75" height="75" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="56" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="2">
			<img src="images/siswa_baru_25.gif" width="121" height="19" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="8" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="images/siswa_baru_26.gif" width="160" height="11" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="11" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/spacer.gif" width="12" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="90" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="11" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="38" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="21" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="75" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="25" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="5" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="67" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="49" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="5" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="132" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="9" height="1" alt=""></td>
		<td></td>
	</tr>
</table>
</td>
	<td align="left" valign="top">
    <a href="siswa_baru/settingpsb_main.php" style="text-decoration:none" onMouseOver="showhint('Konfigurasi Pendataan PSB', this, event, '100px');">
        <img src="images/ico/settings.png" height="60" alt="" border="0"><br>
        <font style="font-family:Verdana, Geneva, sans-serif; font-size:11px; color:#000; font-weight:bold; ">Konfigurasi Pendataan PSB</font>
    </a>
    <br><br>
    <a href="siswa_baru/pincs.main.php" style="text-decoration:none" onMouseOver="showhint('PIN Calon Siswa', this, event, '100px');">
        <img src="images/pincs.jpg" height="60" alt="" border="0"><br>
        <font style="font-family:Verdana, Geneva, sans-serif; font-size:11px; color:#000; font-weight:bold; ">PIN Calon Siswa</font>
    </a>
    <br><br>
    <a href="referensi/tambahandata.php?from=Penerimaan%20Siswa%20Baru" style="text-decoration:none;" onMouseOver="showhint('Konfigurasi Tambahan Data', this, event, '100px')">
        <img src="images/tambahandata.png" height="60" alt="" border="0"><br>
        <font style="font-family:Verdana, Geneva, sans-serif; font-size:11px; color:#000; font-weight:bold">Kolom Tambahan Data Calon Siswa</font>
    </a>
  </td>
</tr>
</table>
<div style="right:5px; bottom:5px; position:absolute;" align="right">
</div>

<!-- End ImageReady Slices -->
</body>
</html>