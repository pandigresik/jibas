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
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="height: 100%">
<tr>
    <td width="85%">

        <table border="0" width="100%" cellpadding="4" cellspacing="0" style="height: 100%">
        <tr>
            <td width="100%" colspan="5" valign="middle" align="left">
                <span class="spJudulUjian"><?=$_SESSION["Judul"]?></span>
            </td>
        </tr>
        <tr>
            <td width="*" valign="middle" align="left">
                <span class="spInfoUjian" id="spPelajaran">
                Pelajaran
                </span>
            </td>
            <td width="17%" valign="middle" align="left">
                <span class="spInfoUjian" id="spWaktuServer">
                Waktu Server: -
                </span>
            </td>
            <td width="15%" valign="middle" align="left">
                <span class="spInfoUjian" id="spDurasi">
                Durasi: -
                </span>
            </td>
            <td width="15%" valign="middle" align="left">
                <span class="spInfoUjian" id="spElapsed">
                Waktu Ujian: -
                </span>
            </td>
            <td width="15%" valign="middle" align="left">
                <span class="spInfoUjian" id="spTimeLeft">
                Sisa Waktu: -
                </span>
            </td>
        </tr>
        </table>

    </td>
    <td width="*">

        <table border="0" width="100%" cellpadding="4" cellspacing="0" style="height: 100%">
        <tr>
            <td width="100%" align="right" valign="middle">
                <input type="button" class="BtnDefault"
                       style="background-color: #a048a1; color: #fff; font-size: 18px;
                              width: 140px; height: 40px;" value="SELESAI UJIAN"
                       name="btUjian"
                       onclick="ujian_confirmFinishUjian()"><br>
                <span style="color: #FFFFFF;" id="lbFinishInfo"></span>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>