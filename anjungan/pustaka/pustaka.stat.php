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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('pustaka.stat.func.php');

OpenDb();
?>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
    <td align="left" colspan="3">
    <font style='color: #36720e; font-family: Verdana; font-size: 12px; font-weight: bold'>STATISTIK PUSTAKA TERFAVORIT</font>
    </td>
</tr>    
<tr>
    <td align="right" width='10%'>
    <strong>Perpustakaan:</strong>
    </td>    
    <td align="left" width='52%'>
<?php  ShowCbPustaka();    ?>
    &nbsp;
    <strong>Statistik:</strong>
<?php  ShowCbJumlah() ?>terfavorit
    </td>
    <td rowspan='2'>
    <input type='button' value='Lihat' class='but'
           style='height: 40px; width: 100px;'
           onclick='ptkastat_showstat()'>
    </td>
</tr>
<tr>
    <td align="right" width='100'>
    <strong>Bulan:</strong>
    </td>    
    <td align="left">
<?php  ShowCbBulan() ?>
    </td>
</tr>
<tr>
    <td align="left" colspan="3">
    <br>
    <div id="ptkastat_content">

    </div>    
    </td>
</tr>
</table>
<?php
CloseDb();
?>