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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('penilaian.format.class.php');
OpenDb();
$FP = new FormatPenilaian();
//$P->OnStart();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Format Penilaian</title>
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<script language="javascript" src="penilaian.format.js"></script>
<script language="javascript" src="../script/ShowError.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>
<body id="body">
<div id="SubTitle" align="right">
<span style="color:#F90; background-color:#F90; font-size:20px">&nbsp;</span>&nbsp;<span style="color:#060; font-size:16px; font-weight:bold">Format Penilaian</span><br />
<a href="penilaian.main.php">Penilaian</a> > Format Laporan Penilaian
</div>
<?php
$FP->Main();
?>
</body>
</html>