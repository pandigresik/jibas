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
require_once("infosiswa.session.php");
require_once("infosiswa.security.php");
require_once("infosiswa.config.php");

$checked = $IsAllowStudentEdit ? "checked" : "";
?>
<table border='0' cellpadding='0' cellspacing='0'>
<tr>
<td align='left' valign='top' style='line-height: 22px;'>
    
<font style="font-family: 'Times New Roman'; font-size: 18px;">Pengaturan Halaman Informasi Siswa</font>
<br><br>
<input type='checkbox' id='is_allowedit' <?=$checked?>>&nbsp;
<font style='font-weight: bold; color: maroon;'>Perbolehkan siswa mengubah data pribadinya.</font><br>
Konfigurasi ini mengatur apakah siswa dapat mengubah/menambah data-data pribadinya.<br>
Konfigurasi ini diperlukan supaya siswa tidak sembarangan mengubah data-data pribadinya.<br>
Atur supaya siswa hanya dapat mengubah data pribadinya di waktu tertentu saja, misalnya bagi siswa baru di awal tahun ajaran.<br>
<br>
<input type="button"
       id='is_BtnSaveConfig'
       onclick="is_SaveConfig()"
       value="Simpan" style="width: 80px; height: 40px" class="but">
<input type="button"
       id='is_BtnCloseConfig'
       onclick="is_CloseConfig()"
       value="Batal" style="width: 80px; height: 40px" class="but">    
</td>    
</tr>    
</table>