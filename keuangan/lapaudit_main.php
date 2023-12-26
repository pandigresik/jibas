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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/sessioninfo.php');
if (getLevel() == 2) { ?>
	<script language="javascript">
        alert('Maaf, anda tidak berhak mengakses halaman ini!');
        document.location.href = "lapkeuangan.php";
    </script>
<?php  exit();
} ?>   
<frameset rows="80,*" frameborder="yes" border="0" framespacing="0" frameborder="0">
	<frame name="header" src="lapaudit_header.php" scrolling="no" noresize="noresize" style="border:1px; border-bottom-color:#000000; border-bottom-style:solid" />
    <frame name="contentblank" src="lapaudit_blank.php" />
	<!--<frameset border="1" cols="250,*" frameborder="no" framespacing="yes">
		
    	<frame name="pilih" src="lapaudit_blank.php" scrolling="auto" style="border:1; border-right-color:#000000; border-right-style:solid" />
	    <frame name="content" src="lapaudit_blank.php" />
    </frameset>-->
</frameset><noframes></noframes>