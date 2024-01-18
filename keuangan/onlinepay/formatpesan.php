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

$defaultNotifTagihan = "Kami informasikan {NAMA} {NIS} memiliki tagihan sebesar {JUMLAH} untuk {IURAN} bulan {BULAN} {TAHUN}";
$defaultNotifBayar = "Terima kasih, kami telah menerima pembayaran sebesar {JUMLAH} dari {NAMA} {NIS} untuk {TRANSAKSI}";

$dept = $_REQUEST["dept"] ?? "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Format Tagihan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="onlinepay.style.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/request.factory.js?r=<?=filemtime('../script/request.factory.js')?>"></script>
    <script language="javascript" src="formatpesan.js?r=<?=filemtime('formatpesan.js')?>"></script>
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
                    </font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Format Pesan Notifikasi</font>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <a href="onlinepay.php">
                        <font size="1" color="#000000"><b>OnlinePay</b></font>
                    </a>&nbsp>&nbsp
                    <font size="1" color="#000000"><b>Format Pesan Notifikasi</b></font>
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

            <br><br><br>
            <span style="font-size: 14px; font-weight: bold">Format Pesan Notifikasi Tagihan</span>&nbsp;&nbsp;&nbsp;
            <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a><br>
            <i>Format pesan yang akan dikirimkan yang berisi informasi tagihan</i>
            <br><br>
            <table id="table" border="1" cellpadding="5" style="border-width: 1px; border-collapse: collapse; border-color: #dddddd">
                <tr style="height: 30px">
                    <td class="header" width="30" align="center">No</td>
                    <td class="header" width="250" align="center">Departemen</td>
                    <td class="header" width="450" align="center">Pesan</td>
                </tr>
<?php
                $no = 0;
                $sql = "SELECT departemen FROM jbsakad.departemen WHERE aktif = 1 ORDER BY urutan";
                $res = QueryDb($sql);

                while($row = mysqli_fetch_row($res))
                {
                    $no += 1;

                    $pesan = "";
                    $sql = "SELECT pesan FROM jbsfina.formatpesanpg WHERE departemen = '".$row[0]."' AND kelompok = 'TAGIHAN'";
                    $res2 = QueryDb($sql);
                    if ($row2 = mysqli_fetch_row($res2))
                    {
                        $pesan = $row2[0];
                    }
                    else
                    {
                        $sql = "INSERT INTO jbsfina.formatpesanpg SET departemen = '".$row[0]."', pesan = '$defaultNotifTagihan', kelompok = 'TAGIHAN', issync = 0";
                        QueryDb($sql);

                        $pesan = $defaultNotifTagihan;
                    } ?>
                    <tr>
                        <td align="center" valign="top" style="background-color: #efefef"><?=$no?></td>
                        <td align="left" valign="top"><?=$row[0]?></td>
                        <td align="left" valign="top">
                            <input type="hidden" id="npdept<?=$no?>" name="npdept<?=$no?>" value="<?=$row[0]?>">
                            <textarea rows="3" cols="55" id="pesan<?=$no?>" class="inputbox" name="pesan<?=$no?>"><?=$pesan?></textarea>
                        </td>
                    </tr>
                    <?php
                }
            ?>
            <tr>
            </table>
            <br>
            <input type="hidden" id="ndept" name="ndept" value="<?=$no?>">
<?php       if (getLevel() != 2) { ?>
            <input type="button" class="but" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanPesanTagihan()">
            &nbsp;&nbsp;<span style="color: #0000ff; font-style: italic;" id="statuspesan"></span>
<?php       } ?>

            <br><br><br>
            <span style="font-size: 14px; font-weight: bold">Format Pesan Notifikasi Pembayaran</span><br>
            <i>Format pesan yang akan dikirimkan yang berisi informasi transaksi pembayaran yang dilakukan</i>
            <br><br>
            <table id="table" border="1" cellpadding="5" style="border-width: 1px; border-collapse: collapse; border-color: #dddddd">
                <tr style="height: 30px">
                    <td class="header" width="30" align="center">No</td>
                    <td class="header" width="250" align="center">Departemen</td>
                    <td class="header" width="450" align="center">Pesan</td>
                </tr>
                <?php
                $no = 0;
                $sql = "SELECT departemen FROM jbsakad.departemen WHERE aktif = 1 ORDER BY urutan";
                $res = QueryDb($sql);

                while($row = mysqli_fetch_row($res))
                {
                    $no += 1;

                    $pesan = "";
                    $sql = "SELECT pesan FROM jbsfina.formatpesanpg WHERE departemen = '".$row[0]."' AND kelompok = 'PEMBAYARAN'";
                    $res2 = QueryDb($sql);
                    if ($row2 = mysqli_fetch_row($res2))
                    {
                        $pesan = $row2[0];
                    }
                    else
                    {
                        $sql = "INSERT INTO jbsfina.formatpesanpg SET departemen = '".$row[0]."', pesan = '$defaultNotifBayar', kelompok = 'PEMBAYARAN', issync = 0";
                        QueryDb($sql);

                        $pesan = $defaultNotifBayar;
                    } ?>
                    <tr>
                        <td align="center" valign="top" style="background-color: #efefef"><?=$no?></td>
                        <td align="left" valign="top"><?=$row[0]?></td>
                        <td align="left" valign="top">
                            <input type="hidden" id="npdeptb<?=$no?>" name="npdeptb<?=$no?>" value="<?=$row[0]?>">
                            <textarea rows="3" cols="55" id="pesanb<?=$no?>" name="pesanb<?=$no?>"><?=$pesan?></textarea>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
            </table>
            <br>
<?php       if (getLevel() != 2) { ?>
            <input type="button" class="but" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanPesanPembayaran()">
            &nbsp;&nbsp;<span style="color: #0000ff; font-style: italic;" id="statuspesanb"></span>
<?php       } ?>
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