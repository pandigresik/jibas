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
require_once('inc/sessionchecker.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style/style.css" rel="stylesheet" type="text/css" />
<title>JIBAS EMA</title>
</head> 
<body>
<table width='780' cellpadding='10' cellspacing='10'>
<tr>
<td align='left' valign='top'>
    
<font class='mainTitle'>K E P E G A W A I A N</font>
<br><br><br>
<font class='mainSubTitle'>Laporan Kepegawaian</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/peg.statistik.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pegawai/statpegawai.php' target='content'>
        <font class='mainMenuTitle'>
        Statistik Kepegawaian
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan grafik statistik kepegawaian berdasarkan jenis kelamin, usia, agama dan lainnya.
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='250' align='left' valign='top'>
        &nbsp;
    </td>
</tr>
</table>

<br><br>
<font class='mainSubTitle'>Laporan Presensi Pegawai</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/peg.presensi.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pegawai/lappengajar.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Presensi Mengajar
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan presensi mengajar guru
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='250' align='left' valign='top'>
        &nbsp;
    </td>
</tr>
<tr>
    <td colspan='5'>&nbsp;</td>
</tr>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/peg.presensiharian.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pegawai/rekappresensi.main.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Presensi Harian
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan presensi harian setiap pegawai
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;        
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/peg.presensiharian.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pegawai/rekapall.main.php' target='content'>
        <font class='mainMenuTitle'>
        Rekapitulasi Presensi Harian
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan rekapitulasi presensi harian semua pegawai
        </font>
        </a>
    </td>
</tr>
<tr>
    <td colspan='5'>&nbsp;</td>
</tr>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/peg.presensikegiatan.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pegawai/presensikeg.guru.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Presensi Kegiatan
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan presensi kegiatan setiap pegawai
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;        
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/peg.presensikegiatan.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pegawai/presensikeg.rekapguru.php' target='content'>
        <font class='mainMenuTitle'>
        Rekapitulasi Presensi Kegiatan
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan rekapitulasi presensi pegawai di setiap kegiatan
        </font>
        </a>
    </td>
</tr>
</table>

</td>    
</tr>    
</table>    
</body>
</html>