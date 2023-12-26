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
require_once('konfigurasi.dialog.func.php');

OpenDb();

$idPt = $_REQUEST["idpt"];
$dept = $_REQUEST["dept"];

$idTabungan = 0;
$rekKasVendor = "";
$rekUtangVendor = "";
$maxTransVendor = 0;
$isReadOnly = "";

LoadValue();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Konfigurasi SchoolPay</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah2.js" ></script>
    <script language="javascript" src="../script/request.factory.js?r=<?=filemtime('../script/request.factory.js')?>"></script>
    <script language="javascript" src="konfigurasi.dialog.js?r=<?=filemtime('konfigurasi.dialog.js')?>"></script>
</head>

<body >
<table border="0" width="100%" cellpadding="10">
<tr>
    <td align="left" valign="top">

        <span style="font-size: 14pt">Konfigurasi Cashless Payment Siswa</span><br><br>

        <input type="hidden" id="idpt" name="idpt" value="<?=$idPt?>">
        <table border="0" width="100%" cellpadding="5" cellspacing="2">
        <tr>
            <td align="right" width="35%"><strong>Departemen:</strong></td>
            <td align="left" width="*"><input type="text" id="dept" name="dept" value="<?= $dept ?>" readonly ></td>
        </tr>
        <tr>
            <td align="right" valign="top">
                <strong>Auto Debet Tabungan:</strong>
            </td>
            <td align="left" valign="top">
<?php           ShowSelectTabunganSiswa() ?>
                <br>
                <span style="font-style: italic; color: blue">tabungan yang dijadikan sumber dana untuk pembayaran non tunai</span>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                <strong>Maksimum Transaksi per Hari</strong>
            </td>
            <td align="left" valign="top">
                <input type="text" id="maxtrans" name="maxtrans" value="<?= FormatRupiah($maxTransVendor) ?>" onblur="formatRupiah('maxtrans');" onfocus="unformatRupiah('maxtrans');"><br>
                <span style="font-style: italic; color: blue">maksimum total pembayaran per hari yang dapat dilakukan siswa (0 = tidak dibatasi). untuk pembayaran iuran sekolah tidak dibatasi oleh jumlah ini.
                </span>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                <strong>Rek Kas Vendor</strong>
            </td>
            <td align="left" valign="top">
<?php           ShowSelectRekVendor('HARTA', 'rekkasvendor', $rekKasVendor); ?>
                <br><span style="font-style: italic; color: blue">rekening Kas bagi Vendor untuk mencatat <strong>Hak Vendor</strong> dari transaksi pembayaran non tunai</span>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                <strong>Rek Utang Vendor</strong>
            </td>
            <td align="left" valign="top">
<?php           ShowSelectRekVendor('UTANG', 'rekutangvendor', $rekUtangVendor); ?>
                <br><span style="font-style: italic; color: blue">rekening Utang untuk mencatat <strong>Kewajiban Sekolah</strong> terhadap Vendor dari transaksi pembayaran non tunai</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <input type="button" class="but" value="Simpan" style="width: 80px; height: 30px;" onclick="simpanKonfigurasi()">
                <input type="button" class="but" value="Tutup" style="width: 80px; height: 30px;" onclick="window.close()">
            </td>
        </tr>
        <tr>
            <td colspan="2" align="left">
                <fieldset style="border-color: #d68d09; color: white; border-width: 1px; background-color: #ffffd4">
                    <legend style="background-color: #d68d09; color: white;">&nbsp;Perhatian:&nbsp;</legend>
                    <span style="color: black; font-size: 12px;">
                    Mohon diperhatikan pilihan dan pengaturan untuk Konfigurasi ini. Karena setelah digunakan dalam Transaksi, konfigurasi tidak dapat diubah kecuali Maksimum Transaksi per Hari.
                    </span>
                </fieldset>
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
