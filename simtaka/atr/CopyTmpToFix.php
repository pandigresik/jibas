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
require_once("../inc/session.checker.php");
require_once("../inc/config.php");
require_once("../inc/db_functions.php");
require_once("../inc/common.php");//imageresizer.php
require_once("../inc/imageresizer.php");
require_once("../inc/fileinfo.php");
OpenDb();
$filename=$_REQUEST['filename'];
$perpustakaan=$_REQUEST['perpustakaan'];
if ($filename==''){
	exit;
} else {
$handle = fopen($filename, "r");
$logo_binary = addslashes(fread(fopen($filename,"r"),filesize($filename)));
$sql	= "UPDATE ".$db_name_umum.".identitas SET foto='$logo_binary' WHERE status=1 AND perpustakaan='$perpustakaan'";
//echo $sql;
$result = QueryDb($sql);
if ($result){
	?>
	<script language='javascript'>
		parent.SaveSuccess();
	</script>
	<?php
}
}
?>