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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../library/datearith.php');
require_once('../sessionchecker.php');

$filter = ($idkegiatan == -1) ? "" : "AND p.idkegiatan = " . $idkegiatan;
$sql = "SELECT p.replid, DATE_FORMAT(date_in, '%d %b %Y') AS date_in, time_in, DATE_FORMAT(date_out, '%d %b %Y') AS date_out, 
               p.time_out, p.smssent, p.smssenthome, p.description, 
               DAYOFWEEK(date_in) AS weekday, IF(p.info1 IS NULL, 0, p.info1) AS telat, IF(p.nis IS NULL, 0, 1) AS ownertype,
               k.kegiatan, p.idkegiatan
          FROM jbssat.frpresensikegiatan p, jbssat.frkegiatan k
         WHERE p.idkegiatan = k.replid
           AND MONTH(p.date_in) = $bulan 
           AND YEAR(p.date_in) = $tahun 
           AND p.nis = '$nis' 
               $filter
         ORDER BY p.date_in DESC, k.kegiatan";
$res = QueryDb($sql);

if (mysqli_num_rows($res) == 0)
{
    CloseDb();
    
    echo "<center>Tidak ada data presensi</center>";
    exit();
}

if ($showbutton) {
?>
<table width="99%" align="center" border="0">
<tr>
    <td align="right">
        <a href="#" onClick="document.location.reload()">
            <img src="../images/ico/refresh.png" border="0"/>&nbsp;Refresh
        </a>&nbsp;&nbsp;
        <a href="#" onclick="excel()">
            <img src="../images/ico/excel.png" border="0" />&nbsp;Excel
        </a>&nbsp;&nbsp;
        <a href="#" onclick="cetak()">
            <img src="../images/ico/print.png" border="0" />&nbsp;Cetak
        </a>
    </td>
</tr>    
</table>
<?php
}
?>

<table class="tab" id="table" border="1" align="left" style="border-collapse:collapse" width="99%" align="center" bordercolor="#000000">
<tr height="30" align="center" class="header">		
    <td width="5%">No</td>
    <td width="7%">Hari</td>
    <td width="12%">Tanggal</td>
    <td width="25%">Kegiatan</td>
    <td width="10%">Jam Masuk</td>
    <td width="10%">Jam Pulang</td>
    <td width="10%">Kehadiran</td>
    <td width="10%">Keter lambatan</td>
    <td width="*">Keterangan</td>
</tr>
<?php
$cnt = 0;
while($row = mysqli_fetch_array($res))
{
    $cnt += 1;
    
    $idkegiatan = $row["idkegiatan"];
    $ti = trim((string) $row["time_in"]);
    $to = trim((string) $row["time_out"]);
    $tomark = "";
    if (strlen($to) == 0)
    {
        $wd = $row["weekday"];
        $sql = "SELECT pulangstd
                  FROM jbssat.frjadwalkegiatan
                 WHERE idkegiatan = $idkegiatan
                   AND hari = $wd";

        $res2 = QueryDb($sql);
        if (mysqli_num_rows($res2) > 0)
        {
            $row2 = mysqli_fetch_row($res2);
            $to = $row2[0] . ":00";
            $tomark = "<font color='blue'>(std)</font>";
        }
        else
        {
            $to = "<font color='red'>NA</font>";
        }
        
    }
    
    $hadir = "<font color='red'>NA</font>";
    if ($to != "<font color='red'>NA</font>")
    {
        $h = 0;
        $m = 0;
        $s = 0;
        DateArith::TimeDiff($to, $ti, $h, $m, $s);
        
        $hadir = DateArith::ToStringHour($h, $m, $s);
    }
    
    $telat = 0;
    if (0 == (int)$row['telat'])
    {
        // Kalo tidak telat, justru dicek
        
        $wd = $row["weekday"];
        $sql = "SELECT telat
                  FROM jbssat.frjadwalkegiatan
                 WHERE idkegiatan = $idkegiatan
                   AND hari = $wd";
        
        $res2 = QueryDb($sql);
        if (mysqli_num_rows($res2) > 0)
        {
            $row2 = mysqli_fetch_row($res2);
            $telatt = $row2[0];
            
            $telatm = DateArith::TimeToMinute($telatt);
            $tim = DateArith::TimeToMinute($ti);
            
            if ($tim > $telatm)
                $telat = $tim - $telatm;
        }
    }
    else
    {
        $telat = $row['telat'];
    }
    
    ?>
    <tr height='25'>
        <td align='center'><?=$cnt?></td>
        <td align='left'><?=DateArith::InaDayName($row['weekday'] - 2)?></td>
        <td align='left'><?=$row['date_in']?></td>
        <td align='left'><?=$row['kegiatan']?></td>
        <td align='left'><?= $ti ?></td>
        <td align='left'><?= $to . " " . $tomark?></td>
        <td align='left'><?=$hadir?></td>
        <td align='left'><?=DateArith::ToStringHourFromMinute($telat)?></td>
        <td align='left'><?=$row["description"]?></td>
    </tr>

<?php
}
?>
</table>
<font color='blue'>(std)</font> <font color='#666'>karena belum ada data presensi pulang, jam pulang menggunakan jam pulang standar yang didefinisikan di jadwal kegiatan</font>