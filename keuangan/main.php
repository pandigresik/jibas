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
require_once("include/sessionchecker.php");

if (isset($_REQUEST['theme']))
	$theme = $_REQUEST['theme'];
?>
<title>JIBAS - KEUANGAN</title>
<link href="images/jibas2015.ico" rel="shortcut icon" />
<!--frameset rows="120,*,50" frameborder="0" border="0" framespacing="0">
	<frameset cols="50,*,40" frameborder="0" framespacing="0">
    	<frame name="topleft" src="topleft.php?theme=<?=$theme?>" scrolling="no" noresize />
        <frame name="header" src="topmenu.php" scrolling="no" noresize  />
        <frame name="topright" src="topright.php?theme=<?=$theme?>" scrolling="no" noresize  />
	</frameset><noframes></noframes>
    <frameset cols="50,*,40" frameborder="0" framespacing="0">
    	<frame name="midleft" src="midleft.php?theme=<?=$theme?>" scrolling="no" noresize />
        <frame name="content" src="penerimaan.php" scrolling="auto"  noresize/>
        <frame name="midright" src="midright.php?theme=<?=$theme?>" scrolling="no" noresize  />
	</frameset>
	<frameset cols="50,*,40" frameborder="0" framespacing="0">
    	<frame name="bottomleft" src="bottomleft.php?theme=<?=$theme?>" scrolling="no" noresize />
        <frame name="footer" src="footer.php" scrolling="no" noresize  />
        <frame name="bottomright" src="bottomright.php?theme=<?=$theme?>" scrolling="no" noresize  />
	</frameset>
</noframes-->
<frameset border="0" frameborder="0" framespacing="0" rows="87,*,41">
	<frame name="frametop" src="frametop.php" scrolling="no" noresize="noresize" />
    <frameset border="0" frameborder="0" framespacing="0" cols="20,*,27">
    	<frame name="frameleft" src="frameleft.php" scrolling="no" noresize="noresize" />
        <frame name="content" src="penerimaan.php"/>
        <frame name="frameright" src="frameright.php" scrolling="no" noresize="noresize" />
    </frameset>
    <frame name="framebottom" src="framebottom.php" scrolling="no" noresize="noresize" />
</frameset>