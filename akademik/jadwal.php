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
$page='j';
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
?>
<html>
<head>
<title>Untitled-2</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script type="text/javascript" src="script/tooltips.js"></script>
<script type="text/javascript">
function over(id){
	var actmenu = document.getElementById('actmenu').value;
	if (actmenu==id)
		return false;
		
	if (actmenu=='j')
		document.getElementById('img').src='images/k_over.png';
	else 
		document.getElementById('img').src='images/j_over.png';
}
function out(id){
	var actmenu = document.getElementById('actmenu').value;
	if (actmenu==id)
		return false;
	
	if (actmenu=='j')
		document.getElementById('img').src='images/j.png';
	else
		document.getElementById('img').src='images/k.png';
}
function show(id){
	if (id=='j'){
		document.getElementById('actmenu').value='j';
		document.getElementById('img').src='images/j.png';
		document.getElementById('slice_j').style.display='';
		document.getElementById('slice_k').style.display='none';
	} else {
		document.getElementById('actmenu').value='k';
		document.getElementById('img').src='images/k.png';
		document.getElementById('slice_k').style.display='';
		document.getElementById('slice_j').style.display='none';
	}	
}
</script>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="show('<?=$page?>')">
<input type="hidden" id="actmenu" name="actmenu" value="<?=$page?>" />  
<div id="menu" align="left" style="background-image:url(images/bgtab.png)"><img src="images/j.png" border="0" usemap="#Map" id="img" />
  <map name="Map" id="Map">
    <area shape="rect" coords="18,13,98,45" href="#" onMouseOver="over('j')" onMouseOut="out('j')" onClick="show('j')" />
    <area shape="rect" coords="101,14,180,46" href="#" onMouseOver="over('k')" onMouseOut="out('k')" onClick="show('k')" />
  </map>
</div>

<div id="content">
    <table width="62%" border="0" id="slice_j">
      <tr>
        <td><p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana" color="Gray"><strong>JADWAL</strong></font></p></td>
      </tr>
      <tr>
        <td width="436">
        <table id="Table_01" width="428" height="308" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="4">
                <img src="images/jadwal_01.jpg" width="149" height="91" alt=""></td>
            <td rowspan="2">
                <a href="jadwal/jadwal_guru_main.php" onMouseOver="showhint('Penyusunan Jadwal Setiap Guru', this, event, '100px')"><img src="images/jadwal_02.jpg" alt="" width="93" height="116" border="0"></a></td>
            <td colspan="3">
                <img src="images/jadwal_03.jpg" width="185" height="91" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="1" height="91" alt=""></td>
        </tr>
        <tr>
            <td rowspan="4">
                <img src="images/jadwal_04.jpg" width="14" height="216" alt=""></td>
            <td rowspan="2">
                <a href="jadwal/definisi_jam.php" onMouseOver="showhint('Pendefinisian Jam Belajar', this, event, '100px')"><img src="images/jadwal_05.jpg" alt="" width="66" height="90" border="0"></a></td>
            <td colspan="2" rowspan="2">
                <img src="images/jadwal_06.jpg" width="69" height="90" alt=""></td>
            <td rowspan="4">
                <img src="images/jadwal_07.jpg" width="76" height="216" alt=""></td>
            <td rowspan="3">
                <a href="jadwal/rekap_jadwal_main.php" onMouseOver="showhint('Rekapitulasi Jadwal', this, event, '100px')"><img src="images/jadwal_08.jpg" alt="" width="97" height="100" border="0"></a></td>
            <td rowspan="4">
                <img src="images/jadwal_09.jpg" width="12" height="216" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="1" height="25" alt=""></td>
        </tr>
        <tr>
            <td>
                <img src="images/jadwal_10.jpg" width="93" height="65" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="1" height="65" alt=""></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2">
                <img src="images/jadwal_11.jpg" width="125" height="126" alt=""></td>
            <td colspan="2" rowspan="2">
                <a href="jadwal/jadwal_kelas_main.php" onMouseOver="showhint('Penyusunan Jadwal Setiap Kelas', this, event, '100px')"><img src="images/jadwal_12.jpg" alt="" width="103" height="126" border="0"></a></td>
            <td>
                <img src="images/spacer.gif" width="1" height="10" alt=""></td>
        </tr>
        <tr>
            <td>
                <img src="images/jadwal_13.jpg" width="97" height="116" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="1" height="116" alt=""></td>
        </tr>
        <tr>
            <td>
                <img src="images/spacer.gif" width="14" height="1" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="66" height="1" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="59" height="1" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="10" height="1" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="93" height="1" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="76" height="1" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="97" height="1" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="12" height="1" alt=""></td>
            <td></td>
        </tr>
    </table>
        </td>
      </tr>
    </table>
      
    <!-- ----------------------------------------------------------------------------------------------------------------------- -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="slice_k">
        <tr>
        <td><p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana" color="Gray"><strong>KALENDER AKADEMIK</strong></font></p></td>
      </tr>
      <tr>
        <td>
        <table id="Table_01" width="305" height="242" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="5">
                <img src="images/kalender_01.jpg" width="304" height="13" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="1" height="13" alt=""></td>
        </tr>
        <tr>
            <td rowspan="4">
                <img src="images/kalender_02.jpg" width="15" height="229" alt=""></td>
            <td>
                <img src="images/kalender_03.jpg" width="72" height="77" alt="" style="cursor:pointer;" onClick="alert ('Untuk mendata Tahun Ajaran, \nSilakan masuk ke menu Tahun Ajaran di bagian Referensi');"></td>
            <td colspan="3" rowspan="2">
                <img src="images/kalender_04.jpg" width="217" height="89" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="1" height="77" alt=""></td>
        </tr>
        <tr>
            <td rowspan="3">
                <img src="images/kalender_05.jpg" width="72" height="152" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="1" height="12" alt=""></td>
        </tr>
        <tr>
            <td rowspan="2">
                <img src="images/kalender_06.jpg" width="72" height="140" alt=""></td>
            <td>
                <a href="jadwal/kalender_main.php" onMouseOver="showhint('Pendataan Kalender Akademik', this, event, '100px')"><img src="images/kalender_07.jpg" alt="" width="126" height="129" border="0"></a></td>
            <td rowspan="2">
                <img src="images/kalender_08.jpg" width="19" height="140" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="1" height="129" alt=""></td>
        </tr>
        <tr>
            <td>
                <img src="images/kalender_09.jpg" width="126" height="11" alt=""></td>
            <td>
                <img src="images/spacer.gif" width="1" height="11" alt=""></td>
        </tr>
    </table>
        </td>
      </tr>
    </table>
</div>
</body>
</html>