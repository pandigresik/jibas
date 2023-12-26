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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../library/departemen.php');
require_once('../library/jurnal.php');

OpenDb();

$departemen = $_REQUEST['dept'];
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];
$datetime1 = "$tanggal1 00:00:00";
$datetime2 = "$tanggal2 23:59:59";
$petugas = $_REQUEST['petugas'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="../script/tooltips.js" language="javascript"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function cetak() 
{
	var addr = "laporan.rekap.content.cetak.php?dept=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&petugas=<?=$petugas?>"
	newWindow(addr, 'RekapCetak','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() 
{
	var addr = "laporan.rekap.content.excel.php?dept=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&petugas=<?=$petugas?>"
	newWindow(addr, 'RekapExcel','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ShowDetail(departemen, idtabungan, tanggal1, tanggal2, petugas, jenis, kelompok)
{
	var addr = "laporan.rekap.detail.php?departemen="+departemen+"&idtabungan="+idtabungan+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&petugas="+petugas+"&jenis="+jenis+"&kelompok="+kelompok;
	newWindow(addr, 'DetailLapRekap','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');	
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<table border="0" width="100%" align="center">
<tr height="300">
	<td align="left" valign="top" background="../images/uang_trans.png" style="background-repeat:no-repeat">
    
    <table width="100%" border="0" height="100%">
    <tr><td align="center">
<?php
    require_once('laporan.rekap.content.menu.php');
    require_once('laporan.rekap.content.body.php');
?>
    </td></tr>
    </table>
</td></tr>
</table>    
</body>
</html>
<?php
CloseDb();
?>