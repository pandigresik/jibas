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
header('Content-Disposition: attachment; filename=Laporan_Tunggakan_Iuran_Sukarela_Calon_Siswa_setiap_Kelompok.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');


$urut=$_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

$idpenerimaan = 0;
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
	
$kelompok = -1;
if (isset($_REQUEST['kelompok']))
	$kelompok = (int)$_REQUEST['kelompok'];
OpenDb();
$sql = "SELECT departemen FROM datapenerimaan WHERE replid='$idpenerimaan'";
$result = QueryDb($sql);
$r = @mysqli_fetch_array($result);
$departemen = $r['departemen'];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Pembayaran Iuran Sukarela Calon Siswa Per Kelompok</title>
</head>

<body>

<?php
OpenDb();

$sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
$idtahunbuku = FetchSingle($sql);

if ($kelompok == -1)
	$sql = "SELECT max(jml) FROM ((SELECT s.replid, COUNT(p.replid) as jml 
									 FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s 
									WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
									  AND p.idcalon = s.replid AND p.idpenerimaan = '$idpenerimaan' GROUP BY s.replid) as X)";
else
	$sql = "SELECT max(jml) FROM ((SELECT s.replid, COUNT(p.replid) as jml 
									 FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s 
									WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
									  AND p.idcalon = s.replid AND s.idkelompok = '$kelompok' AND p.idpenerimaan = '$idpenerimaan' GROUP BY s.replid) as X)";
	
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_bayar = $row[0];
$table_width = 520 + $max_n_bayar * 100;

$sql = "SELECT nama FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
?>
<center><font size="4" face="Arial"><strong>LAPORAN PEMBAYARAN IURAN SUKARELA CALON SISWA</strong></font><br /> 
</center>
<br /><br />
<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#333333">
<tr height="30" align="center" class="header">
	<td width="30" bgcolor="#CCCCCC"><strong><font size="2" face="Arial">No</font></strong></td>
    <td width="90" bgcolor="#CCCCCC"><strong><font size="2" face="Arial">No. Reg</font></strong></td>
    <td width="160" bgcolor="#CCCCCC"><strong><font size="2" face="Arial">Nama</font></strong></td>
    <td width="50" bgcolor="#CCCCCC"><strong><font size="2" face="Arial">Kelompok</font></strong></td>
<?php for($i = 0; $i < $max_n_bayar; $i++) { ?>
	<td width="125" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Bayaran-
      <?=$i + 1 ?>
	</font></strong></td>
<?php  } ?>
    <td width="125" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Total Pembayaran</font></strong></td>
    <!--<td class="header" width="200" align="center">Keterangan</td>--->
</tr>
<?php
OpenDb();
if ($kelompok == -1) 
{
	$sql_tot = "SELECT DISTINCT s.replid, s.nopendaftaran, s.nama, k.kelompok 
	              FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s, jbsakad.kelompokcalonsiswa k 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND p.idcalon = s.replid AND s.idkelompok = k.replid AND p.idpenerimaan = '$idpenerimaan' ORDER BY s.nama";
	
	$sql = "SELECT DISTINCT s.replid, s.nopendaftaran, s.nama, k.kelompok 
	          FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s, jbsakad.kelompokcalonsiswa k 
			 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			   AND p.idcalon = s.replid AND s.idkelompok = k.replid AND p.idpenerimaan = '$idpenerimaan'
		  ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
} 
else 
{
	$sql_tot = "SELECT DISTINCT s.replid, s.nopendaftaran, s.nama, k.kelompok 
	              FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s, jbsakad.kelompokcalonsiswa k 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku'
				   AND p.idcalon = s.replid AND s.idkelompok = k.replid AND s.idkelompok = '$kelompok' AND p.idpenerimaan = '$idpenerimaan' ORDER BY s.nama";
	
	$sql = "SELECT DISTINCT s.replid, s.nopendaftaran, s.nama, k.kelompok 
	          FROM penerimaaniurancalon p, jurnal j, jbsakad.calonsiswa s, jbsakad.kelompokcalonsiswa k 
			 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku'
			   AND p.idcalon = s.replid AND s.idkelompok = k.replid AND s.idkelompok = '$kelompok' AND p.idpenerimaan = '$idpenerimaan'
	      ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
}
$result = QueryDb($sql);
$cnt = 0;
$totalall = 0;
while ($row = mysqli_fetch_array($result)) {
	$bg1="#ffffff";
	if ($cnt==0 || $cnt%2==0)
		$bg1="#fcffd3"; 
	$replid = $row['replid'];
?>
	
    <tr height="40" bgcolor="<?=$bg1?>">
    	<td align="center"><font size="2" face="Arial">
   	    <?=++$cnt ?>
    	</font></td>
<td align="center"><font size="2" face="Arial">
        <?=$row['nopendaftaran'] ?>
        </font></td>
<td align="left"><font size="2" face="Arial">
        <?=$row['nama'] ?>
        </font></td>
<td align="center"><font size="2" face="Arial">
        <?=$row['kelompok'] ?>
        </font></td>
<?php 	$sql = "SELECT date_format(p.tanggal, '%d-%b-%y') as tanggal, jumlah 
                  FROM penerimaaniurancalon p, jurnal j
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND idcalon = '$replid' AND idpenerimaan = '".$idpenerimaan."'";
		$result2 = QueryDb($sql);
		$nbayar = mysqli_num_rows($result2);
		$nblank = $max_n_bayar - $nbayar;
		
		$totalbayar = 0;
		$x=0;
		while ($row2 = mysqli_fetch_array($result2)) {
			$bg2=$bg1;
			if ($x%2==0 || $x==0)
				$bg2="#d3fffd";
			$totalbayar += $row2['jumlah']; ?>
            <td bgcolor="<?=$bg2?>">
                <table border="1" width="100%" style="border-collapse:collapse" cellspacing="0" cellpadding="0" bordercolor="#000000">
                <tr height="20"><td align="center"><font size="2" face="Arial">
                <?=$row2['jumlah'] ?>
                </font></td></tr>
                <tr height="20"><td align="center"><font size="2" face="Arial">
                <?=$row2['tanggal'] ?>
                </font></td></tr>
      </table>            </td>
<?php 	$x++;
		} //end for 
		$totalall += $totalbayar;

		for ($i = 0; $i < $nblank; $i++) { ?>        
            <td>
                <table border="1" width="100%" style="border-collapse:collapse" cellspacing="0" cellpadding="0" bordercolor="#000000">
                <tr height="20"><td align="center">&nbsp;</td></tr>
                <tr height="20"><td align="center">&nbsp;</td></tr>
      </table>            </td>
<?php 	} //end for ?>        
		<td align="right"><font size="2" face="Arial">
	    <?=$totalbayar ?>
		</font></td>
      <!--<td align="right"><?=$row['keterangan'] ?></td>-->
    </tr>
<?php } //end for ?>
	<tr height="30">
    	<td bgcolor="#999900" align="center" colspan="<?=4 + $max_n_bayar ?>"><font color="#FFFFFF" size="2" face="Arial"><strong>T O T A L</strong></font></td>
        <td bgcolor="#999900" align="right"><font color="#FFFFFF" size="2" face="Arial"><strong><?=$totalall ?></strong></font></td>
        <!--<td bgcolor="#999900">&nbsp;</td>-->
    </tr>
</table>
<?php
CloseDb();
?>
</td>
</tr>
</table>
</body>
</html>
<script language="javascript">window.print();</script>