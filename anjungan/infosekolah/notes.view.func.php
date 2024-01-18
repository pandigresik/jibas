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
function GetOwnerName($ownerid, $ownertype)
{
    $sql = $ownertype == "S" ?
           "SELECT nama FROM jbsakad.siswa WHERE nis = '$ownerid'" :
           "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$ownerid."'";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        return $row[0];
    }
    else
    {
        return "-";    
    }
}

function ShowTautan()
{
    global $row;
    
    // -- SHOW TAUTAN --
    $tautan = $row['tautan'];
    if (strlen((string) $tautan) > 0)
    {
        echo "<strong>Tautan:</strong><br>\r\n";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<a href='$tautan' target='_blank' style='font-weight: normal; color: blue; text-decoration: underline;'>$tautan</a>\r\n";
        echo "<br><br>\r\n";
    }
}

function ShowGambar()
{
    global $notesid, $FILESHARE_ADDR, $NotesViewFilePerRow, $NotesViewPictIconHeight;
    
    // -- SHOW PICTURE --
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.notesfile
             WHERE notesid = '$notesid'
               AND filecate = 'pict'";
    $ngambar = (int)FetchSingle($sql);
    if ($ngambar > 0)
    {
        echo "<strong>Gambar:</strong><br>\r\n";
        echo "<table border='0' cellpadding='5' cellspacing='0'>\r\n";
        
        $sql = "SELECT *
                  FROM jbsvcr.notesfile
                 WHERE notesid = '$notesid'
                   AND filecate = 'pict'";
        $res = QueryDb($sql);
        $no = 0;
        while($row = mysqli_fetch_array($res))
        {
            $file = $row['location'] . "/" . $row['filename'];
            $info = str_replace("'", "`", (string) $row['fileinfo']);
            //$info = "tessy";
            
            $no += 1;
            if ($no == 1)
            {
                echo "<tr>\r\n";
                echo "<td width='5'>&nbsp;</td>\r\n";
            }
            
            echo "<td align='center' valign='top'>\r\n";
            echo "<a href='$FILESHARE_ADDR/$file' class='lytebox' data-lyte-options='group:notespict' data-title='$info'>\r\n";
            echo "<img src='$FILESHARE_ADDR/$file' border='0' height='$NotesViewPictIconHeight'>\r\n";
            echo "</a>\r\n";
            echo "</td>\r\n";
            
            if ($no == $NotesViewFilePerRow)
            {
                $no = 0;
                echo "</tr>\r\n";
            }
        }
        
        if ($no != 0)
        {
            for($i = $no + 1; $i <= $NotesViewFilePerRow; $i++)
            {
                echo "<td>&nbsp;</td>\r\n";
            }
            echo "</tr>\r\n";
        }
        
        echo "</table>\r\n";
    }
}

function ShowDokumen()
{
    global $notesid, $FILESHARE_ADDR;
    
    // -- SHOW DOKUMENT --
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.notesfile
             WHERE notesid = '$notesid'
               AND filecate = 'doc'";
    $ndoc = (int)FetchSingle($sql);
    if ($ndoc > 0)
    {
        echo "<strong>Dokumen:</strong><br>\r\n";
        echo "<table border='0' cellpadding='5' cellspacing='0'>\r\n";
        echo "<tr>\r\n";
        echo "<td width='5'>&nbsp;</td>\r\n";
        echo "<td align='left' valign='top'>\r\n";
        
        $sql = "SELECT *
                  FROM jbsvcr.notesfile
                 WHERE notesid = '$notesid'
                   AND filecate = 'doc'";
        $res = QueryDb($sql);
        $no = 0;
        while($row = mysqli_fetch_array($res))
        {
            $replid = $row['replid'];
            $file = $FILESHARE_ADDR. "/" . $row['location'] . "/" . $row['filename'];
            $file = urlencode($file);
            $info = str_replace("'", "`", (string) $row['fileinfo']);
            $name = urlencode((string) $row['filename']);
            
            echo "<a style='text-decoration: underline; font-weight: normal; color: blue; cursor: pointer;' onclick=\"not_DownloadDoc('$replid', '$file', '$name')\" title='$info'>\r\n";
            echo $row['filename'] . "\r\n";
            echo "</a><br>\r\n";
        }
        
        echo "</td>\r\n";
        echo "</tr>\r\n";
        echo "</table>\r\n";
    }
}

function ShowPrevCommentLink($notesid)
{
    global $NotesViewActiveComment;
    
    $sql = "SELECT COUNT(replid)
                  FROM jbsvcr.notescomment
                 WHERE notesid = '".$notesid."'";
    $nCmt = (int)FetchSingle($sql);
    if ($nCmt <= $NotesViewActiveComment)
        return;
    
    $nPrevCmt = $nCmt - $NotesViewActiveComment;
?>
    <tr height='30'>
        <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
        <td class='NotesViewCommentCell' width='*' align='left' valign='middle' colspan='2'>
            &nbsp;&nbsp;
            <span id='not_CmtShowPrevLink'
                  class='NotesViewShowPrevCommentLink'
                  onclick='not_ShowPrevComment(<?=$notesid?>)'>
            Lihat <?=$nPrevCmt?> komentar sebelumnya
            </span>
        </td>
    </tr>
<?php
}

function ShowPrevComment($notesid)
{
    global $NotesViewActiveComment;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.notescomment
             WHERE notesid = '".$notesid."'";
    $nCmt = (int)FetchSingle($sql);
    $sqlLimit = "LIMIT " . ($nCmt - $NotesViewActiveComment);
    
    $sql = "SELECT replid, nis, nip, IF(nip IS NULL, 'S', 'P') AS ownertype,
                   TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff,
                   DATE_FORMAT(tanggal, '%d-%m-%Y') AS tanggal,
                   fkomen
              FROM jbsvcr.notescomment
             WHERE notesid = '$notesid'
                   $sqlLimit";
    
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return;
    
    while($row = mysqli_fetch_array($res))
    {
        $replid = $row['replid'];
        $ownertype = $row['ownertype'];
        $ownerid = $ownertype == 'S' ? $row['nis'] : $row['nip'];
        $ownername = GetOwnerName($ownerid, $ownertype);
        $age = SecToAgeDate($row['secdiff'], $row['tanggal']);
        
        $rowId = "not_CommentRow_$replid";
        $delDivId = "not_DeleteCommentButton_$replid";      
        ?>
        <tr id='<?=$rowId?>'>
            <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
            <td class='NotesViewCommentCell' width='10%' align='center' valign='top'>
                <img src='notes.list.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>&ownertype=<?=$ownertype?>' height='35'><br>
            </td>
            <td class='NotesViewCommentCell' width='*' align='left' valign='top'>
                <div style='position: relative'>
                <strong><?=$ownername?>&nbsp;</strong><?=$row['fkomen']?><br>
                <span class='NotesViewCommentAge'>
                <?= $age ?>
                </span>
                <div class='NotesViewCommentDeleteDiv'
                     onmouseover="document.getElementById('<?=$delDivId?>').style.display = 'block';"
                     onmouseout="document.getElementById('<?=$delDivId?>').style.display = 'none';">
                    <div id='<?=$delDivId?>' style='display: none;'>
                        <img src='../images/small-delete.png'
                             title='Hapus komentar ini'
                             onclick="not_ShowDeleteCommentDialog('<?=$replid?>','<?=$rowId?>')">
                    </div>
                </div>
                </div>
            </td>
        </tr>
        <?php
    }
}

function ShowComment($notesid, $maxCommentId)
{
    global $NotesViewActiveComment;
    
    $sqlLimit = "";
    if ($maxCommentId == 0)
    {
        $sql = "SELECT COUNT(replid)
                  FROM jbsvcr.notescomment
                 WHERE notesid = '".$notesid."'";
        $nCmt = (int)FetchSingle($sql);
        if ($nCmt > $NotesViewActiveComment)
            $sqlLimit = "LIMIT " . ($nCmt - $NotesViewActiveComment) . ", $NotesViewActiveComment";
        else
            $sqlLimit = "";
    }
    else
    {
        $sqlLimit = "";
    }
    
    $sql = "SELECT replid, nis, nip, IF(nip IS NULL, 'S', 'P') AS ownertype,
                   TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff,
                   DATE_FORMAT(tanggal, '%d-%m-%Y') AS tanggal,
                   fkomen
              FROM jbsvcr.notescomment
             WHERE notesid = '$notesid'
               AND replid > '$maxCommentId'
                   $sqlLimit";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return;
    
    while($row = mysqli_fetch_array($res))
    {
        $replid = $row['replid'];
        $ownertype = $row['ownertype'];
        $ownerid = $ownertype == 'S' ? $row['nis'] : $row['nip'];
        $ownername = GetOwnerName($ownerid, $ownertype);
        $age = SecToAgeDate($row['secdiff'], $row['tanggal']);
        
        $rowId = "not_CommentRow_$replid";
        $delDivId = "not_DeleteCommentButton_$replid";    
        ?>
        <tr id='<?=$rowId?>'>
            <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
            <td class='NotesViewCommentCell' width='10%' align='center' valign='top'>
                <img src='notes.list.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>&ownertype=<?=$ownertype?>' height='35'><br>
            </td>
            <td class='NotesViewCommentCell' width='*' align='left' valign='top'>
                <div style='position: relative'>
                <strong><?=$ownername?>&nbsp;</strong><?=$row['fkomen']?><br>
                <span class='NotesViewCommentAge'>
                <?= $age ?>
                </span>
                <div class='NotesViewCommentDeleteDiv'
                     onmouseover="document.getElementById('<?=$delDivId?>').style.display = 'block';"
                     onmouseout="document.getElementById('<?=$delDivId?>').style.display = 'none';">
                    <div id='<?=$delDivId?>' style='display: none;'>
                        <img src='../images/small-delete.png'
                             title='Hapus komentar ini'
                             onclick="not_ShowDeleteCommentDialog('<?=$replid?>','<?=$rowId?>')">
                    </div>
                </div>
                </div>
            </td>
        </tr>
        <?php
    }
}

function ShowCommentBox($notesid)
{
    ?>
    
    <fieldset class='NotesViewCommentBox'>
    <legend>Komentar</legend>
    <table id='not_CmtBoxTable' border='0' cellpadding='0' cellspacing='0'>
    <thead>
    <tr>
        <td align='right' valign='top'>
            <span style='color: blue; cursor: pointer;' onclick='ifse_ShowEmoticons()'>
            emoticons
            </span>
        </td>    
    </tr>
    </thead>    
    <tbody>
    <tr>
        <td align='left' valign='top'>
            <textarea id='not_comment_1' name='not_comment_1'
                cols='60' rows='2' class='inputbox'
                onkeyup='not_CheckCommentLength(1)'></textarea>
        </td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td align='left'>
            <input type='hidden' id='not_notesid' name='not_notesid' value='<?= $notesid ?>'>
            <input type='hidden' id='not_ncommentbox' name='not_ncommentbox' value='1'>
            <span style='cursor: pointer; color: blue;'
                  onclick='not_AddCommentBox()'>Tambah komentar</span>    
        </td>
    </tr>
    <tr>
        <td align='left'>
            <br>
            Login: <input type='text' id='not_view_Login' class='inputbox' size='12' maxlength='25'>&nbsp;
            Password: <input type='password' id='not_view_Password' class='inputbox' size='12' maxlength='25'>
            <a href='#' title='Untuk siswa, gunakan NIS dan PIN Siswa. Untuk pegawai, gunakan NIP dan Password aplikasi JIBAS.'><img src='../images/tooltip.png' border='0'></a>
            <input type='button' value=' Simpan ' class='but'
                   style='height: 40px' onclick='not_SaveComment()'><br>
            <span style='color: red' id='not_SaveInfo'>&nbsp;</span>
        </td>
    </tr> 
    </tfoot>
    </table>
    </fieldset>

<?php    
}

function GetMaxCommentId($notesid)
{
    $sql = "SELECT replid
              FROM jbsvcr.notescomment
             WHERE notesid = '$notesid'
             ORDER BY replid DESC
             LIMIT 1";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        return $row[0];
    }
    
    return 0;
}

function ValidateEditNotesLogin($login, $password, &$type, &$info)
{
    if (strtolower((string) $login) == "jibas")
    {
        $info = "Anda tidak berhak mengubah notes ini!";
        return false;
    }
    
    return ValidateLogin("", $login, $password, $type, $info);
}

function ValidateDeleteNotesLogin($login, $password, &$type, &$info)
{
    if (strtolower((string) $login) == "jibas")
        return ValidateAdminLogin($login, $password, $info);
    
    return ValidateLogin("", $login, $password, $type, $info);
}

function ValidateDelCmtLogin($login, $password, &$type, &$info)
{
    if (strtolower((string) $login) == "jibas")
        return ValidateAdminLogin($login, $password, $info);
    
    return ValidateLogin("", $login, $password, $type, $info);
}

function ValidateNotesOwner($notesid, $login)
{
    if (strtolower((string) $login) == "jibas")
        return true;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.notes
             WHERE replid = '$notesid'
               AND (nis = '$login' OR nip = '$login')";
    $n = (int)FetchSingle($sql);
    if ($n > 0)
        return true;
    
    return false;
}

function ValidateCommentOwner($replid, $login)
{
    if (strtolower((string) $login) == "jibas")
        return true;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.notescomment
             WHERE replid = '$replid'
               AND (nis = '$login' OR nip = '$login')";
    $n = (int)FetchSingle($sql);
    if ($n > 0)
        return true;
    
    $sql = "SELECT COUNT(n.replid)
              FROM jbsvcr.notes n, jbsvcr.notescomment nc
             WHERE nc.notesid = n.replid
               AND nc.replid = '$replid' 
               AND (n.nis = '$login' OR n.nip = '$login')";
    $n = (int)FetchSingle($sql);
    if ($n > 0)
        return true;
    
    return false;
}

function DeleteComment($replid)
{
    $sql = "DELETE FROM jbsvcr.notescomment
             WHERE replid = '".$replid."'";
    QueryDb($sql);         
}

function DeleteNotes($notesid)
{
    global $FILESHARE_UPLOAD_DIR;
    
    $sql = "SELECT *
              FROM jbsvcr.notesfile
             WHERE notesid = '".$notesid."'";
    $res = QueryDbEx($sql);
    while($row = mysqli_fetch_array($res))
    {
        $floc = $row['location'] . "/" . $row['filename'];
        $floc = "$FILESHARE_UPLOAD_DIR/$floc";
        
        if (file_exists($floc))
            unlink($floc);
    }
    
    $sql = "DELETE FROM jbsvcr.notesfile
             WHERE notesid = '".$notesid."'";
    QueryDbEx($sql);
    
    $sql = "DELETE FROM jbsvcr.notes
             WHERE replid = '".$notesid."'";
    QueryDbEx($sql);
}
?>