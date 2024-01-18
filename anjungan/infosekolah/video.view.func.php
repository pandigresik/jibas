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

function GetMaxCommentId($videoid)
{
    $sql = "SELECT replid
              FROM jbsvcr.videocomment
             WHERE videoid = '$videoid'
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

function ShowPrevCommentLink($videoid)
{
    global $VideoViewActiveComment;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.videocomment
             WHERE videoid = '".$videoid."'";
    $nCmt = (int)FetchSingle($sql);
    if ($nCmt <= $VideoViewActiveComment)
        return;
    
    $nPrevCmt = $nCmt - $VideoViewActiveComment;
?>
    <tr height='30'>
        <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
        <td class='VideoViewCommentCell' width='*' align='left' valign='middle' colspan='2'>
            &nbsp;&nbsp;
            <span id='vidvw_CmtShowPrevLink'
                  class='VideoViewShowPrevCommentLink'
                  onclick='vidvw_ShowPrevComment(<?=$videoid?>)'>
            Lihat <?=$nPrevCmt?> komentar sebelumnya
            </span>
        </td>
    </tr>
<?php
}

function ShowPrevComment($videoid)
{
    global $VideoViewActiveComment;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.videocomment
             WHERE videoid = '".$videoid."'";
    $nCmt = (int)FetchSingleEx($sql);
    $sqlLimit = "LIMIT " . ($nCmt - $VideoViewActiveComment);
    
    $sql = "SELECT replid, nis, nip, IF(nip IS NULL, 'S', 'P') AS ownertype,
                   TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff,
                   DATE_FORMAT(tanggal, '%d-%m-%Y') AS tanggal,
                   fkomen
              FROM jbsvcr.videocomment
             WHERE videoid = '$videoid'
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
        
        $rowId = "vidvw_CommentRow_$replid";
        $delDivId = "vidvw_DeleteCommentButton_$replid";      
        ?>
        <tr id='<?=$rowId?>'>
            <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
            <td class='VideoViewCommentCell' width='10%' align='center' valign='top'>
                <img src='notes.list.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>&ownertype=<?=$ownertype?>' height='35'><br>
            </td>
            <td class='VideoViewCommentCell' width='*' align='left' valign='top'>
                <div style='position: relative'>
                <strong><?=$ownername?>&nbsp;</strong><?=$fkomen?><br>
                <span class='VideoViewCommentAge'>
                <?= $age ?>
                </span>
                <div class='VideoViewCommentDeleteDiv'
                     onmouseover="document.getElementById('<?=$delDivId?>').style.display = 'block';"
                     onmouseout="document.getElementById('<?=$delDivId?>').style.display = 'none';">
                    <div id='<?=$delDivId?>' style='display: none;'>
                        <img src='../images/small-delete.png'
                             title='Hapus komentar ini'
                             onclick="vidvw_ShowDeleteCommentDialog('<?=$replid?>','<?=$rowId?>')">
                    </div>
                </div>
                </div>
            </td>
        </tr>
        <?php
    }
}

function ShowComment($videoid, $maxCommentId)
{
    global $VideoViewActiveComment;
    
    $sqlLimit = "";
    if ($maxCommentId == 0)
    {
        $sql = "SELECT COUNT(replid)
                  FROM jbsvcr.videocomment
                 WHERE videoid = '".$videoid."'";
        $nCmt = (int)FetchSingleEx($sql);
        if ($nCmt > $VideoViewActiveComment)
            $sqlLimit = "LIMIT " . ($nCmt - $VideoViewActiveComment) . ", $VideoViewActiveComment";
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
              FROM jbsvcr.videocomment
             WHERE videoid = '$videoid'
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
        
        $rowId = "vidvw_CommentRow_$replid";
        $delDivId = "vidvw_DeleteCommentButton_$replid";    
        ?>
        <tr id='<?=$rowId?>'>
            <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
            <td class='VideoViewCommentCell' width='10%' align='center' valign='top'>
                <img src='notes.list.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>&ownertype=<?=$ownertype?>' height='35'><br>
            </td>
            <td class='VideoViewCommentCell' width='*' align='left' valign='top'>
                <div style='position: relative'>
                <strong><?=$ownername?>&nbsp;</strong><?=$fkomen?><br>
                <span class='VideoViewCommentAge'>
                <?= $age ?>
                </span>
                <div class='VideoViewCommentDeleteDiv'
                     onmouseover="document.getElementById('<?=$delDivId?>').style.display = 'block';"
                     onmouseout="document.getElementById('<?=$delDivId?>').style.display = 'none';">
                    <div id='<?=$delDivId?>' style='display: none;'>
                        <img src='../images/small-delete.png'
                             title='Hapus komentar ini'
                             onclick="vidvw_ShowDeleteCommentDialog('<?=$replid?>','<?=$rowId?>')">
                    </div>
                </div>
                </div>
            </td>
        </tr>
        <?php
    } // while
}

function ShowCommentBox($videoid)
{
    ?>
    
    <fieldset class='VideoViewCommentBox'>
    <legend>Komentar</legend>    
    <table id='vidvw_CmtBoxTable' border='0' cellpadding='0' cellspacing='0'>
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
            <textarea id='vidvw_comment_1' name='vidvw_comment_1'
                cols='60' rows='2' class='inputbox'
                onkeyup='vidvw_CheckCommentLength(1)'></textarea>
        </td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td align='left'>
            <input type='hidden' id='vidvw_videoid' name='vidvw_videoid' value='<?= $videoid ?>'>
            <input type='hidden' id='vidvw_ncommentbox' name='vidvw_ncommentbox' value='1'>
            <span style='cursor: pointer; color: blue;'
                  onclick='vidvw_AddCommentBox()'>Tambah komentar</span>    
        </td>
    </tr>
    <tr>
        <td align='left'>
            <br>
            Login: <input type='text' id='vidvw_Login' class='inputbox' size='12' maxlength='25'>&nbsp;
            Password: <input type='password' id='vidvw_Password' class='inputbox' size='12' maxlength='25'>
            <a href='#' title='Untuk siswa, gunakan NIS dan PIN Siswa. Untuk pegawai, gunakan NIP dan Password aplikasi JIBAS.'><img src='../images/tooltip.png' border='0'></a>
            <input type='button' value=' Simpan ' class='but'
                   style='height: 40px' onclick='vidvw_SaveComment()'><br>
            <span style='color: red' id='vidvw_SaveInfo'>&nbsp;</span>
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
    {
        $sql = "SELECT COUNT(replid)
                  FROM jbsuser.landlord
                 WHERE password = MD5('$password')";
        $n = (int)FetchSingleEx($sql);
        if ($n == 0)
        {
            $info = "Password salah!";
            return false;    
        }
        
        return true;
    }
    else
    {
        return ValidateLogin("", $login, $password, $type, $info);
    }
}

function ValidateVideoOwner($videoid, $login)
{
    if (strtolower((string) $login) == "jibas")
        return true;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsvcr.video
             WHERE replid = '$videoid'
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
              FROM jbsvcr.videocomment
             WHERE replid = '$replid'
               AND (nis = '$login' OR nip = '$login')";
    $n = (int)FetchSingleEx($sql);
    if ($n > 0)
        return true;
    
    $sql = "SELECT COUNT(n.replid)
              FROM jbsvcr.video n, jbsvcr.videocomment nc
             WHERE nc.videoid = n.replid
               AND nc.replid = '$replid' 
               AND (n.nis = '$login' OR n.nip = '$login')";
    $n = (int)FetchSingleEx($sql);
    if ($n > 0)
        return true;
    
    return false;
}

function DeleteComment($replid)
{
    $sql = "DELETE FROM jbsvcr.videocomment
             WHERE replid = '".$replid."'";
    QueryDbEx($sql);         
}

function ValidateEditVideoLogin($login, $password, &$type, &$info)
{
    if (strtolower((string) $login) == "jibas")
    {
        $info = "Anda tidak berhak mengubah video ini!";
        return false;
    }
    
    return ValidateLogin("", $login, $password, $type, $info);
}

function ValidateDeleteVideoLogin($login, $password, &$type, &$info)
{
    if (strtolower((string) $login) == "jibas")
    {
        $sql = "SELECT COUNT(replid)
                  FROM jbsuser.landlord
                 WHERE password = MD5('$password')";
        $n = (int)FetchSingle($sql);
        if ($n == 0)
        {
            $info = "Password salah!";
            return false;    
        }
        
        return true;
    }
    else
    {
        return ValidateLogin("", $login, $password, $type, $info);
    }
}

function DeleteVideo($videoid)
{
    global $FILESHARE_UPLOAD_DIR;
    
    $sql = "SELECT *
              FROM jbsvcr.video
             WHERE replid = '".$videoid."'";
    $res = QueryDbEx($sql);
    while($row = mysqli_fetch_array($res))
    {
        $floc = $row['location'] . "/" . $row['filename'];
        $floc = "$FILESHARE_UPLOAD_DIR/$floc";
        
        echo "DELETE $floc<br>";
        if (file_exists($floc))
            unlink($floc);
    }
    
    $sql = "DELETE FROM jbsvcr.video
             WHERE replid = '".$videoid."'";
    echo "$sql<br>";         
    QueryDbEx($sql);
}

?>