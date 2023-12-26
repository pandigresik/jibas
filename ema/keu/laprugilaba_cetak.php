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
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$ndepartemen = $departemen;
	
$idtahunbuku = "";
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];
$ntahunbuku = getname2('tahunbuku',$db_name_fina.'.tahunbuku','replid',$idtahunbuku);

if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
$nperiode = LongDateFormat($tanggal1)." s.d. ".LongDateFormat($tanggal2);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Laporan Rugi Laba]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
  <td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>LAPORAN RUGI LABA</strong></font><br />
 </center><br /><br />
<table width="100%">
<tr>
	<td width="7%" class="news_content1"><strong>Departemen</strong></td>
    <td width="93%" class="news_content1">: 
      <?=$departemen ?></td>
    </tr>
<tr>
  <td class="news_content1"><strong>Tahun Buku</strong></td>
  <td class="news_content1">: 
      <?=$ntahunbuku ?></td>
  </tr>
<tr>
  <td class="news_content1"><strong>Periode</strong></td>
  <td class="news_content1">:
    <?=$nperiode ?></td>
  </tr>
</table>
<br />
<?php OpenDb();
   	$sql = "SELECT nama, kode, SUM(debet) AS debet, SUM(kredit) As kredit FROM (( 
    SELECT DISTINCT j.replid, ra.nama, ra.kode, jd.debet, jd.kredit FROM $db_name_fina.rekakun ra, $db_name_fina.katerekakun k,
    $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori = 'PENDAPATAN' GROUP BY j.replid, ra.nama, ra.kode ORDER BY ra.kode) AS X) GROUP BY nama, kode";
    
    $result = QueryDb($sql);
	
	$sql1 = "SELECT nama, kode, SUM(debet) AS debet, SUM(kredit) As kredit FROM (( 
    SELECT DISTINCT j.replid, ra.nama, ra.kode, jd.debet, jd.kredit FROM $db_name_fina.rekakun ra, $db_name_fina.katerekakun k,
    $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori = 'BIAYA' GROUP BY j.replid, ra.nama, ra.kode ORDER BY ra.kode) AS X) GROUP BY nama, kode";
    
    $result1 = QueryDb($sql1);
	if ((mysqli_num_rows($result) > 0) || (mysqli_num_rows($result1) > 0)) {
		
	
?>
    <table border="0" cellpadding="5" cellspacing="5" width="80%" style="background-image:url(../img/bttablelong.png); background-repeat:repeat-x" bgcolor="#eef5dd" align="center">
    <tr height="30">
        <td colspan="6"><strong><font size="2">PENDAPATAN</font></strong></td>
    </tr>
    <?php
  	$cnt = 0;
	$totalpendapatan = 0;
	if (mysqli_num_rows($result) >0) {
		while($row = mysqli_fetch_array($result)) {
			$debet = $row['kredit'] - $row['debet'];
			$debet = FormatRupiah($debet);
			$kredit = "$nbsp";
			
			$totalpendapatan += ($row['kredit'] - $row['debet']);
    ?>
    <tr height="25">
        <td width="2%" align="right">&nbsp;</td>
        <td width="5%" align="left" valign="top"><?=$row['kode'] ?></td>
        <td align="left" width="*" valign="top"><?=$row['nama'] ?></td>
        <td align="right" width="18%" valign="top"><?=$debet ?></td> 
        <td align="right" width="18%" valign="top"><?=$kredit ?></td>
        <td width="20%">&nbsp;</td>
    </tr>
    <?php } //end while  
	}
	?>
    <tr height="30">
        <td>&nbsp;</td>
        <td colspan="4"><strong>SUB TOTAL PENDAPATAN</strong></td>
        <td align="right"><strong><?=FormatRupiah($totalpendapatan) ?></strong></td>
    </tr>
    <tr height="5">
        <td colspan="6">&nbsp;</td>
    </tr>
    <tr height="30">
        <td colspan="6"><strong><font size="2">BIAYA</font></strong></td>
    </tr>
    <?php
   
    $cnt = 0;
    $totalbiaya = 0;
	//if (mysqli_num_rows($result1) >0) {
		while($row = mysqli_fetch_array($result1)) {
			$kredit = $row['debet'] - $row['kredit'];
			$kredit = FormatRupiah($kredit);
			$debet = "$nbsp";
			
			$totalbiaya += ($row['debet'] - $row['kredit']);
    ?>
    <tr height="25">
        <td width="2%" align="right">&nbsp;</td>
        <td width="5%" align="left" valign="top"><?=$row['kode'] ?></td>
        <td align="left" width="*" valign="top"><?=$row['nama'] ?></td>
        <td align="right" width="18%" valign="top"><?=$debet ?></td> 
        <td align="right" width="18%" valign="top"><?=$kredit ?></td>
        <td width="20%">&nbsp;</td>
    </tr>
    <?php  } //end while  
	}
	?>
    
    <tr height="30">
        <td>&nbsp;</td>
        <td colspan="4"><strong>SUB TOTAL BIAYA</strong></td>
        <td align="right"><strong><?=FormatRupiah($totalbiaya) ?></strong></td>
    </tr>
    <tr height="5">
        <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">
        	<strong><font size="4"><?php if ($totalpendapatan < $totalbiaya) echo "RUGI"; else echo "LABA"; ?>
            </font></strong></td>
        <td colspan="2" align="right"><strong><font size="4"><?=FormatRupiah($totalpendapatan - $totalbiaya) ?></font></strong></td>
    </tr>
    </table>
  </td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>

</html>