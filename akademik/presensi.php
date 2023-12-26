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
$page='ph';
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script type="text/javascript" src="script/tooltips.js"></script>
<script src="script/SpryTabbedPanels.js" type="text/javascript"></script>
<script type="text/javascript">
function over(id){
	var actmenu = document.getElementById('actmenu').value;
	if (actmenu==id)
		return false;
		
	if (actmenu=='pp')
		document.getElementById('img').src='images/ph_over.png';
	else 
		document.getElementById('img').src='images/pp_over.png';
}
function out(id){
	var actmenu = document.getElementById('actmenu').value;
	if (actmenu==id)
		return false;
	
	if (actmenu=='pp')
		document.getElementById('img').src='images/pp.png';
	else
		document.getElementById('img').src='images/ph.png';
}

function show(id)
{
	if (id=='pp')
	{
		document.getElementById('actmenu').value='pp';
		document.getElementById('img').src='images/pp2.png';
		document.getElementById('slice_pp').style.display='';
		document.getElementById('slice_ph').style.display='none';
		document.getElementById('slice_pk').style.display='none';
	}
	else if (id == "ph") 
	{
		document.getElementById('actmenu').value='ph';
		document.getElementById('img').src='images/ph2.png';
		document.getElementById('slice_pp').style.display='none';
		document.getElementById('slice_ph').style.display='';
		document.getElementById('slice_pk').style.display='none';
	}
	else if (id == "pk")
	{
		document.getElementById('actmenu').value='pk';
		document.getElementById('img').src='images/pk2.png';
		document.getElementById('slice_pp').style.display='none';
		document.getElementById('slice_ph').style.display='none';
		document.getElementById('slice_pk').style.display='';
	}
}
</script>
<link href="script/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="show('<?=$page?>')"  >

<input type="hidden" id="actmenu" name="actmenu" value="<?=$page?>" />  
<div id="menu" align="left" style="background-image:url(images/bgtab.png)">
	<img src="images/pp2.png" border="0" usemap="#Map" id="img" />
	<map name="Map" id="Map">
	    <area shape="rect" coords="18,13,98,45" href="#" onclick="show('ph')" />
	    <area shape="rect" coords="100,13,180,45" href="#" onclick="show('pp')" />
		<area shape="rect" coords="182,13,262,45" href="#" onclick="show('pk')" />
	</map>
</div>

<div id="content" align="left">
<table cellpadding="0" cellspacing="0" id="slice_pp">
<tr>
    <td id="mid">
		<p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Arial" color="Gray"><strong>PRESENSI PELAJARAN</strong></font></p>
	</td>
</tr>
<tr>
    <td>
		
    <table id="Table_01" width="568" height="448" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td rowspan="2">
            <a href="presensi/formpresensi_pelajaran.php" onMouseOver="showhint('Cetak Form Presensi Pelajaran', this, event, '100px')"><img src="images/pres_pelajaran_01.jpg" alt="" width="112" height="116" border="0"></a>
		</td>
        <td>
            <img src="images/pres_pelajaran_02.jpg" width="30" height="87" alt="">
		</td>
        <td>
            <img src="images/pres_pelajaran_03.jpg" width="130" height="87" alt="">
		</td>
        <td>
            <img src="images/pres_pelajaran_04.jpg" width="53" height="87" alt="">
		</td>
        <td>
            <img src="images/pres_pelajaran_05.jpg" width="79" height="87" alt="">
		</td>
        <td>
            <img src="images/pres_pelajaran_06.jpg" width="269" height="87" alt="">
		</td>
		<td>
            <img src="images/spacer.gif" width="1" height="87" alt="">
		</td>
    </tr>
    <tr>
        <td rowspan="3">
            <img src="images/pres_pelajaran_07.jpg" width="30" height="126" alt="">
		</td>
        <td rowspan="3">
            <a href="presensi/presensi_main.php" onMouseOver="showhint('Pengisian Data Presensi Setiap Pelajaran', this, event, '100px')"><img src="images/pres_pelajaran_08.jpg" alt="" width="130" height="126" border="0"></a>
		</td>
        <td rowspan="3">
            <img src="images/pres_pelajaran_09.jpg" width="53" height="126" alt="">
		</td>
        <td rowspan="2">
            <img src="images/pres_pelajaran_10.jpg" width="79" height="87" alt="">
		</td>
        <td rowspan="2">
            <img src="images/pres_pelajaran_11.jpg" width="269" height="87" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="29" alt="">
		</td>
    </tr>
    <tr>
        <td rowspan="2">
            <img src="images/pres_pelajaran_12.jpg" width="112" height="97" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="58" alt="">
		</td>
    </tr>
    <tr>
        <td rowspan="2" valign="top">
            <img src="images/pres_pelajaran_13.jpg" width="79" height="72" alt="">
		</td>
        <td rowspan="2">
			<a href="presensi/lap_siswa_main.php" onMouseOver="showhint('Laporan Presensi Setiap Siswa', this, event, '100px')">Laporan Presensi Siswa</a><br>
			<a href="presensi/lap_kelas_main.php" onMouseOver="showhint('Laporan Presensi Siswa Setiap Kelas', this, event, '100px')">Laporan Presensi Siswa per Kelas</a><br>
			<a href="presensi/lap_pengajar_main.php" onMouseOver="showhint('Laporan Presensi Pengajar', this, event, '100px')">Laporan Presensi Pengajar</a><br>
			<a href="presensi/lap_absen_main.php" onMouseOver="showhint('Laporan Presensi Siswa Yang Tidak Hadir', this, event, '100px')" >Laporan Data Siswa yang Tidak Hadir</a><br>
			<a href="presensi/lap_refleksi_main.php" onMouseOver="showhint('Laporan Refleksi Mengajar', this, event, '100px')">Laporan Refleksi Mengajar</a>
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="39" alt="">
		</td>
    </tr>
    <tr>
        <td colspan="4">
            <img src="images/pres_pelajaran_15.jpg" width="325" height="33" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="33" alt="">
		</td>
    </tr>
    <tr>
        <td colspan="6">&nbsp;</td>
        <td>
            <img src="images/spacer.gif" width="1" height="11" alt="">
		</td>
    </tr>
    <tr>
        <td colspan="4">
            <img src="images/pres_pelajaran_17.jpg" width="325" height="83" alt="">
		</td>
        <td valign="top">
            <img src="images/pres_pelajaran_18.jpg" width="79" height="83" alt="">
		</td>
        <td valign="middle">
            <a href="presensi/statistik_siswa_main.php" onMouseOver="showhint('Statistik Kehadiran Setiap Siswa', this, event, '100px')">Statistik Kehadiran Siswa</a><br>
            <a href="presensi/statistik_kelas_main.php" onMouseOver="showhint('Statistik Kehadiran Setiap Kelas', this, event, '100px')">Statistik Kehadiran Setiap Kelas</a>
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="83" alt="">
		</td>
    </tr>
    <tr>
        <td colspan="6">
            <img src="images/pres_pelajaran_20.jpg" width="567" height="108" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="108" alt="">
		</td>
    </tr>
    </table>
    
	</td>
</tr>
</table>
        
<table width="100%" border="0" id="slice_ph" style="display:none">
<tr>
    <td id="up">
		<p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Arial" color="Gray"><strong>PRESENSI HARIAN</strong></font></p>
	</td>
</tr>
<tr>
    <td>
        
	<table id="Table_01" width="563" height="364" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td rowspan="2">
			<a href="presensi/formpresensi_harian.php" onMouseOver="showhint('Cetak Form Presensi Harian', this, event, '100px')">
            <img src="images/harian_01.jpg" alt="" width="80" height="106" border="0"></a>
		</td>
        <td colspan="2">
            <img src="images/harian_02.jpg" width="68" height="83" alt="">
		</td>
        <td>
            <img src="images/harian_03.jpg" width="44" height="83" alt="">
		</td>
        <td>
            <img src="images/harian_04.jpg" width="43" height="83" alt="">
		</td>
        <td>
            <img src="images/harian_05.jpg" width="60" height="83" alt="">
		</td>
        <td>
            <img src="images/harian_06.jpg" width="78" height="83" alt="">
		</td>
        <td>
            <img src="images/harian_07.jpg" width="325" height="83" alt="">
		</td>
        <td>
			<img src="images/spacer.gif" width="1" height="83" alt="">
		</td>
    </tr>
    <tr>
        <td rowspan="3">
            <img src="images/harian_08.jpg" width="54" height="129" alt="">
		</td>
        <td colspan="3" rowspan="3">
            <a href="presensi/input_presensi_main.php" onMouseOver="showhint('Pengisian Presensi Harian Setiap Pelajaran', this, event, '100px')"><img src="images/harian_09.jpg" alt="" width="101" height="129" border="0"></a>
		</td>
        <td rowspan="3">
            <img src="images/harian_10.jpg" width="60" height="129" alt="">
		</td>
        <td rowspan="2">
            <img src="images/harian_11.jpg" width="78" height="79" alt="">
		</td>
        <td rowspan="2">
            <img src="images/harian_12.jpg" width="189" height="79" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="23" alt="">
		</td>
    </tr>
    <tr>
        <td rowspan="2">
            <img src="images/harian_13.jpg" width="80" height="106" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="56" alt="">
		</td>
    </tr>
	<tr>
        <td rowspan="2" valign="top">
            <img src="images/harian_14.jpg" width="78" height="88" alt="">
		</td>
        <td rowspan="2" valign="middle">
			<a href="presensi/lap_hariansiswa_main.php" onMouseOver="showhint('Pengisian Presensi Harian Setiap Siswa', this, event, '100px')">Laporan Presensi Harian Siswa</a><br>
            <a href="presensi/lap_hariankelas_main.php" onMouseOver="showhint('Laporan Presensi Harian Setiap Kelas', this, event, '100px')">Laporan Presensi Harian per Kelas</a><br>
            <a href="presensi/lap_harianabsen_main.php" onMouseOver="showhint('Laporan Presensi Harian Siswa Yang Tidak Hadir', this, event, '100px')">Laporan Harian Data Siswa yang Tidak Hadir</a>
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="50" alt="">
		</td>
    </tr>
    <tr>
        <td colspan="6">
            <img src="images/harian_16.jpg" width="295" height="38" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="38" alt="">
		</td>
    </tr>
	<tr>
		<td colspan="8">
            <img src="images/harian_17.jpg" width="562" height="7" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="7" alt="">
		</td>
    </tr>
    <tr>
        <td colspan="6">
            <img src="images/harian_18.jpg" width="295" height="85" alt="">
		</td>
        <td valign="top">
            <img src="images/harian_19.jpg" width="78" height="85" alt="">
		</td>
        <td valign="middle">
			<a href="presensi/statistik_hariansiswa_main.php" onMouseOver="showhint('Statistik Kehadiran Siswa', this, event, '100px')">Statistik Kehadiran Siswa</a><br>
            <a href="presensi/statistik_hariankelas_main.php" onMouseOver="showhint('Statistik Kehadiran Siswa Setiap Kelas', this, event, '100px')">Statistik Kehadiran per Kelas</a>
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="85" alt="">
		</td>
    </tr>
    <tr>
        <td colspan="8">
			<img src="images/harian_21.jpg" width="562" height="21" alt="" />
		</td>
        <td>
            <img src="images/spacer.gif" width="1" height="21" alt="">
		</td>
    </tr>
    <tr>
        <td>
            <img src="images/spacer.gif" width="80" height="1" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="54" height="1" alt="">
		</td>
        <td>
			<img src="images/spacer.gif" width="14" height="1" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="44" height="1" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="43" height="1" alt="">
		</td>
		<td>
            <img src="images/spacer.gif" width="60" height="1" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="78" height="1" alt="">
		</td>
        <td>
            <img src="images/spacer.gif" width="189" height="1" alt="">
		</td>
        <td></td>
    </tr>
    </table>
    
	</td>
    </tr>
</table>

<table width="100%" border="0" id="slice_pk" style="display:none">
<tr>
    <td id="up">
		<p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Arial" color="Gray"><strong>PRESENSI KEGIATAN</strong></font></p>
	</td>
</tr>
<tr>
	<td id="up">
		
	<table id="Table_01" width="546" height="237" border="0" cellpadding="0" cellspacing="0">
	<tr height="115">
		<td width="172">
			<a href='http://www.jibas.net/content/sptfgr/sptfgr.php' target='_blank'>
				<img src="images/pkegiatan_01.jpg" border='0'>
			</a>
		</td>
		<td width="68"><img src="images/pkegiatan_02.jpg"></td>
		<td width="306">&nbsp;</td>
	</tr>
	<tr height="42">
		<td>&nbsp;</td>
		<td><img src="images/pkegiatan_05.jpg" width="68"></td>
		<td>&nbsp;</td>
	</tr>
	<tr height="80">
		<td>&nbsp;</td>
		<td><img src="images/pkegiatan_08.jpg"></td>
		<td align='left' valign='middle'>
		
			<a href='presensi/presensikeg.siswa2.php'>Presensi Kegiatan Siswa</a><br>
			<a href='presensi/presensikeg.rekapsiswa.php'>Rekapitulasi Presensi Kegiatan Siswa</a><br>
			<a href='presensi/presensikeg.guru.php'>Presensi Kegiatan Guru</a><br>
			<a href='presensi/presensikeg.rekapguru.php'>Rekapitulasi Presensi Kegiatan Guru</a>
		
		</td>
	</tr>
	</table>
		
		
	</td>
</tr>
</table>

</div>
</body>
</html>