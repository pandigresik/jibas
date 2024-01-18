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
require_once("galeri.view.func.php");
require_once("galeri.view.config.php");
require_once("mading.common.func.php");

OpenDb();

$galleryid = $_REQUEST['galleryid'];
//$galleryid = 15;

$sql = "SELECT *, IF(nip IS NULL, 'S', 'P') AS ownertype,
               DAYOFWEEK(tanggal) AS haribuat,
               DATE_FORMAT(tanggal, '%d-%m-%Y %H:%i') AS tglbuat,
               TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff
          FROM jbsvcr.gallery
         WHERE replid = '".$galleryid."'";
$res = QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    CloseDb();
    echo "Tidak ditemukan galeri!";
    exit();
}

$row = mysqli_fetch_array($res);
$ownertype = $row['ownertype'];
$ownerid = $ownertype == "S" ? $row['nis'] : $row['nip'];
$ownername = GetOwnerName($ownerid, $ownertype);

$nocount = (int)$_REQUEST['nocount'];
if ($nocount != 1)
{
    $sql = "UPDATE jbsvcr.gallery
               SET nread = nread + 1, lastread = NOW()
             WHERE replid = '".$galleryid."'";
    QueryDb($sql);    
}
?>
<table border='0' cellpadding='2' cellspacing='0' width='99%'>
<tr>
    <td align='left' valign='top' width='30%'>
        <font class='TitleTabMenu'>G A L E R I</font>    
    </td>
    <td align='right' valign='bottom' width='*'>
        <a onclick='galvw_ShowEditGalleryDialog(<?=$galleryid?>)'>edit</a>&nbsp;&nbsp;
        <a onclick='galvw_ShowDeleteGalleryDialog(<?=$galleryid?>)'>hapus</a>&nbsp;&nbsp;
        <a onclick='galvw_BackToCaller()'>kembali</a>&nbsp;&nbsp;
        <a onclick='galvw_RefreshGalleryView(<?=$galleryid?>)'>refresh</a>  
    </td>
</tr>
<tr><td align="left" valign="top" colspan="2">

    <br>
    
    <table border='0' cellpadding='2' cellspacing='0' width='100%'>
    <tr>
        <td align='center' valign='top' width='14%'>
            <img src='notes.list.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>&ownertype=<?=$ownertype?>' height='60'><br>
            <strong><?=$ownername?></strong><br>
            <font class='GaleriViewAge'>
            <?= SecToAgeDate($row['secdiff'], $row['tglbuat']) ?>
            </font>
        </td>
        <td align='left' valign='top' width='*'>
            <font class='GaleriViewTitle'><?=$row['fjudul']?></font><br>
            <font class='GaleriViewInfo'><?=$row['fketerangan']?></font><br>
            
            <br>
            <strong>Gambar:</strong>
            <table border='0' cellspacing='0' cellpadding='2' width='100%'>
<?php              ShowImageGallery();  ?>                
            </table>
           
            <br>
            <strong>Komentar:</strong>
            <br>
            <input type='hidden' id='galvw_MaxCommentId' value='<?= GetMaxCommentId($galleryid) ?>'>
            <table id='galvw_CmtList' border='0' cellpadding='2' cellspacing='2' width='60%'>
            <thead>
        <?php
            ShowPrevCommentLink($galleryid);
        ?>
            </thead>    
            <tbody>
        <?php
            ShowComment($galleryid, 0);
        ?>    
            </tbody>
            <tfoot>
            <tr>
                <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
                <td style='background-color: #fff' width='*' align='left' valign='top' colspan='2'>
                    <div id='galvw_divAddComment'>
        <?php
                    ShowCommentBox($galleryid);
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