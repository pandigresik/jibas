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
require_once("sessionchecker.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS - INFOSISWA</title>
<link rel="SHORTCUT ICON" href="images/jibas2015.ico">
</head>

<frameset rows="87,*,30" border="0" frameborder="0" framespacing="0">
	<frame name="frametop" src="frametop.php" noresize="noresize" scrolling="no" />
    <frameset cols="15,*,17" border="0" frameborder="0" framespacing="0">
    	<frame name="frameleft" src="frameleft.php" scrolling="no" noresize="noresize" />
    	<frame name="framecenter" src="framecenter.php" />
        <frame name="frameright" src="frameright.php"  scrolling="no" noresize="noresize" />
    </frameset>
    <frame name="framebottom" src="framebottom.php" noresize="noresize" scrolling="no" />
</frameset><noframes></noframes>
</html>