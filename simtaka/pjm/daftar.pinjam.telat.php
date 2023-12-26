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
require_once('../inc/common.php');
require_once('../inc/db_functions.php');
require_once('daftar.pinjam.telat.class.php');

OpenDb();
$T = new CTelat();
$T->OnStart();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scr/tables.js"></script>
<script type="text/javascript" src="../scr/tools.js"></script>
<script type="text/javascript" src="daftar.pinjam.telat.js"></script>
</head>

<body>
	<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Daftar Peminjaman Yang Terlambat</font><br />
        <a href="peminjaman.php" class="welc">Peminjaman</a><span class="welc"> > Daftar Peminjaman Yang Terlambat</span><br /><br /><br />
    </div>
    <div id="content">
      <?=$T->Content()?>
    </div>
</body>
<?=$T->OnFinish()?>
</html>
<?php CloseDb(); ?>