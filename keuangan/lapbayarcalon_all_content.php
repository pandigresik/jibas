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
require_once('include/sessioninfo.php');
require_once('library/departemen.php');
require_once('library/jurnal.php');
require_once('library/repairdatajttcalon.php');

$idtahunbuku = $_REQUEST['idtahunbuku'];
$replid = $_REQUEST['replid'];
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="script/tooltips.js" language="javascript"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function cetak() {
	var addr = "lapbayarcalon_all_cetak.php?idtahunbuku=<?=$idtahunbuku?>&replid=<?=$replid?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>"
	newWindow(addr, 'CetakBayarSiswaAll','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function excel() {
	var addr = "lapbayarcalon_all_excel.php?idtahunbuku=<?=$idtahunbuku?>&replid=<?=$replid?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>"
	newWindow(addr, 'ExcelBayarSiswaAll','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body topmargin="10" leftmargin="10">
<?php
OpenDb();

$sql = "SELECT count(b.replid) 
          FROM besarjttcalon b, penerimaanjttcalon p 
			WHERE p.idbesarjttcalon = b.replid AND b.idcalon='$replid' AND b.info2='$idtahunbuku'
			  AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
$nwajib = FetchSingle($sql);

$sql = "SELECT count(p.replid) 
          FROM penerimaaniurancalon p, jurnal j
			WHERE p.idjurnal = j.replid AND j.idtahunbuku='$idtahunbuku'
			  AND p.idcalon='$replid' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'"; 
$niuran = FetchSingle($sql);

$sql = "SELECT s.nama, s.nopendaftaran FROM jbsakad.calonsiswa s WHERE s.replid = '".$replid."'";
$row = FetchSingleRow($sql);
$namacalon = $row[0];
$no = $row[1];
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed" width="100%">
    <table width="100%" border="0" cellspacing="0" cellpadding="2" height="100%">
   	<tr>
    	<td><font size="5" color="#990000"><strong>Data Pembayaran</strong></font></td>
   	</tr>
    <tr>
        <td><font size="3"><strong><?=$no . " - " . $namacalon?></strong></font></td>
<?php if (($nwajib + $niuran) >  0) {	?>
        <td align="right" >
        <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
        <a href="JavaScript:excel()"><img src="images/ico/excel.png" border="0" onMouseOver="showhint('Buka di Ms Excel!', this, event, '50px')"/>&nbsp;Excel</a>&nbsp;
        </td>
    </tr>
    </table>
    <br />
   
<?php
$sql = "SELECT DISTINCT b.replid AS id, b.besar, b.lunas, b.keterangan, d.nama 
          FROM besarjttcalon b, penerimaanjttcalon p, datapenerimaan d
			WHERE p.idbesarjttcalon = b.replid AND b.idpenerimaan = d.replid AND b.idcalon='$replid' AND b.info2='$idtahunbuku' 
			  AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY nama";

$totalbesarwjb = 0;
$totalbayarwjb = 0;
$totaldiskonwjb = 0;
$totalsisawjb = 0;

$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) {
	$idbesarjtt = $row['id'];
	$namapenerimaan = $row['nama']; 
	$besar = $row['besar'];
	$lunas = $row['lunas'];
	$keterangan = $row['keterangan'];
	
	$sql = "SELECT SUM(jumlah), SUM(info1) FROM penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
	$row2 = FetchSingleRow($sql);
	$pembayaran = $row2[0] + $row2[1];
	$diskon = $row2[1];
	$sisa = $besar - $pembayaran;

    $totalbesarwjb += $besar;
    $totalbayarwjb += $pembayaran;
    $totaldiskonwjb += $diskon;
    $totalsisawjb += $sisa;
	
	$sql = "SELECT jumlah, DATE_FORMAT(tanggal, '%d-%b-%Y') AS ftanggal, info1 FROM penerimaanjttcalon WHERE idbesarjttcalon='$idbesarjtt' ORDER BY tanggal DESC LIMIT 1";
	$result2 = QueryDb($sql);
	$byrakhir = 0;
	$dknakhir = 0;
	$tglakhir = "";
	if (mysqli_num_rows($result2))
	{
		$row2 = mysqli_fetch_row($result2);
		$byrakhir = $row2[0];
		$tglakhir = $row2[1];
		$dknakhir = $row2[2];
	};
	?>
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
    <tr height="35">
        <td colspan="4" bgcolor="#99CC00"><font size="2"><strong><em><?=$namapenerimaan?></em></strong></font></td>
    </tr>    
    <tr height="25">
        <td width="20%" bgcolor="#CCFF66"><strong>Total Bayaran</strong> </td>
        <td width="15%" bgcolor="#FFFFFF" align="right"><?=FormatRupiah($besar) ?></td>
        <td width="22%" bgcolor="#CCFF66" align="center"><strong>Pembayaran Terakhir</strong></td>
        <td width="43%" bgcolor="#CCFF66" align="center"><strong>Keterangan</strong></td>
    </tr>
    <tr height="25">
        <td bgcolor="#CCFF66"><strong>Jumlah Besar Pembayaran</strong> </td>
        <td bgcolor="#FFFFFF" align="right"><?=FormatRupiah($pembayaran) ?></td>
        <td bgcolor="#FFFFFF" align="center" valign="top" rowspan="3"><?=FormatRupiah($byrakhir) . "<br><i>" . $tglakhir . "</i><br>(diskon " . FormatRupiah($dknakhir) . ")" ?> </td>
        <td bgcolor="#FFFFFF" align="left" valign="top" rowspan="3"><?=$keterangan ?></td>
    </tr>
	<tr height="25">
        <td bgcolor="#CCFF66"><strong>Jumlah Diskon</strong> </td>
        <td bgcolor="#FFFFFF" align="right"><?=FormatRupiah($diskon) ?></td>
    </tr>
    <tr height="25">
        <td bgcolor="#CCFF66"><strong>Sisa Bayaran</strong> </td>
        <td bgcolor="#FFFFFF" align="right"><?=FormatRupiah($sisa) ?></td>
    </tr>
    <tr height="3">
        <td colspan="4" bgcolor="#E8E8E8">&nbsp;</td>
    </tr>
<?php 
} //while iuran wajib

$totalbayarskr = 0;

$sql = "SELECT DISTINCT p.idpenerimaan, d.nama 
          FROM penerimaaniurancalon p, jurnal j, datapenerimaan d 
			WHERE p.idjurnal = j.replid AND j.idtahunbuku='$idtahunbuku'
			  AND p.idpenerimaan = d.replid AND p.idcalon='$replid' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY nama";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) {
	$idpenerimaan = $row['idpenerimaan'];
	$namapenerimaan = $row['nama'];
	
	$sql = "SELECT SUM(jumlah) FROM penerimaaniurancalon WHERE idpenerimaan='$idpenerimaan' AND idcalon='$replid'";
	$pembayaran = FetchSingle($sql);
    $totalbayarskr += $pembayaran;

	$sql = "SELECT jumlah, DATE_FORMAT(tanggal, '%d-%b-%Y') AS ftanggal FROM penerimaaniurancalon WHERE idpenerimaan='$idpenerimaan' AND idcalon='$replid' ORDER BY tanggal DESC LIMIT 1";
	$result2 = QueryDb($sql);
	$byrakhir = 0;
	$tglakhir = "";
	if (mysqli_num_rows($result2)) {
		$row2 = mysqli_fetch_row($result2);
		$byrakhir = $row2[0];
		$tglakhir = $row2[1];
	};	
?>
 	<tr height="35">
        <td colspan="4" bgcolor="#99CC00"><font size="2"><strong><em><?=$namapenerimaan?></em></strong></font></td>
    </tr>  
   	<tr height="25">
        <td width="22%" bgcolor="#CCFF66" align="center"><strong>Total Pembayaran</strong> </td>
        <td width="22%" bgcolor="#CCFF66" align="center"><strong>Pembayaran Terakhir</strong></td>
        <td width="50%" colspan="2" bgcolor="#CCFF66" align="center"><strong>Keterangan</strong></td>
    </tr>
    <tr height="25">
        <td bgcolor="#FFFFFF" align="center"><?=FormatRupiah($pembayaran) ?></td>
        <td bgcolor="#FFFFFF" align="center"><?=FormatRupiah($byrakhir) . "<br><i>" . $tglakhir . "</i>" ?></td>
        <td colspan="2" bgcolor="#FFFFFF" align="left">&nbsp;</td>
    </tr>
    <tr height="3">
        <td colspan="4" bgcolor="#E8E8E8">&nbsp;</td>
    </tr>
<?php
} //while iuran sukarela
?>
	</table>

    <br><br>
    <font style="font-size: 16px;">REKAPITULASI PEMBAYARAN</font>
    <table border="0" width="900">
    <tr>
        <td width="50%" align="left" valign="top">
            <table border="1" style="border-width: 1px; border-collapse: collapse;" cellpadding="5">
            <tr>
                <td colspan="2" style="background-color: #87c7f4; font-size: 14px;">Iuran Wajib Siswa</td>
            </tr>
            <tr>
                <td width="240" align="left" style="background-color: #e6f5ff">Total Semua Besar Bayaran</td>
                <td width="140" align="right"><?=FormatRupiah($totalbesarwjb)?></td>
            </tr>
            <tr>
                <td align="left" style="background-color: #e6f5ff">Total Semua Pembayaran</td>
                <td align="right"><?=FormatRupiah($totalbayarwjb)?></td>
            </tr>
            <tr>
                <td align="left" style="background-color: #e6f5ff">Total Semua Diskon</td>
                <td align="right"><?=FormatRupiah($totaldiskonwjb)?></td>
            </tr>
            <tr>
                <td align="left" style="background-color: #e6f5ff">Total Semua Sisa Tagihan</td>
                <td align="right"><?=FormatRupiah($totalsisawjb)?></td>
            </tr>
            </table>
        </td>
        <td width="50%" align="left" valign="top">
            <table border="1" style="border-width: 1px; border-collapse: collapse;" cellpadding="5">
            <tr>
                <td colspan="2" style="background-color: #87c7f4; font-size: 14px;">Iuran Sukarela Siswa</td>
            </tr>
            <tr>
                <td width="240" align="left" style="background-color: #e6f5ff">Total Semua Pembayaran</td>
                <td width="140" align="right"><?=FormatRupiah($totalbayarskr)?></td>
            </tr>
            </table>
        </td>
    </tr>
    </table>
<?php } else { ?>
        <td></td>
    </tr>
    </table>
    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="250">    
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data.         
            <br />Tambah data pembayaran antara tanggal <?=LongDateFormat($tanggal1)." s/d ".LongDateFormat($tanggal2) ?> di menu Penerimaan Pembayaran pada bagian Penerimaan.
            </b></font>
        </td>
    </tr>
    </table>  
<?php } ?>    
	</tr>
</td>
</table>
<?php

CloseDb();
?>
</body>
</html>