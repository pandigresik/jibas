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
require_once('../inc/sessionchecker.php');
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/db_functions.php');
require_once('kembali.class.php');
OpenDb();
$K = new CKembali();
$K->OnStart();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../scr/jquery/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scr/tables.js"></script>
<script type="text/javascript" src="../scr/tools.js"></script>
<script type="text/javascript" src="../scr/rupiah.js"></script>
<script language="javascript" src="../scr/jquery/jquery-1.2.6.js"></script>
<script language="javascript" src="../scr/jquery/jquery.autocomplete.js"></script>
<script type="text/javascript" src="kembali.js"></script>
</head>

<body <?=$K->OnLoad()?>>
	<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Pengembalian Pustaka</font><br />
        <a href="pengembalian.php" class="welc">Pengembalian</a><span class="welc"> > Pengembalian Pustaka</span><br /><br /><br />
    </div>
    <div id="content">
      <?=$K->Content()?>
    </div>
</body>
<?=$K->OnFinish()?>
</html>
<?php CloseDb(); ?>