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
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];
	
$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];

$bln = 0;
if (isset($_REQUEST['bln']))
	$bln = (int)$_REQUEST['bln'];

$thn = 0;
if (isset($_REQUEST['thn']))
	$thn = (int)$_REQUEST['thn'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function cetak() {
	var addr = "lapmodal_cetak.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&idtahunbuku=<?=$idtahunbuku?>&bln=<?=$bln?>&thn=<?=$thn?>";
	newWindow(addr, 'PerubahanModal','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
<style type="text/css">
<!--
.style1 {color: #000000}
-->
</style></head>

<body>
<?php
OpenDb();
$first_date = "$thn-$bln-1";
$sql = "SELECT date_sub('$first_date', INTERVAL 1 DAY)";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$last_date = $row[0];

$sql = "SELECT SUM(jd.kredit - jd.debet) FROM $db_name_fina.rekakun ra, $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd 
		WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND 
			  j.tanggal BETWEEN '$tanggal1' AND '$last_date' AND ra.kategori IN ('PENDAPATAN', 'MODAL')";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$totalpendapatan = (float)$row[0];

$sql = "SELECT SUM(jd.debet - jd.kredit) FROM $db_name_fina.rekakun ra, $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd 
	    WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND 
		      j.tanggal BETWEEN '$tanggal1' AND '$last_date' AND ra.kategori = 'BIAYA'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$totalbiaya = (float)$row[0];

$modalawal = $totalpendapatan - $totalbiaya;

$sql = "SELECT SUM(jd.kredit - jd.debet) FROM $db_name_fina.rekakun ra, $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd 
	    WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND 
		      j.tanggal BETWEEN '$first_date' AND '$tanggal2' AND ra.kategori = 'MODAL' AND jd.kredit > 0";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$jinvestasi = (float)$row[0];

$sql = "SELECT SUM(jd.debet - jd.kredit) FROM $db_name_fina.rekakun ra, $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd 
	    WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND 
		      j.tanggal BETWEEN '$first_date' AND '$tanggal2' AND ra.kategori = 'MODAL' AND jd.debet > 0";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$jpengambilan = (float)$row[0];

$sql = "SELECT SUM(jd.kredit - jd.debet) FROM $db_name_fina.rekakun ra, $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd 
        WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND 
		      j.tanggal BETWEEN '$first_date' AND '$tanggal2' AND ra.kategori = 'PENDAPATAN'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$jpendapatan = (float)$row[0];

$sql = "SELECT SUM(jd.debet - jd.kredit) FROM $db_name_fina.rekakun ra, $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd 
        WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND 
		      j.tanggal BETWEEN '$first_date' AND '$tanggal2' AND ra.kategori = 'BIAYA'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$jbiaya = (float)$row[0];

$jincome = $jpendapatan - $jbiaya;

$modalakhir = $modalawal + $jinvestasi - $jpengambilan + $jincome;
?>
<br />

<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td valign="middle">
    <table border="0" width="70%" align="center" cellpadding="5" cellspacing="5">
    <tr>
        <td>
        	<font size="4"><strong>Laporan Perubahan Modal</strong></font><br />
    		<font size="2">Per Tanggal <?=LongDateFormat($tanggal2) ?></font>        </td>
        <td align="right" valign="top">
        <!--<a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onmouseover="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;-->
        <a href="JavaScript:cetak()"><img src="../img/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;        </td>
    </tr>
    </table>
   
    <table border="0" cellpadding="8" cellspacing="5" align="center" width="70%" background="../img/bttable.png">
    <tr>
        <td width="*">Modal di awal <?=NamaBulan($bln) . " " . $thn?></td>
        <td align="right" width="200"><?=FormatRupiah($modalawal) ?></td>
        <td width="5">&nbsp;</td>
    </tr>
    <tr>
        <td>Investasi pada <?=NamaBulan($bln) . " " . $thn?></td>
        <td align="right"><?=FormatRupiah($jinvestasi) ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Pengambilan pada <?=NamaBulan($bln) . " " . $thn?></td>
        <td align="right"><?=FormatRupiah(-1 * $jpengambilan) ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><?php if ($jpendapatan < $jbiaya) echo "Rugi"; else  echo "Laba"; ?>
        pada <?=NamaBulan($bln) . " " . $thn?></td>
        <td align="right"><?=FormatRupiah($jincome) ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">
        <hr width="100%" style="color:#000000; border-style:dashed; line-height:1px;" />
        </td>
        <td><font size="3"><strong>+</strong></font></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;<font size="2"><strong>Modal per <?=LongDateFormat($tanggal2) ?></strong></font></td>
        <td align="right" class="err"><font size="2"><strong><span class="style1"><?=FormatRupiah($modalakhir) ?></span></strong></font></td>
    </tr>
    </table>
	</td>
</tr>
</table>
</body>
</html>