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

$idpenerimaan = 0;
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = $_REQUEST['idpenerimaan'];	
$npenerimaan = getname2('nama',$db_name_fina.'.datapenerimaan','replid',$idpenerimaan);

if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
$nperiode = LongDateFormat($tanggal1)." s.d. ".LongDateFormat($tanggal2);

$urut = "tanggal";
$urutan = "ASC";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Laporan Penerimaan Lain]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
  <td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>LAPORAN PENERIMAAN LAIN</strong></font><br />
 </center><br /><br />
<table width="100%">
<tr>
	<td width="7%" class="news_content1"><strong>Departemen</strong></td>
    <td width="93%" class="news_content1">: 
      <?=$departemen ?></td>
    </tr>
<tr>
  <td class="news_content1"><strong>Penerimaan</strong></td>
  <td class="news_content1">: 
      <?=$npenerimaan ?></td>
  </tr>
<tr>
  <td class="news_content1"><strong>Periode</strong></td>
  <td class="news_content1">:
    <?=$nperiode ?></td>
  </tr>
</table>
<br />
<?php
$sql = "SELECT nama FROM $db_name_fina.datapenerimaan WHERE replid=$idpenerimaan";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
?>
<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
<?php 
    OpenDb();
	
	$sql = "SELECT replid FROM jbsfina.tahunbuku WHERE departemen='$departemen' AND aktif=1";
	$idtahunbuku = FetchSingle($sql);
	
	$sql = "SELECT p.replid AS id, j.nokas, p.sumber, date_format(p.tanggal, '%d-%b-%Y') AS tanggal, p.keterangan, p.jumlah, p.petugas 
	          FROM $db_name_fina.penerimaanlain p, $db_name_fina.jurnal j, $db_name_fina.datapenerimaan dp 
				WHERE j.replid = p.idjurnal AND j.idtahunbuku = '$idtahunbuku'
				  AND p.idpenerimaan = dp.replid AND p.idpenerimaan = '$idpenerimaan' 
				  AND dp.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' 
		   ORDER BY $urut $urutan"; 
	
	$result = QueryDb($sql);
	//if (mysqli_num_rows($result) > 0) {
?>
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
	<tr height="30" align="center" class="header">
        <td width="5%">No</td>
        <td width="15%">No. Jurnal/Tanggal</td>
        <td width="15%">Sumber</td>
        <td width="15%">Jumlah</td>
        <td width="25%">Keterangan</td>
        <td width="10%">Petugas</td>
    </tr>
<?php 

//if ($page==0)
	$cnt = 0;
//else 
	//$cnt = (int)$page*(int)$varbaris;

$tot = 0;
while ($row = mysqli_fetch_array($result)) {
	$tot += $row['jumlah'];
?>
    <tr height="25">
        <td align="center"><?=++$cnt?></td>
        <td align="center"><?="<strong>" . $row['nokas'] . "</strong><br>" . $row['tanggal']?></td>
        <td align="left"><?=$row['sumber'] ?></td>
        <td align="right"><?=FormatRupiah($row['jumlah'])?></td>
        <td><?=$row['keterangan'] ?></td>
        <td><?=$row['petugas'] ?></td>
    </tr>
<?php
}
?>
    <input type="hidden" name="tes" id="tes" value="<?=$total?>"/>
    <tr height="35">
        <td bgcolor="#996600" colspan="3" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td bgcolor="#996600" align="right" ><font color="#FFFFFF"><strong><?=FormatRupiah($tot) ?></strong></font></td>
        <td bgcolor="#996600" colspan="3">&nbsp;</td>
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