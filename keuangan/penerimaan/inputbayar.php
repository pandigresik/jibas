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
require_once('inputbayar.func.php');

if (getLevel() == 2)
{ ?>
    <script language="javascript">
        alert('Maaf, anda tidak berhak mengakses halaman ini!');
        document.location.href = "penerimaan.php";
    </script>
    <?php 	exit();
} // end if

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
    <script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
    <link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah.js"></script>
    <script language="javascript" src="inputbayar.js"></script>
</head>

<body>

<table border="0" width="100%" height="100%">
<tr><td align="center" valign="top" background="../images/bulu1.png" style="background-repeat:no-repeat">

<table border="0" width="100%" align="center">
<tr><td align="left" valign="top">

    <table border="0" width="95%" align="center">
    <tr>
        <td align="right">
            <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
            <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Besar Pembayaran</font>
        </td>
    </tr>
    <tr>
        <td align="right">
            <a href="../penerimaan.php">
            <font size="1" color="#000000"><b>Penerimaan</b></font></a>&nbsp>&nbsp
            <font size="1" color="#000000"><b>Besar Pembayaran</b></font>
        </td>
    </tr>
    <tr>
        <td align="left">&nbsp;</td>
    </tr>
    </table><br />


    <table border="0" cellpadding="2" cellspacing="5" align="left">
    <tr>
        <td width="160">&nbsp;</td>
        <td colspan="3">
            <span style="color: #666; font-style: italic">Menentukan besar pembayaran yang harus dibayarkan siswa/calon siswa per jenis penerimaan</span>
            <br><br>
        </td>
    </tr>
    <tr>
        <td width="160">&nbsp;</td>
        <td width="180"><strong>Kategori&nbsp;</strong></td>
        <td width="250">
<?php      $idKategori = "";
        ShowSelectKategori() ?>
        </td>
        <td width="260">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><strong>Departemen</strong></td>
        <td>
<?php      $departemen = "";
        ShowSelectDepartemen() ?>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><strong>Jenis Penerimaan</strong></td>
        <td>
        <div id="divPenerimaan">
<?php      ShowSelectPenerimaan($departemen, $idKategori) ?>
        </div>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><strong>Besar Total Pembayaran</strong></td>
        <td>
            <input type="text" style="font-size: 14px; width: 200px; background-color: #fdffc7" id="besar" onblur="formatRupiah('besar')" onfocus="unformatRupiah('besar')">

        </td>
        <td><span style="color: #666; font-style: italic">besar total pembayaran yang harus dilunasi</span></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><strong>Besar Cicilan</strong></td>
        <td>
            <input type="text" style="font-size: 14px; width: 200px; background-color: #fdffc7" id="cicilan" onblur="formatRupiah('cicilan')" onfocus="unformatRupiah('cicilan')">
        </td>
        <td><span style="color: #666; font-style: italic">besar cicilan pembayaran yang dibayarkan ketika membayar</span></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><strong>Cicilan Pertama</strong></td>
        <td>
            <input type="checkbox" id="cicilanpertama">&nbsp;set cicilan pertama Rp 0
        </td>
        <td><span style="color: #666; font-style: italic">set cicilan pertama Rp 0 supaya muncul di Laporan Tunggakan</span></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><span id="lbTingkat" style="font-weight: bold">Tingkat</span></td>
        <td>
            <div id="divTingkat">
<?php       $idTingkat = 0;
            ShowSelectTingkatSiswa($departemen) ?>
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td valign="top"><span id="lbKelas" style="font-weight: bold">Kelas</span></td>
        <td>
            <div id="divKelas">
<?php       ShowSelectKelasSiswa($departemen, $idTingkat) ?>
            </div>
        </td>
        <td valign="top"><span style="color: #666; font-style: italic">pilih minimal satu kelas</span></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td>
            <input type="button" class="but" style="height: 30px; width: 100px;" value="Simpan" onclick="simpan()">
        </td>
        <td><span style="color: #666; font-style: italic">Siswa/calon siswa yang sudah terdata besar pembayarannya, tidak akan di data ulang besar pembayarannya</span></td>
    </tr>
    </table>


</td></tr>
</table>

</td></tr>
</table>
</body>

</html>
<?php
CloseDb();
?>
