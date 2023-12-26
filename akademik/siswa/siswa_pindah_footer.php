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
$departemen=$_REQUEST['departemen'];
$idtingkat=$_REQUEST['idtingkat'];
$idtahunajaran=$_REQUEST['idtahunajaran'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pencarian Siswa Main</title>
	 
</head>
<frameset cols="55%,*" border="1" framespacing="0">
	<!--<frame src="siswa_pindah_menu.php?departemen=<?=$departemen?>&idtingkat=<?=$idtingkat?>&idtahunajaran=<?=$idtahunajaran?>" name="siswa_pindah_menu" id="siswa_pindah_menu" title="siswa_pindah_menu" />-->
	<frameset rows = "110,*" border ="0" framespacing="0" >
		<frame src = "siswa_pindah_menu.php?departemen=<?=$departemen?>&idtingkat=<?=$idtingkat?>&idtahunajaran=<?=$idtahunajaran?>" name ="siswa_pindah_menu" id="siswa_pindah_menu" />	
		<frame src = "blank_pindah_daftar.php" name ="siswa_pindah_daftar"/>
	</frameset>
    <frame src="siswa_pindah_content.php?departemen=<?=$departemen?>&idtingkat=<?=$idtingkat?>&idtahunajaran=<?=$idtahunajaran?>" name="siswa_pindah_content" id="siswa_pindah_content" title="siswa_pindah_content" />
</frameset>
</html>