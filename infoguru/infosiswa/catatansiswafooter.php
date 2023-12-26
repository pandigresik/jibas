<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 2.6.0 (January 14, 2012)
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
require_once('../include/sessioninfo.php');
require_once("../include/sessionchecker.php");

$nis=$_REQUEST['nis'];
?>
<frameset COLS="350,*" frameborder="yes" border="1" framespacing="0" frameborder="0">
	<frame src="../infosiswa/catatansiswamenu.php?nis=<?=$nis?>" name="catatansiswamenu" id="catatansiswamenu" scrolling="no" style="border:1px; border-bottom-color:#000000; border-bottom-style:solid">
	<frame src="../blank.php" name="catatansiswacontent" id="catatansiswacontent">
</frameset>