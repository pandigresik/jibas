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
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$idtahunbuku = "";
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];


$ndepartemen = $departemen;
$ntahunbuku = getname2('tahunbuku',$db_name_fina.'.tahunbuku','replid',$idtahunbuku);
$nperiode = LongDateFormat($tanggal1)." s.d. ".LongDateFormat($tanggal2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA [Daftar Pengeluaran]</title>
</head>

<body>

<table border="0" cellpadding="10" cellspacing="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
<?php getHeader($departemen) ?>
	
<center>
  <font size="4"><strong>DAFTAR LAPORAN PENGELUARAN</strong></font><br />
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
<?php  OpenDb();   
            $sql = "SELECT d.replid AS id, d.nama, SUM(p.jumlah) AS jumlah FROM $db_name_fina.pengeluaran p, $db_name_fina.datapengeluaran d WHERE p.idpengeluaran = d.replid AND d.departemen = '$departemen' AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY d.replid, d.nama ORDER BY d.nama";
            
            $result = QueryDb($sql);    
            if (mysqli_num_rows($result) > 0) {   
        ?>    
            <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
            <tr height="30" align="center">
                <td width="10%" class="header">No</td>
                <td width="50%" class="header">Pengeluaran</td>
              <td width="*" class="header">Jumlah</td>
            </tr>
            <?php
            
            $cnt = 0;
            $total = 0;
            while ($row = mysqli_fetch_array($result)) {
                $total += $row['jumlah'];
            ?>
            <tr height="25" onclicks="show_detail(<?=$row['id'] ?>)" styles="cursor:pointer">
                <td align="center"><?=++$cnt ?></td>
                <td align="left"><strong><?=$row['nama'] ?></strong></td>
              <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
            </tr>
            <?php
            }
            CloseDb();
            ?>
            <tr height="30">
                <td bgcolor="#999900" colspan="2" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
              <td bgcolor="#999900" align="right">
              <font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font>		</td>
            </tr>
            </table>
      <script language='JavaScript'>
                Tables('table', 1, 0);
            </script>
           
        
        <?php } else { ?>	
        
            <table width="100%" border="0" align="center">          
            <tr>
                <td align="center" valign="middle" height="300">    
                    <span class="err">Tidak ditemukan adanya data.</span>
              </td>
            </tr>
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