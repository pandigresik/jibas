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

$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];

if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];

$keyword = "";
if (isset($_REQUEST['keyword']))
	$keyword = $_REQUEST['keyword'];
	
if (isset($_REQUEST['kriteria']))
	$kriteria = $_REQUEST['kriteria'];

switch ($kriteria) {
	case 1:	$sql = "SELECT * FROM jurnal WHERE transaksi LIKE '%$keyword%' AND idtahunbuku='$idtahunbuku' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			$jurnal = "Umum";
		break;
	case 2: $sql = "SELECT * FROM jurnal WHERE nokas LIKE '%$keyword%' AND idtahunbuku='$idtahunbuku' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			$jurnal = "Umum";
		break;
	case 3: $sql = "SELECT * FROM jurnal WHERE keterangan LIKE '%$keyword%' AND idtahunbuku='$idtahunbuku' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			$jurnal = "Umum";
		break;
	case 4: $sql = "SELECT * FROM jurnal WHERE petugas LIKE '%$keyword%' AND idtahunbuku='$idtahunbuku' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2'ORDER BY tanggal";
			$jurnal = "Umum";
		break;
	case 5: $sql = "SELECT * FROM jurnal WHERE idtahunbuku = '$idtahunbuku' AND sumber = 'penerimaanjtt' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			$jurnal = "Penerimaan";
		break;
	case 6: $sql = "SELECT * FROM jurnal WHERE idtahunbuku = '$idtahunbuku' AND sumber = 'penerimaaniuran' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			$jurnal = "Penerimaan";
		break;
	case 7: $sql = "SELECT * FROM jurnal WHERE idtahunbuku = '$idtahunbuku' AND sumber = 'penerimaanjttcalon' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			$jurnal = "Penerimaan";
		break;
	case 8: $sql = "SELECT * FROM jurnal WHERE idtahunbuku = '$idtahunbuku' AND sumber = 'penerimaaniurancalon' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			$jurnal = "Penerimaan";
		break;
	case 9: $sql = "SELECT * FROM jurnal WHERE idtahunbuku = '$idtahunbuku' AND sumber = 'penerimaanlain' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			$jurnal = "Penerimaan";
		break;
	case 10: $sql = "SELECT * FROM jurnal WHERE idtahunbuku = '$idtahunbuku' AND sumber LIKE 'penerimaan%' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			 $jurnal = "Penerimaan";
		break;
	case 11: $sql = "SELECT * FROM jurnal WHERE idtahunbuku = '$idtahunbuku' AND sumber = 'pengeluaran' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			 $jurnal = "Pengeluaran";
		break;
	case "all" : $sql = "SELECT * FROM jurnal WHERE idtahunbuku = '$idtahunbuku' AND tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY tanggal";
			$jurnal = "Umum";
		break;
}

/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Jurnal_'.$jurnal.'.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Pencarian Data Jurnal <?=$jurnal?>]</title>
</head>

<body>
<center><font size="4" face="Verdana"><strong>DATA JURNAL <?=strtoupper((string) $jurnal)?></strong></font><br /> 
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
	<td width="90"><font size="2" face="Arial"><strong>Tanggal </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=LongDateFormat($tanggal1) . " s/d 	" . LongDateFormat($tanggal2) ?>
    </strong></font></td>
</tr>
<?php if ($jurnal == "Umum" && $kriteria <> "all") { 
		switch ($kriteria) {
			case 1	: $namakriteria = "Transaksi";
				break;
			case 2	: $namakriteria = "No. Jurnal";
				break;
			case 3 	: $namakriteria = "Keterangan";
				break;
            case 4	: $namakriteria = "Nama Petugas";
				break;
		}
?> 
<tr>
	<td colspan="2"><font size="2" face="Arial"><strong>Pencarian berdasarkan 
	  <?=$namakriteria?> 
	  dengan keyword 
	  <?=$keyword?>
	</strong></font></td>
</tr>
<?php } ?>
</table>
<br />

<table border="1" style="border-collapse:collapse" cellpadding="5" width="100%" class="tab" bordercolor="#000000">
<tr height="30">
	<td width="4%" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No</font></strong></td>
    <td width="18%" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">No. Jurnal/Tanggal</font></strong></td>
    <td width="32%" align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Transaksi</font></strong></td>
    <td align="center" bgcolor="#CCCCCC" class="header"><strong><font size="2" face="Arial">Detail Jurnal</font></strong></td>  
</tr>

<?php
OpenDb();
$result = QueryDb($sql);
//$cnt = 0;	
$cnt = 1;
while ($row = mysqli_fetch_array($result)) {
	if ($cnt % 2 == 0)
		$bgcolor = "#FFFFB7";
	else
		$bgcolor = "#FFFFB7";
	
	
?>
<tr height="25">
	<td align="center" rowspan="2" bgcolor="<?=$bgcolor ?>"><font size="2" face="Arial"><strong><?=$cnt ?></strong></font></td>
    <td align="center" bgcolor="<?=$bgcolor ?>"><font size="2" face="Arial"><strong>
      <?=$row['nokas']?>
    </strong><br />
    <em>
    <?=LongDateFormat($row['tanggal'])?>
    </em></font></td>
    <td valign="top" bgcolor="<?=$bgcolor ?>"><font size="2" face="Arial">
      <?=$row['transaksi'] ?>
      <?php if (strlen((string) $row['keterangan']) > 0 )  { ?>
	    <br />
	    <strong>Keterangan:</strong>
	    <?=$row['keterangan'] ?> 
      <?php } ?>    
    </font></td>
    <td rowspan="2" valign="top" bgcolor="#E8FFE8">    
    
    <table border="1" style="border-collapse:collapse" width="100%" height="100%" cellpadding="2" bgcolor="#FFFFFF" bordercolor="#000000">    
<?php $idjurnal = $row['replid'];
	$sql = "SELECT jd.koderek,ra.nama,jd.debet,jd.kredit FROM jurnaldetail jd, rekakun ra WHERE jd.idjurnal = '$idjurnal' AND jd.koderek = ra.kode ORDER BY jd.replid";    
	$result2 = QueryDb($sql); 
	while ($row2 = mysqli_fetch_array($result2)) { ?>
    <tr height="25">
    	<td width="12%" align="center"><font size="2" face="Arial">
    	  <?=$row2['koderek'] ?>
    	</font></td>
        <td width="*" align="left"><font size="2" face="Arial">
          <?=$row2['nama'] ?>
        </font></td>
        <td width="25%" align="right"><font size="2" face="Arial">
          <?=$row2['debet'] ?>
        </font></td>
        <td width="25%" align="right"><font size="2" face="Arial">
          <?=$row2['kredit'] ?>
        </font></td>
    </tr>
<?php } ?>    
    </table>    </td>
</tr>
<tr>    
    <td valign="top"><font size="2" face="Arial"><strong>Petugas: </strong>
      <?=$row['petugas'] ?>
    </font></td>
    <td valign="top">
      <font size="2" face="Arial"><strong>Sumber: </strong>
      <?php 	switch($row['sumber']) {	
		case 'jurnalumum':
			echo  "Jurnal Umum"; break;
		case 'penerimaanjtt':
			echo  "Penerimaan Iuran Wajib Siswa"; break;
		case 'penerimaaniuran':
			echo  "Penerimaan Iuran Sukarela Siswa"; break;
		case 'penerimaanlain':
			echo  "Penerimaan Lain-Lain"; break;
		case 'pengeluaran':
			echo  "Pengeluaran"; break;
		case 'penerimaanjttcalon':
            echo  "Penerimaan Iuran Wajib Calon Siswa"; break;
		case 'penerimaaniurancalon':
            echo  "Penerimaan Iuran Sukarela Calon Siswa"; break;
	} ?>   	
    </font></td>
</tr>
<tr style="height:2px">
	<td colspan="4" bgcolor="#EFEFDE"></td>
</tr>
<?php
	$cnt++;
}
CloseDb();
?>
 <!-- END TABLE CONTENT -->
</table>
</table>	
</body>
</html>
<script language="javascript">window.print();</script>