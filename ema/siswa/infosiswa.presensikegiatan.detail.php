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
require_once('../inc/sessionchecker.php');
require_once('../inc/config.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');
require_once('../lib/datearith.php');

$nis = $_SESSION["infosiswa.nis"];

$idkegiatan = $_REQUEST['idkegiatan'];
$bulan = $_REQUEST['bulan'] ?? date('n');
$tahun = $_REQUEST['tahun'] ?? date('Y');
$cnt = $_REQUEST['cnt'];

OpenDb();
?>

    <a href='#' onclick='pk_closeDetail(<?=$cnt?>, <?=$idkegiatan?>)' style='color: blue; font-weight: normal;'>tutup</a>
    <table border='1' cellspacing='0' style='border-collapse: collapse;' cellpadding='2' width='100%'>
    <tr height='25'>
        <td width='7%' align='center' class='header'>No</td>
        <td width='8%' align='left' class='header'>Hari</td>
        <td width='15%' align='center' class='header'>Tanggal<br>Masuk</td>
        <td width='14%' align='center' class='header'>Jam Masuk</td>
        <td width='14%' align='center' class='header'>Jam Pulang</td>
        <td width='12%' align='center' class='header'>Kehadiran</td>
        <td width='12%' align='center' class='header'>Keter lambatan</td>
        <td width='*' align='left' class='header'>Keterangan</td>
    </tr>            
<?php
    $cnt = 0;
    
    $sql = "SELECT p.replid, DATE_FORMAT(date_in, '%d %b %Y') AS date_in, time_in, DATE_FORMAT(date_out, '%d %b %Y') AS date_out, 
                   IF(p.time_out IS NULL, '-', p.time_out) AS time_out, p.smssent, p.smssenthome, p.description, 
                   DAYOFWEEK(date_in) AS weekday, IF(p.info1 IS NULL, 0, p.info1) AS telat, IF(p.nis IS NULL, 0, 1) AS ownertype
              FROM jbssat.frpresensikegiatan p
             WHERE MONTH(p.date_in) = $bulan
               AND YEAR(p.date_in) = $tahun
               AND p.nis = '$nis' 
               AND p.idkegiatan = $idkegiatan
             ORDER BY date_in DESC";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        $ti = trim((string) $row["time_in"]);
		$to = trim((string) $row["time_out"]);
		$tomark = "";
        
        if ($row['time_out'] == "-")
        {
            $wd = $row['weekday'];
            
            $sql = "SELECT pulangstd
                      FROM jbssat.frjadwalkegiatan
                     WHERE idkegiatan = $idkegiatan
                       AND hari = $wd";
            $res2 = QueryDb($sql);
            if (mysqli_num_rows($res2) > 0)
            {
                $row2 = mysqli_fetch_row($res2);
                
                $to = $row2[0] . ":00";
                $tomark = " (std)";
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
        
        $cnt += 1;
?>
        <tr>
            <td align='center'><?= $cnt ?></td>
            <td align='left'><?= DateArith::InaDayName($row['weekday'] - 2) ?></td>
            <td align='center'><?= $row['date_in'] ?></td>
            <td align='center'><?= $ti ?></td>
            <td align='center'><?= $to . $tomark ?></td>
            <td align='center'><?= $hadir ?></td>
            <td align='center'><?= DateArith::ToStringHourFromMinute($telat) ?></td>
            <td align='left'><?= $row['description'] ?>&nbsp;</td>
        </tr>
<?php
    }
?>
    </table>

<?php
CloseDb();
?>