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
require_once("include/config.php");
require_once("include/common.php");
require_once("include/db_functions.php");
?>
<script type="text/javascript" src="mading/mading.menu.js"></script>
<div id='mad_menu'>
<table border="0" cellpadding="0" cellspacing="0">
<tr height='28'>
<td width="240" align="left">
    Departemen:
    <select id='mad_departemen'
            name='mad_departemen'
            class='inputbox'
            style='width: 140px;'
            onchange='mad_DepartemenChanged()'>
<?php      $sql = "SELECT departemen
                  FROM jbsakad.departemen
                 WHERE aktif = 1
                 ORDER BY urutan";
        OpenDb();         
        $res = QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            echo "<option value='".$row[0]."'>".$row[0]."</option>";            
        }
        CloseDb(); ?>        
    </select>
</td>
<td width="116" align="center" valign='bottom'>
    <div id='mad_menu_notes'
         style='position: relative; width: 100%;'
         onmouseover="document.getElementById('mad_menu_notes').style.cursor = 'pointer';"
         onclick='mad_MenuNotesClicked()'>
    <img id='mad_menu_notes_image' src='images/tab-aktif.png'>
    <span style='position: absolute; top: 10px; left: 34px'>N o t e s</span>
    </div>
</td>
<td width="116" align="center" valign='bottom'>
    <div id='mad_menu_galeri'
         style='position: relative; width: 100%;'
         onmouseover="document.getElementById('mad_menu_galeri').style.cursor = 'pointer';"
         onclick='mad_MenuGaleriClicked()'>
    <img id='mad_menu_galeri_image' src='images/tab-pasif.png'>
    <span style='position: absolute; top: 10px; left: 30px'>G a l e r i</span>
    </div>
</td>
<td width="116" align="center" valign='bottom'>
    <div id='mad_menu_video'
         style='position: relative; width: 100%;'
         onmouseover="document.getElementById('mad_menu_video').style.cursor = 'pointer';"
         onclick='mad_MenuVideoClicked()'>
    <img id='mad_menu_video_image' src='images/tab-pasif.png'>
    <span style='position: absolute; top: 10px; left: 30px'>V i d e o</span>
    </div>
</td> 
</tr>    
</table>
</div> 