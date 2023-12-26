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
$op = $_REQUEST['op'];
?>
<input type='hidden' id='b_op' name='b_op' value='<?= $op ?>'>
<br><br><br><br>
<center>
<fieldset style="border-color: #557d1d; border-width: 1px; width: 420px;" >
<legend>
    <font style='color: #557d1d; font-size: 12px; font-weight: bold;'>KONFIGURASI HALAMAN INFORMASI SISWA</font>
</legend>
<br>
<table border='0' cellpadding='2' width='95%' cellspacing='0' align='left' style='line-height: 18px'>
<tr>
    <td align='left' colspan="2">
        <font style='color: #557d1d'>
        Konfigurasi ini mengatur apakah siswa dapat mengubah/menambah data-data pribadinya.
        Konfigurasi ini diperlukan supaya siswa tidak sembarangan mengubah data-data pribadinya.
        Atur supaya siswa hanya dapat mengubah data pribadinya di waktu tertentu saja, misalnya bagi siswa baru di awal tahun ajaran.
        <br><br> 
        Silahkan login dahulu sebagai Administrator JIBAS untuk mengatur konfigurasi halaman Informasi Siswa.<br><br>
        </font>
    </td>
</tr>       
<tr>
    <td width='35%' align='right'>
        <strong>Administrator:</strong>
    </td>
    <td align='left'>
        <input type='text' id='is_admin_login' class='inputbox'
               style='background-color: #bbb' maxlength='14' size='14' value='jibas' readonly="readonly">
    </td>
</tr>
<tr>
    <td align='right'>
        <strong>Password:</strong>
    </td>
    <td align='left'>
        <input type='password' id='is_admin_password' class='inputbox'
               style='font-size:16px' maxlength='30' size='30' value=''>
    </td>
</tr>
<tr>
    <td align='right'>
        &nbsp;
    </td>
    <td align="left">
        <input type="button" onclick="is_Admin_Login()" value="Login" style="width: 80px; height: 40px" class="but">
        <input type="button" onclick="is_Admin_Cancel()" value="Batal" style="width: 80px; height: 40px" class="but">
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <font style='color: red; font-size: 12px;'><?=$ERRMSG?></font>
    </td>
</tr>
</table>
</center>