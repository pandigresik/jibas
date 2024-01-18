<?php
function GetMaxCommentId()
{
    global $idsurat;
    
    $sql = "SELECT replid
              FROM jbsletter.comment
             WHERE idsurat = '$idsurat'
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

function GetOwnerName($ownerid)
{
    $sql = "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$ownerid."'";
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

function CurrentUserId()
{
    // DEBUG ONLY
    return SI_USER_ID();
}

function ShowPrevCommentLink()
{
    global $NotesViewActiveComment, $idsurat;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsletter.comment
             WHERE idsurat = '".$idsurat."'";
    $nCmt = (int)FetchSingle($sql);
    if ($nCmt <= $NotesViewActiveComment)
        return;
    
    $nPrevCmt = $nCmt - $NotesViewActiveComment;
?>
    <tr height='30'>
        <td style='background-color: #fff' width='1%' align='left'>&nbsp;</td>
        <td class='NotesViewCommentCell' width='*' align='left' valign='middle' colspan='2'>
            &nbsp;&nbsp;
            <span id='cmtShowPrevLink'
                  class='NotesViewShowPrevCommentLink'
                  onclick='showPrevComment(<?=$idsurat?>)'>
            Lihat <?=$nPrevCmt?> komentar sebelumnya
            </span>
        </td>
    </tr>
<?php
}

function ShowPrevComment()
{
    global $NotesViewActiveComment, $idsurat;
    
    $userid = CurrentUserId();
    
    $sql = "SELECT COUNT(replid)
              FROM jbsletter.comment
             WHERE idsurat = '".$idsurat."'";
    $nCmt = (int)FetchSingle($sql);
    $sqlLimit = "LIMIT " . ($nCmt - $NotesViewActiveComment);
    
    $sql = "SELECT replid, nip, 
                   TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff,
                   DATE_FORMAT(tanggal, '%d-%m-%Y') AS tanggal,
                   fkomen
              FROM jbsletter.comment
             WHERE idsurat = '$idsurat'
                   $sqlLimit";
    
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return;
    
    while($row = mysqli_fetch_array($res))
    {
        $replid = $row['replid'];
        $ownerid = $row['nip'];
        $ownername = GetOwnerName($ownerid);
        $age = SecToAgeDate($row['secdiff'], $row['tanggal']);
        
        $rowId = "commentRow_$replid";
        $delDivId = "deleteCommentButton_$replid";      
        ?>
        <tr id='<?=$rowId?>'>
            <td style='background-color: #fff' width='1%' align='left'>&nbsp;</td>
            <td class='NotesViewCommentCell' width='10%' align='center' valign='top'>
                <img src='letter.view.comment.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>' height='35'><br>
            </td>
            <td class='NotesViewCommentCell' width='*' align='left' valign='top'>
                <div style='position: relative'>
                <strong><?=$ownername?>&nbsp;</strong><?=$row['fkomen']?><br>
                <span class='NotesViewCommentAge'>
                <?= $age ?>
                </span>
<?php              if ($userid == $ownerid) { ?>                    
                <div class='NotesViewCommentDeleteDiv'
                     onmouseover="document.getElementById('<?=$delDivId?>').style.display = 'block';"
                     onmouseout="document.getElementById('<?=$delDivId?>').style.display = 'none';">                
                    <div id='<?=$delDivId?>' style='display: none;'>
                        <img src='../../images/small-delete.png'
                             title='Hapus komentar ini'
                             onclick="showDeleteCommentDialog('<?=$replid?>','<?=$rowId?>')">
                    </div>
                </div>
<?php              } ?>                                             
                </div>
            </td>
        </tr>
        <?php
    }
}

function ShowComment()
{
    global $NotesViewActiveComment, $idsurat, $maxcommentid;
    
    $userid = CurrentUserId();
    
    $sqlLimit = "";
    if ($maxcommentid == 0)
    {
        $sql = "SELECT COUNT(replid)
                  FROM jbsletter.comment
                 WHERE idsurat = '".$idsurat."'";
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
    
    $sql = "SELECT replid, nip,
                   TIME_TO_SEC(TIMEDIFF(NOW(), tanggal)) AS secdiff,
                   DATE_FORMAT(tanggal, '%d-%m-%Y') AS tanggal,
                   fkomen
              FROM jbsletter.comment
             WHERE idsurat = '$idsurat'
               AND replid > '$maxcommentid'
                   $sqlLimit";

    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return;
    
    while($row = mysqli_fetch_array($res))
    {
        $replid = $row['replid'];
        $ownerid = $row['nip'];
        $ownername = GetOwnerName($ownerid);
        $age = SecToAgeDate($row['secdiff'], $row['tanggal']);
        
        $rowId = "commentRow_$replid";
        $delDivId = "deleteCommentButton_$replid";    
        ?>
        <tr id='<?=$rowId?>'>
            <td style='background-color: #fff' width='1%' align='left'>&nbsp;</td>
            <td class='NotesViewCommentCell' width='10%' align='center' valign='top'>
                <img src='letter.view.comment.gambar.php?r=<?= random_int(1, 99999)?>&ownerid=<?=$ownerid?>' height='35'><br>
            </td>
            <td class='NotesViewCommentCell' width='*' align='left' valign='top'>
                <div style='position: relative'>
                <strong><?=$ownername?>&nbsp;</strong><?=$row['fkomen']?><br>
                <span class='NotesViewCommentAge'>
                <?= $age ?>
                </span>
<?php              if ($userid == $ownerid) { ?>                
                <div class='NotesViewCommentDeleteDiv'
                     onmouseover="document.getElementById('<?=$delDivId?>').style.display = 'block';"
                     onmouseout="document.getElementById('<?=$delDivId?>').style.display = 'none';">
                    <div id='<?=$delDivId?>' style='display: none;'>
                        <img src='../../images/small-delete.png'
                             title='Hapus komentar ini'
                             onclick="showDeleteCommentDialog('<?=$replid?>','<?=$rowId?>')">
                    </div>
                </div>
<?php              } ?>                
                </div>
            </td>
        </tr>
        <?php
    }
}

function ShowCommentBox()
{
    global $idsurat;
    ?>
    
    <fieldset class='NotesViewCommentBox'>
    <legend>Komentar</legend>
    <table id='cmtBoxTable' border='0' cellpadding='0' cellspacing='0'>
    <tbody>
    <tr>
        <td align='left' valign='top'>
            <textarea id='comment_1' name='comment_1'
                cols='40' rows='2' class='inputbox'
                onkeyup='checkCommentLength(1)'></textarea>
        </td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td align='left'>
            <input type='hidden' id='idsurat' name='idsurat' value='<?= $idsurat ?>'>
            <input type='hidden' id='ncommentbox' name='ncommentbox' value='1'>
            <span style='cursor: pointer; color: blue;'
                  onclick='addCommentBox()'>Tambah komentar</span>    
        </td>
    </tr>
    <tr>
        <td align='left'>
            <br>
            <input type='button' value=' Simpan ' class='but'
                   style='height: 20px' onclick='saveComment()'><br>
            <span style='color: red' id='saveInfo'>&nbsp;</span>
        </td>
    </tr> 
    </tfoot>
    </table>
    </fieldset>

<?php    
}

function DeleteComment($replid)
{
    $sql = "DELETE FROM jbsletter.comment
             WHERE replid = '".$replid."'";
    QueryDb($sql);         
}
?>