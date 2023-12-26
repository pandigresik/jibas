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
<font class="mainPageTitle">Direktori Resource</font><br>
<font class="mainPageSubTitle">Direktori tempat menyimpan gambar-gambar soal ketika ujian</font><br><br>

<span style="color: #333; font-style: italic">Gunakan halaman ini untuk menghapus direktori resource yang sudah lama tidak digunakan untuk menghemat tempat kapasitas hard disk</span><br>
<span style="color: red; font-style: italic">Direktori yang baru sebaiknya tidak dihapus</span><br><br>
<span style="color: blue; text-decoration: underline; cursor: pointer" onclick="ru_showResDir()">muat ulang</span>

<br><br>
<div id="ru_divContainer" style="width: 100%; overflow: auto; background-color: #fff;">
    <div id="ru_divResDir"></div>
</div>