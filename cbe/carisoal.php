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
<font class="mainPageTitle">Cari Soal</font><br>
<font class="mainPageSubTitle">Soal-soal yang telah dikerjakan melalui JIBAS CBE Web Client</font>
<br><br>

<table border="0" cellpadding="2" cellspacing="0">
<tr>
    <td align="left" width="70">ID Soal: </td>
    <td align="left" width="220">
        <input type="text" class="inputbox" style="width: 200px" id="cs_idSoal">
    </td>
    <td align="left">
        <img src="images/view.png" onclick="cs_showCariSoal()">
    </td>
</tr>
</table>
<br><br>
<div id="cs_divContent" style="width: 100%">
</div>