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
require_once("notes.view.func.php");
require_once("notes.view.config.php");
require_once("infosekolah.common.func.php");

OpenDb();

$notesid = $_REQUEST['notesid'];
//$notesid = 2;

$sql = "SELECT *, IF(nip IS NULL, 'S', 'P') AS ownertype,
               DAYOFWEEK(tanggal) AS haribuat,
               DATE_FORMAT(tanggal, '%d-%m-%Y') AS tglbuat,
               TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff
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
$ownertype = $row['ownertype'];
$ownerid = $ownertype == "S" ? $row['nis'] : $row['nip'];
$ownername = GetOwnerName($ownerid, $ownertype);

$sql = "UPDATE jbsvcr.notes
           SET nread = nread + 1, lastread = NOW()
         WHERE replid = '".$notesid."'";
QueryDb($sql);         
?>
<table border='0' cellpadding='2' cellspacing='0' width='98%'>
<tr>
    <td align='left' valign='top'>
        <font class='TitleTabMenu'>N O T E S</font>    
    </td>
    <td align='right' valign='bottom'>
        <a onclick='not_ShowEditNotesDialog(<?=$notesid?>)'>edit</a>&nbsp;&nbsp;
        <a onclick='not_ShowDeleteNotesDialog(<?=$notesid?>)'>hapus</a>&nbsp;&nbsp;
        <a onclick='notview_BackToCaller(<?=$notesid?>)'>kembali</a>&nbsp;&nbsp;
        <a onclick='not_RefreshNotesView(<?=$notesid?>)'>refresh</a>
    </td>
</tr>    
</table>
<br>
<table style='background-color: #fff' border='0' width='100%' cellspacing='0' cellpadding='0'>
<tr><td>
<table id='not_NotesView' style='background-color: #fff' border='0' height='500' width='80%' cellspacing='0' cellpadding='5'>
<tr>
<td align='center' valign='top' width='15%'>
    <font class='NotesViewTanggal'>Dari</font><br><br>
    <img src='notes.list.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>&ownertype=<?=$ownertype?>' height='70'><br>
    <strong><?=$ownername?></strong><br>
    <font class='NotesViewAge'><?= SecToAgeDate($row['secdiff'], $row['tglbuat']) ?></font><br><br>
    <font class='NotesViewTanggal'>Kepada</font><br><br>
    <?= $row['kepada'] ?>
</td>
<td align='left' valign='top' width='*'>
    <font class='NotesViewJudul'><?= $row['fjudul'] ?></font>
    <br><br>
    <font class='NotesViewPesan'><?= $row['fpesan'] ?></font><br><br>
<?php
    ShowTautan();
    ShowGambar();
    ShowDokumen();
?>
    <br>
    <strong>Komentar:</strong>
    <br>
    <input type='hidden' id='not_MaxCommentId' value='<?= GetMaxCommentId($notesid) ?>'>
    <table id='not_CmtList' border='0' cellpadding='2' cellspacing='2' width='80%'>
    <thead>
<?php
    ShowPrevCommentLink($notesid);
?>
    </thead>    
    <tbody>
<?php
    ShowComment($notesid, 0);
?>    
    </tbody>
    <tfoot>
    <tr>
        <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
        <td style='background-color: #fff' width='*' align='left' valign='top' colspan='2'>
            <div id='not_divAddComment'>
<?php
            ShowCommentBox($notesid);
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