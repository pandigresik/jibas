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
require_once('tagihan.func.php');

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Informasi Prosedur Pembayaran</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah.js"></script>
    <script language="javascript" src="../script/request.factory.js?r=<?=filemtime('../script/request.factory.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="tagihan.js?r=<?=filemtime('tagihan.js')?>"></script>
</head>

<body >
<table border="0" width="100%" height="100%">
<tr>
    <td align="center" valign="top" background="../images/bulu1.png" style="background-repeat:no-repeat">

    <table border="0" width="100%" align="center">
    <tr>
        <td align="left" valign="top">

            <table border="0"width="95%" align="center">
            <tr>
                <td align="right">
                    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;
                    </font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pembuatan Tagihan</font>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <a href="onlinepay.php">
                        <font size="1" color="#000000"><b>OnlinePay</b></font>
                    </a>&nbsp>&nbsp
                    <font size="1" color="#000000"><b>Pembuatan Tagihan</b></font>
                </td>
            </tr>
            <tr>
                <td align="left">&nbsp;</td>
            </tr>
            </table>
            <br />

        </td>
    </tr>
    </table>
    <br>

    <table border="0" width="100%" align="left">
    <tr>
        <td align="left" valign="top" width="10%">
            &nbsp;
        </td>
        <td align="left" valign="top" width="*">


            <table border="0" cellspacing="2" cellpadding="2">
            <tr style="height: 35px">
                <td width="120" align="left" valign="top">
                    <strong>Departemen:</strong>
                </td>
                <td align="left" valign="top">
<?php           $departemen = "";
                ShowSelectDepartemen() ?>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a><br>
                </td>
            </tr>
            <tr style="height: 35px">
                <td width="120" align="left" valign="top">
                    <strong>Tahun Buku:</strong>
                </td>
                <td align="left" valign="top">
                    <span id="divtahunbuku">
<?php
                    $idTahunBuku = "";
                    $tahunBuku = "";
                    ShowSelectTahunBuku()
?>
                    </span>
                </td>
            </tr>
            <tr style="height: 35px">
                <td width="120" align="left" valign="top">
                    <strong>Tingkat:</strong>
                </td>
                <td align="left" valign="top">
                    <span id="divtingkat">
<?php
                    $idTingkat = "";
                    $tingkat = "";
                    ShowSelectTingkat()
?>
                    </span>
                </td>
            </tr>
            <tr>
                <td width="120" align="left" valign="top">
                    <strong>Kelas:</strong><br><br>
                    &nbsp;&nbsp;&nbsp;
                    <img src="../images/selectall.png" width="16" onclick="checkKelas(1)" title="select all">&nbsp;&nbsp;
                    <img src="../images/deselectall.png" width="16" onclick="checkKelas(0)" title="deselect all">
                </td>
                <td align="left" valign="top">
                    <div id="divkelas" style="width: 600px; height: 150px; background-color: #ffffff; overflow: auto;">
<?php
                    ShowTableKelas();
?>
                    </div>
                </td>
            </tr>
            <tr style="height: 35px">
                <td width="120" align="left" valign="top">
                    <i>kecuali siswa (*):</i>
                </td>
                <td align="left" valign="top">
                    <textarea rows="2" cols="50" id="skiplist" class='inputbox' name="skiplist"></textarea><br>
                    (*) <i>NIS Siswa, pisahkan dengan koma</i><br><br>
                </td>
            </tr>
            <tr>
                <td width="120" align="left" valign="top">
                    <strong>Iuran:</strong><br><br>
                    &nbsp;&nbsp;&nbsp;
                    <img src="../images/selectall.png" width="16" onclick="checkIuran(1)" title="select all">&nbsp;&nbsp;
                    <img src="../images/deselectall.png" width="16" onclick="checkIuran(0)" title="deselect all">
                </td>
                <td align="left" valign="top">
                    <div id="diviuran" style="width: 600px; height: 150px; background-color: #ffffff; overflow: auto;">
<?php
                    ShowTableIuran();
?>
                    </div>
                    <br>
                </td>
            </tr>
            <tr style="height: 55px">
                <td width="120" align="left" valign="top">
                    <strong>Bulan Tagihan:</strong><br>
                </td>
                <td align="left" valign="top">
<?php
                ShowSelectBulan();
                ShowSelectTahun();
?>
                    <br><br>
                    <input type="radio" id="skipinvoice" name="checkpaid" checked="checked">&nbsp;<i>tidak dibuat tagihan apabila sudah membayar cicilan iuran di bulan ini</i><br><br>
                    <input type="radio" id="includeinvoice" name="checkpaid">&nbsp;<i>buat tagihan meski sudah membayar cicilan iuran di bulan ini</i>

                    <br><br>
                </td>
            </tr>
            <tr style="height: 35px">
                <td width="120" align="left" valign="top">
                    Keterangan:
                </td>
                <td align="left" valign="top">
                    <input type="text" id="keterangan" name="keterangan" class='inputbox' style="width: 450px" maxlength="255">
                </td>
            </tr>
            <tr style="height: 35px">
                <td width="120" align="left" valign="top">
                    Notifikasi:
                </td>
                <td align="left" valign="top">
                    <input type="checkbox" id="chnotif" name="chnotif" checked="checked">&nbsp;
                    kirim notifikasi tagihan lewat Jendela Sekolah | Telegram | SMS
                </td>
            </tr>
            <tr>
                <td width="120" align="left" valign="top">
                    &nbsp;
                </td>
                <td align="left" valign="top">
                    <br>
                    <input type="button" class="but" id="btBuatTagihan" value="Buat Tagihan" onclick="createInvoice()" style="width: 120px; height: 40px">
                    <span id="spBuatTagihan" style="color: #0000ff"></span>
                </td>
            </tr>
            </table>

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