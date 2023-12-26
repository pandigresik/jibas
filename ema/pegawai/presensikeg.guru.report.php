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
require_once('../inc/errorhandler.php');
require_once('../inc/sessioninfo.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../lib/departemen.php');
require_once('../lib/datearith.php');
require_once('presensikeg.guru.func.php');

$bulan = $_REQUEST['bulan'];
$tahun = $_REQUEST['tahun'];
$idkegiatan = $_REQUEST['idkegiatan'];
$nip = $_REQUEST['nip'];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Presensi Kegiatan Pegawai</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/jquery-1.9.1.js"></script>
<script language="javascript" src="presensikeg.guru.report.js"></script>
</head>

<body>
<input type='hidden' id='idkegiatan' value='<?=$idkegiatan?>'>
<input type='hidden' id='bulan' value='<?=$bulan?>'>
<input type='hidden' id='tahun' value='<?=$tahun?>'>
<input type='hidden' id='nip' value='<?=$nip?>'>    
<br>
<?php
$showbutton = true;
require_once("presensikeg.guru.report.func.php");
?>
</body>
</html>
<?php
CloseDb();
?>