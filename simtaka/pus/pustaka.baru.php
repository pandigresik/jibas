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
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/fileinfo.php');
require_once('../inc/imageresizer.php');
require_once('../inc/sessioninfo.php'); 
require_once('pustaka.baru.class.php');
OpenDb();
$P = new CPustaka();
$P->OnStart();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scr/tables.js"></script>
<script type="text/javascript" src="../scr/ajax.js"></script>
<script type="text/javascript" src="../scr/tools.js"></script>
<script type="text/javascript" src="../scr/rupiah.js"></script>
<script type="text/javascript" src="pustaka.baru.js"></script>
<script type="text/javascript" src="../scr/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "exact",
        elements : "abstraksi", 
		theme : "simple"
	});
</script>
</head>
<body>
	<div id="waitBox" style="position:absolute; visibility:hidden;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="32"><img src="../img/movewait.gif"  border="0" /></td>
            <td>&nbsp;<span class="news_title1">Please&nbsp;wait...</span></td>
          </tr>
        </table>
    </div>
	<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Pustaka Baru</font><br />
        <a href="pustaka.php" class="welc">Pustaka</a><span class="welc"> > Pustaka Baru</span><br /><br /><br />
    </div>
    <div id="content">
      <?=$P->Content()?>
    </div>
</body>
<?=$P->OnFinish()?>
</html>
<?php CloseDb(); ?>