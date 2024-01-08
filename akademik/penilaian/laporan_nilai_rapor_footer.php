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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];

if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];

if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];

if (isset($_REQUEST['pelajaran'])) 
	$pelajaran = $_REQUEST['pelajaran'];

if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];

if (isset($_REQUEST['harian']))
	$harian = $_REQUEST['harian'];

if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];

if (isset($_REQUEST['tglmulai']))
    $tglmulai = $_REQUEST['tglmulai'];

if (isset($_REQUEST['tglakhir']))
    $tglakhir = $_REQUEST['tglakhir'];
?>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>

<frameset cols="17%,*" border="0" frameborder="0" framespacing="0" >
<frame src="laporan_nilai_rapor_menu.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&kelas=<?=$kelas?>&harian=<?=$harian?>&pelajaran=<?=$pelajaran?>&tglmulai=<?=$tglmulai?>&tglakhir=<?=$tglakhir?>"
       style="border:1px; border-color:#000000; border-bottom-style:solid">
<frame src="blank_laporan_rapor_content.php" name="laporan_rapor_content" id="laporan_rapor_content" style="border:1px; border-left-color:#000000; border-left-style:solid">
</frameset><noframes></noframes>