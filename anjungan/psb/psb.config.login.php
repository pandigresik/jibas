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
<br><br><br>
<center>
<fieldset style="border-color: #557d1d; border-width: 1px; width: 420px;" >
<legend>
    <font style='color: #557d1d; font-size: 12px; font-weight: bold;'>KONFIGURASI HALAMAN PSB</font>
</legend>
<br>   
<table border='0' cellpadding='2' cellspacing='2' align='center'>
<tr>
    <td align='left' colspan="2">
        <font style='color: #557d1d'>
        Konfigurasi halaman PSB mengatur pengaktifan pendataan calon siswa baru melalui JIBAS Anjungan Informasi. Setelah proses PSB selesai, anda dapat menonaktifkan kembali pendataan ini.<br><br> 
        Silahkan login dahulu sebagai Administrator JIBAS untuk mengatur konfigurasi halaman PSB.<br><br>
        </font>
    </td>
</tr>    
<tr>
    <td align='right'>
        <strong>Administrator:</strong>
    </td>
    <td align='left'>
        <input type='text' id='psb_admin' style='background-color: #ccc' maxlength='14' size='14' class='inputbox' value='jibas' readonly="readonly">
    </td>
</tr>
<tr>
    <td align='right'>
        <strong>Password:</strong>
    </td>
    <td align='left'>
        <input type='password' id='psb_password' maxlength='30' size='30' value='' class='inputbox'>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <input type="button" onclick="psb_Login()" value="Login" style='width: 100px; height: 30px;' class="but">
        <input type="button" onclick="psb_CancelLogin()" value="Batal" style='width: 100px; height: 30px;' class="but">
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <span id="psb_ConfigLoginError" style='color: red'></span>
    </td>
</tr>
</table>

</fieldset>
</center>
<br>
