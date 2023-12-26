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
<br><br>
<table border='0' cellspacing='5' width='100%' style='background-color: #eee;'>
<tr>
    <td width='120' align='right'>&nbsp;</td>
    <td width='300' align='left'>
        <font style='font-size: 14px; color: #666'>UPLOAD GAMBAR</font>
    </td>
</tr>
<tr>
    <td align='right'>Gambar:</td>
    <td align='left'>
        <input type='file' id='gambar' name='gambar' style='width: 320px'>
    </td>
</tr>
<tr>
    <td align='right'>Deskripsi:</td>
    <td align='left'>
        <textarea rows='3' cols='40' id='deskripsi'></textarea>
    </td>
</tr>
<tr>
    <td width='120' align='right'>&nbsp;</td>
    <td width='300' align='left'>
        <input type='button' value='Upload' id='btUpload' class='but' onclick='uploadPict()'>
        <span id='lbInfo'></span>    
    </td>
</tr>    
</table>