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
require_once("galeri.input.config.php");
require_once("galeri.edit.session.php");

if (!isset($_SESSION['allowedit']))
{
    echo "Session Invalid";
    exit();
}

$galleryid = $_REQUEST['galleryid'];
// $galleryid = 15;

OpenDb();
$sql = "SELECT *
          FROM jbsvcr.gallery
         WHERE replid = '".$galleryid."'";
$res = QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    CloseDb();
    
    echo "Tidak ditemukan gallery!";
    exit();
}
$row = mysqli_fetch_array($res);
?>
<table border='0' cellpadding='2' cellspacing='0' width='98%'>
<tr>
    <td align='left' valign='top'>
        <font class='TitleTabMenu'>U B A H&nbsp;&nbsp;&nbsp;G A L E R I</font>    
    </td>
    <td align='right' valign='bottom'>
        <a onclick='galed_BackToGalleryView()'>kembali</a>        
    </td>
</tr>    
</table>
<br>
<form name="galed_form" id="galed_form" method="post">
<input type='hidden' id='galed_galleryid' name='galed_galleryid' value='<?=$galleryid?>'>    
<table border="0" width="100%" cellpadding="2" cellspacing="5">
<tr>
    <td width="10%" align="right">
        <strong>Judul:</strong>
    </td>
    <td width="*" align="left">
        <input type="text" name="galed_judul" id="galed_judul" size="90" maxlength="100" class="inputbox" value="<?=$row['judul']?>">
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        <strong>Keterangan:</strong><br>
        <input type='text' size='3' readonly='readonly'
               style='background-color: #ddd; text-align: right;'
               class='inputbox'
               value='<?=$maxNotesLength?>'
               id='galed_sisa'><br>
        <span style='color: blue; cursor: pointer;' onclick='ifse_ShowEmoticons()'>
            emoticons
        </span>       
    </td>
    <td width="*" align="left">
        <textarea id='galed_pesan' name='galed_pesan'
                  cols='88' rows='9' class='inputbox'
                  onkeyup='galed_CheckMsgLength()'><?=$row['keterangan']?></textarea>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        <strong>Cover:</strong><br>
        <a href='#' title='<?= GetAllowedPictType() ?>'><img src='../images/tooltip.png' border='0'></a>
    </td>
    <td width="*" align="left" valign="top">
        <table cellpadding='0' cellspacing='0'>
<?php
        $sql = "SELECT *
                  FROM jbsvcr.galleryfile
                 WHERE galleryid = '$galleryid'
                   AND iscover = 1";
        $res2 = QueryDb($sql);
        $row2 = mysqli_fetch_array($res2);
        $coverfile = $row2['location'] . "/" . $row2['filename'];
        $coverinfo = str_replace("'", "`", (string) $row2['fileinfo']);
?>
        <tbody>
        <tr>
            <td align='left'>
                <input type='textbox' id='galed_cover_file' name='galed_cover_file'
                       style='width: 300px; height: 32px; background-color: #ddd'
                       class='inputbox' readonly='readonly'
                       value="<?= $row2['filename'] ?>">
            </td>
            <td align='left'>
                <input type='textbox' id='galed_cover_info' name='galed_cover_info'
                       style='width: 300px; height: 32px;'
                       class='inputbox' value="<?= $row2['fileinfo'] ?>">
                <a href='<?= "$FILESHARE_ADDR/$coverfile" ?>' class='lytebox' data-lyte-options='group:gallerypict' data-title='<?= $coverinfo ?>'>
                    <img src='../images/lihat.png' border='0'>
                </a>       
            </td>
        </tr>
        <tr>
            <td colspan="2" align="left">
                Ganti cover:
            </td>    
        </tr>
        <tr>
            <td align='left'>
                <input type='file' id='galed_new_cover_file' name='galed_new_cover_file'
                       style='width: 300px; height: 32px;' class='inputbox'>
            </td>
            <td align='left'>
                <input type='textbox' id='galed_new_cover_info' name='galed_new_cover_info'
                       style='width: 300px; height: 32px;' class='inputbox'>
            </td>
        </tr>
        </tbody>
        </table>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        <strong>Gambar:</strong><br>
        <a href='#' title='<?= GetAllowedPictType() ?>'><img src='../images/tooltip.png' border='0'></a>
    </td>
    <td width="*" align="left">
        <table id='galed_tabGambar' cellpadding='0' cellspacing='0'>
        <thead>
<?php
        $sql = "SELECT *
                  FROM jbsvcr.galleryfile
                 WHERE galleryid = '$galleryid'
                   AND iscover = 0";
        $res2 = QueryDb($sql);
        
        $n = 0;
        while($row2 = mysqli_fetch_array($res2))
        {
            $file = $row2['location'] . "/" . $row2['filename'];
            $info = str_replace("'", "`", (string) $row2['fileinfo']);
            
            $n += 1; ?>
            
            <input type='hidden' id='galed_gambar_replid<?=$n?>' name='galed_gambar_replid<?=$n?>' value="<?=$row2['replid']?>">
            <input type='hidden' id='galed_gambar_delete<?=$n?>' name='galed_gambar_delete<?=$n?>' value="0">
            <tr id='galed_gambar_row<?=$n?>'>
                <td align='left'>
                    <input type='textbox' id='galed_gambar_file<?=$n?>' name='galed_gambar_file<?=$n?>'
                           style='width: 300px; height: 32px; background-color: #ddd;' class='inputbox'
                           value="<?= $row2['filename'] ?>"
                           readonly="readonly">    
                </td>
                <td align='left'>
                    <input type='textbox' id='galed_gambar_info<?=$n?>' name='galed_gambar_info<?=$n?>'
                           style='width: 300px; height: 32px;' class='inputbox'
                           value="<?= $row2['fileinfo'] ?>">
                    <a href='<?= "$FILESHARE_ADDR/$file" ?>' class='lytebox' data-lyte-options='group:notespict' data-title='<?= $info ?>'>
                        <img src='../images/lihat.png' border='0'>
                    </a>
                    <a onclick="galed_DeleteEditGambar(<?=$n?>)" title="hapus gambar ini!">
                        <img src='../images/hapus.png' border='0'>
                    </a>       
                </td>
            </tr>   
<?php
        } // while
        echo "<input type='hidden' id='galed_ngambar' name='galed_ngambar' value='$n'>";
?>
        </thead>    
        <tbody>
            <tr id='galed_new_row1'>
                <td align='left'><input type='file' id='galed_new_gambar_file1' name='galed_new_gambar_file1' style='width: 300px; height: 32px;' class='inputbox'></td>
                <td align='left'><input type='textbox' id='galed_new_gambar_info1' name='galed_new_gambar_info1' style='width: 300px; height: 32px;' class='inputbox'></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan='2' align='left'>
                    <input type='hidden' id='galed_new_ngambar' name='ngaled_new_ngambar' value='1'>
                    <span style='color: blue; cursor: pointer;' onclick='galed_addPicture()'>
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
        &nbsp;
    </td>
    <td width="*" align="left" valign='middle'>
        <input id='galed_SaveButton' type='button' class='but' 
               style='height: 40px; width: 90px;' value='Simpan'
               onclick='galed_SaveNotes()'>
        <span id='galed_WaitBox' style='visibility: hidden;'>
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