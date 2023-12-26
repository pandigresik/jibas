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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../library/departemen.php');

$iddep = $_REQUEST['iddep'];
$tgl = $_REQUEST['tgl'];
$bln = $_REQUEST['bln'];
$thn = $_REQUEST['thn'];
$kate = $_REQUEST['kate'];

OpenDb();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Riwayat SMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css" />
    <script language="javascript" src="riwayatsms.header.js"></script>
	<script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
</head>

<body>
<br><br>
<?php
$sql = "SELECT replid
          FROM jbssms.smsgeninfo
         WHERE tanggal = '$thn-$bln-$tgl'
           AND info LIKE '[$kate.$iddep]%'";
//echo "$sql<br>";           
$res = QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    echo "<center><i>Belum ada pengiriman SMS Informasi Pembayaran</i></center>";
    
    CloseDb();
    exit();
}

$row = mysqli_fetch_row($res);
$idsmsgen = $row[0];

?>
<table id='table' class='tab' border='0' width='90%' cellpadding='2' cellspacing='0'>
<tr>
    <td width='5%' class='header' align='center'>No</td>
    <td width='12%' class='header' align='center'>Jam</td>
    <td width='12%' class='header' align='center'>HP</td>
    <td width='*' class='header' align='center'>SMS</td>
</tr>
<?php
$sql = "SELECT DATE_FORMAT(SendingDateTime, '%H:%i:%s'),
               DestinationNumber, Text
          FROM jbssms.outboxhistory
         WHERE idsmsgeninfo = '$idsmsgen'
         ORDER BY SendingDateTime DESC";       
$res = QueryDb($sql);
$no = 0;
while($row = mysqli_fetch_row($res))
{
?>
    <tr height='25'>
        <td align='center' valign='top'><?= ++$no ?></td>
        <td align='center' valign='top'><?= $row[0] ?></td>
        <td align='center' valign='top'><?= $row[1] ?></td>
        <td align='left' valign='top'><?= $row[2] ?></td>
    </tr>
<?php
}
?>
</table>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>
</body>
</html>
<?php
CloseDb();
?>