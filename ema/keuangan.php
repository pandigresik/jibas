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
<table width='1200' border='0' cellpadding='10' cellspacing='10'>
<tr>
<td align='left' valign='top'>
    
<font class='mainTitle'>K E U A N G A N</font>
<br><br><br>
<font class='mainSubTitle'>Penerimaan</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.bayarkelas.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapbayarsiswa_kelas.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Pembayaran Setiap Kelas
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan penerimaan pembayaran setiap kelas
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.bayarsiswa.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapbayarsiswa_all.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Pembayaran Setiap Siswa
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan penerimaan pembayaran setiap siswa
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.bayartelat.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapbayarsiswa_nunggak.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Pembayaran Setiap Siswa Yang Mengunggak
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan siswa-siswa yang mempunyai tunggakan pembayaran
        </font>
        </a>
    </td>
</tr>
</table>
<br><br>

<font class='mainSubTitle'>Penerimaan dari Pendaftaran Siswa Baru</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.bayarkelas.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapbayarcalon_kelompok.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Pembayaran per kelompok Calon Siswa
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan penerimaan pembayaran setiap kelompok calon siswa
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.bayarsiswa.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapbayarcalon_all.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Pembayaran Setiap Calon Siswa
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan penerimaan pembayaran setiap calon siswa
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.bayartelat.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapbayarcalon_nunggak.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Pembayaran Setiap Calon Siswa yang Menunggak
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan calon siswa yang mempunyai tunggakan pembayaran
        </font>
        </a>
    </td>
</tr>
</table>

<br><br>
<font class='mainSubTitle'>Penerimaan Lain</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.terimalain.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lappenerimaanlain.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Penerimaan Lainnya
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan penerimaan dari sumber pendapatan lainnya
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.jurnal.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/jurnalpenerimaan.php' target='content'>
        <font class='mainMenuTitle'>
        Jurnal Penerimaan
        </font><br>
        <font class='mainMenuInfo'>
        &nbsp;
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
<font class='mainSubTitle'>Pengeluaran</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.keluar.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lappengeluaran.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Pengeluaran
        </font><br>
        <font class='mainMenuInfo'>
        Menampilkan laporan penggunaan dan pengeluaran dana
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.jurnalkeluar.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lappengeluaran_cari.php' target='content'>
        <font class='mainMenuTitle'>
        Pencarian Data Pengeluaran
        </font><br>
        <font class='mainMenuInfo'>
        Mencari informasi detil penggunaan dan pengeluaran dana
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.carikeluar.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/jurnalpengeluaran.php' target='content'>
        <font class='mainMenuTitle'>
        Jurnal Pengeluaran
        </font><br>
        <font class='mainMenuInfo'>
        &nbsp;
        </font>
        </a>
    </td>
</tr>
</table>

<br><br>
<font class='mainSubTitle'>Laporan Keuangan</font>
<hr style='background-color: #0080c0; height: 1px; border: 0;'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.transaksi.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/laptransaksi.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Transaksi Keuangan
        </font><br>
        <font class='mainMenuInfo'>
        &nbsp;
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.audit.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapaudit.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Audit Perubahan Data Keuangan
        </font><br>
        <font class='mainMenuInfo'>
        &nbsp;
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.bukubesar.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapbukubesar.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Buku Besar
        </font><br>
        <font class='mainMenuInfo'>
        &nbsp;
        </font>
        </a>
    </td>
</tr>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.trialbalance.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapneracaper.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Neraca Percobaan
        </font><br>
        <font class='mainMenuInfo'>
        &nbsp;
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.rugilaba.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/laprugilaba.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Rugi Laba
        </font><br>
        <font class='mainMenuInfo'>
        &nbsp;
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.trialbalance.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapneraca.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Neraca
        </font><br>
        <font class='mainMenuInfo'>
        &nbsp;
        </font>
        </a>
    </td>
</tr>
<tr>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.perubahanmodal.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapmodal.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Perubahan Modal
        </font><br>
        <font class='mainMenuInfo'>
        &nbsp;
        </font>
        </a>
    </td>
    <td width='50' align='center' valign='top'>
        &nbsp;
    </td>
    <td width='50' align='center' valign='top'>
        <img src='img/new/keu.cashflow.png'>
    </td>
    <td width='250' align='left' valign='top'>
        <a href='keu/lapcashflow.php' target='content'>
        <font class='mainMenuTitle'>
        Laporan Arus Kas
        </font><br>
        <font class='mainMenuInfo'>
        &nbsp;
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