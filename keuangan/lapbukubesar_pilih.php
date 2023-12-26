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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];
	
$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$kategori = "";
if (isset($_REQUEST['kategori']))
	$kategori = $_REQUEST['kategori'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Laporan Buku Besar Pilih</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function show_detail(koderek) {
	parent.content.location.href = "lapbukubesar_content.php?departemen=<?=$departemen?>&kategori=<?=$kategori?>&idtahunbuku=<?=$idtahunbuku?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&koderek="+koderek;
}

function cetak() {
	var addr = "lapbukubesar_cetak_rekap.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&kategori=<?=$kategori?>&idtahunbuku=<?=$idtahunbuku?>";
	newWindow(addr, 'CetakRekap','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body leftmargin="0" marginheight="0" marginwidth="0">
<br />
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left">
<?php 	OpenDb();
	if ($kategori != "ALL")
		$sql = "SELECT r.nama, r.kode, sum(jd.debet), sum(jd.kredit) FROM jurnal j, jurnaldetail jd, rekakun r WHERE j.replid = jd.idjurnal AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND  jd.koderek = r.kode AND r.kategori = '$kategori' GROUP BY r.nama, r.kode ORDER BY r.kode";
	else
		$sql = "SELECT r.nama, r.kode, sum(jd.debet), sum(jd.kredit) FROM jurnal j, jurnaldetail jd, rekakun r WHERE j.replid = jd.idjurnal AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND  jd.koderek = r.kode GROUP BY r.nama, r.kode ORDER BY r.kode";
		
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) {
?>
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right">
        <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
        </td>
    </tr>
    </table>
    <br />
     <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="95%" align="center" bordercolor="#000000" />
    <tr height="30">
        <td class="header" width="4%" align="center">No</td>
        <td class="header" width="*" align="center">Rekening</td>
        <td class="header" width="22%" align="center">Debet</td>
        <td class="header" width="22%" align="center">Kredit</td>
    </tr>
<?php
	$cnt = 0;
	$totaldebet = 0;
	$totalkredit = 0;
	while($row = mysqli_fetch_row($result)) {
		$totaldebet += $row[2];
		$totalkredit += $row[3];
?>
    <tr height="25" onclick="show_detail('<?=$row[1] ?>')" style="cursor:pointer">
        <td align="center"><?=++$cnt ?></td>
        <td align="left"><strong><u><?=$row[1] . " " . $row[0] ?></u></strong></td>
        <td align="right"><?=FormatRupiah($row[2]) ?></td>
        <td align="right"><?=FormatRupiah($row[3]) ?></td>
    </tr>
<?php } ?>
    <tr height="30">
        <td colspan="2" align="center" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totaldebet) ?></strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalkredit) ?></strong></font></td>
    </tr>
    </table>
    <script language='JavaScript'>
		Tables('table', 1, 0);
	</script>
<?php } else { ?>	

    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="300">    
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data transaksi <?php if ($kategori <> "ALL") echo  "pada kategori ".$kategori; ?> antara tanggal <?=LongDateFormat($tanggal1)." s/d ".LongDateFormat($tanggal2) ?>.
            </b></font>
        </td>
    </tr>
    </table>  
<?php } ?>
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table> 
</body>
</html>