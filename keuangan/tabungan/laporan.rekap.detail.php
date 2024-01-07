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
require_once('../include/errorhandler.php');
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../library/departemen.php');

OpenDb();

$departemen = $_REQUEST['departemen'];
$idtabungan = $_REQUEST['idtabungan'];
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];
$petugas = $_REQUEST['petugas'];
$jenis = $_REQUEST['jenis'];
$kelompok = $_REQUEST['kelompok'];

if ($kelompok == "siswa")
{
    $sql = "SELECT nama
              FROM jbsfina.datatabungan
             WHERE replid = $idtabungan";
}
else
{
    $sql = "SELECT nama
              FROM jbsfina.datatabunganp
             WHERE replid = $idtabungan";
}
$namatabungan = FetchSingle($sql);

if ($petugas == "ALL")
{
    $sql_idpetugas = "";
    $namapetugas = "Semua Petugas";
}
elseif ($petugas == "landlord")
{
    $sql_idpetugas = " AND t.petugas = 'landlord'";
    $namapetugas = "Administrator JIBAS";
}
else
{
    $sql_idpetugas = " AND t.petugas = '".$petugas."'";
    $sql = "SELECT nama
              FROM jbssdm.pegawai
             WHERE nip = '".$petugas."'";
    $namapetugas = FetchSingle($sql);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Detail Rekapitulasi Tabungan</title>
    <script src="../script/tooltips.js" language="javascript"></script>
    <script language="javascript" src="../script/tools.js"></script>
</head>

<body>
<center>
    <h3>Detail Rekapitulasi Tabungan</h3>
</center><br>
<table border='0'>
<tr>
    <td width='100' align='right'>Departemen:</td>
    <td width='200' align='left'><strong><?=$departemen?></strong></td>
    <td width='100' align='right'>Tabungan:</td>
    <td width='200' align='left'><strong><?=$namatabungan?></strong></td>
</tr>
<tr>
    <td width='100' align='right'>Tanggal:</td>
    <td width='200' align='left'><strong><?=$tanggal1?> s/d <?=$tanggal2?></strong></td>
    <td width='100' align='right'>Petugas:</td>
    <td width='200' align='left'><strong><?=$namapetugas?></strong></td>
</tr>
</table>
<br><br>
<table border='1' cellpadding='2' cellspacing='0' width='99%' style='border-width: 1px; border-collapse: collapse;'>
<tr height='25'>
    <td class='header' width='5%' align='center'>No</td>
    <td class='header' width='12%' align='center'>Tanggal</td>
    <td class='header' width='25%' align='center'>Siswa</td>
    <td class='header' width='20%' align='center'><?=$jenis?></td>
    <td class='header' width='20%' align='center'>Petugas</td>
    <td class='header' width='*' align='center'>Keterangan</td>
</tr>
<?php
$select = $jenis == "SETORAN" ? "t.kredit AS jumlah" : "t.debet AS jumlah";
if ($kelompok == "siswa")
{
    $sql = "SELECT t.nis AS userid, s.nama, $select, t.petugas, t.keterangan, t.tanggal
              FROM jbsfina.tabungan t, jbsakad.siswa s 
             WHERE t.nis = s.nis
               AND idtabungan = '$idtabungan'
               AND tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
}
else
{
    $sql = "SELECT t.nip AS userid, p.nama, $select, t.petugas, t.keterangan, t.tanggal
              FROM jbsfina.tabunganp t, jbssdm.pegawai p 
             WHERE t.nip = p.nip
               AND idtabungan = '$idtabungan'
               AND tanggal BETWEEN '$tanggal1' AND '$tanggal2'";
}

$sql .=  $sql_idpetugas;
if ($jenis == "SETORAN")
    $sql .= " AND t.kredit <> 0";
else
    $sql .= " AND t.debet <> 0";
$sql .= " ORDER BY t.tanggal";
//echo $sql;

$total = 0;
$no = 0;
$res = QueryDb($sql);
while($row = mysqli_fetch_array($res))
{
    $total += (int)$row['jumlah'];
    $no += 1;
    ?>
    <tr height='22'>
        <td align='center'><?=$no?></td>
        <td align='left'><?=$row['tanggal']?></td>
        <td align='left'><?=$row['userid'] . " - " . $row['nama']?></td>
        <td align='right'><?=FormatRupiah($row['jumlah'])?></td>
        <td align='left'><?=$namapetugas?></td>
        <td align='left'><?=$row['keterangan']?></td>
    </tr>
<?php
}
?>
<tr height='25' style='background-color: #ddd'>
    <td colspan='3' align='right'><strong>TOTAL</strong></td>
    <td align='right'><strong><?=FormatRupiah($total)?></strong></td>
    <td colspan='2'>&nbsp;</td>
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>