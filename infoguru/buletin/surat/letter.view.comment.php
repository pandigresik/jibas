<?php
require_once("../../include/sessionchecker.php");
require_once("../../include/sessioninfo.php");
require_once("../../include/config.php");
require_once("../../include/db_functions.php");
require_once("letter.view.comment.func.php");
require_once("letter.view.comment.config.php");
require_once("common.func.php");

$idsurat = $_REQUEST['idsurat'];

OpenDb();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../style/style.css">    
<link type="text/css" rel="stylesheet" href="letter.view.comment.css" />
<link type="text/css" rel="stylesheet" href="../../script/themes/ui-lightness/jquery-ui-1.8.custom.css" />      
<script src="../../script/jquery-1.9.1.js"></script>
<script src="../../script/jquery-ui-1.10.3.custom.min.js"></script>
<script src="letter.view.comment.js"></script>
</head>
<body style='background-color: #fff'>
<input type='hidden' id='idsurat' value='<?= $idsurat ?>'>
<h2>INFORMASI SURAT</h2>
<a href='#' onclick='document.location.reload()'>refresh</a>
<span id='lbInfo'></span>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Informasi</a></li>
        <li><a href="#tabs-2">Komentar</a></li>
    </ul>
    <div id="tabs-1">
        <div id='divInfo' style='overflow: auto; background-color: #fff; height: 200px;'></div>
    </div>
    <div id="tabs-2">
        <div id='divComment' style='overflow: auto; background-color: #fff; height: 200px;'>
        <input type='hidden' id='maxCommentId' value='<?= GetMaxCommentId() ?>'>
        <table id='cmtList' border='0' cellpadding='2' cellspacing='0' width='100%'>
        <thead>
<?php
        ShowPrevCommentLink();
?>
        </thead>              
        <tbody>
<?php
        $maxcommentid = 0;
        ShowComment();
?>             
        </tbody>
        <tfoot>
            <tr>
                <td style='background-color: #fff' width='3%' align='left'>&nbsp;</td>
                <td style='background-color: #fff' width='*' align='left' valign='top' colspan='2'>
                    <div id='divAddComment'>
<?php
                    ShowCommentBox();
?>    
                    </div>
                </td>
            </tr>   
        </tfoot>
        </table>    
        </div>
    </div>
</div>    
</body>
</html>
<?php
CloseDb();
?>