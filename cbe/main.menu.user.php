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
<br>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
    <td width="5">&nbsp;</td>
    <td width="*" colspan="2">
        <a href="#" style="color: #fff;" onclick="main_showHome()">Beranda</a><br><br>
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="*" colspan="2">
        <span style="font-size:14px; color: white">MULAI UJIAN</span>
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="5"><span style="color: white">&bull;</span></td>
    <td width="*">
        <a href="#" style="color: white;" onclick="main_showUjianKhusus()">Ujian Khusus</a>
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="5"><span style="color: white">&bull;</span></td>
    <td width="*">
        <input type="hidden" id="menu_userTupe" value="<?= $_SESSION["UserType"] ?>">
        <a href="#" style="color: white;" onclick="main_showUjianUmum()">Ujian Umum</a>
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="5"><span style="color: white">&bull;</span></td>
    <td width="*">
        <a href="#" style="color: white;" onclick="main_showUjianRemedial()">Ujian Remedial</a>
    </td>
</tr>
<tr>
    <td width="*" colspan="3">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="*" colspan="2">
        <span style="font-size:14px; color: white">HASIL UJIAN</span>
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="5"><span style="color: white">&bull;</span></td>
    <td width="*">
        <a href="#" style="color: white;" onclick="main_showRekapUjian()">Rekap Hasil Ujian</a>
    </td>
</tr>
<tr>
    <td width="*" colspan="3">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="*" colspan="2">
        <span style="font-size:14px; color: white">JADWAL UJIAN</span>
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="5"><span style="color: white">&bull;</span></td>
    <td width="*">
        <a href="#" style="color: white;" onclick="main_showJadwalUjian()">Jadwal Ujian</a>
    </td>
</tr>
<tr>
    <td width="*" colspan="3">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="*" colspan="2">
        <span style="font-size:14px; color: white">BANK SOAL</span>
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="5"><span style="color: white">&bull;</span></td>
    <td width="*">
        <a href="#" style="color: white;" onclick="main_showBankSoal()">Bank Soal</a>
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="5"><span style="color: white">&bull;</span></td>
    <td width="*">
        <a href="#" style="color: white;" onclick="main_showCariSoal()">Cari Soal</a>
    </td>
</tr>
<tr>
    <td width="*" colspan="3">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="*" colspan="2">
        <span style="font-size:14px; color: white">PENGATURAN</span>
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="5"><span style="color: white">&bull;</span></td>
    <td width="*">
        <a href="#" style="color: white;" onclick="main_showTestConn()">Test Koneksi CBE Server</a>
    </td>
</tr>
<?php
if ($_SESSION["TimAdmin"]) {
?>
    <tr>
        <td width="5">&nbsp;</td>
        <td width="5"><span style="color: white">&bull;</span></td>
        <td width="*">
            <a href="#" style="color: white;" onclick="main_showDaftarPeserta()">Koneksi Pengguna</a>
        </td>
    </tr>
    <tr>
        <td width="5">&nbsp;</td>
        <td width="5"><span style="color: white">&bull;</span></td>
        <td width="*">
            <a href="#" style="color: white;" onclick="main_showPesertaUjianOffline()">Peserta Ujian Offline</a>
        </td>
    </tr>
<?php
}
?>
<tr>
    <td width="*" colspan="3">
        &nbsp;
    </td>
</tr>
<tr>
    <td width="5">&nbsp;</td>
    <td width="*" colspan="2">
        <a href="#" style="color: #ffdc8f;" onclick="main_Logout()">Logout</a>
    </td>
</tr>
</table>
