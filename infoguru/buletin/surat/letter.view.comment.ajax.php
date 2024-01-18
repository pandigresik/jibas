<?php
require_once("../../include/sessionchecker.php");
require_once("../../include/sessioninfo.php");
require_once("../../include/config.php");
require_once("../../include/common.php");
require_once("../../include/compatibility.php");
require_once("../../include/db_functions.php");
require_once("letter.view.comment.func.php");
require_once("letter.view.comment.config.php");
require_once("letter.view.common.func.php");

$op = $_REQUEST['op'];
if ($op == "reloadcommentbox")
{
    $idsurat = $_REQUEST['idsurat'];
    ShowCommentBox();
}
elseif ($op == "showprevcomment")
{
    $idsurat = $_REQUEST['idsurat'];
    
    OpenDb();
    ShowPrevComment();
    CloseDb();
}
elseif ($op == "shownewcomment")
{
    $idsurat = $_REQUEST['idsurat'];
    $maxcommentid = $_REQUEST['maxcommentid'];
    
    OpenDb();
    ShowComment();
    CloseDb();
}
elseif ($op == "getmaxcommentid")
{
    $idsurat = $_REQUEST['idsurat'];
    
    OpenDb();
    $maxcommentid = GetMaxCommentId();
    CloseDb();
    
    echo $maxcommentid;
}
elseif ($op == "deletecomment")
{
    $replid = $_REQUEST['replid'];
    $rowid = $_REQUEST['rowid'];
    
    OpenDb();
    DeleteComment($replid);
    CloseDb();
    
    http_response_code(200);
    echo "OK";    
}
?> 