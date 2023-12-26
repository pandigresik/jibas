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
require_once('inc/theme.php');
require_once('inc/sessioninfo.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript">
function logout(){
	if (confirm('Anda yakin akan keluar dari Jibas SimTaka?')) {
		document.location.href = "logout.php";
	}
}
</script>
<style type="text/css">
<!--
.style2 {
	font-size: 11px;
	font-family: Verdana;
	color:#FFFFFF;
	text-decoration:none;
	font-weight:bold
}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#7c7f4f" style="overflow:hidden">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr height="50">
    <td width="10" background="<?=getThemeDir()?>bgmain_01.jpg"></td>
    <td background="<?=getThemeDir()?>bgmain_02.jpg" width="20"></td>
    <td width="*" align="left"  background="<?=getThemeDir()?>bgmain_03.jpg" valign="bottom">
      <table width="550" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center">
            	<a href="ref/referensi.php" target="content" class="style2"><img src="img/ico/ref.png" border="0"><br>
            Referensi</a>            </td>
            <td align="center">
            	<a href="pus/pustaka.php" target="content" class="style2"><img src="img/ico/pustaka.png" border="0"><br>Pustaka</a>            </td>
            <td align="center">
            	<a href="pjm/peminjaman.php" target="content" class="style2"><img src="img/ico/pinjam.png" border="0"><br>Peminjaman</a>            </td>
            <td align="center">
            	<a href="kbl/pengembalian.php" target="content" class="style2"><img src="img/ico/kembali.png" border="0"><br>Pengembalian</a>            </td>
            <td align="center">
            	<a href="akt/aktivitas.php" target="content" class="style2"><img src="img/ico/aktivitas.png" border="0"><br>Aktivitas</a>            </td>    
            <td align="center">
            	<a href="atr/pengaturan.php" target="content" class="style2"><img src="img/ico/atur.png" border="0"><br>Pengaturan</a>            </td>
            <td align="center">
            	<a href="#" target="_self" class="style2" onClick="logout()"><img src="img/ico/logout.png" border="0"><br>Keluar</a>            </td>
          </tr>
      </table>	</td>
    <td width="277" background="<?=getThemeDir()?>bgmain_03.jpg" align="right"><img src="img/thm/grn/bgmain_04.jpg"></td>
    <td width="17" background="<?=getThemeDir()?>bgmain_05.jpg"></td>
    <td width="13" background="<?=getThemeDir()?>bgmain_01.jpg"></td>
  </tr>
  <tr height="10">
    <td width="10" background="<?=getThemeDir()?>bgmain_01.jpg"></td>
    <td background="<?=getThemeDir()?>bgmain_08.jpg" width="20"></td>
    <td colspan="2" background="<?=getThemeDir()?>bgmain_09.jpg" width="*"></td>
    <td background="<?=getThemeDir()?>bgmain_10.jpg" width="17"></td>
    <td width="13" background="<?=getThemeDir()?>bgmain_01.jpg"></td>
  </tr>
</table>
</body>
</html>