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

$kategori = "ALL";
if (isset($_REQUEST['kategori']))
	$kategori = $_REQUEST['kategori'];
if ($kategori!="ALL")	
	$nkategori = getname2('kategori',$db_name_fina.'.katerekakun','kategori',$kategori);	
else
	$nkategori = "Semua Kategori";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
$nperiode = LongDateFormat($tanggal1)." s.d. ".LongDateFormat($tanggal2);

$koderek = "";
if (isset($_REQUEST['koderek']))
	$koderek = $_REQUEST['koderek'];
$nrekening = getname2('nama',$db_name_fina.'.rekakun','kode',$koderek);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Laporan Buku Besar]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>LAPORAN BUKU BESAR</strong></font><br />
 </center><br /><br />
<table width="100%">
<tr>
	<td width="7%" class="news_content1"><strong>Departemen</strong></td>
    <td width="30%" class="news_content1">: 
      <?=$departemen ?></td>
    <td width="5%" class="news_content1"><strong>Periode</strong></td>
    <td width="58%" class="news_content1">:
<?=$nperiode ?></td>
</tr>
<tr>
  <td class="news_content1"><strong>Tahun Buku</strong></td>
  <td class="news_content1">: 
      <?=$ntahunbuku ?></td>
  <td class="news_content1"><strong>Kategori</strong></td>
  <td class="news_content1">: 
      <?=$nkategori ?></td>
</tr>
<tr>
  <td class="news_content1"><strong>Rekening</strong></td>
  <td class="news_content1">: 
      <?=$koderek ?> - <?=$nrekening ?></td>
  <td class="news_content1">&nbsp;</td>
  <td class="news_content1">&nbsp;</td>
</tr>
</table>
<br />
<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="center" bordercolor="#000000">
    <tr height="30">
        <td class="header" width="3%" align="center">No</td>
        <td class="header" width="20%" align="center">No. Jurnal/Tgl</td>
        <td class="header" width="9%" align="center">Petugas</td>
        <td class="header" width="*" align="center">Transaksi</td>
        <td class="header" width="17%" align="center">Debet</td>
        <td class="header" width="17%" align="center">Kredit</td>
    </tr>
    <?php
    OpenDb();
    $sql = "SELECT date_format(j.tanggal, '%d-%b-%Y') AS tanggal, j.petugas, j.transaksi, j.keterangan, j.nokas, jd.debet, jd.kredit FROM $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd WHERE j.replid = jd.idjurnal AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND jd.koderek = '$koderek' ORDER BY j.tanggal, j.petugas";
    $result = QueryDb($sql);
    $cnt = 0;
    $totaldebet = 0;
    $totalkredit = 0;
    while($row = mysqli_fetch_array($result)) {
        $totaldebet += $row['debet'];
        $totalkredit += $row['kredit'];
    ?>
    <tr height="25">
        <td valign="top" align="center"><?=++$cnt ?></td>
        <td valign="top" align="center"><strong><?=$row['nokas'] ?></strong><br /><em><?=$row['tanggal'] ?></em></td>
        <td valign="top" align="left"><?=$row['petugas'] ?></td>
        <td valign="top" align="left"><?=$row['transaksi'] ?><br />
        <?php if ($row['keterangan'] <> "") { ?>
        <strong>Keterangan: </strong><?=$row['keterangan'] ?>
        <?php } ?>
        </td>
        <td valign="top" align="right"><?=FormatRupiah($row['debet']) ?></td>
        <td valign="top" align="right"><?=FormatRupiah($row['kredit']) ?></td>
    </tr>
    <?php
    }
    CloseDb();
    ?>
    <tr height="30">
        <td colspan="4" align="center" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totaldebet) ?></strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalkredit) ?></strong></font></td>
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