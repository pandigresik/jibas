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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../library/datearith.php");

$tahun1 = $_REQUEST['tahun1'];
$bulan1 = $_REQUEST['bulan1'];
$tanggal1 = $_REQUEST['tanggal1'];
$tahun2 = $_REQUEST['tahun2'];
$bulan2 = $_REQUEST['bulan2'];
$tanggal2 = $_REQUEST['tanggal2'];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style.css" />
<link rel="stylesheet" href="../script/themes/ui-lightness/jquery.ui.all.css">
<script type="application/x-javascript" src="../script/jquery-1.9.0.js"></script>
<script type="application/x-javascript" src="../script/validator.js"></script>
<script type="application/x-javascript" src="../script/logger.js"></script>
<script type="application/x-javascript" src="../script/tools.js"></script>
<script type="application/x-javascript" src="lembur.content.js"></script>
</head>

<body>
<input type="hidden" name="tahun1" id="tahun1" value="<?= $tahun1 ?>">
<input type="hidden" name="bulan1" id="bulan1" value="<?= $bulan1 ?>">
<input type="hidden" name="tanggal1" id="tanggal1" value="<?= $tanggal1 ?>">
<input type="hidden" name="tahun2" id="tahun2" value="<?= $tahun2 ?>">
<input type="hidden" name="bulan2" id="bulan2" value="<?= $bulan2 ?>">
<input type="hidden" name="tanggal2" id="tanggal2" value="<?= $tanggal2 ?>">

<br>

<table border="0" cellpadding="5" width="1115">
<tr>
<td align="right">
<a href="#" onclick="InputLembur()"><img src="../images/ico/ujian.png" border="0">&nbsp;Input Data Lembur</a>&nbsp;&nbsp;
<a href="#" onclick="Cetak()"><img src="../images/ico/print.png" border="0">&nbsp;Cetak</a>&nbsp;&nbsp;
<a href="#" onclick="Excel()"><img src="../images/ico/excel.png" border="0">&nbsp;Excel</a>
</td>    
</tr>    
</table>
<table border="1" style="border-width: 1px; border-collapse: collapse;" width="1115">
<tr height="25">
    <td width="25" align="center" class="header">No</td>
    <td width="100" align="center" class="header">Tanggal</td>
    <td width="100" align="center" class="header">NIP</td>
    <td width="200" align="center" class="header">Nama</td>
    <td width="100" align="center" class="header">Jam Masuk</td>
    <td width="100" align="center" class="header">Jam Pulang</td>
    <td width="200" align="center" class="header">Keterangan</td>
    <td width="140" align="center" class="header">&nbsp;</td>
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
    echo "<input type='hidden' id='replid$n' name='replid$n' value='".$row[5]."'>\r\n";
    echo "<input type='hidden' id='tglpresensi$n' name='tglpresensi$n' value='".$row[7]."'>\r\n";
    echo "<td align='center'>$n</td>\r\n";
    echo "<td align='center'>".$row[6]."</td>\r\n";
    echo "<td align='left'>$row[0] <input type='hidden' name='nip$n' id='nip$n' value='".$row[0]."'></td>\r\n";
    echo "<td align='left'>".$row[1]."</td>\r\n";
    
    $h = "";
    $m = "";
    
    echo "<td align='center'>\r\n";
    GetHourMinute($row[2], $h, $m);
    echo "<input type='text' maxlength='2' size='2' name='jammasuk$n' id='jammasuk$n' value='$h'>&nbsp;:&nbsp;\r\n";
    echo "<input type='text' maxlength='2' size='2' name='menitmasuk$n' id='menitmasuk$n' value='$m'>\r\n";
    echo "</td>\r\n";
    
    echo "<td align='center'>\r\n";
    GetHourMinute($row[3], $h, $m);
    echo "<input type='text' maxlength='2' size='2' name='jampulang$n' id='jampulang$n' value='$h'>&nbsp;:&nbsp;\r\n";
    echo "<input type='text' maxlength='2' size='2' name='menitpulang$n' id='menitpulang$n' value='$m'>\r\n";
    echo "</td>\r\n";
    
    echo "<td align='left'><input type='text' name='ket$n' id='ket$n' value='".$row[4]."' size='27' maxlength='255'></td>\r\n";
    echo "<td align='center'>\r\n";
    
    echo "<input type='button' value='simpan' onclick='SaveEdit($n)'>\r\n";
    echo "<span id='info$n' style='color: blue; font-weight: bold;'></span>\r\n";
    echo "<input type='button' value='hapus' style='color: red' onclick='Delete($n)'>\r\n";
    
    echo "</td>\r\n";
    echo "</tr>\r\n";
}
?>
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








