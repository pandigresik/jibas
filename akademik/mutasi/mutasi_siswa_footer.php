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
$tingkat=$_REQUEST['tingkat'];
$tahunajaran=$_REQUEST['tahunajaran'];
?>
<frameset cols="37%,*" border="1" framespacing="yes" frameborder="yes">
<frameset rows="140,*" border="1" framespacing="yes" frameborder="no">
<frame src="mutasi_siswa_menu.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>" name="mutasi_siswa_menu" scrolling="No" noresize="noresize" id="mutasi_siswa_menu" title="mutasi_siswa_menu" style="border:1; border-bottom-color:#000000; border-bottom-style:solid"/>
<frame src="blank_mutasi.php" name="mutasi_siswa_pilih" id="mutasi_siswa_pilih" title="mutasi_siswa_pilih" scrolling="yes"/>
</frameset> 
<frame src="mutasi_siswa_content.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>" name="mutasi_siswa_tujuan" id="mutasi_siswa_tujuan" title="mutasi_siswa_tujuan" style="border:1; border-left-color:#000000; border-left-style:solid"/>
</frameset><noframes></noframes>