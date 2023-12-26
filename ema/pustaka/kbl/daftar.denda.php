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
require_once('../../inc/config.php');
require_once('../../inc/common.php');
require_once('../../inc/rupiah.php');
require_once('../../inc/db_functions.php');
require_once('../../inc/sessionchecker.php');
require_once('daftar.denda.class.php');
OpenDb();
$DD = new CDaftarDenda();
$DD->OnStart();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../script/tables.js"></script>
<script type="text/javascript" src="../../script/tools.js"></script>
<script type="text/javascript" src="../../script/tooltips.js"></script>
<script type="text/javascript" src="../../script/rupiah.js"></script>
<script type="text/javascript" src="daftar.denda.js"></script>
<link href="../../style/tooltips.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="title" align="right">
        <font style="background-color: rgb(255, 204, 102);" face="Verdana, Arial, Helvetica, sans-serif" size="4">&nbsp;</font>&nbsp;					
        <span class="news_title2">Daftar Penerimaan Denda</span>
    </div>
    <div id="content">
      <?=$DD->Content()?>
    </div>
</body>
<?=$DD->OnFinish()?>
</html>
<?php CloseDb(); ?>