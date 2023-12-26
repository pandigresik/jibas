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
require_once ("welcome.func.php");
?>
<table border="0" cellpadding="10" cellspacing="0" width="100%">
<tr>
    <td colspan="2" align="left">
        <font class="mainPageTitle">Selamat Datang <?=$_SESSION["UserName"]?></font><br><br><br>
    </td>
</tr>
<tr>
    <td width="50%" align="left" valign="top">
        <font class="mainPageSection">Pesan</font><br>
        <div style="width: 95%; height: 200px;
             background-color: #e4fffd; font-family: Georgia, 'Times New Roman', Times, serif;
             font-size: 14px; line-height: 24px; padding: 10px; overflow: auto; font-style: italic;">

        <?= getWelcome() ?>
        </div>
    </td>
    <td width="50%" align="left" valign="top">
        <font class="mainPageSection">Hasil Ujian Terbaru</font><br>
        <div id="divCbeLastUjian" style="overflow:auto; height: 200px;">
            <table id="tabCbeLastUjian" border='1' cellpadding='2' cellspacing='0'
                   width='520' style='border-collapse: collapse; border-width: 1px'>
            <thead>
            <tr style='background-color: #144da4; color: #fff'>
                <td width='25' align='center'>No</td>
                <td width='70' align='center'>Nilai</td>
                <td width='300' align='center'>Ujian</td>
                <td width='*' align='center'>Tanggal</td>
            </tr>
            </thead>
            <tbody>
            <?=getLastUjian(1)?>
            </tbody>
            </table>
            <a style="color: blue; text-decoration: none; cursor: pointer" onclick="welcome_getLastUjian();">selanjutnya..</a>
        </div>
    </td>
</tr>
<tr>
    <td colspan="2" align="left" valign="top">
        <br>
        <font class="mainPageSection">Jadwal Ujian Bulan <?= getCurrentMonthYear() ?></font><br><br>
        <div id="welcome_divJadwalUjian">

        </div>
    </td>
</tr>
</table>

