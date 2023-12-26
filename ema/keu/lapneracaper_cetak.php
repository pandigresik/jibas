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
<title>JIBAS EMA [Cetak Laporan Neraca Percobaan]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
  <td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>LAPORAN NERACA PERCOBAAN</strong></font><br />
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
	$sql = "SELECT ra.nama, ra.kode, k.kategori, SUM(jd.debet) AS debet, SUM(jd.kredit) AS kredit FROM $db_name_fina.rekakun ra, $db_name_fina.katerekakun k, $db_name_fina.jurnal j, $db_name_fina.jurnaldetail jd WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND ra.kategori = k.kategori AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY ra.nama, ra.kode, k.kategori ORDER BY k.urutan, ra.kode;";
	$result = QueryDb($sql);
	//if (mysqli_num_rows($result) > 0) {
	?>
    <table class="tab" style="border-collapse:collapse" id="table" border="1" cellpadding="2"  width="100%" bordercolor="#000000" />
    <tr height="30">
        <td class="header" width="5%" align="center">No</td>
        <td class="header" width="8%" align="center">Kode</td>
        <td class="header" width="*" align="center">Rekening</td>
        <td class="header" width="20%" align="center">Debet</td>
        <td class="header" width="20%" align="center">Kredit</td>
    </tr>
	<?php
    $cnt = 0;
    $totaldebet = 0;
    $totalkredit = 0;
    while($row = mysqli_fetch_array($result)) {
        $kategori = $row['kategori'];
        switch($kategori) {
            case 'HARTA':
			case 'PIUTANG':
            case 'INVENTARIS':
            case 'BIAYA':
                $debet1 = $row['debet'] - $row['kredit'];
                $debet = FormatRupiah($debet1);
                $kredit = "$nbsp";
				$totaldebet += $debet1;
                break;
            default:
                $kredit1 = $row['kredit'] - $row['debet'];
                $kredit = FormatRupiah($kredit1);
                $debet = "&nbsp";
				$totalkredit += $kredit1;
        }
        
        
    ?>
    <tr height="25">
        <td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['kode'] ?></td>
        <td align="left"><?=$row['nama'] ?></td>
        <td align="right"><?=$debet ?></td>
        <td align="right"><?=$kredit ?></td>
    </tr>
    <?php
    }
    CloseDb();
    ?>
    <tr height="30">
        <td colspan="3" align="center" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
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