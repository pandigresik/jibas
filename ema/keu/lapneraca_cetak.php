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
<title>JIBAS EMA [Cetak Laporan Neraca]</title>
<style type="text/css">
<!--
.style1 {color: #000000}
-->
</style>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
  <td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>LAPORAN NERACA</strong></font><br />
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
<?php
	OpenDb();
	$sql = "SELECT jd.koderek, ra.nama, sum(jd.debet - jd.kredit) FROM $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd, $db_name_fina.rekakun ra WHERE j.replid = jd.idjurnal AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori IN ('HARTA', 'PIUTANG') GROUP BY jd.koderek, ra.nama ORDER BY jd.koderek";
	$result = QueryDb($sql);   
	//if (mysqli_num_rows($result) > 0) {
?>    
    <table border="0" width="100%" cellpadding="10" cellspacing="5" align="center" background="../img/bttablelong.png">
    <tr>
        <td width="50%" valign="top">
        	<font size="2"><strong>HARTA</strong></font><br />
            <table border="0" style="border-collapse:collapse" cellpadding="2" width="100%" align="center">
            <tr height="28">
                <td width="2%">&nbsp;</td>
                <td colspan="6"><strong>AKTIVA LANCAR</strong><br /></td>
            </tr>
            <?php
            
            $totalaktivalancar = 0;
            while ($row = mysqli_fetch_row($result)) {
                $totalaktivalancar += (float)$row[2];
            ?>
            <tr height="23">
                <td width="2%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
                <td width="5%" align="left"><?=$row[0] ?></td>
                <td width="*" align="left"><?=$row[1] ?></td>
                <td width="28%" align="right"><?=FormatRupiah($row[2]) ?></td>
                <td width="30%"  align="right">&nbsp;</td>
                <td width="13">&nbsp;</td>
            </tr>
            <?php
            }
            ?>
            <tr height="23">
                <td width="2%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
                <td colspan="3" align="left"><strong><em>Sub Total Aktiva Lancar:</em></strong><br /></td>
                <td align="right"><span class="news_title2 style1"><?=FormatRupiah($totalaktivalancar) ?></span></td>
                <td>&nbsp;</td>
            </tr>
            </table>
            <br />
    
            <table border="0" style="border-collapse:collapse" cellpadding="2" width="100%" align="center">
            <tr height="28">
                <td width="2%">&nbsp;</td>
                <td colspan="6"><strong>AKTIVA TETAP</strong><br /></td>
            </tr>
            <?php
            $sql = "SELECT jd.koderek, ra.nama, sum(jd.debet - jd.kredit) FROM $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd, $db_name_fina.rekakun ra WHERE j.replid = jd.idjurnal AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori = 'INVENTARIS' GROUP BY jd.koderek, ra.nama ORDER BY jd.koderek";
            $result = QueryDb($sql);
            $totalaktivatetap = 0;
            while ($row = mysqli_fetch_row($result)) {
                $totalaktivatetap += (float)$row[2];
            ?>
            <tr height="23">
                <td width="2%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
                <td width="5%" align="left"><?=$row[0] ?></td>

                <td width="*" align="left"><?=$row[1] ?></td>
                <td width="28%" align="right"><?=FormatRupiah($row[2]) ?></td>
                <td width="30%"  align="right">&nbsp;</td>
                <td width="13">&nbsp;</td>
            </tr>
            <?php
            }
            ?>
            <tr height="23">
                <td width="2%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
                <td colspan="3" align="left"><strong><em>Sub Total Aktiva Tetap:</em></strong><br /></td>
                <td align="right"><span class="news_title2 style1"><?=FormatRupiah($totalaktivatetap) ?></strong></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6"><hr width="100%" style="border-style:dashed" /></td>
                <td align="right">+</td>
            </tr>
            <tr height="28">
                <td colspan="5" align="left"><font size="2"><strong>TOTAL HARTA</strong></font><br /></td>
                <td align="right"><font size="2"><span class="news_title2 style1"><?=FormatRupiah($totalaktivatetap + $totalaktivalancar) ?></strong></font></td>
                <td >&nbsp;</td>
            </tr>
            </table>
        </td>
        <td width="50%" valign="top">
        	<font size="2"><strong>KEWAJIBAN</strong></font><br />
            <table border="0" style="border-collapse:collapse" cellpadding="2" width="100%" align="center">
            <tr height="28">
                <td width="2%">&nbsp;</td>
                <td colspan="6"><strong>HUTANG</strong><br /></td>
            </tr>
            <?php
            $sql = "SELECT jd.koderek, ra.nama, sum(jd.kredit - jd.debet) FROM $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd, $db_name_fina.rekakun ra WHERE j.replid = jd.idjurnal AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori = 'UTANG' GROUP BY jd.koderek, ra.nama ORDER BY jd.koderek";
            $result = QueryDb($sql);
            $totalhutang = 0;
            while ($row = mysqli_fetch_row($result)) {
                $totalhutang += (float)$row[2];
            ?>
            <tr height="23">
                <td width="2%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
                <td width="5%" align="left"><?=$row[0] ?></td>
                <td width="*" align="left"><?=$row[1] ?></td>
                <td width="28%" align="right"><?=FormatRupiah($row[2]) ?></td>
                <td width="30%"  align="right">&nbsp;</td>
                <td width="13">&nbsp;</td>
            </tr>
            <?php
            }
            ?>
            <tr height="23">
                <td width="2%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
                <td colspan="3" align="left"><strong><em>Sub Total Hutang:</em></strong><br /></td>
                <td align="right"><span class="news_title2 style1"><?=FormatRupiah($totalhutang) ?></span></td>
                <td>&nbsp;</td>
            </tr>
            </table>
            <br />
            <table  border="0" style="border-collapse:collapse" cellpadding="2" width="100%" align="center">
            <tr height="28">
                <td width="2%">&nbsp;</td>
                <td colspan="6"><strong>MODAL</strong><br /></td>
            </tr>
            <?php
            $sql = "SELECT tanggalmulai FROM $db_name_fina.tahunbuku WHERE replid = '".$idtahunbuku."'";
            $result = QueryDb($sql);
            $row = mysqli_fetch_row($result);
            $tanggal1 = $row[0];
            
            $sql = "SELECT SUM(jd.kredit - jd.debet) FROM $db_name_fina.rekakun ra,
            $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori IN ('PENDAPATAN', 'MODAL')";
            //echo "$sql<br>";
            $result = QueryDb($sql);
            $row = mysqli_fetch_row($result);
            $totalpendapatan = (float)$row[0];
            //echo "$totalpendapatan<br>";
            
            $sql = "SELECT SUM(jd.debet - jd.kredit) FROM $db_name_fina.rekakun ra, $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori = 'BIAYA'";
            //echo "$sql<br>";
            $result = QueryDb($sql);
            $row = mysqli_fetch_row($result);
            $totalbiaya = (float)$row[0];
            //echo "$totalbiaya<br>";
            $modalusaha = $totalpendapatan - $totalbiaya;
            //echo "$modalusaha<br>"; ?>
            <tr height="23">
                <td width="2%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
                <td width="5%" align="left">&nbsp;</td>
                <td width="*" align="left">Modal Usaha</td>
                <td width="28%" align="right"><?=FormatRupiah($modalusaha) ?></td>
                <td width="30%"  align="right">&nbsp;</td>
                <td width="13">&nbsp;</td>
            </tr>
            <tr height="23">
                <td width="2%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
                <td colspan="3" align="left"><strong><em>Sub Total Modal Usaha:</em></strong><br /></td>
                <td align="right"><span class="news_title2 style1"><?=FormatRupiah($modalusaha) ?></span></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6"><hr width="100%" style="border-style:dashed" /></td>
                <td align="right">+</td>
            </tr>
            <tr height="28">
                <td colspan="5" align="left"><font size="2"><strong>TOTAL KEWAJIBAN DAN MODAL</strong></font><br /></td>
                <td align="right"><font size="2"><span class="news_title2 style1"><?=FormatRupiah($modalusaha + $totalhutang) ?></span></font></td>
                <td>&nbsp;</td>
            </tr>
            </table>
        </td>
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