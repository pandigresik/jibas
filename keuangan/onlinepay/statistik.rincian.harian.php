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
//require_once('onlinepay.util.func.php');
//require_once('riwayattrans.func.php');
require_once('statistik.rincian.harian.func.php');

OpenDb();

$tanggal = $_REQUEST["tanggal"];
$departemen = $_REQUEST["departemen"];
$bankNo = $_REQUEST["bankno"];
$metode = $_REQUEST["metode"];
$idPetugas = $_REQUEST["idpetugas"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Rincian Statistik Harian</title>
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
    <script language="javascript" src="statistik.rincian.harian.js?r=<?=filemtime('statistik.rincian.harian.js')?>"></script>
</head>

<body>
    <div style="text-align: center; font-size: 20px">RINCIAN STATISTIK HARIAN</div>
    <br><br>
    <table border="0" cellpadding="10" width="900">
    <tr>
        <td width="50%" valign="top">

<?php   ShowRequestInfo() ?>

        </td>
        <td width="50%" valign="top">

<?php   ShowRekapStatistikHarian(); ?>

        </td>
    </tr>
    </table>


    <table border="0" cellpadding="10"  width="900" align="left">
    <tr>
        <td align="left" valign="top" width="100%">

        <div id="dvContent">

<?php   ShowRincianStatistikHarian() ?>

        </div>
        </td>
    </tr>
    </table>

</body>
</html>
<?php
CloseDb();
?>