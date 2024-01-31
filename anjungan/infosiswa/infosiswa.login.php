<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 3.11 (May 02, 2018)
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
<br><br><br><br>
<center>
<fieldset style="border-color: #557d1d; border-width: 1px; width: 420px;" >
<legend>
    <font style='color: #557d1d; font-size: 12px; font-weight: bold;'>LOGIN INFORMASI SISWA</font>
</legend>
<br>
<table border='0' cellpadding='2' width="96%" cellspacing='2' align='center' style='line-height: 18px;'>
<tr>
    <td align="left" colspan="2">
        <font style='color: #557d1d'>
        Melalui halaman Informasi Siswa, anda dapat melihat Data Pribadi, Akademik, Presensi dan Keuangan siswa.<br><br>
        Silahkan isikan PIN Siswa untuk masuk ke halaman Informasi Siswa. Anda dapat menanyakan PIN Siswa kepada staf sekolah apabila anda tidak mengetahuinya.
        </font>
    </td>
</tr>    
<tr>
    <td align='right' width="35%">
        <strong>N I S:</strong>
    </td>
    <td align='left'>
        <input type='text' id='is_nis' class='inputbox'  maxlength='24' size='24' value=''>
    </td>
</tr>
<tr>
    <td align='right'>
        <strong>P I N:</strong>
    </td>
    <td align='left'>
        <input type='password' id='is_pin' class='inputbox' maxlength='6' size='6' value=''>
    </td>
</tr>
<tr>
    <td align='right'>
        &nbsp;
    </td>
    <td align='left'>
        <input type="button" onclick="is_Login()" value="Login" style="height: 40px; width: 100px;" class="but">
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <font style='color: red; font-size: 12px;'><?=$ERRMSG ?? ''?></font>
    </td>
</tr>
</table>
</center>
