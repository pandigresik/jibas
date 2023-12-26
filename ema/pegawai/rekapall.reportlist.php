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
require_once('../inc/sessionchecker.php');
require_once("../inc/config.php");
require_once("../inc/db_functions.php");
require_once("../inc/common.php");
require_once("../lib/datearith.php");
require_once("rekapall.reportlist.func.php");

$status = $_REQUEST['status'];
$tahun30 = $_REQUEST['tahun30'];
$bulan30 = $_REQUEST['bulan30'];
$tanggal30 = $_REQUEST['tanggal30'];
$tahun = $_REQUEST['tahun'];
$bulan = $_REQUEST['bulan'];
$tanggal = $_REQUEST['tanggal'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA</title>
<link rel="stylesheet" href="../style/style.css" />
</head>

<body>
<?php
OpenDb();
ShowReportList($status);
CloseDb();
?>
</body>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>
</html>








