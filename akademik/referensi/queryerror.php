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
require_once('../cek.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Query Error Log</title>
</head>

<body>
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/ico/b_warning.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	<td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <tr>
        <td align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Query Error Log</font></td>
    </tr>
    <tr>
        <td align="right"><a href="../usermenu.php" target="content">
          <font size="1" face="Verdana" color="#000000"><b>Pengaturan</b></font></a>&nbsp>&nbsp 
          <font size="1" face="Verdana" color="#000000"><b>Query Error Log</b></font>
        </td>
    </tr>
    <tr>
      <td align="left">&nbsp;</td>
    </tr>
	</table>

<?php $logFile = realpath(__DIR__) . "/../../log/akademik-error.log";
	$logFile = str_replace("\\", "/", $logFile);
	if (!file_exists($logFile))
		$logFile = ""; 
	
	if ($logFile != "")
	{
		$r = random_int(1, 30000);
		$docRoot = $_SERVER['DOCUMENT_ROOT'];
		$logFile = "http://" . $_SERVER['SERVER_ADDR'] . str_replace($docRoot, "", $logFile) . "?$r";
	} ?>
		
    <iframe name="logContent" id="logContent" 
    		width="100%" height="400" 
            src="<?=$logFile?>" 
            style="border-width:1px; background-color:#FFF"></iframe>
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    
<script>
function scrollToBottom()
{
	var dh = document.getElementById("logContent").contentWindow.document.body.scrollHeight;
	var fh = document.getElementById("logContent").height;
	if (dh > fh)
	{
		var movelen = dh - fh;
		logContent.window.scrollTo(0, movelen);
	}
}
setTimeout("scrollToBottom()", 1000);
</script>

</body>
</html>
