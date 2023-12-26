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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
$departemen=$_REQUEST['departemen'];
$tahun=$_REQUEST['tahun'];
$replidmutasi=$_REQUEST['replidmutasi'];
openDb();
		$query="SELECT s.nis,s.nama,m.tglmutasi,m.keterangan FROM jbsakad.mutasisiswa m,jbsakad.jenismutasi j,jbsakad.angkatan a,jbsakad.siswa s WHERE	a.departemen='$departemen' AND s.idangkatan=a.replid  AND m.nis=s.nis AND s.statusmutasi=m.jenismutasi AND m.jenismutasi=j.replid AND YEAR(m.tglmutasi) = '$tahun' AND j.jenismutasi='".$_REQUEST['jenismutasi']."'" ;
		$result=queryDb($query);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {
	color: #666666;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><fieldset>
		<legend><span class="style1">Daftar Mutasi Siswa Tahun <?=$tahun?> <br>Karena <?=$_REQUEST['jenismutasi']?></span></legend>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" bordercolor="#666666">
  <tr>
    <td><table width="100%"  border="1" class="tab" cellspacing="0" cellpadding="0">
      <tr align="center" class="header">
        <td width="25" height="30">No</td>
        <td width="80" height="30">NIS </td>
        <td width="100" height="30">Nama </td>
        <td width="100" height="30">Tanggal Mutasi </td>
        <td height="30">Keterangan</td>
      </tr>
	  <?php
	  $a=0;
	  while($fetch=mysqli_fetch_row($result)){$a++
	  ?>
      <tr valign="top">
        <td height="25" valign="middle"><?=$a;?></td>
        <td height="25" valign="middle"><?=$fetch[0];?></td>
        <td height="25" valign="middle"><?=$fetch[1];?></td>
        <td height="25" valign="middle"><?=TglTextLong($fetch[2]);?></td>
        <td height="25" valign="middle"><?=$fetch[3];?></td>
      </tr>
	  <?php
	  }
	  ?>
    </table></td>
  </tr>
</table>
	</fieldset></td>
  </tr>
</table>
<p>
  <input type="button" class="but" id="print" onClick="javascript: document.getElementById('print').style.display='none'; window.print(); setTimeout('document.getElementById(\'print\').style.display=\'\'',300);" value="Print">
</p>
</body>
</html>