<?php
require_once("../../include/sessionchecker.php");
require_once("../../include/sessioninfo.php");

$idsurat = $_REQUEST['idsurat'];
?>
<link type="text/css" rel="stylesheet" href="../script/themes/ui-lightness/jquery-ui-1.8.custom.css" />      
<script src="../../script/jquery-1.9.1.js"></script>
<script src="../../script/jquery-ui-1.10.3.custom.min.js"></script>
<div id="emoticonsDialog"></div>
<frameset cols='*,340' frameborder='0'>
    <frame name='gallery' src='letter.view.gallery.php?idsurat=<?=$idsurat?>' scrolling='no'>
    <frame name='comment' src='letter.view.comment.php?idsurat=<?=$idsurat?>' scrolling='no'>
</frameset>