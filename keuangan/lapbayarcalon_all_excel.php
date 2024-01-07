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
require_once('include/getheader.php'); 
require_once('library/jurnal.php');
require_once('library/repairdatajttcalon.php');

$idtahunbuku = $_REQUEST['idtahunbuku'];
$replid = $_REQUEST['replid'];
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];

OpenDb();

$sql = "SELECT s.nama, s.nopendaftaran, k.kelompok, p.proses, p.departemen 
          FROM jbsakad.calonsiswa s, jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p 
			WHERE s.replid = '$replid' AND s.idkelompok = k.replid AND s.idproses = p.replid";
$row = FetchSingleRow($sql);
$namacalon = $row[0];
$kelompok = $row[2];
$proses = $row[3];
$no = $row[1];
$departemen = $row[4];
/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Data_Pembayaran_Calon_Siswa_'.$no.'.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Pembayaran Per Siswa</title>
</head>

<body>
<center><font size="4"><strong><font face="Verdana">DATA PEMBAYARAN CALON SISWA</font></strong></font><font face="Verdana"><br /> 
  </font>
</center>
<br /><br />
<table border="0">
<tr>
	<td><font size="2" face="Arial"><strong>Calon Siswa </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$no . " - " . $namacalon?>
    </strong></font></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><strong>Proses </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$proses?>
    </strong></font></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><strong>Kelompok </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$kelompok?>
    </strong></font></td>
</tr>

<tr>
	<td><font size="2" face="Arial"><strong>Tanggal </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=LongDateFormat($tanggal1) . " s/d " . LongDateFormat($tanggal2) ?>
    </strong></font></td>
</tr>
</table>
<br />

<table border="1" style="border-collapse:collapse" width="100%" bordercolor="#000000"> 
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
while ($row = mysqli_fetch_array($result))
{
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
	if (mysqli_num_rows($result2)) {
		$row2 = mysqli_fetch_row($result2);
		$byrakhir = $row2[0];
		$tglakhir = $row2[1];
		$dknakhir = $row2[2];
	};	?>
    <tr height="35">
        <td colspan="4" bgcolor="#99CC00"><font size="2" face="Arial"><strong><em><?=$namapenerimaan?></em></strong></font></td>
  </tr>    
    <tr height="25">
        <td width="20%" bgcolor="#CCFF66"><font size="2" face="Arial"><strong>Total Bayaran</strong> </font></td>
<td width="15%" bgcolor="#FFFFFF" align="right"><font size="2" face="Arial">
        <?=$besar ?>
        </font></td>
      <td width="22%" bgcolor="#CCFF66" align="center"><font size="2" face="Arial"><strong>Pembayaran Terakhir</strong></font></td>
      <td width="43%" bgcolor="#CCFF66" align="center"><font size="2" face="Arial"><strong>Keterangan</strong></font></td>
  </tr>
    <tr height="25">
        <td bgcolor="#CCFF66"><font size="2" face="Arial"><strong>Jumlah Besar Pembayaran</strong> </font></td>
<td bgcolor="#FFFFFF" align="right"><font size="2" face="Arial">
        <?=$pembayaran ?>
        </font></td>
<td bgcolor="#FFFFFF" align="center" valign="top" rowspan="2"><font size="2" face="Arial">
        <?=$byrakhir . "<br><i>" . $tglakhir . "</i><br>(diskon: $dknakhir)"  ?> 
        </font></td>
<td bgcolor="#FFFFFF" align="left" valign="top" rowspan="2"><font size="2" face="Arial">
        <?=$keterangan ?>
        </font></td>
  </tr>
	<tr height="25">
        <td bgcolor="#CCFF66"><font size="2" face="Arial"><strong>Jumlah Diskon</strong> </font></td>
<td bgcolor="#FFFFFF" align="right"><font size="2" face="Arial">
        <?=$diskon ?>
        </font></td>
  </tr>
    <tr height="25">
        <td bgcolor="#CCFF66"><font size="2" face="Arial"><strong>Sisa Bayaran</strong> </font></td>
<td bgcolor="#FFFFFF" align="right"><font size="2" face="Arial">
        <?=$sisa ?>
        </font></td>
  </tr>
    <tr height="3">
        <td colspan="4" bgcolor="#E8E8E8">&nbsp;</td>
  </tr>
<?php 
} //while iuran wajib

$totalbayarskr = 0;

$sql = "SELECT DISTINCT p.idpenerimaan, d.nama FROM penerimaaniurancalon p, datapenerimaan d WHERE p.idpenerimaan = d.replid AND p.idcalon='$replid' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY nama";
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
        <td colspan="4" bgcolor="#99CC00"><font size="2" face="Arial"><strong><em><?=$namapenerimaan?></em></strong></font></td>
  </tr>  
   	<tr height="25">
        <td width="22%" bgcolor="#CCFF66" align="center"><font size="2" face="Arial"><strong>Total Pembayaran</strong> </font></td>
      <td width="22%" bgcolor="#CCFF66" align="center"><font size="2" face="Arial"><strong>Pembayaran Terakhir</strong></font></td>
      <td width="50%" colspan="2" bgcolor="#CCFF66" align="center"><font size="2" face="Arial"><strong>Keterangan</strong></font></td>
  </tr>
    <tr height="25">
        <td bgcolor="#FFFFFF" align="center"><font size="2" face="Arial">
        <?=$pembayaran ?>
        </font></td>
<td bgcolor="#FFFFFF" align="center"><font size="2" face="Arial">
        <?=$byrakhir . "<br><i>" . $tglakhir . "</i>" ?>
        </font></td>
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
            <td width="140" align="right"><?=($totalbesarwjb)?></td>
        </tr>
        <tr>
            <td align="left" style="background-color: #e6f5ff">Total Semua Pembayaran</td>
            <td align="right"><?=($totalbayarwjb)?></td>
        </tr>
        <tr>
            <td align="left" style="background-color: #e6f5ff">Total Semua Diskon</td>
            <td align="right"><?=($totaldiskonwjb)?></td>
        </tr>
        <tr>
            <td align="left" style="background-color: #e6f5ff">Total Semua Sisa Tagihan</td>
            <td align="right"><?=($totalsisawjb)?></td>
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
            <td width="140" align="right"><?=($totalbayarskr)?></td>
        </tr>
        </table>
    </td>
</tr>
</table>

<?php
CloseDb();
?>

</table>

</body>
</html>
<script language="javascript">window.print();</script>