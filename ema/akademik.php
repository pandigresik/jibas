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
<title>Untitled Document</title>
</head> 
<body>
<table width='780' cellpadding='10' cellspacing='10'>
<tr>
<td align='left' valign='top'>
    
<font class='mainTitle'>A K A D E M I K</font>
<br><br><br>
<font class='mainSubTitle'>Laporan Kesiswaan</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/akad.infosiswa.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='siswa/siswa.php' target='content'>
        <font class='mainMenuTitle'>
        Rekapitulasi Informasi Siswa
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan informasi nilai, presensi, keuangan dan laporan-laporan lainnya dari setiap siswa
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/akad.statistik.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='siswa/stat.php' target='content'>
        <font class='mainMenuTitle'>
        Statistik Kesiswaan
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan grafik statistik siswa berdasarkan tahun kelahiran, agama, orangtua dan lainnya
        </font>
        </a>
    </td>
</tr>
<tr height='10'>
    <td colspan='5'>&nbsp;</td>
</tr>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/akad.nilaisiswa.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='siswa/rataus.top.php' target='content'>
        <font class='mainMenuTitle'>
        Rata-rata Nilai Siswa
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan perolehan nilai siswa dibandingkan dengan nilai rata-rata kelas nya
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/akad.nilaisiswa.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='siswa/statnilai.php' target='content'>
        <font class='mainMenuTitle'>
        Statistik Nilai Rapor
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan grafik statistik distribusi perolehan nilai rapor. Dari laporan ini bisa diketahui berapa banyak siswa yang nilai rapornya 7, 8 atau 9.
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