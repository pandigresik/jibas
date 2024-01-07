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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/dpupdate.php');
    
$id_tingkat = $_REQUEST['id_tingkat'];
$nip_guru = $_REQUEST['nip_guru'];
$id_pelajaran = $_REQUEST['id_pelajaran'];
$aspek = $_REQUEST['aspek'];
$departemen = $_REQUEST['departemen'];
$op = $_REQUEST['op'];


if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql = "DELETE FROM aturannhb WHERE idpelajaran = '$id_pelajaran' AND nipguru = '$nip_guru' AND idtingkat = '$id_tingkat' AND dasarpenilaian = '".$aspek."'"; 
	
	QueryDb($sql);
	CloseDb();
	?>
    <script>
		document.location.href="perhitungan_rapor_content.php?id_pelajaran=<?=$id_pelajaran?>&nip=<?=$nip_guru?>&departemen=<?=$departemen?>";    	
    </script> 
	<?php
}	

?>