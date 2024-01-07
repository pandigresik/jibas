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
require_once('library/jurnal.php');
require_once('library/repairdatajttcalon.php');

/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Laporan_Tunggakan_Iuran_Wajib_Calon_Siswa_setiap_Kelompok.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$urut=$_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];

$idpenerimaan = 0;
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
	
$kelompok = -1;
if (isset($_REQUEST['kelompok']))
	$kelompok = (int)$_REQUEST['kelompok'];
	
if (isset($_REQUEST['lunas']))
	$statuslunas = (int)$_REQUEST['lunas'];

OpenDb();

$sql = "SELECT departemen FROM datapenerimaan WHERE replid='$idpenerimaan'";
$departemen = FetchSingle($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Pembayaran Iuran Wajib Calon Siswa Per Kelompok]</title>
</head>

<body>
<?php
// Dapatkan banyaknya pembayaran yang telah terjadi untuk pembayaran terpilih di kelas terpilih
if ($statuslunas == -1)
	if ($kelompok == -1)
		$sql = "SELECT MAX(jumlah) 
		          FROM ((SELECT c.replid, count(p.replid) as jumlah 
								 FROM penerimaanjttcalon p, besarjttcalon b, jbsakad.calonsiswa c 
								WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku'
								  AND b.idpenerimaan = '$idpenerimaan' GROUP BY c.replid) AS x);";
	else
		$sql = "SELECT MAX(jumlah) 
		          FROM ((SELECT c.replid, count(p.replid) as jumlah 
								 FROM penerimaanjttcalon p, besarjttcalon b, jbsakad.calonsiswa c 
							   WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku'
								  AND c.idkelompok = '$kelompok' AND b.idpenerimaan = '$idpenerimaan' GROUP BY c.replid) AS x);";
else
	if ($kelompok == -1)
		$sql = "SELECT MAX(jumlah) 
		          FROM ((SELECT c.replid, count(p.replid) as jumlah 
							    FROM penerimaanjttcalon p, besarjttcalon b, jbsakad.calonsiswa c 
								WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku' 
								 AND b.idpenerimaan = '$idpenerimaan' AND b.lunas = '$statuslunas' GROUP BY c.replid) AS x);";
	else
		$sql = "SELECT MAX(jumlah) 
		          FROM ((SELECT c.replid, count(p.replid) as jumlah 
								 FROM penerimaanjttcalon p, besarjttcalon b, jbsakad.calonsiswa c 
								WHERE p.idbesarjttcalon = b.replid AND b.idcalon = c.replid AND b.info2='$idtahunbuku' 
								  AND c.idkelompok = '$kelompok' AND b.idpenerimaan = '$idpenerimaan' AND b.lunas = '$statuslunas' GROUP BY c.replid) AS x);";

$result = QueryDb($sql);
$max_n_cicilan = FetchSingle($sql);
$table_width = 810 + $max_n_cicilan * 90;

$sql = "SELECT nama FROM datapenerimaan WHERE replid='$idpenerimaan'";
$namapenerimaan = FetchSingle($sql);
?>
<center><font size="4"><strong><font face="Verdana">LAPORAN PEMBAYARAN IURAN WAJIB CALON SISWA</font></strong></font><font face="Verdana"><br /> 
  </font>
</center>
<br /><br />
<br />
<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
<tr height="30" align="center">
	<td width="30" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No</font></strong></td>
    <td width="80" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No. Reg</font></strong></td>
    <td width="140" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Nama</font></strong></td>
    <td width="50" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Kel</font></strong></td>
<?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
			$n = $i + 1; ?>
    		<td width="120" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">
   		    <?="Bayaran-$n" ?>
    		</font></strong></td>	
    <?php  } ?>
    <td width="80" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Status</font></strong></td>
    <td width="125" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">
      <?=$namapenerimaan ?>
    </font></strong></td>
    <td width="125" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Total Besar Pembayaran</font></strong></td>
    <td width="125" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Total Diskon</font></strong></td>
    <td width="125" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Total Tunggakan</font></strong></td>
    <td width="200" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Keterangan</font></strong></td>
</tr>

<?php
if ($statuslunas == -1) 
{
	if ($kelompok == -1) 
	{
		$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya
						    FROM besarjttcalon b, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k 
						   WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' AND c.idkelompok = k.replid";
								  
		$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon 
								   FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k 
								  WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' AND c.idkelompok = k.replid";
					 
		$sql_tot = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
		              FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
					 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' AND c.idkelompok = k.replid 
				 	 ORDER BY c.nama";
		
		$sql = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
		          FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
				 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
				   AND c.idkelompok = k.replid ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	} 
	else 
	{
		$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya
							 FROM besarjttcalon b, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
							WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
							 AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid ORDER BY c.nama";
								 
		$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon  
								 FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
								WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
								 AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid ORDER BY c.nama";
					   
		$sql_tot = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
		              FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
				 	 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
					   AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid ORDER BY c.nama";
		
		$sql = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
		          FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
				 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' 
				   AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	}
} 
else 
{
	if ($kelompok == -1) 
	{
		$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya  
							FROM besarjttcalon b, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
							WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
							AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
									
		$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon  
									FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
									WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
									AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
						
		$sql_tot = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
					  FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
					 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
					   AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
	
		$sql = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
					 FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
					WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku' 
					  AND c.idkelompok = k.replid AND b.lunas = '$statuslunas' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	} 
	else 
	{
		$sql_sum_biaya = "SELECT SUM(b.besar) AS TotalBiaya 
							FROM besarjttcalon b, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
						   WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
							 AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
								  
		$sql_sum_bayar_diskon = "SELECT SUM(p.jumlah) AS TotalBayar, SUM(p.info1) AS TotalDiskon
								FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
								 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
								  AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'";
						   
		$sql_tot = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas
		              FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
						 WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
						   AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid AND b.lunas = '".$statuslunas."'"; 
		
		$sql = "SELECT DISTINCT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
		          FROM penerimaanjttcalon p RIGHT JOIN besarjttcalon b ON p.idbesarjttcalon = b.replid, jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k
					WHERE c.replid = b.idcalon AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
					  AND c.idkelompok = '$kelompok' AND c.idkelompok = k.replid AND b.lunas = '$statuslunas' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	}
}

$result = QueryDb($sql);
$cnt = 0;

while ($row = mysqli_fetch_array($result))
{
	$bg1="#ffffff";
	if ($cnt==0 || $cnt%2==0)
		$bg1="#fcffd3";
	$idbesarjtt = $row['id'];
	$besarjtt = $row['besar'];
	$ketjtt = $row['keterangan'];
	$lunasjtt = $row['lunas'];
	if ($lunasjtt == 1)
		$infojtt = "<font color=blue><strong>Lunas</strong></font>";
	elseif ($lunasjtt == 2)
		$infojtt = "<font color=green><strong>Gratis</strong></font>";
	else
		$infojtt = "<font color=red><strong>Belum Lunas</strong></font>";
?>
<tr height="40" bgcolor="<?=$bg1?>">
	<td align="center">	  <font size="2" face="Arial">
	  <?=++$cnt ?>	
    </font></td>
    <td align="center">      <font size="2" face="Arial">
      <?=$row['nopendaftaran'] ?>    
    </font></td>
    <td>      <font size="2" face="Arial">
      <?=$row['nama'] ?>    
    </font></td>
    <td align="center">      <font size="2" face="Arial">
      <?=$row['kelompok'] ?>    
    </font></td>
<?php
	$sql = "SELECT count(*) FROM penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	$nbayar = $row2[0];
	$nblank = $max_n_cicilan - $nbayar;
    
	$totalbayar = 0;
    $totaldiskon = 0;
	if ($nbayar > 0)
    {
		$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah, info1 FROM penerimaanjttcalon WHERE idbesarjttcalon = '$idbesarjtt' ORDER BY tanggal";
		$result2 = QueryDb($sql);
		$x = 0;
		while ($row2 = mysqli_fetch_row($result2))
        {
			$bg2=$bg1;
			if ($x%2==0 || $x==0)
				$bg2="#d3fffd";
			$totalbayar += $row2[1] + $row2[2];
            $totaldiskon += $row2[2]; ?>
            <td bgcolor="<?=$bg2?>">
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000">
                <tr height="20"><td align="center">                  <font size="2" face="Arial">
                <?=$row2[1] ?>
                </font></td></tr>
                <tr height="20"><td align="center">                  <font size="2" face="Arial">
                <?=$row2[0] ?>
                </font></td></tr>
    </table>            </td>
<?php 	$x++;
		}
 		$totalbayarall += $totalbayar;
        $totaldiskonall += $totaldiskon;
	}	
	for ($i = 0; $i < $nblank; $i++) { ?>
	    <td>
            <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000">
            <tr height="20"><td align="center">&nbsp;</td></tr>
            <tr height="20"><td align="center">&nbsp;</td></tr>
    </table>        </td>
    <?php }?>
    <td align="center">      <font size="2" face="Arial">
      <?=$infojtt ?>    
    </font></td>
    <td align="right">      <font size="2" face="Arial">
      <?=$besarjtt ?>    
    </font></td>
    <td align="right">      <font size="2" face="Arial">
      <?=$totalbayar ?>    
    </font></td>
    <td align="right">      <font size="2" face="Arial">
      <?=$totaldiskon ?>    
    </font></td>
    <td align="right">      <font size="2" face="Arial">
      <?=$besarjtt - $totalbayar?>    
    </font></td>
    <td>      <font size="2" face="Arial">
      <?=$ketjtt ?>    
    </font></td>
</tr>
<?php
}

    $totalBiayaAll = FetchSingle($sql_sum_biaya);
	
	$row = FetchSingleRow($sql_sum_bayar_diskon);
	$totalBayarAll = $row[0] + $row[1];
	$totalDiskonAll = $row[1];
?>
<tr height="40">
	<td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong>T O T A L</strong></font></td>
	<td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=$totalBiayaAll ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=$totalBayarAll ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=$totalDiskonAll ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=$totalBiayaAll - $totalBayarAll?></strong></font></td>
    <td bgcolor="#999900">&nbsp;</td>
</tr>
</table>
<?php CloseDb() ?>

</td>
</tr>
</table>
</body>
</html>
<script language="javascript">window.print();</script>