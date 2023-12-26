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
<font class="mainPageTitle">Test Koneksi CBE Server</font><br>
<font class="mainPageSubTitle">Test kualitas koneksi jaringan ke CBE Server</font>
<br><br>

<table border="0" cellpadding="2" cellspacing="0">
<tr>
    <td align="left" width="200">
        Interval:&nbsp;
        <select id="cbIntervalTc">
            <option value="5">per 5 detik</option>
            <option value="10">per 10 detik</option>
            <option value="15">per 15 detik</option>
        </select>
    </td>
    <td align="left" width="200">
        Pengulangan:&nbsp;
        <select id="cbRepetitionTc">
            <option value="10">10 kali</option>
            <option value="20">20 kali</option>
            <option value="30">30 kali</option>
            <option value="40">40 kali</option>
            <option value="50">50 kali</option>
        </select>
    </td>
    <td align="left" width="200">
        Ukuran Data:&nbsp;
        <select id="cbDataSizeTc">
            <option value="32">32 KB</option>
            <option value="64">64 KB</option>
        </select>
    </td>
    <td align="left" width="150">
        Berhasil:&nbsp;
        <input type="text" id="txSuccessTc" readonly size="5" style="font-size: 14px; width: 70px; background-color: #c6f8ff">
    </td>
    <td align="left" width="150">
        Gagal:&nbsp;
        <input type="text" id="txFailedTc" readonly size="5" style="font-size: 14px; width: 70px;  background-color: #ffe3d4">
    </td>
    <td align="left">
        <input type="button" id="btStartTc" value="Start" onclick="tc_Start()" class="BtnDefault">
        <input type="button" id="btStopTc" value="Stop" onclick="tc_Stop()" class="BtnDefault" disabled>
    </td>
</tr>
</table>
<br><br>
<div id="tc_divContent" style="width: 100%; height: 500px">
<table id="tabContentTc" border="0" cellpadding="2" cellspacing="2" width="100%">
<tbody id="tabBodyTc">

</tbody>
</table>
</div>