<?php
require_once("../../include/sessionchecker.php");
require_once("../../include/sessioninfo.php");
require_once("../../include/config.php");
require_once("../../include/db_functions.php");
require_once("letter.view.gallery.func.php");

$idsurat = $_REQUEST['idsurat'];

OpenDb();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../style/style.css">        
<script src='../../script/jquery-1.9.1.js'></script>
<script src='letter.view.gallery.js'></script>
</head>
<body marginwidth='0' marginheight='0' leftmargin='0' topmargin='0' style='background-color: #eee'>

<div id='control' style='background-color: #ccc; height: 28px; width: 100%'>
<table border='0' width='100%' cellpadding='0' cellspacing='0'>
<tr>
    <td width='50%' align='left'>
        &nbsp;Tampilan gambar:&nbsp;
        <select id='cbSize' class='inputbox' onchange='showImage()'>
            <option value='0'>Actual Size</option>
            <option value='1'>Fit Width</option>
            <option value='2'>Fit Height</option>
        </select>
    </td>
    <td width='50%' align='right'>
<?php      ShowControl() ?>
    &nbsp;&nbsp;
    </td>
</tr>    
</table>    
</div>
<div id='container' style='background-color: #aaa; overflow: auto'>
<?php
ShowImage();
?>
<div id='infobox' style='background-color: #000; opacity: 0.7; height: 32px; width: 75%; position: fixed'>
<table border='0' cellpadding='2' cellspacing='0' width='100%'>
<tr>
    <td width='100%' align='center' valign='top' style='font-size: 11px; color: #fff; font-family: Arial;'>
        <span id='infomsg'></span>
    </td>
</tr>    
</table>    
</div>
</div>

</body>
</html>
<?php
CloseDb();
?>