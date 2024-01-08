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
/*require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aturan Penentuan Grading Nilai</title>

<!--<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>-->
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">
function caripegawai() {
	//parent.footer.location.href = "http://192.168.1.102/jibassimaka/blank2.php";
	parent.footer.location.href = "../blank2.php";
	newWindow('../library/caripegawai.php?flag=0', 'CariPegawai','600','500','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(nip, nama, flag) {
	//parent.footer.location.href = "../blank2.php";
	document.getElementById('nip').value = nip;	
	//document.getElementById('nipguru').value = nip;	
	document.getElementById('nama').value = nama;	
	//parent.footer.location.href = "http://192.168.1.102/jibassimaka/guru/aturannilai_footer.php?nip="+nip;
	parent.footer.location.href = "../guru/aturannilai_footer.php";
	//parent.footer.location.href = "../blank.php";
}

</script>
<style type="text/css">
<!--
.style1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<body background="../images/buatdiatas_120.jpg">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />&nbsp;please wait...
</div>
<form name="main" enctype="multipart/form-data" >
<table width="100%"  border="0"  cellpadding="0" cellspacing="0" style="margin-left:10">
  <tr>
    <td width="106" rowspan="4" ><img src="../images/b_departemen.png" height="96" /></td>
    <td width="399"></td>
    <td width="36" >&nbsp;</td>
<td>&nbsp;</td>
    <td rowspan="4" align="left" valign="middle">&nbsp;</td>
    </tr>
  <tr>
    <td valign="bottom"><span class="style1"><font color="#660000">ATURAN PERHITUNGAN NILAI RAPOR</font></span></td>
    <td colspan="2" rowspan="2"><div id="tingkatInfo"><strong>Guru</strong>
            <strong>
        <input type="text" name="nip" id="nip" size="10" style="background-color:#CCCCCC" readonly value="<?=$nip ?>" /> <input type="hidden" name="nipguru" id="nipguru" value="<?=$nipguru ?>" /> 
        <input type="text" name="nama" id="nama" size="20" style="background-color:#CCCCCC" readonly value="<?=$nama ?>" />
      </strong> &nbsp;
      <a href="JavaScript:caripegawai()"><img src="../images/ico/lihat.png" border="0" /></a>
      <div id="tahunajaranInfo">      &nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
  <tr>
    <td valign="top"><a href="../guru.php" target="content">
        <font size="1" color="#000000"><b>Guru&nbsp;&amp;&nbsp;Pelajaran</b></font></a>&nbsp>&nbsp
    <font size="1" color="#000000"><b>Departemen</b></font></td>
    </tr>
  <tr>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td></td>
    <td></td>
    <td width="289">&nbsp;</td>
    <td width="159" align="left" valign="top">&nbsp;</td>
    </tr>
</table>
</form>
</body>
</html>