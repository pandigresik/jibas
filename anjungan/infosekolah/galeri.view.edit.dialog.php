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
$galleryid = $_REQUEST['galleryid'];
?>
<input type='hidden' id='galed_GalleryId' value='<?=$galleryid?>'>
Silahkan login terlebih dahulu untuk mengubah galeri ini.<br>
Notes ini hanya dapat diubah oleh pemilik galeri.<br>
<table>
<tr>
    <td width='100' align='right'>Login</td>
    <td align='left'><input type='text' id='galed_Login' class='inputbox' size='12' maxlength='25'></td>
</tr>
<tr>
    <td align='right'>Password</td>
    <td align='left'><input type='password' id='galed_Password' class='inputbox' size='12' maxlength='25'></td>
</tr>    
</table>