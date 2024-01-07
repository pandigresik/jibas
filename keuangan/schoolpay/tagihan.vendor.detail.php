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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/errorhandler.php');
require_once('../library/stringbuilder.php');
require_once('../library/date.func.php');
require_once('tagihan.vendor.func.php');

OpenDb();

$dept = $_REQUEST["dept"];
$vendorId = $_REQUEST["vendorid"];
$jenis = $_REQUEST["jenis"];

$vendorName = "";
$sql = "SELECT nama FROM jbsfina.vendor WHERE vendorid = '".$vendorId."'";
$res = QueryDb($sql);
if ($row = mysqli_fetch_row($res))
    $vendorName = $row[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JIBAS KEU [Penerimaan Vendor Yang Belum Di Refund]</title>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
</head>

<body>


<center><font size="4"><strong>PENERIMAAN VENDOR YANG BELUM DI REFUND</strong></font></center>
<br />

<table border="0" cellpadding="2">
<tr>
    <td width="100" align="right">Departemen: </td>
    <td align="left"><?= $dept ?></td>
</tr>
<tr>
    <td width="100" align="right">Vendor: </td>
    <td align="left"><?= "$vendorName ($vendorId)" ?></td>
</tr>
<tr>
    <td width="100" align="right">Kelompok: </td>
    <td align="left"><?= $jenis == 1 ? "Pegawai" : "Siswa" ?></td>
</tr>
</table>

<?php
    if ($jenis == 2)
    {
        $sql = "SELECT p.replid, DATE_FORMAT(p.waktu, '%d-%b-%Y %H:%i') AS waktu, v.nama AS vendor, u.nama AS petugas, 
                   p.jumlah, p.jenistrans, p.keterangan, IFNULL(dp.nama, '') AS penerimaan,
                   IF(p.valmethod = 1, 'PIN', 'Agreement') AS valmethod, p.transactionid,
                   IFNULL(p.idrefund, 0) AS idrefund,  
                   IF(r.waktu IS NULL, '(belum refund)', DATE_FORMAT(r.waktu, '%d-%b-%Y %H:%i')) AS refund,
                   IFNULL(p.nis, '') AS userid, p.jumlah, IFNULL(s.nama, '') AS username
              FROM jbsfina.paymenttrans p
             INNER JOIN jbsfina.vendor v ON p.vendorid = v.vendorid
             INNER JOIN jbsfina.userpos u ON p.userid = u.userid
             INNER JOIN jbsakad.siswa s ON p.nis = s.nis
             INNER JOIN jbsakad.angkatan a ON s.idangkatan = a.replid AND a.departemen = '$dept'
              LEFT JOIN jbsfina.datapenerimaan dp ON p.iddatapenerimaan = dp.replid
              LEFT JOIN jbsfina.refund r ON p.idrefund = r.replid
             WHERE p.vendorid = '$vendorId'
               AND p.jenistrans = 0
               AND p.jenis = 2
               AND p.idrefund IS NULL  
             ORDER BY p.waktu DESC";
    }
    else
    {
        $sql = "SELECT p.replid, DATE_FORMAT(p.waktu, '%d-%b-%Y %H:%i') AS waktu, v.nama AS vendor, u.nama AS petugas, 
                       p.jumlah, p.jenistrans, p.keterangan, IFNULL(dp.nama, '') AS penerimaan,
                       IF(p.valmethod = 1, 'PIN', 'Agreement') AS valmethod, p.transactionid,
                       IFNULL(p.idrefund, 0) AS idrefund,  
                       IF(r.waktu IS NULL, '(belum refund)', DATE_FORMAT(r.waktu, '%d-%b-%Y %H:%i')) AS refund,
                       IFNULL(p.nip, '') AS userid, p.jumlah, IFNULL(pg.nama, '') AS username
                  FROM jbsfina.paymenttrans p
                 INNER JOIN jbsfina.vendor v ON p.vendorid = v.vendorid
                 INNER JOIN jbsfina.userpos u ON p.userid = u.userid
                 INNER JOIN jbssdm.pegawai pg ON p.nip = pg.nip
                  LEFT JOIN jbsfina.datapenerimaan dp ON p.iddatapenerimaan = dp.replid
                  LEFT JOIN jbsfina.refund r ON p.idrefund = r.replid
                 WHERE p.vendorid = '$vendorId'
                   AND p.jenistrans = 0
                   AND p.jenis = 1
                   AND p.idrefund IS NULL  
                 ORDER BY p.waktu DESC";
    }


?>
    <br>
    <table border="1" id="table" style="border-width: 1px; border-collapse: collapse;" cellpadding="2" cellspacing="0">
    <tr style="height: 30px">
        <td align="center" class="header" width="30">No</td>
        <td align="center" class="header" width="150">Waktu</td>
        <td align="center" class="header" width="200">Pelanggan</td>
        <td align="center" class="header" width="150">Jumlah</td>
        <td align="center" class="header" width="150">Petugas</td>
        <td align="center" class="header" width="250">Keterangan</td>
    </tr>
<?php
    $total = 0;
    $no = 0;
    $res = QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        $total += $row["jumlah"];
        ?>
        <tr style="height: 30px">
            <td align="center"><?=$no?></td>
            <td align="left"><?=$row["waktu"]?></td>
            <td align="left"><?= $row["username"] . " (" . $row["userid"] .")" ?></td>
            <td align="right"><?=FormatRupiah($row["jumlah"])?></td>
            <td align="left"><?= $row["petugas"]?></td>
            <td align="left"><?= "ID: " . $row["transactionid"] . "<br>" . $row["keterangan"] ?></td>
        </tr>
<?php
    }
?>
    <tr style="height: 30px">
        <td align="right" style="background-color: #dedede" colspan="3"><strong>TOTAL</strong></td>
        <td align="right" style="background-color: #dedede; font-weight: bold"> <?=FormatRupiah($total)?></td>
        <td align="left" style="background-color: #dedede" colspan="2">&nbsp</td>
    </tr>
    </table>




</body>
</html>
<?php
CloseDb();
?>

