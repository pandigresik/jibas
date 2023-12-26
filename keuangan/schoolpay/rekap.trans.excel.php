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
require_once('../include/errorhandler.php');
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/date.func.php');
require_once('../library/stringbuilder.php');
require_once('common.func.php');
require_once('rekap.trans.func.php');

header('Content-Type: application/vnd.ms-excel'); //IE and Opera
header('Content-Type: application/x-msexcel'); // Other browsers
header('Content-Disposition: attachment; filename=Rekap_Trans.xls');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JIBAS KEU [Rekapitulasi Transaksi Vendor]</title>
</head>

<body>
<center><font size="4"><strong>REKAPITULASI TRANSAKSI VENDOR</strong></font><br /> </center><br /><br />`
<table border="0">
    <tr>
        <td><strong>Vendor</strong></td>
        <td><strong>: <?= GetVendorName($_REQUEST["vendorid"]) ?></strong></td>
    </tr>
    <tr>
        <td><strong>Tanggal</strong></td>
        <td><strong>: <?= FormatMySqlDate($_REQUEST["dtstart"]) ?> s/d <?= FormatMySqlDate($_REQUEST["dtend"]) ?></strong></td>
    </tr>
</table>
<br />

<?php
ShowRekapTransReport(false);
?>

</body>
</html>
<?php
CloseDb();
?>