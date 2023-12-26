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
<font class="mainPageTitle">Ujian Remedial</font><br>
<font class="mainPageSubTitle">Ujian perbaikan dari ujian yang telah dikerjakan</font><br><br>

<table border="0" cellpadding="2" cellspacing="0">
<tr>
    <td align="left" width="80">Status: </td>
    <td align="left">
        <select id="ur_cbViewDaftarUjian" class="inputbox" style="width: 120px" onchange="ur_changeCbView()">
            <option value="0" selected>Terjadwal</option>
            <option value="1">Belum dikerjakan</option>
            <option value="2">Sudah dikerjakan</option>
            <option value="3">Semua</option>
        </select>
    </td>
    <td width="120" valign="middle" align="center" rowspan="2">
        <img src="images/view.png" id="btView" onclick="ur_showDaftarUjian()">
    </td>
</tr>
<tr>
    <td align="left">Pelajaran: </td>
    <td align="left">
        <span id="ur_spCbPelajaran">
            memuat ..
        </span>
    </td>
</tr>
</table>
<br>
<div id="ur_divDaftarUjian" style="width: 100%">
</div>