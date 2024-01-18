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

$tahun = DateArith::FormatDigit($_REQUEST['tahun']);
$bulan = DateArith::FormatDigit($_REQUEST['bulan']);
$tanggal = DateArith::FormatDigit($_REQUEST['tanggal']);

OpenDb();
$sql = "SELECT COUNT(replid)
          FROM jbssdm.presensi
         WHERE tanggal = '$tahun-$bulan-$tanggal'";
$newInput = (int)FetchSingle($sql) == 0;
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
<script type="application/x-javascript" src="inputpresensi.content.js"></script>
</head>

<body>
<input type="hidden" name="tahun" id="tahun" value="<?= $tahun ?>">
<input type="hidden" name="bulan" id="bulan" value="<?= $bulan ?>">
<input type="hidden" name="tanggal" id="tanggal" value="<?= $tanggal ?>">
<input type="hidden" name="tglpresensi" id="tglpresensi" value="<?= "$tahun-$bulan-$tanggal" ?>">
<table border="1" style="border-width: 1px; border-collapse: collapse;" width="1065">
<tr height="25">
    <td width="25" align="center" class="header">No</td>
    <td width="150" align="center" class="header">NIP</td>
    <td width="200" align="center" class="header">Nama</td>
    <td width="150" align="center" class="header">Status</td>
    <td width="120" align="center" class="header">Jam Masuk</td>
    <td width="120" align="center" class="header">Jam Pulang</td>
    <td width="200" align="center" class="header">Keterangan</td>
    <td width="100" align="center" class="header">&nbsp;</td>
</tr>
<?php
$sql = "SELECT pg.nip, pg.nama, IF(p.status IS NULL, -1, p.status),
               p.jammasuk, p.jampulang, p.keterangan, IF(p.replid IS NULL, -1, p.replid)
          FROM jbssdm.pegawai pg
          LEFT JOIN jbssdm.presensi p
            ON pg.nip = p.nip
           AND p.tanggal = '$tahun-$bulan-$tanggal'
         WHERE pg.aktif = 1   
         ORDER BY pg.nama";

/*
$sql = "SELECT pg.nip, pg.nama, IF(p.status IS NULL, -1, p.status),
               '08:00', '17:00', p.keterangan, IF(p.replid IS NULL, -1, p.replid)
          FROM jbssdm.pegawai pg
          LEFT JOIN jbssdm.presensi p
            ON pg.nip = p.nip
           AND p.tanggal = '$tahun-$bulan-$tanggal'
         WHERE pg.aktif = 1    
         ORDER BY pg.nama";
*/

$res = QueryDb($sql);
$n = 0;
while($row = mysqli_fetch_row($res))
{
    $n++;
    
    echo "<tr>";
    echo "<input type='hidden' id='replid$n' name='replid$n' value='".$row[6]."'>";
    echo "<td align='center'>$n</td>";
    echo "<td align='center'>$row[0] <input type='hidden' name='nip$n' id='nip$n' value='".$row[0]."'></td>";
    echo "<td align='left'>".$row[1]."</td>";
    
    echo "<td align='center'>";
    echo "<select name='status$n' id='status$n'>";
    echo "<option value='1' " . IntIsSelected(1, $row[2]) . ">Hadir</option>";
    echo "<option value='2' " . IntIsSelected(2, $row[2]) . ">Izin</option>";
    echo "<option value='3' " . IntIsSelected(3, $row[2]) . ">Sakit</option>";
    echo "<option value='4' " . IntIsSelected(4, $row[2]) . ">Cuti</option>";
    echo "<option value='5' " . IntIsSelected(5, $row[2]) . ">Alpa</option>";
    echo "<option value='6' " . IntIsSelected(6, $row[2]) . ">Bebas</option>";
    if (!$newInput)
        echo "<option value='-1' " . IntIsSelected(-1, $row[2]) . ">Belum Ada Data</option>";
    echo "</select>";
    echo "</td>";
    
    $h = "";
    $m = "";
    
    $copyIn = "";
    $copyOut = "";
    if ($n == 1)
    {
        $copyIn = "<a href='#' onclick='CopyBelowIn()' title='Salin ke semua baris'><img src='../images/ico/desc.png' border='0'></a>";
        $copyOut = "<a href='#' onclick='CopyBelowOut()' title='Salin ke semua baris'><img src='../images/ico/desc.png' border='0'></a>";
    }
    
    echo "<td align='left'>";
    GetHourMinute($row[3], $h, $m);
    echo "&nbsp;&nbsp;&nbsp;<input type='text' maxlength='2' size='2' name='jammasuk$n' id='jammasuk$n' value='$h'>&nbsp;:&nbsp;";
    echo "<input type='text' maxlength='2' size='2' name='menitmasuk$n' id='menitmasuk$n' value='$m'>";
    echo $copyIn;
    echo "</td>";
    
    echo "<td align='left'>";
    GetHourMinute($row[4], $h, $m);
    echo "&nbsp;&nbsp;&nbsp;<input type='text' maxlength='2' size='2' name='jampulang$n' id='jampulang$n' value='$h'>&nbsp;:&nbsp;";
    echo "<input type='text' maxlength='2' size='2' name='menitpulang$n' id='menitpulang$n' value='$m'>";
    echo $copyOut;
    echo "</td>";
    
    echo "<td align='left'><input type='text' name='ket$n' id='ket$n' value='".$row[5]."' size='27' maxlength='255'></td>";
    echo "<td align='center'>";
    if (!$newInput)
        echo "<input type='button' value='Simpan' onclick='SaveEdit($n)'>";
    else
        echo "&nbsp;";
    echo "<span id='info$n' style='color: blue; font-weight: bold;'></span>";
    echo "</td>";
    echo "</tr>";
}

if ($newInput)
{
    echo "<tr height='35'>";
    echo "<td colspan='8' align='center' bgcolor='#ccc'>";
    echo "<input type='hidden' id='ndata' name='ndata' value='$n'>";
    echo "<input type='button' id='btSimpan' name='btSimpan' value='Simpan' onclick='SaveNew()'>";
    echo "<span id='infosave' style='color: red; font-weight: bold;'></span>";
    echo "</td>";
    echo "</tr>";
}
else
{
    echo "<tr height='35'>";
    echo "<td colspan='8' align='center' bgcolor='#ccc'>";
    echo "<input type='hidden' id='ndata' name='ndata' value='$n'>";
    echo "<input type='button' id='btHapus' name='btHapus' value='Hapus Data Presensi Ini' onclick='Delete()' style='font-weight: bold; color: red;'>";
    echo "<span id='infosave' style='color: red; font-weight: bold;'></span>";
    echo "</td>";
    echo "</tr>";
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








