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
require_once('include/sessioninfo.php'); 
require_once('cek.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function ganti() {
	newWindow('user/user_ganti.php','GantiPasswordUser','500','280','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
<style type="text/css">
<!--
.style2 {
	font-weight: bold;
	font-size: 16px;
}
-->
</style>
</head>

<body leftmargin="0" topmargin="0">
<table border="0" cellpadding="0" cellspacing="0" width="70%" align="left">
<tr><td valign="top" align="left">
<p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<span class="style2"><font face="Verdana" color="Gray">PENGATURAN</font></span></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    	<?php if (SI_USER_LEVEL() != "2") { ?>
     	<table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><a href="user/user.php"><img src="images/user_group.png" border="0" height="80" /></a></td>
            <td><a href="user/user.php"><strong>Daftar Pengguna</strong></a></td>
          </tr>
        </table>
        <?php } else { ?>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><a href="#" onClick="alert ('Maaf, Anda tidak berhak mengakses halaman ini !');"><img src="images/user_group.png" border="0" height="80" /></a></td>
            <td><a href="#" onClick="alert ('Maaf, Anda tidak berhak mengakses halaman ini !');"><strong>Daftar Pengguna</strong></a></td>
          </tr>
        </table>
        <?php } ?>
    </td>
    <td>
    	<table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><a href="JavaScript:ganti()"><img src="images/lock.png" border="0" /></a></td>
            <td><a href="JavaScript:ganti()">Ganti Password</a></td>
          </tr>
        </table>
    </td>
    <td>
    	<table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><a href="referensi/auditnilai.php"><img src="images/Draft.png" border="0" height="80" /></a></td>
            <td><a href="referensi/auditnilai.php"><strong>Audit Perubahan Nilai</strong></a></td>
          </tr>
        </table>
    </td>
    <td>
    	<?php if (SI_USER_LEVEL() != "2") { ?>
    	<table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><a href="referensi/queryerror.php"><img src="images/ico/b_warning.png" border="0" height="80" /></a></td>
            <td><a href="referensi/queryerror.php"><strong>Query Error Log</strong></a></td>
          </tr>
        </table>
        <?php } else { ?>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><a href="#" onClick="alert ('Maaf, Anda tidak berhak mengakses halaman ini !');"><img src="images/ico/b_warning.png" border="0" height="80" /></a></td>
            <td><a href="#" onClick="alert ('Maaf, Anda tidak berhak mengakses halaman ini !');"><strong>Query Error Log</strong></a></td>
          </tr>
        </table>
        <?php } ?>
    </td>
  </tr>
</table>


</td></tr>
</table>
</body>
</html>