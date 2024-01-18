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
<title>JIBAS EMA [Cetak Transaksi Keuangan]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>LAPORAN TRANSAKSI KEUANGAN</strong></font><br />
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
        //$sql_tot = "SELECT nokas, date_format(tanggal, '%d-%b-%Y') AS tanggal, petugas, transaksi, keterangan, debet, kredit FROM transaksilog WHERE departemen='$departemen' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND idtahunbuku = $idtahunbuku with ROLLUP";
        $sql_tot = "SELECT COUNT(nokas), SUM(debet) AS totdebet, SUM(kredit) AS totkredit FROM $db_name_fina.transaksilog WHERE departemen='$departemen' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND idtahunbuku = '".$idtahunbuku."'";
    
        $result_tot = QueryDb($sql_tot);
        $row_tot = mysqli_fetch_row($result_tot);
        //$jumlah = $row_tot[0];
        //$total=ceil($jumlah/(int)$varbaris);
        //$akhir = ceil($jumlah/5)*5;
        
        $totaldebet = $row_tot[1];
        $totalkredit = $row_tot[2];
        
        
        $sql = "SELECT nokas, date_format(tanggal, '%d-%b-%Y') AS tanggal, petugas, transaksi, keterangan, debet, kredit FROM $db_name_fina.transaksilog WHERE departemen='$departemen' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND idtahunbuku = '$idtahunbuku' ORDER BY tanggal";
            
        $result = QueryDb($sql);	
        if (mysqli_num_rows($result) > 0) {
    ?>    
      <input type="hidden" name="total" id="total" value="<?=$total?>"/>
      <table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="100%" align="left" bordercolor="#000000">
        <tr height="30" align="center">
            <td width="4%" class="header" >No</td>
            <td width="18%" class="header">No. Jurnal/Tanggal</td>
            <td width="10%" class="header">Petugas</td>
            <td width="*" class="header" >Transaksi</td>
            <td width="15%" class="header">Debet</td>
            <td width="15%" class="header">Kredit</td>
        </tr>
    <?php 	//if ($page==0)
                $cnt = 0;
            //else 
              //  $cnt = (int)$page*(int)$varbaris;
            
            while($row = mysqli_fetch_array($result)) {
                
    ?>
        <tr height="25">
            <td align="center" valign="top"><?=++$cnt ?></td>
            <td align="center" valign="top"><strong><?=$row['nokas'] ?></strong><br /><?=$row['tanggal'] ?></td>
            <td valign="top" align="center"><?=$row['petugas'] ?></td>
            <td align="left" valign="top"><?=$row['transaksi'] ?>
            <?php if ($row['keterangan'] <> "") { ?>
            <br /><strong>Keterangan: </strong><?=$row['keterangan'] ?>
            <?php } ?>
            </td>
            <td align="right" valign="top"><?=FormatRupiah($row['debet']) ?></td>
            <td align="right" valign="top"><?=FormatRupiah($row['kredit']) ?></td>
        </tr>
    <?php
            }
            CloseDb();
        //echo 'total '.$total.' dan '.$page;	
        //if ($total-1 == $page) {
            
            
    ?>
       
        <tr height="30">
            <td colspan="4" align="center" bgcolor="#999900">
            <font color="#FFFFFF"><strong>T O T A L</strong></font>
            </td>
            <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totaldebet) ?></strong></font></td>
            <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalkredit) ?></strong></font></td>
        </tr>
    <?php 	//} ?>
      </table>
<?php } ?>
  </td>
</tr>    
</table>
</body>
<script language="javascript">
window.print();
</script>

</html>