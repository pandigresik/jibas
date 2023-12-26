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
require_once('../library/departemen.php');
require_once('../include/errorhandler.php');

OpenDb();

$idTagihanData = $_REQUEST["idtagihandata"];
$noTagihan = $_REQUEST["notagihan"];

$sql = "SELECT td.idbesarjtt, td.status, b.besar, b.cicilan, td.idpenerimaan, td.penerimaan, td.jtagihan, td.jdiskon
          FROM jbsfina.tagihansiswadata td, jbsfina.besarjtt b
         WHERE td.idbesarjtt = b.replid
           AND td.replid = $idTagihanData";
$res = QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    echo "Data tagihan tidak ditemukan!";
    return;
}

$row = mysqli_fetch_row($res);
$idBesarJtt = $row[0];
$status = $row[1];
$besarJtt = $row[2];
$cicilanJtt = $row[3];
$idPenerimaan = $row[4];
$penerimaan = $row[5];
$tagihan = $row[6];
$diskon = $row[7];

$jumlahBayar = 0;
$jumlahSisa = 0;
$sql = "SELECT SUM(jumlah) + SUM(info1)
          FROM jbsfina.penerimaanjtt
         WHERE idbesarjtt = $idBesarJtt";
$res = QueryDb($sql);
if ($row = mysqli_fetch_row($res))
{
    $jumlahBayar = $row[0];
    $jumlahSisa = $besarJtt - $jumlahBayar;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Data Tagihan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah2.js" ></script>
    <script language="javascript" src="../script/request.factory.js?r=<?=filemtime('../script/request.factory.js')?>"></script>
    <script language="javascript" src="daftartagihan.edit.js?r=<?=filemtime('daftartagihan.edit.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
</head>

<body >
<table border="0" width="100%" cellpadding="10">
<tr>
    <td align="left" valign="top">

    <span style="font-size: 14pt">Data Tagihan</span><br><br>
    <input type="hidden" id="idtagihandata" value="<?=$idTagihanData?>">
    <input type="hidden" id="notagihan" value="<?=$noTagihan?>">
    <table border="0" cellspacing="0" cellpadding="5">
    <tr>
        <td width="100px" align="right" valign="top">Penerimaan:</td>
        <td width="300px" align="left" valign="top"><span style="font-size: 16px;"><?=$penerimaan?></span><br>
            <table border="1" cellpadding="5" cellspacing="0" style="border: 1px #efefef; border-collapse: collapse;">
            <tr>
                <td align="right" width="90px"><i>Besar Iuran</i></td>
                <td align="left" width="120px"><i><?= FormatRupiah($besarJtt) ?></i></td>
            </tr>
            <tr>
                <td align="right" valign="middle"><i>Cicilan</i></td>
                <td align="left">
                    <i><?= FormatRupiah($cicilanJtt) ?></i>
                    <input type="hidden" id="jcicilan" value="<?=$cicilanJtt?>">
                </td>
            </tr>
            <tr>
                <td align="right" valign="middle"><i>Terbayarkan</i></td>
                <td align="left"><i><?= FormatRupiah($jumlahBayar) ?></i></td>
            </tr>
            <tr>
                <td align="right" valign="middle"><i>Sisa</i></td>
                <td align="left">
                    <i><?= FormatRupiah($jumlahSisa) ?></i>
                    <input type="hidden" id="jsisa" value="<?=$jumlahSisa?>">
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="right" valign="top">Tagihan:</td>
        <td align="left" valign="top">
            <input type="text" id="jtagihan" value="<?= FormatRupiah($tagihan) ?>"
                   style="width: 150px; font-size: 16px;" class="inputbox" onfocus="unformatRupiah('jtagihan');" onblur="formatRupiah('jtagihan')" onkeyup="calcPay1()">
        </td>
    </tr>
    <tr>
        <td align="right" valign="top">Diskon:</td>
        <td align="left" valign="top">
            <input type="text" id="jdiskon" value="<?= FormatRupiah($diskon) ?>"
                   style="width: 150px; font-size: 16px;" class="inputbox" onfocus="unformatRupiah('jdiskon');" onblur="formatRupiah('jdiskon')" onkeyup="calcPay2()">
        </td>
    </tr>
    <tr>
        <td align="right" valign="top">Pembayaran:</td>
        <td align="left" valign="top">
            <span id="jpembayaran" style="font-size:16px;"><?= FormatRupiah($tagihan - $diskon)?> </span>
        </td>
    </tr>
    <tr>
        <td align="right" valign="top">&nbsp;</td>
        <td align="left" valign="top">
            <input type="button" id="btSimpan" class="but" style="height: 35px; width: 80px" value="Simpan" onclick="simpanEdit()">
            <input type="button" class="but" style="height: 35px; width: 80px" value="Tutup" onclick="window.close()"><br>
            <span style="color: blue; font-size: 12px; font-style: italic;" id="spInfo"></span>
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
