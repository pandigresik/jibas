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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/jurnal.php');
require_once('laporan.kelas.content.func.php');

OpenDb();

ReadRequest();
CheckTahunBuku();
GetNames();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Tabungan Siswa Per Kelas</title>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
</head>

<body topmargin="0" leftmargin="0">
<?php
$pageLimit = true;
require_once("laporan.kelas.content.menu.php");
require_once("laporan.kelas.content.body.php");
require_once("laporan.kelas.content.navi.php");
?>
</body>
</html>
<?php
CloseDb();
?>

<script language="javascript">
function refresh() 
{	
	document.location.href = "laporan.kelas.content.php?idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idtabungan=<?=$idtabungan?>&idtingkat=<?=$idtingkat?>&departemen=<?=$departemen?>";	
}

function cetak()
{
	var addr = "laporan.kelas.content.cetak.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idtabungan=<?=$idtabungan?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>";
	newWindow(addr, 'CetakTabungan','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel()
{
	var addr = "laporan.kelas.content.excel.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idtabungan=<?=$idtabungan?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>";
	newWindow(addr, 'ExcelTabungan','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan)
{		
	var varbaris = document.getElementById("varbaris").value;	
	if (urutan == "ASC")
		urutan = "DESC";
	else 
		urutan="ASC";
		
	document.location.href = "laporan.kelas.content.php?departemen=<?=$departemen?>&idkelas=<?=$idkelas?>&idangkatan=<?=$idangkatan ?>&idtabungan=<?=$idtabungan?>&idtingkat=<?=$idtingkat?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function change_page(page) {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="laporan.kelas.content.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idtabungan=<?=$idtabungan?>&idtingkat=<?=$idtingkat?>&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() 
{
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="laporan.kelas.content.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idtabungan=<?=$idtabungan?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&page="+hal+"&hal="+hal;
}

function change_baris() {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="laporan.kelas.content.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idtabungan=<?=$idtabungan?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>