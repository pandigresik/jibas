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
header('Content-Disposition: attachment; filename=Iuran_Sukarela_Siswa_per_Kelas.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$urut=$_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
	
if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];

if (isset($_REQUEST['idtingkat']))
	$idtingkat = (int)$_REQUEST['idtingkat'];

if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];

OpenDb();	
$sql = "SELECT departemen FROM jbsakad.angkatan WHERE replid='$idangkatan'"; 	
$result = QueryDb($sql);    
$row = mysqli_fetch_row($result);	
$departemen = $row[0];	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Pembayaran Iuran Sukarela Siswa Per Kelas]</title>
</head>

<body>

<?php
OpenDb();

$sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
$idtahunbuku = FetchSingle($sql);

if ($idtingkat == -1) 
{
	$sql = "SELECT MAX(jml) FROM ((SELECT p.nis, COUNT(p.replid) as jml 
								     FROM penerimaaniuran p, jurnal j, jbsakad.siswa s
									WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku'
									  AND p.nis = s.nis AND s.idangkatan = '$idangkatan'
									  AND p.idpenerimaan = '$idpenerimaan' GROUP BY p.nis) as X)";
} 
else 
{
	if ($idkelas == -1)
		$sql = "SELECT MAX(jml) FROM ((SELECT p.nis, COUNT(p.replid) as jml 
										 FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k 
										WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
										  AND p.nis = s.nis AND s.idangkatan = '$idangkatan' AND p.idpenerimaan = '$idpenerimaan' 
										  AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' GROUP BY p.nis) as X)";
	else
		$sql = "SELECT MAX(jml) FROM ((SELECT p.nis, COUNT(p.replid) as jml 
								         FROM penerimaaniuran p, jurnal j, jbsakad.siswa s 
										WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
										  AND p.nis = s.nis AND s.idkelas = '$idkelas' AND s.idangkatan = '$idangkatan' 
										  AND p.idpenerimaan = '$idpenerimaan' GROUP BY p.nis) as X)";
}	
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_bayar = $row[0];
$table_width = 520 + $max_n_bayar * 100;

$sql = "SELECT nama FROM datapenerimaan WHERE replid = $idpenerimaan";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
?>

<center><font size="4" face="Verdana"><strong>LAPORAN PEMBAYARAN IURAN SUKARELA SISWA</strong></font><br />
 </center>
<br /><br />


<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
<tr height="30" align="center">
	<td width="30" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No</font></strong></td>
    <td width="90" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">N I S</font></strong></td>
    <td width="160" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Nama</font></strong></td>
    <td width="50" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Kelas</font></strong></td>
<?php for($i = 0; $i < $max_n_bayar; $i++) { ?>
	<td width="100" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Bayaran-
      <?=$i + 1 ?>
	</font></strong></td>
<?php  } ?>
    <td width="100" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Total Pembayaran</font></strong></td>
    <!--<td class="header" width="200" align="center">Keterangan</td>-->
</tr>
<?php
OpenDb();
if ($idtingkat == -1) 
{
	$sql_tot = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
	              FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND p.nis = s.nis AND s.idkelas = k.replid AND s.idangkatan = '$idangkatan' 
				   AND p.idpenerimaan = '$idpenerimaan' AND k.idtingkat = t.replid ORDER BY s.nama";
	
	$sql = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
	          FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
			 WHERE p.nis = s.nis AND s.idkelas = k.replid AND s.idangkatan = '$idangkatan' 
			   AND p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			   AND p.idpenerimaan = '$idpenerimaan' AND k.idtingkat = t.replid 
		  ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
} 
else 
{
	if ($idkelas == -1)
	{
		$sql_tot = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
		              FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
					 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
					   AND p.nis = s.nis AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' 
					   AND s.idangkatan = '$idangkatan' AND p.idpenerimaan = '$idpenerimaan' AND k.idtingkat = t.replid ORDER BY s.nama";
		
		$sql = "SELECT DISTINCT p.nis, s.nama, k.kelas, t.tingkat 
		          FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND p.nis = s.nis AND s.idkelas = k.replid AND k.idtingkat = '$idtingkat' AND s.idangkatan = '$idangkatan' 
				   AND p.idpenerimaan = '$idpenerimaan' AND k.idtingkat = t.replid ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	} 
	else 
	{
		$sql_tot = "SELECT DISTINCT p.nis, s.nama, k.kelas 
		              FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k 
					 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
					   AND p.nis = s.nis AND s.idkelas = k.replid AND s.idkelas ='$idkelas' 
					   AND s.idangkatan = '$idangkatan' AND p.idpenerimaan = '$idpenerimaan' ORDER BY s.nama";
		
		$sql = "SELECT DISTINCT p.nis, s.nama, k.kelas 
		          FROM penerimaaniuran p, jurnal j, jbsakad.siswa s, jbsakad.kelas k 
				 WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
				   AND p.nis = s.nis AND s.idkelas = k.replid AND s.idkelas = '$idkelas' 
				   AND s.idangkatan = '$idangkatan' AND p.idpenerimaan = '$idpenerimaan' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris"; 
	}
}
$result = QueryDb($sql);
$cnt = 0;
$totalall = 0;
while ($row = mysqli_fetch_array($result)) { 
	$bg1="#ffffff";
	if ($cnt==0 || $cnt%2==0)
		$bg1="#fcffd3";
	$nis = $row['nis'];
?>
	
    <tr height="40" bgcolor="<?=$bg1?>">
    	<td align="center"><font size="2" face="Arial">
   	    <?=++$cnt ?>
    	</font></td>
<td align="center"><font size="2" face="Arial">
        <?=$row['nis'] ?>
        </font></td>
<td align="left"><font size="2" face="Arial">
        <?=$row['nama'] ?>
        </font></td>
<td align="center"><font size="2" face="Arial">
        <?=$row['kelas'] ?>
        </font></td>
<?php 	$sql = "SELECT date_format(p.tanggal, '%d-%b-%y') as tanggal, jumlah 
	           FROM penerimaaniuran p, jurnal j
			  WHERE p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			    AND nis = '".$row['nis']."' AND idpenerimaan = '".$idpenerimaan."'";
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
<?php 	
		$x++;
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

</body>
</html>
<script language="javascript">window.print();</script>