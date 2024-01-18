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
require_once("video.view.func.php");
require_once("video.view.config.php");
require_once("infosekolah.common.func.php");

OpenDb();

$videoid = $_REQUEST['videoid'];
//$videoid = 2;

$sql = "SELECT *, IF(nip IS NULL, 'S', 'P') AS ownertype,
               DAYOFWEEK(tanggal) AS haribuat,
               DATE_FORMAT(tanggal, '%d-%m-%Y %H:%i') AS tglbuat,
               TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff
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
$ownertype = $row['ownertype'];
$ownerid = $ownertype == "S" ? $row['nis'] : $row['nip'];
$ownername = GetOwnerName($ownerid, $ownertype);

$nocount = (int)$_REQUEST['nocount'];
if ($nocount != 1)
{
    $sql = "UPDATE jbsvcr.video
               SET nread = nread + 1, lastread = NOW()
             WHERE replid = '".$videoid."'";
    QueryDb($sql);    
}
?>
<table border='0' cellpadding='2' cellspacing='0' width='99%'>
<tr>
    <td align='left' valign='top' width='30%'>
        <font class='TitleTabMenu'>V I D E O</font>    
    </td>
    <td align='right' valign='bottom' width='*'>
        <a onclick='vidvw_ShowEditVideoDialog(<?=$videoid?>)'>edit</a>&nbsp;&nbsp;
        <a onclick='vidvw_ShowDeleteVideoDialog(<?=$videoid?>)'>hapus</a>&nbsp;&nbsp;
        <a onclick='vidvw_BackToCaller()'>kembali</a>&nbsp;&nbsp;
        <a onclick='vidvw_RefreshVideoView(<?=$videoid?>)'>refresh</a>  
    </td>
</tr>
<tr><td align="left" valign="top" colspan="2">

    <br>
    
    <table border='0' cellpadding='2' cellspacing='0' width='100%'>
    <tr>
        <td align='center' valign='top' width='14%'>
            <img src='notes.list.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>&ownertype=<?=$ownertype?>' height='60'><br>
            <strong><?=$ownername?></strong><br>
            <font class='VideoViewAge'>
            <?= SecToAgeDate($row['secdiff'], $row['tglbuat']) ?>
            </font>
        </td>
        <td align='left' valign='top' width='*'>
            <font class='VideoViewTitle'><?=$row['fjudul']?></font>
            <br>
            <div id="vidvw_player" style="width: <?=$VideoViewVideoWidth?>px; height: <?=$VideoViewVideoHeight?>px;">
            <video>
                <source type="<?=$row['filetype']?>" src="<?= $FILESHARE_ADDR . "/" . $row['location'] . "/" . $row['filename']  ?>">
            </video>
            </div>
            <br><br>
            <strong>Deskripsi:</strong><br>
            <font class='VideoViewInfo'><?=$row['fketerangan']?></font><br>
          
            <br>
            <strong>Komentar:</strong>
            <br>
            <input type='hidden' id='vidvw_MaxCommentId' value='<?= GetMaxCommentId($videoid) ?>'>
            <table id='vidvw_CmtList' border='0' cellpadding='2' cellspacing='2' width='60%'>
            <thead>
        <?php
            ShowPrevCommentLink($videoid);
        ?>
            </thead>    
            <tbody>
        <?php
            ShowComment($videoid, 0);
        ?>    
            </tbody>
            <tfoot>
            <tr>
                <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
                <td style='background-color: #fff' width='*' align='left' valign='top' colspan='2'>
                    <div id='vidvw_divAddComment'>
        <?php
                    ShowCommentBox($videoid);
        ?>
                    </div>
                </td>
            </tr>    
            </tfoot>
            </table>
            
        </td>
    </tr>
    </table>
    
</td></tr>
</table>
<?php
CloseDb();
?>