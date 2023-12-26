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
require_once("video.index.func.php");
?>
<table border='0' cellpadding='2' cellspacing='0' width='98%'>
<tr>
    <td align='left' valign='top'>
        <font class='TitleTabMenu'>V I D E O&nbsp;&nbsp;&nbsp;I N D E K S</font>    
    </td>
    <td align='right' valign='bottom'>
        <a onclick='vididx_BackToVideoList()'>kembali</a>        
    </td>
</tr>    
</table>
<br>
Bulan: <?php ShowComboBulan(); ?><?php ShowComboTahun(); ?>
<input type='button' value='Lihat'
       class='but' onclick='vididx_ShowIndex()'>    
<table id='vididx_VideoIndexTableList' border='0' width='100%' cellspacing='0' cellpadding='5'>
<thead>
<tr height='25' class='VideoTableHeader'>
    <td width='25%' align='center'>VIDEO</td>
    <td width='*' align='left'>DESKRIPSI</td>
    <td width='10%' align='center'>KOMENTAR</td>
    <td width='10%' align='center'>DILIHAT</td>
</tr>
</thead>    
<tbody>   
</tbody>
</table>