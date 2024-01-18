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
require_once("../../include/sessioninfo.php");
require_once('../../include/sessionchecker.php'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {
	color: #FF9900;
	font-weight: bold;
	font-size: 12px;
}
.style2 {
	color: #009900;
	font-size: 12px;
	font-weight: bold;
}
.style5 {
	color: #009900;
	font-size: 14px;
}
-->
</style>
</head>

<body>
<div align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font color="Gray" size="4" face="Verdana, Arial, Helvetica, sans-serif">File Sharing</font><br />
  <a href="../../home.php" target="framecenter"> <font size="1" color="#000000"><b>Home</b></font></a>&nbsp>&nbsp;<strong><font color="#000000" size="1">File Sharing</font></strong>
</div>
<table width="100%" height="150" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle"><br /><br /><br /><br />
    <?php if (SI_USER_ID()!="landlord"){ ?>
	<span class="style1">Folder milik Anda adalah folder </span><span class="style2">'
	<?=trim((string) SI_USER_ID())?>
    '</span><span class="style1"> yang berada di bawah folder </span><span class="style2">'(root)'</span><br />
    <span class="style1">Anda dapat mengelola file atau folder yang berada di dalam folder</span> <span class="style2">'
    <?=trim((string) SI_USER_ID())?>
    '</span><span class="style1">.</span>
    <?php } ?>
	</td>
  </tr>
</table>

</body>
</html>