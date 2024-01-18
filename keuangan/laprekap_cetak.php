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

OpenDb();

$dept = $_REQUEST['dept'];
$idkategori = $_REQUEST['idkategori'];
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];
$petugas = $_REQUEST['petugas'];

if ($petugas == "ALL")
	$namapetugas = "(Semua Petugas)";
elseif ($petugas == "landlord")
	$namapetugas = "Administrator JIBAS";
else
{
	$sql = "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$petugas."'";
	$res = QueryDb($sql);
	$row = mysqli_fetch_row($res);
	$namapetugas = $row[0];
}

function NamaJenis($id)
{
	if ($id == "JTT")
		return "Iuran Wajib Siswa";
	elseif ($id == "SKR")
		return "Iuran Sukarela Siswa";
	elseif ($id == "CSWJB")
		return "Iuran Wajib Calon Siswa";
	elseif ($id == "CSSKR")
		return "Iuran Sukarela Calon Siswa";
	elseif ($id == "LNN")
		return "Penerimaan Lainnya";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Pembayaran Per Siswa]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">
<?php
$d = ($dept!='ALL')?$dept:'yayasan';
$d2 = ($dept!='ALL')?$dept:'(Semua departemen)';
?>
<?=getHeader($d)?>

<center><font size="4"><strong>REKAPITULASI PENERIMAAN</strong></font><br /> </center><br /><br />

<table border="0">
<tr>
	<td><strong>Departemen </strong></td>
    <td><strong>: <?=$d2 ?></strong></td>
</tr>
<tr>
	<td><strong>Jenis </strong></td>
    <td><strong>: <?=NamaJenis($idkategori) ?></strong></td>
</tr>
<tr>
	<td><strong>Tanggal </strong></td>
    <td><strong>: <?=LongDateFormat($tanggal1) . " s/d " . LongDateFormat($tanggal2) ?></strong></td>
</tr>
<tr>
	<td><strong>Petugas </strong></td>
    <td><strong>: <?= "$petugas - $namapetugas" ?></strong></td>
</tr>
</table>
<br />

<table cellpadding="5" border="1" style="border-width:1px; border-color:#999; border-collapse:collapse;" cellspacing="0" align="left">
<?php


if ($dept == "ALL")
{
	$sql = "SELECT departemen FROM jbsakad.departemen ORDER BY urutan";
	$dres = QueryDb($sql);
	$k = 0;
	while ($drow = mysqli_fetch_row($dres))
		$darray[$k++] = $drow[0];
}
else
{
	$darray = [$dept];
}

if ($petugas == "ALL")
	$sql_idpetugas = "";
elseif ($petugas == "landlord")
	$sql_idpetugas = " AND j.idpetugas IS NULL ";
else
	$sql_idpetugas = " AND j.idpetugas = '$petugas' ";

$total = 0;
for($k = 0; $k < count($darray); $k++)
{ 
	$dept = $darray[$k];
	$cnt = 0;
	
	$sql = "SELECT COUNT(replid) FROM tahunbuku WHERE departemen='$dept' AND aktif=1";
	$ntb = FetchSingle($sql);
	
	if ($ntb == 0)
		continue;
	
	$sql = "SELECT replid FROM tahunbuku WHERE departemen='$dept' AND aktif=1";
	$idtahunbuku = FetchSingle($sql);
	
	$subtotal = 0;
	$rarray = [];
	$sql = "SELECT replid, nama FROM jbsfina.datapenerimaan WHERE departemen='$dept' AND aktif=1 AND idkategori='$idkategori'";
	$pres = QueryDb($sql);
	while($prow = mysqli_fetch_row($pres))
	{
		$idp = $prow[0];
		$pen = $prow[1];
		
		if ($idkategori == "JTT")
		{
			$sql = "SELECT SUM(p.jumlah), SUM(p.info1) 
			          FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b, jbsfina.datapenerimaan dp, jbsfina.jurnal j 
			         WHERE p.idbesarjtt = b.replid
					   AND b.idpenerimaan = dp.replid 
					   AND p.idjurnal = j.replid 
					   AND j.idtahunbuku = '$idtahunbuku'
					   AND dp.replid = '$idp'
					   AND dp.departemen='$dept'
					   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					   $sql_idpetugas";
		}
		elseif ($idkategori == "SKR")
		{
			$sql = "SELECT SUM(p.jumlah), 0 
			          FROM jbsfina.penerimaaniuran p, jbsfina.datapenerimaan dp, jbsfina.jurnal j
			         WHERE p.idpenerimaan = dp.replid
					   AND p.idjurnal = j.replid
					   AND j.idtahunbuku = '$idtahunbuku' 
					   AND dp.replid = '$idp'
					   AND dp.departemen='$dept'
					   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					   $sql_idpetugas";
		}
		elseif ($idkategori == "CSWJB")
		{
			$sql = "SELECT SUM(p.jumlah), SUM(p.info1)
			          FROM jbsfina.penerimaanjttcalon p, jbsfina.besarjttcalon b, jbsfina.datapenerimaan dp, jbsfina.jurnal j 
			         WHERE p.idbesarjttcalon = b.replid
					   AND b.idpenerimaan = dp.replid
					   AND p.idjurnal = j.replid
					   AND j.idtahunbuku = '$idtahunbuku'
					   AND dp.replid = '$idp'
					   AND dp.departemen = '$dept'
					   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					   $sql_idpetugas";
		}
		elseif ($idkategori == "CSSKR")
		{
			$sql = "SELECT SUM(p.jumlah), 0 
			          FROM jbsfina.penerimaaniurancalon p, jbsfina.datapenerimaan dp, jbsfina.jurnal j
			         WHERE p.idjurnal = j.replid
					   AND j.idtahunbuku = '$idtahunbuku'
					   AND p.idpenerimaan = dp.replid
					   AND dp.replid = '$idp' 
					   AND dp.departemen='$dept'
					   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					   $sql_idpetugas";
		}
		elseif ($idkategori == "LNN")
		{
			$sql = "SELECT SUM(p.jumlah), 0 
			          FROM jbsfina.penerimaanlain p, jbsfina.datapenerimaan dp , jbsfina.jurnal j
			         WHERE p.idjurnal = j.replid
					   AND j.idtahunbuku = '$idtahunbuku'
					   AND p.idpenerimaan = dp.replid
					   AND dp.replid = '$idp' 
					   AND dp.departemen='$dept'
					   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					   $sql_idpetugas";
		}
		
		$jres = QueryDb($sql);
		$jrow = mysqli_fetch_row($jres);
		$jumlah = 0;
		if (!is_null($jrow[0]))
			$jumlah = $jrow[0];
		
		$subtotal = $subtotal + $jumlah;
		$rarray[$cnt][0] = $pen;
		$rarray[$cnt][1] = $jumlah;
			
		$cnt++;	
	}
	
	$total = $total + $subtotal;
	
	for($i = 0; $i < $cnt; $i++)
	{
		$pen = $rarray[$i][0];
		$jumlah = $rarray[$i][1];
		
		if ($i == 0) 
		{ ?>
        <tr>
        	<td colspan="4" align="right" bgcolor="#660099">
            <font color="#FFFFFF"><strong><em><?=$dept?></em></strong></font>
            </td>
        </tr>
<?php      } ?>
        <tr>
        	<td width="25" align="center" valign="top" bgcolor="#CCCCCC"><?=$i + 1?></td>
            <td width="350" align="left" valign="top"><?=$pen?></td>
            <td width="120" align="right" valign="top"><?=FormatRupiah($jumlah)?></td>
<?php 	if ($i == 0)
		{ ?>
        	<td width="120" rowspan="<?=$cnt?>" valign="middle" align="right" bgcolor="#FFECFF"><strong><?=FormatRupiah($subtotal)?></strong></td>
<?php 	} ?>        
        </tr>
<?php  } 
}
CloseDb();
?>
    <tr height="40">
        <td colspan="3" align="right" valign="middle" bgcolor="#333333">
        <font color="#FFFFFF"><strong>T O T A L</strong></font>
        </td>
        <td valign="middle" align="right" bgcolor="#333333">
        <font color="#FFFFFF"><strong><?=FormatRupiah($total)?></strong></font>
        </td>
    </tr>
</table>

</td></tr></table>
</body>
</html>
<script language="javascript">window.print();</script>