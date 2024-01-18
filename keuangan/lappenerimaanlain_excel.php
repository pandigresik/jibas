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
header('Content-Disposition: attachment; filename=Laporan_Penerimaan_Lain.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');


$urut=$_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
	
$idpenerimaan = 0;
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Penerimaan Lain</title>
</head>

<body>
<?php
OpenDb();
$sql = "SELECT nama FROM datapenerimaan WHERE replid= $idpenerimaan";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
?>

<center><font size="4" face="Verdana"><strong>LAPORAN PENERIMAAN <?=strtoupper((string) $namapenerimaan) ?></strong></font><br /> 
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
	<td width="90"><font size="2" face="Arial"><strong>Tanggal </strong></font></td>
    <td><font size="2" face="Arial"><strong>:  
      <?=LongDateFormat($tanggal1) . " s/d " . LongDateFormat($tanggal2) ?>
    </strong></font></td>
</tr>
</table>
<br />

<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
<tr height="30" align="center">
	<td width="5%" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No</font></strong></td>
    <td width="15%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Tanggal</font></strong></td>
  	<td width="15%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Sumber</font></strong></td>
    <td width="15%" align="right" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Jumlah</font></strong></td>
    <td width="25%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Keterangan</font></strong></td>
    <td width="10%" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Petugas</font></strong></td>
</tr>
<?php 
OpenDb();
$sql = "SELECT p.replid AS id, j.nokas, p.sumber, date_format(p.tanggal, '%d-%b-%Y') AS tanggal, p.keterangan, p.jumlah, p.petugas FROM penerimaanlain p, jurnal j, datapenerimaan dp WHERE j.replid = p.idjurnal AND p.idpenerimaan = dp.replid AND p.idpenerimaan = '$idpenerimaan' AND dp.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY $urut $urutan "; 
//echo  $sql;
$result = QueryDb($sql);
$cnt = 0;
$tot = 0;
while ($row = mysqli_fetch_array($result)) {
	$bg1="#ffffff";
	if ($cnt==0 || $cnt%2==0)
		$bg1="#fcffd3";
	$tot += $row['jumlah'];
?>
<tr height="25" bgcolor="<?=$bg1?>">
	<td align="center"><font size="2" face="Arial">
	  <?=++$cnt?>
	</font></td>
    <td align="center"><font size="2" face="Arial">
      <?="<strong>" . $row['nokas'] . "</strong><br>" . $row['tanggal']?>
    </font></td>
    <td align="left"><font size="2" face="Arial">
      <?=$row['sumber'] ?>
    </font></td>
    <td align="right"><font size="2" face="Arial">
      <?=$row['jumlah']?>
    </font></td>
    <td><font size="2" face="Arial">
      <?=$row['keterangan'] ?>
    </font></td>
    <td><font size="2" face="Arial">
      <?=$row['petugas'] ?>
    </font></td>
</tr>
<?php
}
?>
<tr height="35">
	<td bgcolor="#996600" colspan="3" align="center"><font color="#FFFFFF" size="2" face="Arial"><strong>T O T A L</strong></font></td>
    <td bgcolor="#996600" align="right"><font color="#FFFFFF" size="2" face="Arial"><strong><?=$tot ?></strong></font></td>
    <td bgcolor="#996600" colspan="2">&nbsp;</td>
</tr>
</table>


</td>
</tr>
    </table>
</body>
</html>
<script language="javascript">window.print();</script>