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
require_once('../cek.php');
require_once('../include/getheader.php');

OpenDb();

$nip = $_REQUEST['nip'];
$idkegiatan = $_REQUEST['idkegiatan'];
$bulan = $_REQUEST['bulan'];
$tahun = $_REQUEST['tahun'];

$departemen = "yayasan";

$sql = "SELECT nama
          FROM jbssdm.pegawai
         WHERE nip = '".$nip."'";   
$res = QueryDB($sql);	
$row = mysqli_fetch_row($res);
$nama = $row[0];

$sql = "SELECT kegiatan
          FROM jbssat.frkegiatan
         WHERE replid = $idkegiatan";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$kegiatan = $row[0];

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS AKADEMIK [Detail Presensi Kegiatan Guru]</title>
</head>

<body>
    
<table border="0" cellpadding="2" cellpadding="0" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">
		<?php getHeader($departemen) ?>
		<center>
			<font size="4"><strong>DETAIL PRESENSI KEGIATAN GURU</strong></font><br />
		</center>
		<br /><br />
	</td>
</tr>	
<tr>
	<td width='60'><strong>Nama</strong></td>
    <td><strong>: <?= $nip . ' - ' . $nama ?></strong></td>
</tr>
<tr>
    <td width='60'><strong>Kegiatan</strong></td>
    <td><strong>: <?= $kegiatan ?></strong></td>
</tr>
<tr>
	<td align="left" valign="top" colspan="2">
        
        <table border='1' cellspacing='0' style='border-collapse: collapse;' cellpadding='5' width='100%'>
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
               AND p.nip = '$nip' 
               AND p.idkegiatan = $idkegiatan
             ORDER BY date_in DESC";         
    $res = QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        $ti = trim((string) $row["time_in"]);
		$to = trim((string) $row["time_out"]);
		$tomark = "";
        
		//echo "$to<br>";
        if ($row['time_out'] == "-")
        {
            $wd = $row['weekday'];
            
            $sql = "SELECT pulangstd
                      FROM jbssat.frjadwalkegiatan
                     WHERE idkegiatan = $idkegiatan
                       AND hari = $wd";
			//echo $sql;		   
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
        
    </td>
</tr>	        
</table>

</body>
</html>
<?php
CloseDb();
?>