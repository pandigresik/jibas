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
$iddasar=$_REQUEST['iddasar'];
$departemen=$_REQUEST['departemen'];
$idproses=$_REQUEST['idproses'];
//$judul = $_REQUEST['judul'];

for ($i=1;$i<=17;$i++) {
   if ($iddasar == $i) {
	$dasar = $kriteria[$i];	
	$tabel = $kriteria_tabel[$i];
	$judul = $kriteria_judul[$i];	
   }				
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<title>Header Statistik</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<style type="text/css">
<!--
.style2 {
	font-size: larger;
	font-weight: bold;
}
-->
</style>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh(){
	var departemen = document.getElementById("departemen").value;	
	var idproses = document.getElementById("idproses").value;	
	var judul = document.getElementById("judul").value;
	var iddasar = document.getElementById("iddasar").value;
	var dasar = document.getElementById('dasar').value;	
	var tabel = document.getElementById('tabel').value;	
	parent.grafik_statistik.wait();
	document.location.href = "statistik_grafik_header.php?departemen="+departemen+"&idproses="+idproses+"&iddasar="+iddasar+"&judul="+judul+"&dasar="+dasar+"&tabel="+tabel;
	parent.grafik_statistik.location.href = "grafik.php?departemen="+departemen+"&idproses="+idproses+"&iddasar="+iddasar+"&judul="+judul+"&dasar="+dasar+"&tabel="+tabel;
	parent.table_statistik.location.href = "statistik_table.php?departemen="+departemen+"&idproses="+idproses+"&iddasar="+iddasar+"&nama_judul="+judul+"&dasar="+dasar+"&tabel="+tabel;
}

function cetak() {
	var departemen = document.getElementById('departemen').value;
	var idproses = document.getElementById('idproses').value;
	var dasar = document.getElementById('dasar').value;	
	var tabel = document.getElementById('tabel').value;	
	var nama_judul = document.getElementById('judul').value;	
	newWindow('statistik_cetak.php?departemen='+departemen+'&idproses='+idproses+'&dasar='+dasar+'&tabel='+tabel+'&nama_judul='+nama_judul, 'CetakStatistikCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
	
}
	
</script>
</head>
<body topmargin="0" leftmargin="0">
<input type="hidden" name="idproses" id="idproses" value="<?=$idproses?>" />
<input type="hidden" name="judul" id="judul" value="<?=$judul?>" />
<input type="hidden" name="iddasar" id="iddasar" value="<?=$iddasar?>" />
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
<input type="hidden" name="dasar" id="dasar" value="<?=$dasar?>" />
<input type="hidden" name="tabel" id="tabel" value="<?=$tabel?>" />
<!--<div align="center"><font size="4"><b>STATISTIK PENERIMAN SISWA BARU<BR />BERDASARKAN <?=$judul?></b></font></div>-->
<div align="right"> 	
  		<a href="#" onClick="javascript:refresh()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
  		<a href="#" onClick="cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
        
</body>
</html>