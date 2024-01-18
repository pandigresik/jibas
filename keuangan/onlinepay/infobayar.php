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

$dept = $_REQUEST["dept"] ?? "";
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
    <script language="javascript" src="../script/request.factory.js?r=<?=filemtime('../script/request.factory.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="infobayar.js?r=<?=filemtime('infobayar.js')?>"></script>
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
                    </font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Informasi Prosedur Pembayaran</font>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <a href="onlinepay.php">
                        <font size="1" color="#000000"><b>OnlinePay</b></font>
                    </a>&nbsp>&nbsp
                    <font size="1" color="#000000"><b>Informasi Prosedur Pembayaran</b></font>
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

            <table border="0" cellpadding="2" cellspacing="2">
            <tr>
                <td align="left">
                    <span style="font-weight: bold; font-size: 14px">Departemen:</span>&nbsp;
                    <select id="dept" name="dept" class="inputbox" style="width: 250px" onchange="changeDept()">
<?php
                    $sql = "SELECT departemen FROM jbsakad.departemen WHERE aktif = 1 ORDER BY urutan";
                    $res = QueryDb($sql);
                    while($row = mysqli_fetch_row($res))
                    {
                        if ($dept == "") $dept = $row[0];
                        $sel = ($dept == $row[0]) ? "selected" : "";

                        echo "<option value='".$row[0]."' $sel>".$row[0]."</option>";
                    }
?>
                    </select>&nbsp;&nbsp;
                    <a href="#" onclick="location.reload();"
                       style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <br>
                    <span style="font-weight: bold; font-size: 14px">Prosedur Pembayaran</span><br>
                    <span style="font-style: italic; font-size: 11px">Informasi mengenai prosedur pembayaran, seperti: syarat dan ketentuan umum, cara transfer, konfirmasi pembayaran, nomor untuk notifikasi sudah transfer dan lama waktu verifikasi pembayaran</span>
                </td>
            </tr>
            <tr>
                <td align="left">
<?php
                    $id = 0;
                    $info = "";

                    $sql = "SELECT replid, info FROM jbsfina.infobayar WHERE departemen = '$dept' AND bagian = 'bayar'";
                    $res = QueryDb($sql);
                    if ($row = mysqli_fetch_row($res))
                    {
                        $id = $row[0];
                        $info = $row[1];
                    }
?>
                    <input type="hidden" id="idinfobayar" name="idinfobayar" value="<?=$id?>">
                    <textarea rows="10" cols="120" id="infobayar" name="infobayar" class="inputbox" style="font-size: 12px"><?=$info?></textarea><br>
<?php               if (getLevel() != 2) { ?>
                        <input type="button" class="but" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanInfo('bayar', '#idinfobayar', '#infobayar', '#statusinfobayar')">&nbsp;&nbsp;
                        <span id="statusinfobayar" name="statusinfobayar" style="font-style: italic; font-size: 12px; color: #0000ff"></span>
<?php               } ?>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <br>
                    <span style="font-weight: bold; font-size: 14px">Prosedur Pembatalan</span><br>
                    <span style="font-style: italic; font-size: 11px">Informasi mengenai prosedur pembatalan pembayaran, seperti: bisa atau tidak dilakukan pembatalan, nomor yang dihubungi untuk pengajuan pembatalan, lama waktu proses pembatalan</span>
                </td>
            </tr>
            <tr>
                <td align="left">
<?php
                    $id = 0;
                    $info = "";

                    $sql = "SELECT replid, info FROM jbsfina.infobayar WHERE departemen = '$dept' AND bagian = 'batal'";
                    $res = QueryDb($sql);
                    if ($row = mysqli_fetch_row($res))
                    {
                        $id = $row[0];
                        $info = $row[1];
                    }
?>
                    <input type="hidden" id="idinfobatal" name="idinfobatal" value="<?=$id?>">
                    <textarea rows="10" cols="120" id="infobatal" name="infobatal" class="inputbox" style="font-size: 12px"><?=$info?></textarea><br>
<?php               if (getLevel() != 2) { ?>
                        <input type="button" class="but" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanInfo('batal', '#idinfobatal', '#infobatal', '#statusinfobatal')">
                        <span id="statusinfobatal" name="statusinfobatal" style="font-style: italic; font-size: 12px; color: #0000ff"></span>
<?php               } ?>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <br>
                    <span style="font-weight: bold; font-size: 14px">Prosedur Pengembalian</span><br>
                    <span style="font-style: italic; font-size: 11px">Informasi mengenai prosedur pengembalian pembayaran, seperti: bisa atau tidak dilakukan pengembalian, nomor yang dihubungi untuk informasi pengembalian, lama waktu proses pengembalian</span>
                </td>
            </tr>
            <tr>
                <td align="left">
<?php
                    $id = 0;
                    $info = "";

                    $sql = "SELECT replid, info FROM jbsfina.infobayar WHERE departemen = '$dept' AND bagian = 'kembali'";
                    $res = QueryDb($sql);
                    if ($row = mysqli_fetch_row($res))
                    {
                        $id = $row[0];
                        $info = $row[1];
                    }
?>
                    <input type="hidden" id="idinfokembali" name="idinfokembali" value="<?=$id?>">
                    <textarea rows="10" cols="120" id="infokembali" name="infokembali" class="inputbox" style="font-size: 12px"><?=$info?></textarea><br>
<?php               if (getLevel() != 2) { ?>
                        <input type="button" class="but" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanInfo('kembali', '#idinfokembali', '#infokembali', '#statusinfokembali')">
                        <span id="statusinfokembali" name="statusinfokembali" style="font-style: italic; font-size: 12px; color: #0000ff"></span>
<?php               } ?>
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