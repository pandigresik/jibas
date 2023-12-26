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
require_once('../include/getheader.php');
require_once('../include/common.php');
require_once('../include/sessionchecker.php');
require_once('../include/db_functions.php');
require_once('siswa.class.php');

OpenDb();
$S = new CSiswa();
$S->OnStart();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="../script/jquery-1.9.1.js" type="text/javascript"></script>
<script src="../script/ajax.js" type="text/javascript"></script>
<script src="siswaui.js" type="text/javascript"></script>
<script src="infosiswa.js" type="text/javascript"></script>
<script src="infosiswa.cbe.js" type="text/javascript"></script>
<script src="../script/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../script/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/cal2.js"></script>
<script language="javascript" src="../script/cal_conf3.js"></script>
</head> 
<body>
<div id="waitBox" style="position:absolute; visibility:hidden;">
	<img src="../img/loading2.gif" border="0" />&nbsp;<span class="tab2">Please&nbsp;wait...</span>
</div>
<table width="100%" border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td width="250" align="left" valign="top"><div id="list" style=" width:350px">
<?php 	$S->ShowStudentList();	?>
	</td>
    <td width="*" align="left" valign="top">
		<div id="content"></div>
	</td>
  </tr>
</table>
</body>
</html>