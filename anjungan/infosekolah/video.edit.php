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
require_once("video.input.config.php");
require_once("video.edit.session.php");

if (!isset($_SESSION['allowedit']))
{
    echo "Invalid Session";
    exit();
}

OpenDb();

$videoid = $_REQUEST['videoid'];
//$videoid = 2;

OpenDb();
$sql = "SELECT *
          FROM jbsvcr.video
         WHERE replid = '".$videoid."'";
$res = QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    CloseDb();
    
    echo "Tidak ditemukan video!";
    exit();
}
$row = mysqli_fetch_array($res);
?>
<table border='0' cellpadding='2' cellspacing='0' width='98%'>
<tr>
    <td align='left' valign='top'>
        <font class='TitleTabMenu'>U B A H&nbsp;&nbsp;&nbsp;V I D E O</font>    
    </td>
    <td align='right' valign='bottom'>
        <a onclick='vided_BackToVideoView()'>kembali</a>        
    </td>
</tr>    
</table>
<br>
<form name="vided_form" id="vided_form" method="post">
<input type='hidden' id='vided_videoid' name='vided_videoid' value='<?=$videoid?>'> 
<table border="0" width="100%" cellpadding="2" cellspacing="5">
<tr>
    <td width="10%" align="right">
        <strong>Judul:</strong>
    </td>
    <td width="*" align="left">
        <input type="text" name="vided_judul" id="vided_judul" size="90"
               maxlength="100" class="inputbox" value="<?=$row['judul']?>">
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        <strong>Keterangan:</strong><br>
        <input type='text' size='3' readonly='readonly'
               style='background-color: #ddd; text-align: right;'
               class='inputbox'
               value='<?=$maxNotesLength?>'
               id='vided_sisa'><br>
        <span style='color: blue; cursor: pointer;' onclick='ifse_ShowEmoticons()'>
            emoticons
        </span>       
    </td>
    <td width="*" align="left">
        <textarea id='vided_pesan' name='vided_pesan'
                  cols='88' rows='9' class='inputbox'
                  onkeyup='vided_CheckMsgLength()'><?=$row['keterangan']?></textarea>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        <strong>Video:</strong><br>
        <a href='#' title='<?= GetAllowedVideoType() ?>'><img src='../images/tooltip.png' border='0'></a>
    </td>
    <td width="*" align="left">
        <table id='vided_tabGambar' cellpadding='0' cellspacing='0'>
        <tr id='vided_row1'>
            <td align='left'>
                <input type='text' id='vided_video_file' name='vided_video_file'
                       style='width: 500px; height: 32px; background-color: #ddd'
                       class='inputbox' value="<?= $row['filename'] ?>">
            </td>
        </tr>
        <tr>
            <td colspan="2" align="left">
                Ganti video:
            </td>    
        </tr>
        <tr id='vided_row2'>
            <td align='left'>
                <input type='file' id='vided_new_video_file' name='vided_new_video_file'
                       style='width: 500px; height: 32px;'
                       class='inputbox'>
            </td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        &nbsp;
    </td>
    <td width="*" align="left" valign='middle'>
        <input id='vided_SaveButton' type='button' class='but' 
               style='height: 40px; width: 90px;' value='Simpan'
               onclick='vided_SaveVideo()'>
        <span id='vided_WaitBox' style='visibility: hidden;'>
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