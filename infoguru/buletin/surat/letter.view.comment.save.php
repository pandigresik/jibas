<?php
require_once("../../include/sessionchecker.php");
require_once("../../include/sessioninfo.php");
require_once("../../include/config.php");
require_once("../../include/common.php");
require_once("../../include/compatibility.php");
require_once("../../include/db_functions.php");
require_once("letter.view.comment.func.php");
require_once("letter.view.common.func.php");

$nip = SI_USER_ID();

try
{
    OpenDb();
    
    BeginTrans();
    
    $idsurat = $_REQUEST['idsurat'];
    $ncomment = $_REQUEST['ncomment'];
    for($i = 1; $i <= $ncomment; $i++)
    {
        $id = "comment_$i";
        $text = trim((string) $_REQUEST[$id]);
        $comment = $text;
        $fcomment = FormattedText($text);
        
        $sql = "INSERT INTO jbsletter.comment
                   SET nip = '$nip', ";
        $sql .= "idsurat = '$idsurat', tanggal = NOW(), komen = '$comment', fkomen = '".$fcomment."'";
        
        QueryDbEx($sql);
    }
    
    $sql = "UPDATE jbsletter.surat
               SET lastactive = NOW()
             WHERE replid = '".$idsurat."'";
    QueryDbEx($sql);         
    
    CommitTrans();
    CloseDb();
    
    http_response_code(200);
}
catch(DbException $dbe)
{
    RollbackTrans();
    CloseDb();

    http_response_code(500);
    echo $sql . "<br>" . $dbe->getMessage();
}
catch(Exception $e)
{
    RollbackTrans();
    CloseDb();

    http_response_code(500);
    echo $e->getMessage();
}    

?> 