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
if(isset($_REQUEST["departemen"]))
	$departemen = $_REQUEST["departemen"];

if(isset($_REQUEST["tingkat"]))
	$tingkat = $_REQUEST["tingkat"];

if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];

if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];

if(isset($_REQUEST["nip"]))
	$nip = $_REQUEST["nip"];

if(isset($_REQUEST["nama"]))
	$nama = $_REQUEST["nama"];
?>
<frameset cols = "18%, *" border ="1" frameborder="0" framespacing="0">
		<frame src = "nilai_pelajaran_menu.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&nama=<?=$nama?>&semester=<?=$semester?>&kelas=<?=$kelas?>&nip=<?=$nip?>" name ="nilai_pelajaran_menu" scrolling="auto"/>
		<frame src = "blank_nilai_pelajaran_content.php" name ="nilai_pelajaran_content" style="border:1px; border-left-color:#000000; border-left-style:solid" scrolling="auto"/>
    </frameset><noframes></noframes>