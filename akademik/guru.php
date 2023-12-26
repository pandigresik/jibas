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

$page='p';
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
?>
<html>
<head>
<title>pelajaran</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script type="text/javascript" src="script/tooltips.js"></script>
<script type="text/javascript">
function over(id){
	var actmenu = document.getElementById('actmenu').value;
	if (actmenu==id)
		return false;
		
	if (actmenu=='g')
		document.getElementById('img').src='images/p_over.png';
	else 
		document.getElementById('img').src='images/g_over.png';
}
function out(id){
	var actmenu = document.getElementById('actmenu').value;
	if (actmenu==id)
		return false;
	
	if (actmenu=='g')
		document.getElementById('img').src='images/g.png';
	else
		document.getElementById('img').src='images/p.png';
}
function show(id){
	if (id=='g'){
		document.getElementById('actmenu').value='g';
		document.getElementById('img').src='images/g.png';
		document.getElementById('slice_g').style.display='';
		document.getElementById('slice_p').style.display='none';
	} else {
		document.getElementById('actmenu').value='p';
		document.getElementById('img').src='images/p.png';
		document.getElementById('slice_p').style.display='';
		document.getElementById('slice_g').style.display='none';
	}	
}
</script>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="show('<?=$page?>')">
<!-- ImageReady Slices (pelajaran.psd) -->
<input type="hidden" id="actmenu" name="actmenu" value="<?=$page?>" />  
<div id="menu" align="left" style="background-image:url(images/bgtab.png)"><img src="images/p.png" border="0" usemap="#Map" id="img" />
  <map name="Map" id="Map">
    <area shape="rect" coords="18,13,98,45" href="#" onMouseOver="over('p')" onMouseOut="out('p')" onClick="show('p')" />
    <area shape="rect" coords="101,14,180,46" href="#" onMouseOver="over('g')" onMouseOut="out('g')" onClick="show('g')" />
  </map>
</div>

<div id="content">
<table width="100%" border="0" align="center" id="slice_p" style="display:none">
  <tr>
    <td width="35%"><p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana" color="Gray"><strong>PELAJARAN</strong></font></p></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left">
    
    <table id="Table_01" width="501" height="480" border="0" cellpadding="0" cellspacing="0" >
	<tr>
		<td colspan="10">
			<img src="images/pelajaran_01.jpg" width="500" height="8" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="8" alt=""></td>
	</tr>
	<tr>
		<td rowspan="8">
			<img src="images/pelajaran_02.jpg" width="26" height="471" alt=""></td>
		<td colspan="2" rowspan="2">
			<a href="guru/pelajaran.php" onMouseOver="showhint('Pendataan Pelajaran Wajib', this, event, '100px')"><img src="images/pelajaran_03.jpg" alt="" width="116" height="126" border="0"></a></td>
		<td colspan="7">
			<img src="images/pelajaran_04.jpg" width="358" height="86" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="86" alt=""></td>
	</tr>
	<tr>
		<td colspan="5" rowspan="2">
			<img src="images/pelajaran_05.jpg" width="166" height="90" alt=""></td>
		<td rowspan="2">
			<a href = "guru/rpp_main.php" onMouseOver="showhint('Pendataan Rencana Program Pembelajaran', this, event, '100px')"><img src="images/pelajaran_06.jpg" alt="" width="99" height="90" border="0"></a></td>
		<td rowspan="7">
			<img src="images/pelajaran_07.jpg" width="93" height="385" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="40" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="4">
			<img src="images/pelajaran_08.jpg" width="116" height="200" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="50" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="images/pelajaran_09.jpg" width="23" height="150" alt=""></td>
		<td colspan="3"><a href="guru/jenis_pengujian.php" onMouseOver="showhint('Pendataan Jenis Pengujian', this, event, '100px')"><img src="images/pelajaran_10.jpg" alt="" width="107" height="84" border="0"></a></td>
		<td colspan="2" rowspan="2">
			<img src="images/pelajaran_11.jpg" width="135" height="144" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="84" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="images/pelajaran_12.jpg" width="107" height="60" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="60" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="images/pelajaran_13.jpg" width="99" height="6" alt=""></td>
		<td colspan="3" rowspan="2">
			<a href="guru/perhitungan_rapor.php" onMouseOver="showhint('Pendataan Aturan Perhitungan Nilai Rapor', this, event, '100px')"><img src="images/pelajaran_14.jpg" alt="" width="143" height="98" border="0"></a></td>
		<td>
			<img src="images/spacer.gif" width="1" height="6" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/pelajaran_15.jpg" width="26" height="145" alt=""></td>
		<td colspan="3">
			<a href="guru/aturannilai_main.php" onMouseOver="showhint('Pendataan Aturan Grading Nilai', this, event, '100px')"><img src="images/pelajaran_16.jpg" alt="" width="133" height="92" border="0"></a></td>
		<td rowspan="2">
			<img src="images/pelajaran_17.jpg" width="79" height="145" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="92" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="images/pelajaran_18.jpg" width="133" height="53" alt=""></td>
		<td colspan="3">
			<img src="images/pelajaran_19.jpg" width="143" height="53" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="53" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/spacer.gif" width="26" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="26" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="90" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="23" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="20" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="79" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="8" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="36" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="99" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="93" height="1" alt=""></td>
		<td></td>
	</tr>
</table>
    </td>
    <td align="left" valign="top">
    <div align="center" style="width: 140px;">
    <a href="guru/aspeknilai.php" style="text-decoration:none;" onMouseOver="showhint('Aspek Penilaian', this, event, '100px')">
        <img src="images/ico/aspek.png" height="60" alt="" border="0"><br>
        <font style="font-family:Verdana, Geneva, sans-serif; font-size:11px; color:#000; font-weight:bold">Aspek Penilaian</font>
    </a>
    </div>
    <br><br>
    <div align="center" style="width: 140px;">
    <a href="guru/kelompokpelajaran.php" style="text-decoration:none;" onMouseOver="showhint('Kelompok Pelajaran', this, event, '100px')">
        <img src="images/ico/kelompok.png" height="60" alt="" border="0"><br>
        <font style="font-family:Verdana, Geneva, sans-serif; font-size:11px; color:#000; font-weight:bold">Kelompok Pelajaran</font>
    </a>
    </div>
    </td>
  </tr>
</table>  

 <!------------------------------------------------------------------------------------------------------------------------->
<table width="100%" border="0" align="center" id="slice_g">
<tr>
    <td><p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana" color="Gray"><strong>GURU</strong></font></p></td>
  </tr>
  <tr>
    <td>
    <table id="Table_01" width="501" height="350" border="0" cellpadding="0" cellspacing="0" >
	<tr>
		<td colspan="7">
			<img src="images/guru_01.jpg" width="500" height="13" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="13" alt=""></td>
	</tr>
	<tr>
		<td rowspan="7">
			<img src="images/guru_02.jpg" width="61" height="336" alt=""></td>
		<td colspan="2">
			<a href="guru/statusguru.php" onMouseOver="showhint('Pendataan Status Guru', this, event, '100px')"><img src="images/guru_03.jpg" alt="" width="67" height="97" border="0"></a></td>
		<td colspan="4">
			<img src="images/guru_04.jpg" width="372" height="97" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="97" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="images/guru_05.jpg" width="240" height="51" alt=""></td>
		<td rowspan="3">
			<a href="guru/guru_main.php" onMouseOver="showhint('Pendataan Guru', this, event, '100px')"><img src="images/guru_06.jpg" alt="" width="111" height="143" border="0"></a></td>
		<td rowspan="6">
			<img src="images/guru_07.jpg" width="88" height="239" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="51" alt=""></td>
	</tr>
	<tr>
		<td rowspan="5">
			<img src="images/guru_08.jpg" width="11" height="188" alt=""></td>
		<td>
			<a href="#" onClick="alert ('Gunakan Pendataan Pelajaran di menu Pelajaran untuk mendata pelajaran');" onMouseOver="showhint('Pendataan Pelajaran', this, event, '100px')"><img src="images/guru_09.jpg" alt="" width="56" height="63" border="0"></a></td>
		<td colspan="2" rowspan="3">
			<img src="images/guru_10.jpg" width="173" height="105" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="63" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/guru_11.jpg" width="56" height="42" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="29" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="images/guru_12.jpg" width="111" height="96" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="13" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="images/guru_13.jpg" alt="" width="71" height="74" border="0" style="cursor:pointer" onClick="alert ('Gunakan menu Pegawai di bagian referensi untuk mendata pegawai');"></td>
		<td rowspan="2">
			<img src="images/guru_14.jpg" width="158" height="83" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="74" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="images/guru_15.jpg" width="71" height="9" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="9" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/spacer.gif" width="61" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="11" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="56" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="15" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="158" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="111" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="88" height="1" alt=""></td>
		<td></td>
	</tr>
</table>
    </td>
  </tr>
</table>
 
</body>
</html>