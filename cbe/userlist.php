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
require_once ("include/session.php");
?>
<font class="mainPageTitle">Daftar Koneksi Pengguna CBE</font><br>
<span style="color: #333; font-style: italic">Halaman ini akan otomatis di muat ulang setiap 1 menit</span><br>
<span style="color: blue; text-decoration: underline; cursor: pointer" onclick="ul_getUserList()">muat ulang</span>

<br><br>
<div id="ul_divContainer" style="overflow: auto; background-color: #fff;">
<div id="ul_divDaftarPeserta"></div>
</div>
<br>
<div id="ul_divSearchUser" style="overflow: auto; background-color: #e8e8e8; line-height: 24px;">
<strong>Search User:</strong><br>
User Id: <input type="text" id="ul_searchUserId" size="30" maxlength="30">&nbsp;<input type="button" value="cari" onclick="ul_cariUser()"><br>
<div id="ul_divSearchResult"></div>
</div>