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

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Laporan_Neraca_Percobaan.xls');
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

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Neraca Percobaan]</title>
</head>

<body>
<center>
  <font size="4" face="Verdana"><strong>NERACA PERCOBAAN</strong></font><font face="Verdana"><br /> 
  </font>
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
	<td><font size="2" face="Arial"><strong>Tanggal </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=LongDateFormat($tanggal1) ?> 
      s/d 
      <?=LongDateFormat($tanggal2) ?>
    </strong></font></td>
</tr>
</table>
<br />
<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
    <?php 
    OpenDb();
	$sql = "SELECT ra.nama, ra.kode, k.kategori, SUM(jd.debet) AS debet, SUM(jd.kredit) AS kredit FROM rekakun ra, katerekakun k, jurnal j, jurnaldetail jd WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND ra.kategori = k.kategori AND j.idtahunbuku = '$idtahunbuku' AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY ra.nama, ra.kode, k.kategori ORDER BY k.urutan, ra.kode;";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) {
	?>
    <table class="tab" style="border-collapse:collapse" id="table" border="1" cellpadding="2"  width="100%" bordercolor="#000000" />
    <tr height="30">
        <td width="5%" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No</font></strong></td>
        <td width="8%" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Kode</font></strong></td>
        <td width="*" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Rekening</font></strong></td>
        <td width="20%" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Debet</font></strong></td>
        <td width="20%" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Kredit</font></strong></td>
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
                $debet = $debet1;
                $kredit = "$nbsp";
                $totaldebet += $debet1;
				break;
            default:
                $kredit1 = $row['kredit'] - $row['debet'];
                $kredit = $kredit1;
                $debet = "&nbsp";
				$totalkredit += $kredit1;
		}
        
        
    ?>
    <tr height="25">
        <td align="center"><font size="2" face="Arial">
        <?=++$cnt ?>
        </font></td>
        <td align="center"><font size="2" face="Arial">
        <?=$row['kode'] ?>
        </font></td>
        <td align="left"><font size="2" face="Arial">
        <?=$row['nama'] ?>
        </font></td>
        <td align="right"><font size="2" face="Arial">
        <?=$debet ?>
        </font></td>
        <td align="right"><font size="2" face="Arial">
        <?=$kredit ?>
        </font></td>
    </tr>
    <?php
    }
    CloseDb();
    ?>
    <tr height="30">
        <td colspan="3" align="center" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong>T O T A L</strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=$totaldebet ?></strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=$totalkredit ?></strong></font></td>
    </tr>
    </table>
<script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
<?php } else { ?>
    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="300">
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data transaksi keuangan pada departemen <?=$departemen?> antara tanggal <?=LongDateFormat($tanggal1)?> s/d <?=LongDateFormat($tanggal2)?>.</b></font>
            
        </td>
    </tr>
    </table>  
<?php } ?>
	</td>
</tr>
</table>


<script language="javascript">window.print();</script>

</body>
</html>