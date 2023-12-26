<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 2.6.0 (January 14, 2012)
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
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once("../include/sessionchecker.php");

$nis = "";
if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];
$nama = "";
if (isset($_REQUEST['nama']))
	$nama = $_REQUEST['nama'];
if ($nis!=""){
?>
<script language="javascript">
	parent.catatansiswamenu.location.href="../infosiswa/catatansiswamenu.php?nis=<?=$nis?>";
</script>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function carisiswa() {
	//parent.catatansiswamenu.location.href="../blank.php";
	//parent.catatansiswacontent.location.href="../blank.php";
	newWindow('../library/siswa.php?flag=0', 'CariSiswa','600','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptSiswa(nis, nama) {
	document.getElementById('nis').value=nis;
	document.getElementById('nama').value=nama;
	//parent.catatansiswamenu.location.href="../infosiswa/catatansiswamenu.php?nis="+nis;
	parent.catatansiswafooter.location.href="../infosiswa/catatansiswafooter.php?nis="+nis;
	//document.location.href="../infosiswa/catatansiswaheader.php?nis="+nis+"&nama="+nama;
}
</script>
</head>

<body leftmargin="0" topmargin="0">
<table border="0"width="100%" align="center">
    <tr>
        <td width="72%" rowspan="2" align="right" valign="bottom"><div align="left"><strong>Siswa</strong> 
          <input name="nis" size="20" id="nis" type="text" style="height: 26px; font-size: 14px; background-color: #efffe9;" onClick="carisiswa()" readonly="true" />
          <input name="nama" size="45" id="nama" type="text" style="height: 26px; font-size: 14px; background-color: #efffe9;" onClick="carisiswa()" readonly="true" />
        <img src="../images/ico/cari.png" onClick="carisiswa();" /></div></td>
        <td width="28%" align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Catatan Siswa</font></td>
    </tr>
    <tr>
        <td align="right">
        <a href="../catatankejadian.php" target="framecenter">
        <font size="1" face="Verdana" color="#000000"><b>Catatan Siswa</b></font>
        </a>&nbsp>&nbsp <font size="1" face="Verdana" color="#000000">Catatan Kategori</font>&nbsp;</td>
    </tr>
     
	</table>
</body>
</html>