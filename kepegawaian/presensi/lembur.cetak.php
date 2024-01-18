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
require_once('../include/config.php');
require_once('../include/common.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
require_once("../library/datearith.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script type="application/x-javascript" src="../script/jquery-1.9.0.js"></script>
</head>
<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

    <?php include("../include/headercetak.php") ?>
    <center>
      <font size="4"><strong>Presensi Lembur Pegawai</strong></font><br />
    </center>
    
    <br /><br />
<?php
OpenDb();

$tahun1 = $_REQUEST['tahun1'];
$bulan1 = $_REQUEST['bulan1'];
$tanggal1 = $_REQUEST['tanggal1'];
$tahun2 = $_REQUEST['tahun2'];
$bulan2 = $_REQUEST['bulan2'];
$tanggal2 = $_REQUEST['tanggal2'];

?>

<strong>Tanggal: <?= $tanggal2 . " " . NamaBulan($bulan2) . " " . $tahun2 . " s/d " . $tanggal1 . " " . NamaBulan($bulan1) . " " . $tahun1 ?></strong><br /><br />

<table border="1" style="border-width: 1px; border-collapse: collapse;">
<tr height="25">
    <td width="25" align="center" class="header">No</td>
    <td width="100" align="center" class="header">Tanggal</td>
    <td width="100" align="center" class="header">NIP</td>
    <td width="200" align="center" class="header">Nama</td>
    <td width="100" align="center" class="header">Jam Masuk</td>
    <td width="100" align="center" class="header">Jam Pulang</td>
    <td width="200" align="center" class="header">Keterangan</td>
</tr>
<?php
$sql = "SELECT pg.nip, pg.nama, 
               p.jammasuk, p.jampulang,
               p.keterangan, IF(p.replid IS NULL, -1, p.replid),
               DATE_FORMAT(p.tanggal, '%d %b %Y'), p.tanggal
          FROM jbssdm.pegawai pg, jbssdm.presensi p
         WHERE pg.nip = p.nip
           AND p.source = 'LEMBUR'
           AND p.tanggal BETWEEN '$tahun2-$bulan2-$tanggal2' AND '$tahun1-$bulan1-$tanggal1'
         ORDER BY p.tanggal DESC, pg.nama ASC";
//echo $sql;         
$res = QueryDb($sql);
$n = 0;
while($row = mysqli_fetch_row($res))
{
    $n++;
    
    echo "<tr>\r\n";
    echo "<td align='center'>$n</td>\r\n";
    echo "<td align='center'>".$row[6]."</td>\r\n";
    echo "<td align='left'>".$row[0]."</td>\r\n";
    echo "<td align='left'>".$row[1]."</td>\r\n";
    
    $h = "";
    $m = "";
    
    echo "<td align='center'>\r\n";
    GetHourMinute($row[2], $h, $m);
    echo "$h&nbsp;:&nbsp;$m\r\n";
    echo "</td>\r\n";
    
    echo "<td align='center'>\r\n";
    GetHourMinute($row[3], $h, $m);
    echo "$h&nbsp;:&nbsp;$m\r\n";
    echo "</td>\r\n";
    
    echo "<td align='left'>".$row[4]."</td>\r\n";
    echo "</tr>\r\n";
}
?>
</table>

</td>
</tr>
</table>

</body>
<?php
CloseDb();
?>
</html>

<?php
function GetHourMinute($strtime, &$hour, &$minute)
{
    if (str_contains((string) $strtime, ":"))
    {
        $temp = explode(":", (string) $strtime);
        $hour = $temp[0];
        $minute = $temp[1];
        
        return;
    }
    
    $hour = "";
    $minute = "";
}
?>