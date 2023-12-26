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
require_once('bank.dialog.func.php');

OpenDb();

$bankReplid = $_REQUEST["replid"];
$dept = $_REQUEST["dept"];

$bank = "";
$bankNo = "";
$bankName = "";
$bankLoc = "";
$keterangan = "";
$rekKas = "";
$rekPendapatan = "";
$useInTrans = false;

LoadValue();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Rekening Bank</title>
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
    <script language="javascript" src="bank.dialog.js?r=<?=filemtime('bank.dialog.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
</head>

<body >
<table border="0" width="100%" cellpadding="10">
<tr>
    <td align="left" valign="top">

    <span style="font-size: 14pt">Rekening Bank</span><br><br>

    <input type="hidden" id="bankreplid" name="bankreplid" value="<?=$bankReplid?>">
    <input type="hidden" id="dept" name="dept" value="<?=$dept?>">

    <table border="0" width="100%" cellpadding="5" cellspacing="2">
    <tr>
        <td align="right" width="22%"><strong>Bank:</strong></td>
        <td align="left" width="*"><input type="text" id="bank" class="inputbox" maxlength="255" name="bank" size="45" value="<?= $bank ?>" ></td>
    </tr>
    <tr>
        <td align="right"><strong>Cabang/Lokasi:</strong></td>
        <td align="left" width="*"><input type="text" id="bankloc" class="inputbox" maxlength="255" name="bankloc" size="45" value="<?= $bankLoc ?>" ></td>
    </tr>
    <tr>
        <td align="right" valign="top">
            <strong>No Rekening:</strong>
        </td>
        <td align="left" valign="top">
            <input type="text" id="bankno" class="inputbox" name="bankno" maxlength="100" size="45" value="<?= $bankNo ?>">
        </td>
    </tr>
    <tr>
        <td align="right" valign="top">
            <strong>Nama Rekening:</strong>
        </td>
        <td align="left" valign="top">
            <input type="text" id="bankname" class="inputbox" name="bankname" maxlength="255" size="45" value="<?= $bankName ?>">
        </td>
    </tr>
    <tr>
        <td align="right" valign="top">
            <strong>Rek. Kas:</strong>
        </td>
        <td align="left" valign="top">
<?php       ShowSelectRek('HARTA', 'rekkas', $rekKas); ?>
            <br><span style="font-style: italic; color: blue">rekening untuk melakukan jurnal penerimaan dari transaksi pembayaran online</span>
        </td>
    </tr>
    <tr>
        <td align="right" valign="top">
            <strong>Rek. Pendapatan:</strong>
        </td>
        <td align="left" valign="top">
<?php       ShowSelectRek('PENDAPATAN', 'rekpendapatan', $rekPendapatan); ?>
        </td>
    </tr>
    <tr>
        <td align="right" valign="top">
            Keterangan:
        </td>
        <td align="left" valign="top">
            <textarea rows="2" cols="45" id="keterangan" class="inputbox" name="keterangan"><?= $keterangan ?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <br>
            <input type="button" class="but" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanBank()">
            <input type="button" class="but" value="Tutup" style="width: 80px; height: 30px;"  onclick="window.close()">
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
