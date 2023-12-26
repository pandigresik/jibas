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
$middle="0";
if (isset($_REQUEST['flag'])){
	$middle="1";
	} else {
	$middle="0";
	}
?>
<html>
<head>
<title>Penilaian</title>
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
function scrollMiddle() {
 	  var myWidth = 0, myHeight = 0;
	  
	  if( typeof( window.innerWidth ) == 'number' ) {
    	//Non-IE
	    myWidth = window.innerWidth;
	    myHeight = window.innerHeight;
	  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
	    //IE 6+ in 'standards compliant mode'
	    myWidth = document.documentElement.clientWidth;
	    myHeight = document.documentElement.clientHeight;
	  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
	    //IE 4 compatible
	    myWidth = document.body.clientWidth;
	    myHeight = document.body.clientHeight;
	  }
	  
	  myHeight = myHeight / 0.5;
	  window.scrollTo(myWidth, myHeight);
   }
   
   function scrollTop() {
	  window.scrollTo(0, 0);
   }
</script>
<link href="style/style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" <?php if ($middle=="1") { ?>onload="scrollMiddle()" <?php } else { ?> onLoad="scrollTop()"  <?php } ?>>
<table width="100%" border="0">
  <tr>
    <td><p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Arial" color="Gray"><strong>PENILAIAN</strong></font></p></td>
  </tr>
  <tr>
    <td>
    <?php if (SI_USER_LEVEL()!=0) { ?>
	<table id="Table_01" width="601" height="601" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="12">
			<img src="images/penilaian_01.jpg" width="600" height="39" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="39" alt=""></td>
	</tr>
	<tr>
		<td rowspan="16">
			<img src="images/penilaian_02.jpg" width="29" height="561" alt=""></td>
		<td rowspan="6">
			<a href="penilaian/formpenilaian.php" onMouseOver="showhint('Cetak Form-form Penilaian', this, event, '100px')"><img src="images/penilaian_03.jpg" alt="" width="66" height="99" border="0"></a></td>
  <td colspan="10">
			<img src="images/penilaian_04.jpg" width="505" height="6" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="6" alt=""></td>
	</tr>
	<tr>
		<td colspan="4" rowspan="6">
			<img src="images/penilaian_05.jpg" width="249" height="102" alt=""></td>
		<td colspan="5">
			<a href="penilaian/ujian_rpp_kelas.php" onMouseOver="showhint('Daftar Nilai RPP Setiap Kelas', this, event, '100px')"><img src="images/penilaian_06.jpg" alt="" width="110" height="39" border="0"></a></td>
		<td rowspan="15" valign="top" align="right">
			<table border="0" cellpadding="0" width="95%">
			<tr>
				<td width="10%">
				<img src="images/table_small.png" border="0" width="40">	
				</td>
				<td>
				<a href="penilaian/lap_legger.php">Laporan Legger Nilai</a>
				</td>
			</tr>
            <tr>
                <td width="10%">
                    <img src="images/table_small.png" border="0" width="40">
                </td>
                <td>
                    <a href="penilaian/legger.rapor.php">Legger Nilai Rapor</a>
                </td>
            </tr>
            </table>
		</td>
		<td>
			<img src="images/spacer.gif" width="1" height="39" alt=""></td>
	</tr>
	<tr>
		<td colspan="5">
			<img src="images/penilaian_08.jpg" width="110" height="6" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="6" alt=""></td>
	</tr>
	<tr>
		<td colspan="5">
			<a href="penilaian/ujian_rpp_siswa.php" onMouseOver="showhint('Daftar Nilai RPP Setiap Siswa', this, event, '100px')"><img src="images/penilaian_09.jpg" alt="" width="110" height="39" border="0"></a></td>
  <td>
			<img src="images/spacer.gif" width="1" height="39" alt=""></td>
	</tr>
	<tr>
		<td colspan="5">
			<img src="images/penilaian_10.jpg" width="110" height="8" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="8" alt=""></td>
	</tr>
	<tr>
		<td colspan="4" rowspan="3">
			<a href="penilaian/lap_pelajaran_main.php" onMouseOver="showhint('Laporan Nilai Pelajaran Setiap Siswa', this, event, '100px')"><img src="images/penilaian_11.jpg" alt="" width="107" height="38" border="0"></a></td>
  <td rowspan="6">
			<img src="images/penilaian_12.jpg" width="3" height="145" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="1" alt=""></td>
	</tr>
	<tr>
		<td rowspan="10">
			<img src="images/penilaian_13.jpg" width="66" height="462" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="9" alt=""></td>
	</tr>
	<tr>
		<td rowspan="9">
			<img src="images/penilaian_14.jpg" width="7" height="453" alt=""></td>
		<td rowspan="3">
			<a href="penilaian/lihat_nilai_pelajaran.php" onMouseOver="showhint('Pendataan Nilai Pelajaran Setiap Siswa', this, event, '100px')"><img src="images/penilaian_15.jpg" alt="" width="113" height="107" border="0"></a></td>
  <td colspan="2" rowspan="2">
			<img src="images/penilaian_16.jpg" width="129" height="84" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="28" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<a href="penilaian/rataus.main.php"><img src="images/penilaian_17.jpg" alt="" width="107" height="56" border="0"></a></td>
		<td>
			<img src="images/spacer.gif" width="1" height="56" alt=""></td>
	</tr>
	<tr>
		<td rowspan="7">
			<img src="images/penilaian_18.jpg" width="13" height="369" alt=""></td>
		<td colspan="2" rowspan="3">
			<a href="penilaian/lihat_penentuan.php" onMouseOver="showhint('Pendataan Nilai Rapor Setiap Siswa', this, event, '100px')"><img src="images/penilaian_19.jpg" alt="" width="117" height="90" border="0"></a></td>
  <td colspan="3" rowspan="2">
			<img src="images/penilaian_20.jpg" width="106" height="51" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="23" alt=""></td>
	</tr>
	<tr>
		<td rowspan="6">
			<img src="images/penilaian_21.jpg" width="113" height="346" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="28" alt=""></td>
	</tr>
	<tr>
		<td rowspan="5">
			<img src="images/penilaian_22.jpg" width="17" height="318" alt=""></td>
		<td colspan="3" rowspan="2">
			<a href="penilaian/komentar_main.php" onMouseOver="showhint('Pendataan Komentar Rapor Setiap Siswa', this, event, '100px')"><img src="images/penilaian_23.jpg" alt="" width="92" height="99" border="0"></a></td>
  <td>
			<img src="images/spacer.gif" width="1" height="39" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="4">
			<img src="images/penilaian_24.jpg" width="117" height="279" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="60" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="images/penilaian_25.jpg" width="92" height="32" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="32" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/penilaian_26.jpg" width="22" height="187" alt=""></td>
		<td>
			<a href="penilaian/lap_rapor_main.php" onMouseOver="showhint('Laporan Akhir Hasil Belajar Setiap Siswa', this, event, '100px')"><img src="images/penilaian_27.jpg" alt="" width="67" height="116" border="0"></a></td>
  <td rowspan="2">
			<img src="images/penilaian_28.jpg" width="3" height="187" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="116" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/penilaian_29.jpg" width="67" height="71" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="71" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/spacer.gif" width="29" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="66" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="7" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="113" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="13" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="116" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="17" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="22" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="67" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="3" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="146" height="1" alt=""></td>
		<td></td>
	</tr>
</table>
<?php } else { ?>
<table width="256" border="0" cellpadding="5" cellspacing="20">
  <tr>
    <td width="47%" align="center"><a href="penilaian/lap_pelajaran_main.php"><img src="images/penilaian_11a.jpg" width="67" height="88" border="0"><br>
      Laporan&nbsp;Nilai Setiap&nbsp;Siswa</a></td>
    <td width="53%" align="center"><a href="penilaian/lap_rapor_main.php"><img src="images/penilaian_27a.jpg" width="67" height="88" border="0"><br>
      Nilai&nbsp;Rapor Siswa</a></td>
  </tr>
</table>

<?php } ?>
    </td>
  </tr>
</table>

<!-- ImageReady Slices (Penilaian.psd) -->

<!-- End ImageReady Slices -->
</body>
</html>