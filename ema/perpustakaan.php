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
    
<font class='mainTitle'>P E R P U S T A K A A N</font>
<br><br><br>
<font class='mainSubTitle'>Peminjaman</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/pus.daftarpinjam.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pustaka/pjm/daftar.pinjam.php' target='content'>
        <font class='mainMenuTitle'>
        Daftar Peminjaman
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan peminjaman buku di perpustakaan
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/pus.pinjamtelat.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pustaka/pjm/daftar.pinjam.telat.php' target='content'>
        <font class='mainMenuTitle'>
        Daftar Peminjaman Terlambat
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan peminjaman buku yang terlambat
        </font>
        </a>
    </td>
</tr>
</table>

<br><br>
<font class='mainSubTitle'>Pengembalian</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/pus.daftarkembali.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pustaka/kbl/daftar.kembali.php' target='content'>
        <font class='mainMenuTitle'>
        Daftar Pengembalian
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan pengembalian buku di perpustakaan
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/pus.denda.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pustaka/kbl/daftar.denda.php' target='content'>
        <font class='mainMenuTitle'>
        Daftar Penerimaan Denda
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan penerimaan denda dari pengembalian buku yang terlambat
        </font>
        </a>
    </td>
</tr>
</table>

<br><br>
<font class='mainSubTitle'>Laporan Perpustakaan</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/pus.statpinjam.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pustaka/pus/stat.pinjam.php' target='content'>
        <font class='mainMenuTitle'>
        Statistik Peminjam Terbanyak
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan siswa/pegawai yang paling banyak meminjam buku di perpustakaan
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/pus.favorit.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pustaka/kbl/daftar.denda.php' target='content'>
        <font class='mainMenuTitle'>
        Statistik Pustaka Yang Paling Banyak Dipinjam
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan buku yang paling banyak dipinjam
        </font>
        </a>
    </td>
</tr>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/pus.statistik.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='pustaka/pus/stat.all.php' target='content'>
        <font class='mainMenuTitle'>
        Statistik Peminjaman Pustaka
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan statistik peminjaman pustaka di perpustakaan
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

</td>    
</tr>    
</table>    
</body>
</html>