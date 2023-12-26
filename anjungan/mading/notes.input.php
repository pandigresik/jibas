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
require_once("../library/datearith.php");
require_once("notes.input.config.php");

OpenDb();
?>
<table border='0' cellpadding='2' cellspacing='0' width='98%'>
<tr>
    <td align='left' valign='top'>
        <font class='TitleTabMenu'>N O T E S&nbsp;&nbsp;&nbsp;B A R U</font>    
    </td>
    <td align='right' valign='bottom'>
        <a onclick='not_BackRefreshNotesList()'>kembali</a>&nbsp;&nbsp;
    </td>
</tr>    
</table>
<br>
<form name="not_form" id="not_form" method="post">
<table border="0" width="100%" cellpadding="2" cellspacing="5">
<tr>
    <td width="10%" align="right">
        <strong>Judul:</strong>
    </td>
    <td width="*" align="left">
        <input type="text" name="not_judul" id="not_judul" size="90" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td width="10%" align="right">
        <strong>Kepada:</strong>
    </td>
    <td width="*" align="left">
        <input type="text" name="not_kepada" id="not_kepada" size="40" maxlength="100" class="inputbox">
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        <strong>Pesan:</strong><br>
        <input type='text' size='3' readonly='readonly'
               style='background-color: #ddd; text-align: right;'
               class='inputbox'
               value='<?=$maxNotesLength?>'
               id='not_sisa'><br>
        <span style='color: blue; cursor: pointer;' onclick='mad_ShowEmoticons()'>
            emoticons
        </span>       
    </td>
    <td width="*" align="left">
        <textarea id='not_pesan' name='not_pesan'
                  cols='88' rows='8' class='inputbox'
                  onkeyup='not_CheckMsgLength()'></textarea>
    </td>
</tr>
<tr>
    <td width="10%" align="right">
        Tautan:
    </td>
    <td width="*" align="left">
        <input type="text" name="not_tautan" id="not_tautan" size="90" maxlength="255" class="inputbox">
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Gambar:<br>
        <a href='#' title='<?= GetAllowedPictType() ?>'><img src='../images/tooltip.png' border='0'></a>
        <input type='hidden' name='not_ngambar' id='not_ngambar' value='1'>
    </td>
    <td width="*" align="left">
        <table id='not_tabGambar' cellpadding='0' cellspacing='0'>
        <tbody>
            <tr id='not_row1'>
                <td align='left'><input type='file' id='not_gambar_file1' name='not_gambar_file1' style='width: 300px; height: 32px;' class='inputbox'></td>
                <td align='left'><input type='textbox' id='not_gambar_info1' name='not_gambar_info1' style='width: 300px; height: 32px;' class='inputbox'></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan='2' align='left'>
                    <span style='color: blue; cursor: pointer;' onclick='not_addPicture()'>
                        Tambah gambar
                    </span>    
                </td>
            </tr>
        </tfoot>
        </table>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Dokumen:<br>
        <a href='#' title='<?= GetAllowedDocType() ?>'><img src='../images/tooltip.png' border='0'></a>
        <input type='hidden' name='not_nfile' id='not_nfile' value='1'>
    </td>
    <td width="*" align="left">
        <table id='not_tabFile' cellpadding='0' cellspacing='0'>
        <tbody>
            <tr id='not_file_row1'>
                <td align='left'><input type='file' id='not_file_file1' name='not_file_file1' style='width: 300px; height: 32px;' class='inputbox'></td>
                <td align='left'><input type='textbox' id='not_file_info1' name='not_file_info1' style='width: 300px; height: 32px;' class='inputbox'></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan='2' align='left' valign='middle'>
                    <span style='color: blue; cursor: pointer;' onclick='not_addFile()'>
                        Tambah berkas
                    </span>
                </td>
            </tr>
        </tfoot>
        </table>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        &nbsp;
    </td>
    <td width="*" align="left" valign='middle'>
        <br><br>
        <fieldset class='NotesLoginBox' style='width: 60%;'>
            <legend><strong>Login &amp; Simpan</strong></legend>
        Silahkan login terlebih dahulu untuk menyimpan notes ini.<br>
        Untuk siswa, gunakan NIS dan PIN Siswa.<br>
        Untuk pegawai, gunakan NIP dan Password aplikasi JIBAS.<br><br>
        Login: <input type='text' id='not_Login' class='inputbox' size='12' maxlength='25'>&nbsp;
        Password: <input type='password' id='not_Password' class='inputbox' size='12' maxlength='25'>&nbsp;
        <input id='not_SaveButton' type='button' class='but' 
               style='height: 40px; width: 90px;' value='Simpan'
               onclick='not_ValidateNotes()'>
        <span id='not_WaitBox' style='visibility: hidden;'>
            <img src='../images/wait.gif' height='20'>&nbsp;sedang menyimpan ...
        </span>
        </fieldset>
    </td>
</tr>
</table>
<br><br><br>
</form>    
<?php
CloseDb();
?>
