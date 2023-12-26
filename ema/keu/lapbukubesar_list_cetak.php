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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Cetak Daftar Buku Besar]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>DAFTAR BUKU BESAR</strong></font><br />
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
</table>
<br />
<?php 	OpenDb();
            if ($kategori != "ALL")
                $sql = "SELECT r.nama, r.kode, sum(jd.debet), sum(jd.kredit) FROM $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd, $db_name_fina.rekakun r WHERE j.replid = jd.idjurnal AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND  jd.koderek = r.kode AND r.kategori = '$kategori' GROUP BY r.nama, r.kode ORDER BY r.nama";
            else
                $sql = "SELECT r.nama, r.kode, sum(jd.debet), sum(jd.kredit) FROM $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd, $db_name_fina.rekakun r WHERE j.replid = jd.idjurnal AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND  jd.koderek = r.kode GROUP BY r.nama, r.kode ORDER BY r.nama";
                
            $result = QueryDb($sql);
        ?>
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
            <tr height="25">
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
  </td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>

</html>