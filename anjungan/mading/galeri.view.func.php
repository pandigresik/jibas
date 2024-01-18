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

function ShowImageGallery()
{
    global $FILESHARE_ADDR, $galleryid, $GalleryViewIconHeight, $GalleryViewIconWidth;
    
    $sql = "SELECT filename, ffileinfo, location, width, height
              FROM jbsvcr.galleryfile
             WHERE galleryid = '".$galleryid."'";         
    $res = QueryDb($sql);
    $cntcol = 0;
    $nimage = 0;
    while($row = mysqli_fetch_array($res))
    {
        $nimage += 1;
        
        $fn = $row['filename'];
        $loc = $row['location'];
        $info = $row['ffileinfo'];
        $w = $row['width'];
        $h = $row['height'];
        
        if ($cntcol == 0)
            echo "<tr height='170'>\r\n";
        
        $imgdim = "";
        if ($w > $GalleryViewIconWidth)
        {
            $delta = $GalleryViewIconWidth / $w;
            $h = ($h > $GalleryViewIconHeight) ? $h * $delta : $h;
            
            if ($h > $GalleryViewIconHeight)
                $imgdim = "height = '".$GalleryViewIconHeight."'";
            else    
                $imgdim = "width = '".$GalleryViewIconWidth."'";
        }
        ?>
        
        <td align='center' valign='middle' width='33%'>
            <a href='<?= "$FILESHARE_ADDR/$loc/$fn" ?>' class='lytebox' data-lyte-options='group:gallerypict' data-title='<?= ChangeSingleQuote($info) ?>'>
            <img src='<?= "$FILESHARE_ADDR/$loc/$fn" ?>' <?=$imgdim?>
            </a><br>
            <?= $info ?>
        </td>
        
<?php      $cntcol += 1;
        if ($cntcol == 3)
        {
            $cntcol = 0;
            echo "</tr>\r\n";
        }
    }
    
    if ($cntcol != 0)
    {
        for($i = $cntcol + 1; $i <= 3; $i++)
            echo "<td width='33%'>&nbsp;</td>\r\n";
        echo "</tr>\r\n";
    }
    echo "<input type='hidden' id='galvw_nimage' value='$nimage'>";
}

function GetMaxCommentId($galleryid)
{
    $sql = "SELECT replid
              FROM jbsvcr.gallerycomment
             WHERE galleryid = '$galleryid'
             ORDER BY replid DESC
             LIMIT 1";
    $res = QueryDbEx($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        return $row[0];
    }
    
    return 0;
}

function ShowPrevCommentLink($galleryid)
{
    global $GalleryViewActiveComment;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.gallerycomment
             WHERE galleryid = '".$galleryid."'";
    $nCmt = (int)FetchSingle($sql);
    if ($nCmt <= $GalleryViewActiveComment)
        return;
    
    $nPrevCmt = $nCmt - $GalleryViewActiveComment;
?>
    <tr height='30'>
        <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
        <td class='GalleryViewCommentCell' width='*' align='left' valign='middle' colspan='2'>
            &nbsp;&nbsp;
            <span id='galvw_CmtShowPrevLink'
                  class='GalleryViewShowPrevCommentLink'
                  onclick='galvw_ShowPrevComment(<?=$galleryid?>)'>
            Lihat <?=$nPrevCmt?> komentar sebelumnya
            </span>
        </td>
    </tr>
<?php
}

function ShowPrevComment($galleryid)
{
    global $GalleryViewActiveComment;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.gallerycomment
             WHERE galleryid = '".$galleryid."'";
    $nCmt = (int)FetchSingleEx($sql);
    $sqlLimit = "LIMIT " . ($nCmt - $GalleryViewActiveComment);
    
    $sql = "SELECT replid, nis, nip, IF(nip IS NULL, 'S', 'P') AS ownertype,
                   TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff,
                   DATE_FORMAT(tanggal, '%d-%m-%Y') AS tanggal,
                   fkomen
              FROM jbsvcr.gallerycomment
             WHERE galleryid = '$galleryid'
                   $sqlLimit";
    
    $res = QueryDbEx($sql);
    if (mysqli_num_rows($res) == 0)
        return;
    
    while($row = mysqli_fetch_array($res))
    {
        $replid = $row['replid'];
        $ownertype = $row['ownertype'];
        $ownerid = $ownertype == 'S' ? $row['nis'] : $row['nip'];
        $ownername = GetOwnerName($ownerid, $ownertype);
        $age = SecToAgeDate($row['secdiff'], $row['tanggal']);
        $fkomen = $row['fkomen'];
        
        $rowId = "galvw_CommentRow_$replid";
        $delDivId = "galvw_DeleteCommentButton_$replid";      
        ?>
        <tr id='<?=$rowId?>'>
            <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
            <td class='GalleryViewCommentCell' width='10%' align='center' valign='top'>
                <img src='notes.list.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>&ownertype=<?=$ownertype?>' height='35'><br>
            </td>
            <td class='GalleryViewCommentCell' width='*' align='left' valign='top'>
                <div style='position: relative'>
                <strong><?=$ownername?>&nbsp;</strong><?=$fkomen?><br>
                <span class='GalleryViewCommentAge'>
                <?= $age ?>
                </span>
                <div class='GalleryViewCommentDeleteDiv'
                     onmouseover="document.getElementById('<?=$delDivId?>').style.display = 'block';"
                     onmouseout="document.getElementById('<?=$delDivId?>').style.display = 'none';">
                    <div id='<?=$delDivId?>' style='display: none;'>
                        <img src='../images/small-delete.png'
                             title='Hapus komentar ini'
                             onclick="galvw_ShowDeleteCommentDialog('<?=$replid?>','<?=$rowId?>')">
                    </div>
                </div>
                </div>
            </td>
        </tr>
        <?php
    }
}

function ShowComment($galleryid, $maxCommentId)
{
    global $GalleryViewActiveComment;
    
    $sqlLimit = "";
    if ($maxCommentId == 0)
    {
        $sql = "SELECT COUNT(replid)
                  FROM jbsvcr.gallerycomment
                 WHERE galleryid = '".$galleryid."'";
        $nCmt = (int)FetchSingleEx($sql);
        if ($nCmt > $GalleryViewActiveComment)
            $sqlLimit = "LIMIT " . ($nCmt - $GalleryViewActiveComment) . ", $GalleryViewActiveComment";
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
              FROM jbsvcr.gallerycomment
             WHERE galleryid = '$galleryid'
               AND replid > '$maxCommentId'
                   $sqlLimit";
    $res = QueryDbEx($sql);
    if (mysqli_num_rows($res) == 0)
        return;
    
    while($row = mysqli_fetch_array($res))
    {
        $replid = $row['replid'];
        $ownertype = $row['ownertype'];
        $ownerid = $ownertype == 'S' ? $row['nis'] : $row['nip'];
        $ownername = GetOwnerName($ownerid, $ownertype);
        $age = SecToAgeDate($row['secdiff'], $row['tanggal']);
        $fkomen = $row['fkomen'];
        
        $rowId = "galvw_CommentRow_$replid";
        $delDivId = "galvw_DeleteCommentButton_$replid";    
        ?>
        <tr id='<?=$rowId?>'>
            <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
            <td class='GalleryViewCommentCell' width='10%' align='center' valign='top'>
                <img src='notes.list.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>&ownertype=<?=$ownertype?>' height='35'><br>
            </td>
            <td class='GalleryViewCommentCell' width='*' align='left' valign='top'>
                <div style='position: relative'>
                <strong><?=$ownername?>&nbsp;</strong><?=$fkomen?><br>
                <span class='GalleryViewCommentAge'>
                <?= $age ?>
                </span>
                <div class='GalleryViewCommentDeleteDiv'
                     onmouseover="document.getElementById('<?=$delDivId?>').style.display = 'block';"
                     onmouseout="document.getElementById('<?=$delDivId?>').style.display = 'none';">
                    <div id='<?=$delDivId?>' style='display: none;'>
                        <img src='../images/small-delete.png'
                             title='Hapus komentar ini'
                             onclick="galvw_ShowDeleteCommentDialog('<?=$replid?>','<?=$rowId?>')">
                    </div>
                </div>
                </div>
            </td>
        </tr>
        <?php
    } // while
}

function ShowCommentBox($galleryid)
{
    ?>
    
    <fieldset class='GalleryViewCommentBox'>
    <legend>Komentar</legend>    
    <table id='galvw_CmtBoxTable' border='0' cellpadding='0' cellspacing='0'>
    <thead>
    <tr>
        <td align='right' valign='top'>
            <span style='color: blue; cursor: pointer;' onclick='mad_ShowEmoticons()'>
            emoticons
            </span>
        </td>    
    </tr>
    </thead>
    <tbody>
    <tr>
        <td align='left' valign='top'>
            <textarea id='galvw_comment_1' name='galvw_comment_1'
                cols='60' rows='2' class='inputbox'
                onkeyup='galvw_CheckCommentLength(1)'></textarea>
        </td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td align='left'>
            <input type='hidden' id='galvw_galleryid' name='galvw_galleryid' value='<?= $galleryid ?>'>
            <input type='hidden' id='galvw_ncommentbox' name='galvw_ncommentbox' value='1'>
            <span style='cursor: pointer; color: blue;'
                  onclick='galvw_AddCommentBox()'>Tambah komentar</span>    
        </td>
    </tr>
    <tr>
        <td align='left'>
            <br>
            Login: <input type='text' id='galvw_Login' class='inputbox' size='12' maxlength='25'>&nbsp;
            Password: <input type='password' id='galvw_Password' class='inputbox' size='12' maxlength='25'>
            <a href='#' title='Untuk siswa, gunakan NIS dan PIN Siswa. Untuk pegawai, gunakan NIP dan Password aplikasi JIBAS.'><img src='../images/tooltip.png' border='0'></a>
            <input type='button' value=' Simpan ' class='but'
                   style='height: 40px' onclick='galvw_SaveComment()'><br>
            <span style='color: red' id='galvw_SaveInfo'>&nbsp;</span>
        </td>
    </tr> 
    </tfoot>
    </table>
    </fieldset>

<?php    
}

function ValidateDelCmtLogin($login, $password, &$type, &$info)
{
    if (strtolower((string) $login) == "jibas")
        return ValidateAdminLogin($login, $password, $info);
    
    return ValidateLogin("", $login, $password, $type, $info);
}

function ValidateGalleryOwner($galleryid, $login)
{
    if (strtolower((string) $login) == "jibas")
        return true;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.gallery
             WHERE replid = '$galleryid'
               AND (nis = '$login' OR nip = '$login')";
    $n = (int)FetchSingleEx($sql);
    if ($n > 0)
        return true;
    
    return false;
}

function ValidateCommentOwner($replid, $login)
{
    if (strtolower((string) $login) == "jibas")
        return true;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.gallerycomment
             WHERE replid = '$replid'
               AND (nis = '$login' OR nip = '$login')";
    $n = (int)FetchSingleEx($sql);
    if ($n > 0)
        return true;
    
    $sql = "SELECT COUNT(n.replid)
              FROM jbsvcr.gallery n, jbsvcr.gallerycomment nc
             WHERE nc.galleryid = n.replid
               AND nc.replid = '$replid' 
               AND (n.nis = '$login' OR n.nip = '$login')";
    $n = (int)FetchSingleEx($sql);
    if ($n > 0)
        return true;
    
    return false;
}

function DeleteComment($replid)
{
    $sql = "DELETE FROM jbsvcr.gallerycomment
             WHERE replid = '".$replid."'";
    QueryDbEx($sql);         
}

function ValidateEditGalleryLogin($login, $password, &$type, &$info)
{
    if (strtolower((string) $login) == "jibas")
    {
        $info = "Anda tidak berhak mengubah galeri ini!";
        return false;
    }
    
    return ValidateLogin("", $login, $password, $type, $info);
}

function ValidateDeleteGalleryLogin($login, $password, &$type, &$info)
{
    if (strtolower((string) $login) == "jibas")
        return ValidateAdminLogin($login, $password, $info);
    
    return ValidateLogin("", $login, $password, $type, $info);
}

function DeleteGallery($galleryid)
{
    global $FILESHARE_UPLOAD_DIR;
    
    $sql = "SELECT *
              FROM jbsvcr.galleryfile
             WHERE galleryid = '".$galleryid."'";
    $res = QueryDbEx($sql);
    while($row = mysqli_fetch_array($res))
    {
        $floc = $row['location'] . "/" . $row['filename'];
        $floc = "$FILESHARE_UPLOAD_DIR/$floc";
        
        echo "DELETE $floc<br>";
        if(file_exists($floc))
            unlink($floc);
    }
    
    $sql = "DELETE FROM jbsvcr.galleryfile
             WHERE galleryid = '".$galleryid."'";
    echo "$sql<br>";        
    QueryDbEx($sql);
    
    $sql = "DELETE FROM jbsvcr.gallery
             WHERE replid = '".$galleryid."'";
    echo "$sql<br>";         
    QueryDbEx($sql);
}
?>