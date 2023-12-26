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
<title>Mutasi</title>
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
<!-- ImageReady Slices (Mutasi.psd) -->
<table width="100%" border="0">
  <tr>
    <td><p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Arial" color="Gray"><strong>MUTASI</strong></font></p></td>
  </tr>
  <tr>
    <td>
    <table id="Table_01" width="319" height="376" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="9">
			<img src="images/mutasi_01.jpg" width="318" height="11" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="11" alt=""></td>
	</tr>
	<tr>
		<td colspan="4" rowspan="2">
			<img src="images/mutasi_02.jpg" width="121" height="129" alt=""></td>
		<td>
			<a href="mutasi/jenis_mutasi_siswa.php" onMouseOver="showhint('Pendataan Jenis Mutasi Siswa', this, event, '100px')"><img src="images/mutasi_03.jpg" alt="" width="68" height="95" border="0"></a></td>
  <td colspan="4" rowspan="2">
			<img src="images/mutasi_04.jpg" width="129" height="129" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="95" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/mutasi_05.jpg" width="68" height="34" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="34" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="3">
			<img src="images/mutasi_06.jpg" width="114" height="133" alt=""></td>
		<td colspan="3">
			<a href="mutasi/mutasi_siswa.php" onMouseOver="showhint('Pendataan Siswa Yang Akan Dimutasi', this, event, '100px')"><img src="images/mutasi_07.jpg" alt="" width="84" height="95" border="0"></a></td>
  <td colspan="3" rowspan="2">
			<img src="images/mutasi_08.jpg" width="120" height="131" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="95" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="4">
			<img src="images/mutasi_09.jpg" width="84" height="140" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="36" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="images/mutasi_10.jpg" width="36" height="104" alt=""></td>
		<td rowspan="2">
			<a href="mutasi/daftar_mutasi.php" onMouseOver="showhint('Daftar Siswa Yang Sudah Dimutasi', this, event, '100px')"><img src="images/mutasi_11.jpg" alt="" width="69" height="96" border="0"></a></td>
  <td rowspan="3">
			<img src="images/mutasi_12.jpg" width="15" height="104" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="2" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/mutasi_13.jpg" width="19" height="102" alt=""></td>
		<td>
			<a href="mutasi/statistik_mutasi_siswa.php" onMouseOver="showhint('Statistik Mutasi Siswa', this, event, '100px')"><img src="images/mutasi_14.jpg" alt="" width="67" height="94" border="0"></a></td>
  <td rowspan="2">
			<img src="images/mutasi_15.jpg" width="28" height="102" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="94" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/mutasi_16.jpg" width="67" height="8" alt=""></td>
		<td>
			<img src="images/mutasi_17.jpg" width="69" height="8" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="8" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/spacer.gif" width="19" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="67" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="28" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="7" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="68" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="9" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="36" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="69" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="15" height="1" alt=""></td>
		<td></td>
	</tr>
</table>
    </td>
  </tr>
</table>


<div style="right:5px; bottom:5px; position:absolute;" align="right">
<script type="text/javascript" language="JavaScript1.2">
<!--
stm_bm(["menu6126",650,"","blank.gif",0,"","",0,0,250,0,500,1,0,0,"","",0,0,1,2,"default","hand",""],this);
stm_bp("p0",[1,4,0,0,0,2,0,0,100,"",-2,"",-2,90,0,0,"#000000","transparent","",3,0,0,"#000000"]);
stm_ai("p0i0",[0,"Themes","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,1,1,"#CC9999",1,"#CC6666",1,"script/button1.gif","script/button2.gif",3,3,0,0,"#000000","#000000","#CCCCCC","#E0FF7D","bold 8pt Arial","bold 8pt Arial",0,0],146,21);
stm_bpx("p1","p0",[1,2,2,0,0,0,25,0,90,"progid:DXImageTransform.Microsoft.Wipe(GradientSize=1.0,wipeStyle=0,motion=forward,enabled=0,Duration=0.83)",6,"stEffect(\"slip\")",-2,27]);
stm_ai("p1i0",[6,10,"#000000","",-1,-1,0]);
stm_aix("p1i1","p0i0",[0,"Green","","",-1,-1,0,"javascript:change_theme('1')","_self","","","","",25,0,0,"","",0,0,0,0,1,"#3D3D3D",1,"#66CC33",1,"script/button1.gif","script/button2.gif",3,3,0,0,"#CCCC00","#CCCC00"],146,21);
stm_aix("p1i2","p1i1",[0,"Pink","","",-1,-1,0,"javascript:change_theme('2')"],146,21);
stm_aix("p1i3","p1i1",[0,"Casual","","",-1,-1,0,"javascript:change_theme('3')"],146,21);
stm_aix("p1i4","p1i1",[0,"Apple","","",-1,-1,0,"javascript:change_theme('4')"],146,21);
stm_aix("p1i5","p1i1",[0,"Vista","","",-1,-1,0,"javascript:change_theme('5')"],146,21);
stm_aix("p1i6","p1i1",[0,"Coffee","","",-1,-1,0,"javascript:change_theme('6')"],146,21);
stm_aix("p1i7","p1i1",[0,"Wood","","",-1,-1,0,"javascript:change_theme('7')"],146,21);
stm_aix("p1i8","p1i1",[0,"Gold","","",-1,-1,0,"javascript:change_theme('8')"],146,21);
stm_aix("p1i9","p1i1",[0,"Granite","","",-1,-1,0,"javascript:change_theme('9')"],146,21);
stm_aix("p1i10","p1i0",[]);
stm_ep();
stm_ep();
stm_sc(1,["transparent","transparent","","",3,3,0,0,"#FFFFF7","#000000","script/up_disabled.gif","script/up_enabled.gif",7,9,0,"script/down_disabled.gif","script/down_enabled.gif",7,9,0,0,200]);
stm_em();
//-->
</script>
</div>
<!-- End ImageReady Slices -->
</body>
</html>