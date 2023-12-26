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
require_once("beranda.session.php");
require_once("beranda.security.php");
require_once("beranda.listbg.php");

$nbg = count($bgList);
?>
<font style="font-family: 'Times New Roman'; font-size: 18px;">Pengaturan Gambar Latar</font><br><br>
Jeda waktu: <input type='text' class='inputbox' style='width: 40px; height: 32px;'
                   id='b_delay' value='<?= $bgDelay ?>'>&nbsp;detik<br>
<input type='hidden' name='b_ngambar' id='b_ngambar' value='<?= $nbg ?>'>
<table id='b_tabGambar' cellpadding='0' cellspacing='0'>
<tbody>
<?php
for($i = 1; $i <= $nbg; $i++)
{
    $bgFile = $bgList[$i - 1];
?>
    <input type='hidden'
           id='b_isdel<?= $i ?>'
           value='0'>
    <input type='hidden'
           id='b_filename<?= $i ?>'
           value='<?= $bgFile ?>'>                    
    <tr id='b_row<?= $i ?>'>
        <td align='left'>
            <input type='text' id='b_gambar_file<?= $i ?>'
                   name='b_gambar_file<?= $i ?>'
                   style='width: 500px; height: 32px; background-color: #eee;'
                   readonly='readonly'
                   class='inputbox'
                   value='<?= $bgFile ?>'>
            <a href='<?= "images/background/$bgFile" ?>' class='lytebox' data-lyte-options='group:bglist'>
                <img src='images/lihat.png' border='0'>
            </a>
            <a onclick="b_DelPicture(<?=$i?>)" title="hapus gambar ini!">
                <img src='images/hapus.png' border='0'>
            </a> 
        </td>
    </tr>
<?php
}
?>
</tbody>
</table>
<br>
<input type='hidden' name='b_new_ngambar' id='b_new_ngambar' value='1'>
<table id='b_new_tabGambar' cellpadding='0' cellspacing='0'>
<tbody>
    <tr id='b_new_row1'>
        <td align='left'>
            <input type='file' id='b_new_gambar_file1'
                   name='b_new_gambar_file1' style='width: 500px; height: 32px;'
                   class='inputbox'>
        </td>
    </tr>
</tbody>
<tfoot>
    <tr>
        <td align='left'>
            <span style='color: blue; cursor: pointer;' onclick='b_addNewPicture()'>
                Tambah gambar
            </span>    
        </td>
    </tr>
</tfoot>
</table>
<br>
<input type="button"
       id='btnSavePicture'
       onclick="b_SavePicture()"
       value="Simpan" style="width: 80px; height: 40px" class="but">
<input type="button"
       id='btnClosePicture'
       onclick="b_Close()"
       value="Batal" style="width: 80px; height: 40px" class="but">    