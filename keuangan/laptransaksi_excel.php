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

/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Laporan_Transaksi.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Transaksi]</title>
</head>

<body>
<center><font size="4" face="Verdana"><strong>LAPORAN TRANSAKSI</strong></font><br /> 
</center>
<br /><br />
<table border="0">
<tr>
	<td width="90"><font size="2" face="Arial"><strong>Departemen </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$departemen ?>
    </strong></font></td>
</tr>
<tr>
	<td width="90"><font size="2" face="Arial"><strong>Tahun Buku </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?php  OpenDb();
		$sql = "SELECT tahunbuku FROM tahunbuku WHERE replid = $idtahunbuku";
	   	$result = QueryDb($sql);
	   	$row = mysqli_fetch_row($result);
	   	echo  $row[0];
    ?>
	</strong></font></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><strong>Tanggal </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=LongDateFormat($tanggal1) ?> 
      s/d 
      <?=LongDateFormat($tanggal2) ?>
    </strong></font></td>
</tr>
</table>
<br />
<table class="tab" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="100%" align="left" bordercolor="#000000">
<tr height="30" align="center">
	<td width="4%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No</font></strong></td>
    <td width="18%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No. Jurnal/Tanggal</font></strong></td>
    <td width="8%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Petugas</font></strong></td>
    <td width="*" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Transaksi</font></strong></td>
    <td width="15%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Debet</font></strong></td>
    <td width="15%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Kredit</font></strong></td>
</tr>
<?php
OpenDb();
$sql = "SELECT nokas, date_format(tanggal, '%d-%b-%Y') AS tanggal, petugas, transaksi, keterangan, debet, kredit 
          FROM transaksilog 
         WHERE departemen='$departemen' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND idtahunbuku = '$idtahunbuku'
         ORDER BY nokas DESC";
$result = QueryDb($sql);
$cnt = 0;
$totaldebet = 0;
$totalkredit = 0;
while($row = mysqli_fetch_array($result)) {
	$bg1="#ffffff";
	if ($cnt==0 || $cnt%2==0)
		$bg1="#fcffd3";
	$totaldebet += $row['debet'];
	$totalkredit += $row['kredit'];
?>
<tr height="25" bgcolor="<?=$bg1?>">
	<td align="center" valign="top"><font size="2" face="Arial">
	  <?=++$cnt ?>
	</font></td>
    <td align="center" valign="top"><font size="2" face="Arial"><strong>
      <?=$row['nokas'] ?>
    </strong><br />
    <?=$row['tanggal'] ?>
    </font></td>
    <td align="center" valign="top"><font size="2" face="Arial">
      <?=$row['petugas'] ?>
    </font></td>
    <td align="left" valign="top"><font size="2" face="Arial">
      <?=$row['transaksi'] ?>
      <?php if ($row['keterangan'] <> "") { ?>
      <br />
      <strong>Keterangan: </strong>
      <?=$row['keterangan'] ?>
      <?php } ?>    
    </font></td>
    <td align="right" valign="top"><font size="2" face="Arial">
      <?=$row['debet'] ?>
    </font></td>
    <td align="right" valign="top"><font size="2" face="Arial">
      <?=$row['kredit'] ?>
    </font></td>
</tr>
<?php
}
CloseDb();
?>
<tr height="30">
	<td colspan="4" align="center" bgcolor="#999900">
    <font color="#FFFFFF" size="2" face="Arial"><strong>T O T A L</strong></font>    </td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=$totaldebet ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=$totalkredit ?></strong></font></td>
</tr>
</table>

</td></tr></table>

<script language="javascript">window.print();</script>

</body>
</html>