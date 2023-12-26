<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 3.0 (January 09, 2013)
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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('onlinepay.func.php');
require_once('pgservice.config.php');
require_once('pgserver.config.php');
require_once('pgschoolid.config.php');
require_once('appserver.config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JIBAS Online Payment</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="onlinepay.js?r=<?=filemtime('onlinepay.js')?>"></script>
</head>

<body>
<p align="left">&nbsp;&nbsp;<font size="5" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<span class="style2">
<font face="Verdana" size="3" color="Gray"><strong>ONLINE PAYMENT</strong></font></span></p>
<br><br><br>
<table width="676" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td><img src="../images/jspay2_01.jpg" width="30" height="94" alt=""></td>
    <td><img src="../images/jspay2_02.jpg" width="75" height="94" alt=""></td>
    <td width="302" height="110" valign="top" align="left">
        &bull;&nbsp;<a href="http://www.jibas.net/content/paygate/registrasi.php" target="_blank">Aktifasi JIBAS Payment Gateway<br>
        &bull;&nbsp;<a href="appserver.php">Sinkronisasi Jendela Sekolah</a> <?php CheckJsSyncAddrConfig() ?><br>
        &bull;&nbsp;<a href="kodesekolah.php">Kode Sekolah &amp; Biaya Layanan</a> <?php CheckPgServiceConfig() ?><br>
        <hr style="border: 1px dashed #999; width: 300px;">
        &bull;&nbsp;<a href="bank.php">Rekening Bank</a> <?php CheckBankConfig() ?><br>
        &bull;&nbsp;<a href="infobayar.php">Informasi Prosedur Pembayaran</a> <?php CheckInfoBayarConfig() ?><br>
        &bull;&nbsp;<a href="formattagihan.php">Kode Awalan Nomor Tagihan</a> <?php CheckFormatNomorTagihanConfig() ?><br>
        &bull;&nbsp;<a href="formatpesan.php">Format Pesan Notifikasi</a> <?php CheckPesanPgConfig() ?><br>
    </td>
    <td><img src="../images/jspay2_04.jpg" width="133" height="94" alt=""></td>
    <td><img src="../images/jspay2_05.jpg" width="136" height="94" alt=""></td>
</tr>
<tr>
    <td><img src="../images/jspay2_06.jpg" width="30" height="58" alt=""></td>
    <td><img src="../images/jspay2_07.jpg" width="75" height="58" alt=""></td>
    <td><img src="../images/jspay2_08.jpg" width="302" height="58" alt=""></td>
    <td><img src="../images/jspay2_09.jpg" width="133" height="58" alt=""></td>
    <td><img src="../images/jspay2_10.jpg" width="136" height="58" alt=""></td>
</tr>
<tr>
    <td><img src="../images/jspay2_11.jpg" width="30" height="89" alt=""></td>
    <td><img src="../images/jspay2_12.jpg" width="75" height="89" alt=""></td>
    <td width="302" height="89" valign="top" align="left">
        <br>
        &bull;&nbsp;<a href="#" onclick="checkAllConfigReady(1)">Buat Tagihan per Kelas</a><br>
        &bull;&nbsp;<a href="#" onclick="checkAllConfigReady(2)">Buat Tagihan per Siswa</a><br>
        &bull;&nbsp;<a href="#" onclick="checkAllConfigReady(3)">Daftar Tagihan</a><br>
        &bull;&nbsp;<a href="#" onclick="checkAllConfigReady(4)">Cari Tagihan</a><br>
    </td>
    <td><img src="../images/jspay2_14.jpg" width="133" height="89" alt=""></td>
    <td><img src="../images/jspay2_15.jpg" width="136" height="89" alt=""></td>
</tr>
<tr>
    <td><img src="../images/jspay2_16.jpg" width="30" height="128" alt=""></td>
    <td><img src="../images/jspay2_17.jpg" width="75" height="128" alt=""></td>
    <td><img src="../images/jspay2_18.jpg" width="302" height="128" alt=""></td>
    <td><img src="../images/jspay2_19.jpg" width="133" height="128" alt=""></td>
    <td><img src="../images/jspay2_20.jpg" width="136" height="128" alt=""></td>
</tr>
<tr>
    <td><img src="../images/jspay2_21.jpg" width="30" height="156" alt=""></td>
    <td>
        <a href="http://jibas.net/content/jendelasekolah/jendelasekolah.php" title="JIBAS Jendela Sekolah" target="_blank">
            <img src="../images/jspay2_22.jpg" width="75" height="156" alt="JIBAS Jendela Sekolah" border="0">
        </a>
    </td>
    <td>
        <a href="http://jibas.net/content/jendelasekolah/jendelasekolah.php" title="JIBAS Jendela Sekolah" target="_blank">
            <img src="../images/jspay2_23.jpg" width="302" height="156" alt="JIBAS Jendela Sekolah" border="0">
        </a>
    </td>
    <td>
        <a href="https://paygate.jendelasekolah.id" title="JIBAS Payment Gateway" target="_blank">
            <img src="../images/jspay2_24.jpg" width="133" height="156" alt="JIBAS Payment Gateway" border="0">
        </a>
    </td>
    <td><img src="../images/jspay2_25.jpg" width="136" height="156" alt=""></td>
</tr>
<tr>
    <td><img src="../images/jspay2_26.jpg" width="30" height="155" alt=""></td>
    <td><img src="../images/jspay2_27.jpg" width="75" height="155" alt=""></td>
    <td><img src="../images/jspay2_28.jpg" width="302" height="155" alt=""></td>
    <td><img src="../images/jspay2_29.jpg" width="133" height="155" alt=""></td>
    <td><img src="../images/jspay2_30.jpg" width="136" height="155" alt=""></td>
</tr>
<tr>
    <td><img src="../images/jspay2_31.jpg" width="30" height="107" alt=""></td>
    <td><img src="../images/jspay2_32.jpg" width="75" height="107" alt=""></td>
    <td><img src="../images/jspay2_33.jpg" width="302" height="107" alt=""></td>
    <td><img src="../images/jspay2_34.jpg" width="133" height="107" alt=""></td>
    <td><img src="../images/jspay2_35.jpg" width="136" height="107" alt=""></td>
</tr>
<tr>
    <td width="407" height="150" valign="top" colspan="3">&nbsp;</td>
    <td width="269" height="150" colspan="2" valign="top">
        <br>
        &bull;&nbsp;<a href="#" onclick="checkAllConfigReady(5)">Riwayat Transaksi</a><br>
        &bull;&nbsp;<a href="#" onclick="checkAllConfigReady(6)">Saldo Bank</a><br>
        &bull;&nbsp;<a href="#" onclick="checkAllConfigReady(7)">Mutasi Bank</a><br>
        &bull;&nbsp;<a href="#" onclick="checkAllConfigReady(8)">Kelebihan Pembayaran</a><br>
        &bull;&nbsp;<a href="#" onclick="checkAllConfigReady(9)">Statistik</a><br>
    </td>
</tr>
</table>
</body>
</html>
