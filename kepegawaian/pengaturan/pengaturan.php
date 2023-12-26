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
require_once('../include/config.php');
require_once('../include/sessioninfo.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawian</title>
<link rel="stylesheet" href="../style/style.css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function ganti() {
	newWindow('user_ganti.php','GantiPasswordUser','500','280','resizable=1,scrollbars=1,status=0,toolbar=0')
}  
</script>
</head>

<body>
<table width="489" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td align="center"><h3>PENGATURAN</h3></td>
  </tr>
  <tr>
    <td>
    
    <!-- ImageReady Slices (pengaturan.psd) -->
<table id="Table_01" width="488" height="311" border="0" cellpadding="0" cellspacing="0">
	<tr height="139">
		<td>
			<a href="user.php"><img src="../images/bkatur_01.jpg" width="103" height="139" alt="" border="0"></a></td>
		<td colspan="2">
			<img src="../images/bkatur_02.jpg" width="73" height="139" alt=""></td>
		<td width="121">
		    <a href="#" onclick="ganti()"><img src="../images/bkatur_03.jpg" width="121" height="139" alt="" border="0"></a>
		</td>
		<td colspan="2">
			<img src="../images/bkatur_04.jpg" width="46" height="139" alt=""></td>
		<td>
		    <a href="queryerror.php"><img src="../images/ico/b_warning.png" border="0" height="80" /></a><br>
            <a href="queryerror.php"><strong>Query Error Log</strong></a>
		</td>
	</tr>
	<tr>
		<td colspan="7">
			<img src="../images/bkatur_06.jpg" width="488" height="40" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="../images/spacer.gif" width="103" height="1" alt=""></td>
		<td>
			<img src="../images/spacer.gif" width="69" height="1" alt=""></td>
		<td>
			<img src="../images/spacer.gif" width="4" height="1" alt=""></td>
		<td>
			<img src="../images/spacer.gif" width="121" height="1" alt=""></td>
		<td>
			<img src="../images/spacer.gif" width="1" height="1" alt=""></td>
		<td>
			<img src="../images/spacer.gif" width="45" height="1" alt=""></td>
		<td>
			<img src="../images/spacer.gif" width="145" height="1" alt=""></td>
	</tr>
</table>
    
<!-- End ImageReady Slices -->
    
    </td>
  </tr>
</table>
</body>
</html>