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
require_once('../library/date.func.php');

OpenDb();

$vendorId = $_REQUEST["vendorid"];
$departemen = $_REQUEST["departemen"];
$idTahunBuku = $_REQUEST["idtahunbuku"];

$deptPeg = "---";
$sql = "SELECT departemen
          FROM jbsfina.paymenttabungan
         WHERE jenis = 1";
$res = QueryDb($sql);
if ($row = mysqli_fetch_row($res))
    $deptPeg = $row[0];

// Ambil replid yg terlibat skrg supaya konsisten
$sql = "SELECT p.replid, p.tanggal
          FROM jbsfina.paymenttrans p, jbsakad.siswa s, jbsakad.angkatan a
         WHERE p.nis = s.nis
           AND s.idangkatan = a.replid
           AND a.departemen = '$departemen'
           AND p.vendorid = '$vendorId'
           AND p.idrefund IS NULL
           AND p.jenis = 2
           AND p.jenistrans = 0
           AND p.tanggal <= CURDATE()
         ORDER BY p.tanggal";
$res = QueryDb($sql);

$allIdPayment = "";
$lsIdPaymentTanggal = [];
$keyTanggal = "";
$lsTanggal = [];
while($row = mysqli_fetch_row($res))
{
    $replid = $row[0];
    $tanggal = $row[1];

    if ($allIdPayment != "") $allIdPayment .= ",";
    $allIdPayment .= $replid;

    $key = "#$tanggal@";
    if (!str_contains($keyTanggal, $key))
    {
        $lsTanggal[] = $tanggal;
        $keyTanggal .= $key;
        $lsIdPaymentTanggal[$tanggal] = $replid;
    }
    else
    {
        $lsIdPaymentTanggal[$tanggal] .= "," . $replid;
    }
}

if ($departemen == $deptPeg)
{
    // Tanggal dan tagihan transaksi pegawai
    $sql = "SELECT p.replid, p.tanggal
              FROM jbsfina.paymenttrans p
             WHERE p.idrefund IS NULL
               AND p.vendorid = '$vendorId'
               AND p.jenis = 1
               AND p.jenistrans = 0
               AND p.tanggal <= CURDATE()
             ORDER BY p.tanggal";

    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $replid = $row[0];
        $tanggal = $row[1];

        if ($allIdPayment != "") $allIdPayment .= ",";
        $allIdPayment .= $replid;

        $key = "#$tanggal@";
        if (!str_contains($keyTanggal, $key))
        {
            $lsTanggal[] = $tanggal;
            $keyTanggal .= $key;
            $lsIdPaymentTanggal[$tanggal] = $replid;
        }
        else
        {
            $lsIdPaymentTanggal[$tanggal] .= "," . $replid;
        }
    }
}

// Tanggal dan tagihan transaksi siswa
$lsTagihan = [];
$sql = "SELECT p.tanggal, IFNULL(SUM(p.jumlah), 0) AS jumlah
          FROM jbsfina.paymenttrans p
         WHERE p.replid IN ($allIdPayment)
         GROUP BY p.tanggal";
$res = QueryDb($sql);
while($row = mysqli_fetch_row($res))
{
    $tanggal = $row[0];
    $lsTagihan[$tanggal] = $row[1];
}

$nData = count($lsTanggal);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Refund Tagihan Penerimaan Vendor</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah2.js" ></script>
    <script language="javascript" src="../script/request.factory.js?r=<?=filemtime('../script/request.factory.js')?>"></script>
    <script language="javascript" src="refund.trans.js?r=<?=filemtime('refund.trans.js')?>"></script>
</head>

<body >
<table border="0" width="100%" cellpadding="10">
<tr>
    <td align="left" valign="top">

        <span style="font-size: 14pt">Refund Tagihan Penerimaan Vendor</span><br>
        <a href="#" onclick="location.reload()"><img src="../images/ico/refresh.png" border="0">&nbsp;refresh</a><br><br>

        <input type="hidden" id="vendorid" name="vendorid" value="<?=$vendorId?>">
        <input type="hidden" id="departemen" name="departemen" value="<?=$departemen?>">
        <input type="hidden" id="idtahunbuku" name="idtahunbuku" value="<?=$idTahunBuku?>">
        <input type="hidden" id="ndata" name="ndata" value="<?=$nData?>">

        <table id="table" border="1" cellpadding="5" cellspacing="0" style="border-width: 1px; border-collapse: collapse">
        <tr style="height: 30px">
            <td class="header" width="50" align="center">No</td>
            <td class="header" width="60" align="center">&nbsp;</td>
            <td class="header" width="200" align="center">Tanggal Transaksi</td>
            <td class="header" width="200" align="center">Tagihan</td>
        </tr>
<?php
        $total = 0;
        $no = 0;
        for($i = 0; $i < $nData; $i++)
        {
            $tanggal = $lsTanggal[$i];
            $tagihan = $lsTagihan[$tanggal];
            $stReplid = $lsIdPaymentTanggal[$tanggal];

            $no += 1;
            $total += $tagihan;
?>
            <tr style="height: 30px">
                <td align="center"><?=$no?></td>
                <td align="center">
                    <input type="checkbox" id="chTagihan<?=$no?>" name="chTagihan<?=$no?>" checked onchange="chTagihanChange(<?= $no ?>)">
                    <input type="hidden" id="tanggal<?=$no?>" name="tanggal<?=$no?>" value="<?=$tanggal?>">
                    <input type="hidden" id="tagihan<?=$no?>" name="tagihan<?=$no?>" value="<?=$tagihan?>">
                    <input type="hidden" id="replid<?=$no?>" name="replid<?=$no?>" value="<?=$stReplid?>">
                </td>
                <td align="center"><?= FormatMySqlDate($tanggal) ?></td>
                <td align="right"><?= FormatRupiah($tagihan) ?></td>
            </tr>
<?php
        }
?>
        </table>
        <br><br>
        <table border="0" cellpadding="2">
        <tr>
            <td width="150" align="right"><strong>Jumlah Pembayaran:</strong></td>
            <td>&nbsp;<input type="text" id="jumlah" name="jumlah" style="font-size: 14px; background-color: #fff6d0;" readonly value="<?= FormatRupiah($total) ?>"></td>
        </tr>
        <tr>
            <td width="150" align="right"><strong>Penerima:</strong></td>
            <td>&nbsp;
<?php
            $sql = "SELECT vu.userid, u.nama
                      FROM jbsfina.vendoruser vu, jbsfina.userpos u 
                     WhERE vu.userid = u.userid
                       AND vu.vendorid = '$vendorId'
                       AND vu.tingkat = 1
                     ORDER BY u.nama";
            $res = QueryDb($sql);

            echo "<select id='penerima' name='penerima' style='width: 250px'>";
            while($row = mysqli_fetch_row($res))
            {
                echo "<option value='".$row[0]."'>".$row[1]."</option>";
            }
            echo "</select>";
?>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">Keterangan</td>
            <td>&nbsp;<textarea rows="2" cols="45" id="keterangan" name="keterangan"></textarea></td>
        </tr>
            <tr>
                <td align="right">&nbsp;</td>
                <td>
                    <br>
                    <input type="button" class="but" style="width: 70px; height: 30px;" value="Bayar" onclick="bayarTagihan()">
                    <input type="button" class="but" style="width: 70px; height: 30px;" value="Tutup" onclick="window.close()">
                </td>
            </tr>
        </table>



    </td>
</tr>
</table>
</body>
</html>
<?php
CloseDb();
?>
