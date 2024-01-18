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
require_once('include/sessioninfo.php');

$dept = $_REQUEST['dept'];
$idpenerimaan = $_REQUEST['idpenerimaan'];
$idkategori = $_REQUEST['idkategori'];
$tanggal = $_REQUEST['tanggal'];
$petugas = $_REQUEST['petugas'];
$idtahunbuku = $_REQUEST['idtahunbuku'];

OpenDb();

$sql = "SELECT nama
          FROM jbsfina.datapenerimaan
         WHERE replid = $idpenerimaan";
$penerimaan = FetchSingle($sql);

$namadepartemen = $dept == "ALL" ? "Semua Departemen" : $dept;
$deptlist = "";
if ($dept == "ALL")
{
	$sql = "SELECT departemen FROM jbsakad.departemen ORDER BY urutan";
	$dres = QueryDb($sql);
	$k = 0;
	while ($drow = mysqli_fetch_row($dres))
    {
		if ($deptlist != "")
            $deptlist .= ",";
        $deptlist .= "'" . $drow[0] . "'";    
    }
}
else
{
    $deptlist = "'" . $dept . "'";    
}

if ($petugas == "ALL")
{
    $sql_idpetugas = "";
    $namapetugas = "Semua Petugas";
}
elseif ($petugas == "landlord")
{
    $sql_idpetugas = " AND j.idpetugas IS NULL ";
    $namapetugas = "Administrator JIBAS";
}
else
{
    $sql_idpetugas = " AND j.idpetugas = '$petugas' ";
    $sql = "SELECT nama
              FROM jbssdm.pegawai
             WHERE nip = '".$petugas."'";
    $namapetugas = FetchSingle($sql);         
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Detail Rekapitulasi Penerimaan</title>
</head>
<body>
<center>
<h3>Detail Rekapitulasi Penerimaan</h3>    
</center><br>
<table border='0'>
<tr>
    <td width='100' align='right'>Departemen:</td>
    <td width='200' align='left'><strong><?=$namadepartemen?></strong></td>
    <td width='100' align='right'>Penerimaan:</td>
    <td width='200' align='left'><strong><?=$penerimaan?></strong></td>
</tr>
<tr>
    <td width='100' align='right'>Tanggal:</td>
    <td width='200' align='left'><strong><?=LongDateFormat($tanggal)?></strong></td>
    <td width='100' align='right'>Petugas:</td>
    <td width='200' align='left'><strong><?=$namapetugas?></strong></td>
</tr>    
</table>
<br><br>
<?php
if ($idkategori == "JTT")
{
    $sql = "SELECT p.tanggal, p.jumlah, p.info1 AS diskon, p.keterangan, IF(j.idpetugas IS NULL, 'admin', j.idpetugas) AS idpetugas, j.transaksi
              FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b, jbsfina.datapenerimaan dp, jbsfina.jurnal j  
             WHERE p.idbesarjtt = b.replid
               AND b.idpenerimaan = dp.replid
               AND j.replid = p.idjurnal
               AND j.idtahunbuku = '$idtahunbuku'
               AND dp.replid = '$idpenerimaan'
               AND dp.departemen IN ($deptlist)
               AND p.tanggal = '$tanggal'
              $sql_idpetugas
             ORDER BY p.tanggal DESC, p.replid DESC";
}
elseif ($idkategori == "SKR")
{
    $sql = "SELECT p.tanggal, p.jumlah, 0 AS diskon, p.keterangan, IF(j.idpetugas IS NULL, 'admin', j.idpetugas) AS idpetugas, j.transaksi
              FROM jbsfina.penerimaaniuran p, jbsfina.datapenerimaan dp, jbsfina.jurnal j 
             WHERE p.idpenerimaan = dp.replid
               AND p.idjurnal = j.replid
               AND j.idtahunbuku = '$idtahunbuku'
               AND dp.replid = '$idpenerimaan'
               AND dp.departemen IN ($deptlist)
               AND p.tanggal = '$tanggal'
              $sql_idpetugas
             ORDER BY p.tanggal DESC, p.replid DESC";
}
elseif ($idkategori == "CSWJB")
{
    $sql = "SELECT p.tanggal, p.jumlah, p.info1 AS diskon, p.keterangan, IF(j.idpetugas IS NULL, 'admin', j.idpetugas) AS idpetugas, j.transaksi
              FROM jbsfina.penerimaanjttcalon p, jbsfina.besarjttcalon b, jbsfina.datapenerimaan dp, jbsfina.jurnal j  
             WHERE p.idbesarjttcalon = b.replid
               AND b.idpenerimaan = dp.replid
               AND j.replid = p.idjurnal
               AND j.idtahunbuku = '$idtahunbuku'
               AND dp.replid = '$idpenerimaan'
               AND dp.departemen IN ($deptlist)
               AND p.tanggal = '$tanggal'
              $sql_idpetugas
             ORDER BY p.tanggal DESC, p.replid DESC";
}
elseif ($idkategori == "CSSKR")
{
    $sql = "SELECT p.tanggal, p.jumlah, 0 AS diskon, p.keterangan, IF(j.idpetugas IS NULL, 'admin', j.idpetugas) AS idpetugas, j.transaksi
              FROM jbsfina.penerimaaniurancalon p, jbsfina.datapenerimaan dp, jbsfina.jurnal j  
             WHERE p.idpenerimaan = dp.replid
               AND p.idjurnal = j.replid
               AND j.idtahunbuku = '$idtahunbuku'
               AND dp.replid = '$idpenerimaan' 
               AND dp.departemen IN ($deptlist)
               AND p.tanggal = '$tanggal'
              $sql_idpetugas
             ORDER BY p.tanggal DESC, p.replid DESC";
}
elseif ($idkategori == "LNN")
{
    $sql = "SELECT p.tanggal, p.jumlah, 0 AS diskon, p.keterangan, IF(j.idpetugas IS NULL, 'admin', j.idpetugas) AS idpetugas, j.transaksi
              FROM jbsfina.penerimaanlain p, jbsfina.datapenerimaan dp, jbsfina.jurnal j   
             WHERE p.idpenerimaan = dp.replid
               AND p.idjurnal = j.replid
               AND j.idtahunbuku = '$idtahunbuku'
               AND dp.replid = '$idpenerimaan' 
               AND dp.departemen IN ($deptlist)
               AND p.tanggal = '$tanggal'
              $sql_idpetugas
             ORDER BY p.tanggal DESC, p.replid DESC";
}
//echo $sql;
?>
<table border='1' cellpadding='2' cellspacing='0' width='99%' style='border-width: 1px; border-collapse: collapse;'>
<tr height='25'>
    <td class='header' width='5%' align='center'>No</td>
    <td class='header' width='12%' align='center'>Tanggal</td>
    <td class='header' width='20%' align='center'>Petugas</td> 
    <td class='header' width='14%' align='center'>Jumlah</td>
    <td class='header' width='14%' align='center'>Diskon</td>
    <td class='header' width='*' align='center'>Transaksi/Keterangan</td> 
</tr>
<?php

$res = QueryDb($sql);
$no = 0;
$totalj = 0;
$totald = 0;
while($row = mysqli_fetch_array($res))
{
    $no += 1;
    
    $idpetugas = $row['idpetugas'];
    if ($idpetugas == "admin")
    {
        $petugas = "Administrator JIBAS";
    }
    else
    {
        $sql = "SELECT nama
                  FROM jbssdm.pegawai
                 WHERE nip = '".$idpetugas."'";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $petugas = $row2[0];
    }
    
    $jumlah = (int)$row['jumlah'] + (int)$row['diskon'];
    $totalj += $jumlah;
    $totald += $row['diskon'];
    
    $keterangan = trim((string) $row['keterangan']);
    if (strlen($keterangan) > 0)
        $keterangan = "<br><strong>Keterangan:</strong> $keterangan";
    ?>
    <tr height='22'>
        <td align='center'><?=$no?></td>
        <td align='center'><?=RegularDateFormat($row['tanggal'])?></td>
        <td align='center'><?=$petugas?></td>
        <td align='right'><?=FormatRupiah($jumlah)?></td>
        <td align='right'><?=FormatRupiah($row['diskon'])?></td>
        <td align='left'><?=$row['transaksi'] . $keterangan?></td>
    </tr>
<?php    
}
?>
<tr height='25' style='background-color: #ddd'>
    <td colspan='3' align='right'><strong>TOTAL</strong></td>
    <td align='right'><strong><?=FormatRupiah($totalj)?></strong></td>
    <td align='right'><strong><?=FormatRupiah($totald)?></strong></td>
    <td align='center' style='color: maroon; font-size: 12px;'><strong><?=FormatRupiah($totalj - $totald)?></strong></td>
</tr>
</table>

</body>
</html>
<?php
CloseDb();
?>