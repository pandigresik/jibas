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

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Laporan_Tunggakan_Iuran_Wajib_Calon_Siswa.xls');
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

if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];

if (isset($_REQUEST['kelompok']))
	$kelompok = (int)$_REQUEST['kelompok'];
	
if (isset($_REQUEST['telat']))
	$telat = (int)$_REQUEST['telat'];
	
$tanggal = "";
if (isset($_REQUEST['tanggal'])) 
	$tanggal = $_REQUEST['tanggal'];

$tgl = MySqlDateFormat($tanggal);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Tunggakan Iuran Wajib Calon Siswa Per Kelompok]</title>
</head>

<body>
<?php
OpenDb();
if ($kelompok == -1) 
{
	$sql = "SELECT idbesarjttcalon, datediff('$tgl', max(tanggal)) as x 
	          FROM penerimaanjttcalon p , besarjttcalon b, jbsakad.calonsiswa c, jbsakad.prosespenerimaansiswa r 
			   WHERE p.idbesarjttcalon = b.replid AND b.lunas = 0 AND b.idpenerimaan = '$idpenerimaan' AND b.info2='$idtahunbuku'
				  AND c.replid = b.idcalon AND c.idproses = r.replid AND r.aktif = 1 
		   GROUP BY idbesarjttcalon 
		     HAVING x >= $telat";
			  /*UNION SELECT b.replid, '-' FROM besarjttcalon b, jbsakad.calonsiswa c, jbsakad.prosespenerimaansiswa r WHERE b.replid NOT IN (SELECT idbesarjttcalon FROM penerimaanjttcalon p) AND b.lunas = 0 AND c.replid = b.idcalon AND b.idpenerimaan = $idpenerimaan AND c.idproses = r.replid AND r.aktif = 1";	*/
} 
else 
{
	$sql = "SELECT idbesarjttcalon, datediff('$tgl', max(tanggal)) as x 
	          FROM penerimaanjttcalon p , besarjttcalon b, jbsakad.calonsiswa c 
				WHERE p.idbesarjttcalon = b.replid AND b.lunas = 0 AND b.info2='$idtahunbuku' AND c.replid = b.idcalon 
				  AND c.idkelompok = '$kelompok' AND b.idpenerimaan = '$idpenerimaan' 
		   GROUP BY idbesarjttcalon 
			  HAVING x >= $telat";
			  /*UNION SELECT b.replid, '-' FROM besarjttcalon b, jbsakad.calonsiswa c, jbsakad.prosespenerimaansiswa r WHERE b.replid NOT IN (SELECT idbesarjttcalon FROM penerimaanjttcalon p) AND b.lunas = 0 AND c.replid = b.idcalon AND b.idpenerimaan = $idpenerimaan AND c.idkelompok = $kelompok";	*/
} 	

//echo  "$sql<br>";
$result = QueryDb($sql);
$idstr = "";
while($row = mysqli_fetch_row($result)) {
	if (strlen($idstr) > 0)
		$idstr = $idstr . ",";
	$idstr = $idstr . $row[0];
}
//echo  "$idstr<br>";
if (strlen($idstr) == 0) {
	echo  "Tidak ditemukan data!";
	CloseDb();
	exit();
}

$sql = "SELECT MAX(jumlah) FROM (SELECT idbesarjttcalon, count(replid) AS jumlah FROM penerimaanjttcalon WHERE idbesarjttcalon IN ($idstr) GROUP BY idbesarjttcalon) AS X";
//echo  "$sql<br>";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$max_n_cicilan = $row[0];
$table_width = 810 + $max_n_cicilan * 90;

//Dapatkan namapenerimaan
$sql = "SELECT nama, departemen FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
$departemen = $row[1];

$namakelompok = "Semua Kelompok";
if ($kelompok <> -1) {
	$sql = "SELECT proses, kelompok FROM jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p WHERE k.replid = '$kelompok' AND k.idproses = p.replid";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$namaproses = $row[0];
	$namakelompok = $row[1];	
} else  {
	$sql = "SELECT proses FROM jbsakad.prosespenerimaansiswa p WHERE p.aktif = 1";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$namaproses = $row[0];
}
?>

<center><font size="4" face="Verdana"><strong>LAPORAN TUNGGAKAN <?=strtoupper((string) $namapenerimaan) ?><br />
</strong></font><br /> 
</center>
<br />
<table border="0">
<tr>
	<td><font size="2" face="Arial"><strong>Departemen </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$departemen?>
    </strong></font></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><strong>Proses</strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$namaproses?>
    </strong></font></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><strong>Kelompok</strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$namakelompok?>
    </strong></font></td>
</tr>
<tr>
	<td><font size="2" face="Arial"><strong>Telat Bayar </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$telat ?> 
      hari dari tanggal 
      <?=LongDateFormat($tanggal)?>
    </strong></font></td>
</tr>
</table>
<br />

<table class="tab" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width ?>" align="left" bordercolor="#000000">
<tr height="30">
	<td width="30" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No</font></strong></td>
    <td width="80" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No. Reg</font></strong></td>
    <td width="140" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Nama</font></strong></td>
    <td width="50" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Kel</font></strong></td>
<?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
			$n = $i + 1; ?>
    		<td width="120" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">
   		    <?="Bayaran-$n" ?>
    		</font></strong></td>	
    <?php  } ?>
    <td width="80" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Telat<br />
        <em>(hari)</em></font></strong></td>
    <td width="120" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">
      <?=$namapenerimaan ?>
    </font></strong></td>
    <td width="120" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Total Pembayaran</font></strong></td>
    <td width="120" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Total Diskon</font></strong></td>
    <td width="120" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Total Tunggakan</font></strong></td>
    <td width="200" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Keterangan</font></strong></td>
</tr>
<?php
$sql_tot = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
              FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, besarjttcalon b 
				 WHERE c.replid = b.idcalon AND c.idkelompok = k.replid AND b.replid IN ($idstr) ORDER BY c.nama";

$sql = "SELECT c.nopendaftaran, c.nama, k.kelompok, b.replid AS id, b.besar, b.keterangan, b.lunas 
          FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, besarjttcalon b 
			WHERE c.replid = b.idcalon AND c.idkelompok = k.replid AND b.replid IN ($idstr) 
		ORDER BY $urut $urutan";
$result = QueryDb($sql);
$cnt = 0;
$totalbiayaall = 0;
$totalbayarall = 0;
$totaldiskonall = 0;

while ($row = mysqli_fetch_array($result)) {
	$bg1="#ffffff";
	if ($cnt==0 || $cnt%2==0)
		$bg1="#fcffd3";
	$idbesarjtt = $row['id'];
	$besarjtt = $row['besar'];
	$ketjtt = $row['keterangan'];
	$lunasjtt = $row['lunas'];
	$infojtt = "<font color=red><strong>Belum Lunas</strong></font>";
	if ($lunasjtt == 1)
		$infojtt = "<font color=blue><strong>Lunas</strong></font>";
	$totalbiayaall += $besarjtt;
		
?>
<tr height="40" bgcolor="<?=$bg1?>">
	<td align="center"><font size="2" face="Arial">
	  <?=++$cnt ?>
	</font></td>
    <td align="center"><font size="2" face="Arial">
      <?=$row['nopendaftaran'] ?>
    </font></td>
    <td><font size="2" face="Arial">
      <?=$row['nama'] ?>
    </font></td>
    <td align="center"><font size="2" face="Arial">
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
	
	if ($nbayar > 0) {
		$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah, info1 FROM penerimaanjttcalon WHERE idbesarjttcalon = '$idbesarjtt' ORDER BY tanggal";
		$result2 = QueryDb($sql);
		$x=0;
		while ($row2 = mysqli_fetch_row($result2)) {
			$bg2=$bg1;
			if ($x%2==0 || $x==0)
				$bg2="#d3fffd";
			$totalbayar = $totalbayar + $row2[1];
            $totaldiskon = $totaldiskon + $row2[2];
			?>
            <td bgcolor="<?=$bg2?>">
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000">
                <tr height="20"><td align="center"><font size="2" face="Arial">
                <?=FormatNumerik($row2[1]) ?>
                </font></td></tr>
                <tr height="20"><td align="center"><font size="2" face="Arial">
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
    <td align="center">
      <font size="2" face="Arial">
      <?php $sql = "SELECT max(datediff('$tgl', tanggal)) FROM penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	echo  $row2[0]; ?>    
    </font></td>
    <td align="right"><font size="2" face="Arial">
      <?=FormatNumerik($besarjtt) ?>
    </font></td>
    <td align="right"><font size="2" face="Arial">
      <?=FormatNumerik($totalbayar) ?>
    </font></td>
    <td align="right"><font size="2" face="Arial">
            <?=FormatNumerik($totaldiskon) ?>
    </font></td>
    <td align="right"><font size="2" face="Arial">
      <?=FormatNumerik($besarjtt - $totalbayar - $totaldiskon) ?>
    </font></td>
    <td><font size="2" face="Arial">
      <?=$ketjtt ?>
    </font></td>
</tr>
<?php
}
?>
<tr height="40">
	<td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong>T O T A L</strong></font></td>
	<td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=FormatNumerik($totalbiayaall) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=FormatNumerik($totalbayarall) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=FormatNumerik($totaldiskonall) ?></strong></font></td>
    <td align="right" bgcolor="#999900"><font color="#FFFFFF" size="2" face="Arial"><strong><?=FormatNumerik($totalbiayaall - $totalbayarall - $totaldiskonall) ?></strong></font></td>
    <td bgcolor="#999900">&nbsp;</td>
</tr>
</table>
<?php CloseDb() ?>
<!-- END TABLE CONTENT -->
    
</td>
</tr>
</table>
</body>
</html>
<script language="javascript">window.print();</script>