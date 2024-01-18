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
require_once("notes.edit.config.php");
require_once("notes.input.config.php");
require_once("notes.edit.session.php");

if(!isset($_SESSION['allowedit']))
{
    echo "Invalid Session";
    exit();
}

OpenDb();

$notesid = $_REQUEST['notesid'];
//$notesid = 2;

$sql = "SELECT *
          FROM jbsvcr.notes
         WHERE replid = '".$notesid."'";
$res = QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    CloseDb();
    
    echo "Tidak ditemukan notes!";
    exit();
}
$row = mysqli_fetch_array($res);
?>
<table border='0' cellpadding='2' cellspacing='0' width='98%'>
<tr>
    <td align='left' valign='top'>
        <font class='TitleTabMenu'>U B A H&nbsp;&nbsp;&nbsp;N O T E S</font>    
    </td>
    <td align='right' valign='bottom'>
        <a onclick='not_edit_BackToNotesView()'>kembali</a>&nbsp;&nbsp;
    </td>
</tr>    
</table>
<br>
<form name="not_form" id="not_form" method="post">
<input type='hidden' id='not_edit_notesid' name='not_edit_notesid' value='<?=$notesid?>'>
<table border="0" width="100%" cellpadding="2" cellspacing="0"> 
<tr>
    <td width="10%" align="right">
        <strong>Judul:</strong>
    </td>
    <td width="*" align="left">
        <input type="text" name="not_edit_judul" id="not_edit_judul" size="90" maxlength="100" class="inputbox" value="<?=$row['judul']?>">
    </td>
</tr>
<tr>
    <td width="10%" align="right">
        <strong>Kepada:</strong>
    </td>
    <td width="*" align="left">
        <input type="text" name="not_edit_kepada" id="not_edit_kepada" size="40" maxlength="100" class="inputbox" value="<?=$row['kepada']?>">
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        <strong>Pesan:</strong><br>
        <input type='text' size='3' readonly='readonly'
               style='background-color: #ddd; text-align: right;'
               class='inputbox'
               value='<?=$maxNotesLength?>'
               id='not_edit_sisa'><br>
        <span style='color: blue; cursor: pointer;' onclick='mad_ShowEmoticons()'>
            emoticons
        </span>        
    </td>
    <td width="*" align="left">
        <textarea id='not_edit_pesan' name='not_edit_pesan'
                  cols='60' rows='5' class='inputbox'
                  onkeyup='not_edit_CheckMsgLength()'><?=$row['pesan']?></textarea>
    </td>
</tr>
<tr>
    <td width="10%" align="right">
        Tautan:
    </td>
    <td width="*" align="left">
        <input type="text" name="not_edit_tautan" id="not_edit_tautan" size="90" maxlength="255" class="inputbox" 
               value="<?=$row['tautan']?>">
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Gambar:<br>
        <a href='#' title='<?= GetAllowedPictType() ?>'><img src='../images/tooltip.png' border='0'></a>
    </td>
    <td width="*" align="left">
        <table id='not_edit_tabGambar' cellpadding='0' cellspacing='0'>
<?php
        $sql = "SELECT replid, filename, fileinfo, location
                  FROM jbsvcr.notesfile
                 WHERE notesid = '$notesid'
                   AND filecate = 'pict'";
        $res2 = QueryDb($sql);           
        
        $n = 0;
        echo "<thead>";
        while($row2 = mysqli_fetch_array($res2))
        {
            $file = $row2['location'] . "/" . $row2['filename'];
            $info = str_replace("'", "`", (string) $row2['fileinfo']);
            
            $n += 1; ?>
            <input type='hidden' id='not_edit_gambar_replid<?=$n?>' name='not_edit_gambar_replid<?=$n?>' value="<?=$row2['replid']?>">
            <input type='hidden' id='not_edit_gambar_delete<?=$n?>' name='not_edit_gambar_delete<?=$n?>' value="0">
            <tr id='not_edit_gambar_row<?=$n?>'>
                <td align='left'>
                    <input type='textbox' id='not_edit_gambar_file<?=$n?>' name='not_edit_gambar_file<?=$n?>'
                           style='width: 300px; height: 32px; background-color: #ddd;' class='inputbox'
                           value="<?= $row2['filename'] ?>"
                           readonly="readonly">    
                </td>
                <td align='left'>
                    <input type='textbox' id='not_edit_gambar_info<?=$n?>' name='not_edit_gambar_info<?=$n?>'
                           style='width: 300px; height: 32px;' class='inputbox'
                           value="<?= $row2['fileinfo'] ?>">
                    <a href='<?= "$FILESHARE_ADDR/$file" ?>' class='lytebox' data-lyte-options='group:notespict' data-title='<?= $info ?>'>
                        <img src='../images/lihat.png' border='0'>
                    </a>
                    <a onclick="not_edit_DeleteEditGambar(<?=$n?>)" title="hapus gambar ini!">
                        <img src='../images/hapus.png' border='0'>
                    </a>       
                </td>
            </tr>            
<?php      }
        echo "</thead>";
        echo "<input type='hidden' id='not_edit_ngambar' name='not_edit_ngambar' value='$n'>";
        ?>
        <tbody>
            <tr id='not_edit_new_gambar_row1'>
                <td align='left'><input type='file' id='not_edit_new_gambar_file1' name='not_edit_new_gambar_file1' style='width: 300px; height: 32px;' class='inputbox'></td>
                <td align='left'><input type='textbox' id='not_edit_new_gambar_info1' name='not_edit_new_gambar_info1' style='width: 300px; height: 32px;' class='inputbox'></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan='2' align='left'>
                    <input type='hidden' id='not_edit_new_ngambar' name='not_edit_new_ngambar' value='1'>
                    <span style='color: blue; cursor: pointer;' onclick='not_edit_addPicture()'>
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
    </td>
    <td width="*" align="left">
        <table id='not_edit_tabFile' cellpadding='0' cellspacing='0'>
<?php
        $sql = "SELECT replid, filename, fileinfo
                  FROM jbsvcr.notesfile
                 WHERE notesid = '$notesid'
                   AND filecate = 'doc'";
        $res2 = QueryDb($sql);           
        
        $n = 0;
        echo "<thead>";
        while($row2 = mysqli_fetch_array($res2))
        {
            $n += 1; ?>
            <input type='hidden' id='not_edit_file_replid<?=$n?>' name='not_edit_file_replid<?=$n?>' value="<?=$row2['replid']?>">
            <input type='hidden' id='not_edit_file_delete<?=$n?>' name='not_edit_file_delete<?=$n?>' value="0">
            <tr id='not_edit_file_row<?=$n?>'>
                <td align='left'>
                    <input type='textbox' id='not_edit_file_file<?=$n?>' name='not_edit_file_file<?=$n?>'
                           style='width: 300px; height: 32px; background-color: #ddd;' class='inputbox'
                           value="<?= $row2['filename'] ?>"
                           readonly="readonly">    
                </td>
                <td align='left'>
                    <input type='textbox' id='not_edit_file_info<?=$n?>' name='not_edit_file_info<?=$n?>'
                           style='width: 300px; height: 32px;' class='inputbox'
                           value="<?= $row2['fileinfo'] ?>">
                    <a onclick="not_edit_DeleteEditDoc(<?=$n?>)" title="hapus dokumen ini"><img src='../images/hapus.png' border='0'></a>       
                </td>
            </tr>            
<?php      }
        echo "</thead>";
        echo "<input type='hidden' id='not_edit_nfile' name='not_edit_nfile' value='$n'>";
        ?>        
        <tbody>
            <tr id='not_edit_new_file_row1'>
                <td align='left'><input type='file' id='not_edit_new_file_file1' name='not_edit_new_file_file1' style='width: 300px; height: 32px;' class='inputbox'></td>
                <td align='left'><input type='textbox' id='not_edit_new_file_info1' name='not_edit_new_file_info1' style='width: 300px; height: 32px;' class='inputbox'></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan='2' align='left' valign='middle'>
                    <input type='hidden' id='not_edit_new_nfile' name='not_edit_new_nfile' value='1'>
                    <span style='color: blue; cursor: pointer;' onclick='not_edit_addFile()'>
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
        <input id='not_edit_SaveButton' type='button' class='but' 
               style='height: 40px; width: 90px;' value='Simpan'
               onclick='not_edit_SaveNotes()'>
        <span id='not_edit_WaitBox' style='visibility: hidden;'>
            <img src='../images/wait.gif' height='20'>&nbsp;sedang menyimpan ...
        </span>    
    </td>
</tr>
</table>
<br><br><br>
</form>    
<?php
CloseDb();
?>
