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
require_once('onlinepay.util.func.php');
require_once('riwayattrans.func.php');

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Riwayat Transaksi</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="../script/themes/ui-lightness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/ui/jquery-ui.custom.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/dateutil.js"></script>
    <script language="javascript" src="../script/stringutil.js"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="riwayattrans.js?r=<?=filemtime('riwayattrans.js')?>"></script>
</head>

<body >
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<tr>
    <td align="center" valign="top" background="../images/bulu1.png" style="background-repeat:no-repeat">

        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="height: 1200px"  align="left">
        <tr>
            <td align="left" valign="top" style="width: 25px; border-right: 1px solid;" rowspan="2" >

                <br><br><br><br>

                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td align="left" valign="middle" width="50%">
                        <input type="button" id="btcolmenu" value="   <   " class="but" onclick="changeColMenu()">
                    </td>
                    <td align="right" valign="middle" width="50%">
                        <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a>
                        &nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                </table>
                <br>

                <div id="dvcolmenu" style="width: 370px; overflow: auto">

                <table id="tabSelection" border="0" cellspacing="0" cellpadding="5" width="100%">
                <tr>
                    <td><strong>Laporan:</strong></td>
                    <td>
                        <select id="laporan" class="inputbox" style="width: 250px" onchange="clearContent()">
                            <option value="RIWAYAT" selected>Riwayat Harian</option>
                            <option value="REKAP">Rekap Jumlah</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="25%"><strong>Departemen:</strong></td>
                    <td width="75%">
<?php               $departemen = "";
                    ShowSelectDepartemen(); ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Tanggal:</strong></td>
                    <td>
                        <input type="hidden" id="dttanggal1" value="<?= date('Y-m-d', strtotime("-1 months")) ?>">
                        <input type="text" id="tanggal1" name="tanggal1" class="inputbox" value="<?= formatInaMySqlDate(date('Y-m-d', strtotime("-1 months"))) ?>" style="width: 90px" onclick="showDatePicker1()">
                        s/d
                        <input type="hidden" id="dttanggal2" value="<?= date('Y-m-d') ?>">
                        <input type="text" id="tanggal2" name="tanggal2" class="inputbox" value="<?= formatInaMySqlDate(date('Y-m-d')) ?>" style="width: 90px" onclick="showDatePicker2()">
                    </td>
                </tr>
                <tr>
                    <td><strong>Metode:</strong></td>
                    <td>
                        <select id="metode" class="inputbox" style="width: 250px">
                            <option value="0" selected>Semua Metode Transaksi</option>
                            <option value="1">Pembayaran Tagihan</option>
                            <option value="2">Pembayaran Keranjang</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Jenis:</strong></td>
                    <td>
                        <select id="pembayaran" class="inputbox" style="width: 250px" onchange="changePembayaran()">
                            <option value="ALL" selected>Semua Jenis Pembayaran</option>
                            <option value="JTT">Iuran Wajib</option>
                            <option value="SKR">Iuran Sukarela</option>
                            <option value="SISTAB">Tabungan Siswa</option>
                        </select>
                    </td>
                </tr>
                <tr id="trJenisData" style="visibility: hidden; display: none">
                    <td><strong><span id="namaJenis"></span></strong></td>
                    <td>
                        <div id="dvJenis"></div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Siswa:</strong></td>
                    <td>
                        <select id="siswa" class="inputbox" style="width: 250px" onchange="changeSiswa(); clearContent();">
                            <option value="ALL" selected>Semua Siswa</option>
                            <option value="PILIH">Pilih Siswa</option>
                        </select>
                    </td>
                </tr>
                <tr id="trPilihSiswa" style="visibility: hidden; display: none">
                    <td colspan="2">
                        <input type="text" name="noid" id="noid" class="inputbox" readonly style="background-color: #efefef; font-size: 14px; width: 120px">
                        <input type="text" name="nama" id="nama" class="inputbox" readonly style="background-color: #efefef; font-size: 14px; width: 150px">
                        <input type="button" class="but" value="pilih" style="width: 50px; height: 23px;" onclick="SearchUser()">
                        <input type="hidden" name="kelompok" id="kelompok">
                        <input type="hidden" name="kelas" id="kelas">
                    </td>
                </tr>
                <tr>
                    <td><strong>Bank:</strong></td>
                    <td>
<?php                   ShowSelectBank() ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Petugas:</strong></td>
                    <td>
<?php                   ShowSelectPetugas() ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" align="center">
                        <br><br>
                        <input type="button" style="width: 150px; height: 40px" value="Lihat Transaksi" class="but" onclick="showTrans()">
                    </td>
                </tr>
                </table>
                <br>


                </div>

            </td>
            <td align="left" valign="top" width="*" style="height: 60px;">

                <table border="0" width="95%" align="center">
                <tr>
                    <td align="right">
                        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;
                        </font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Riwayat Transaksi</font>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <a href="onlinepay.php">
                            <font size="1" color="#000000"><b>OnlinePay</b></font>
                        </a>&nbsp;&nbsp;
                        <font size="1" color="#000000"><b>Riwayat Transaksi</b></font>
                    </td>
                </tr>
                </table>


            </td>
        </tr>
        <tr>
            <td width="*" align="left" valign="top">

                <div id="dvContent" style="background-color: #FFFFFF; width: 100%; height: auto; overflow: auto; padding: 5px;">
                    <br><span style="font-size: 14px; color: #999; font-weight: bold;">Klik tombol "Lihat Transaksi" di panel kiri untuk menampilkan Riwayat Transaksi</span>

                </div>
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