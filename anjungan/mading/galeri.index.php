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
require_once("../include/config.php");
require_once("../include/common.php");
require_once("../include/compatibility.php");
require_once("../include/db_functions.php");
require_once("galeri.index.func.php");
?>
<table border='0' cellpadding='2' cellspacing='0' width='98%'>
<tr>
    <td align='left' valign='top'>
        <font class='TitleTabMenu'>G A L E R I&nbsp;&nbsp;&nbsp;I N D E K S</font>    
    </td>
    <td align='right' valign='bottom'>
        <a onclick='galidx_BackToGalleryList()'>kembali</a>        
    </td>
</tr>    
</table>
<br>
Bulan: <?php ShowComboBulan(); ?><?php ShowComboTahun(); ?>
<input type='button' value='Lihat'
       class='but' onclick='galidx_ShowIndex()'>    
<table id='galidx_GalleryIndexTableList' border='0' width='100%' cellspacing='0' cellpadding='5'>
<tbody>   
</tbody>
</table>