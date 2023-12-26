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
require_once("notes.list.func.php");
require_once("infosekolah.common.func.php");
?>
<table border='0' cellpadding='2' cellspacing='0' width='98%'>
<tr>
    <td align='left' valign='top'>
        <font class='TitleTabMenu'>N O T E S</font>    
    </td>
    <td align='right' valign='bottom'>
        <a onclick='not_NewNotesClicked()'>notes baru</a>&nbsp;&nbsp;
        <a onclick='not_ShowNotesIndexClicked()'>notes indeks</a>&nbsp;&nbsp;
        <a onclick='not_RefreshNotesList()'>refresh</a>
    </td>
</tr>    
</table>
<br>
<table id='not_NotesTableList' border='0' width='100%' cellspacing='0' cellpadding='5'>
<thead>
<tr height='25' class='NotesTableHeader'>
    <td width='12%' align='center'>DARI</td>
    <td width='12%' align='center'>KEPADA</td>
    <td width='*' align='left'>NOTES</td>
    <td width='10%' align='center'>KOMENTAR</td>
    <td width='10%' align='center'>DIBACA</td>
</tr>
</thead>
<tbody>
    
</tbody>
<tfoot>
    
</tfoot>
</table>