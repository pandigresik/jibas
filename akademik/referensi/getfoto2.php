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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../include/imageresizer.php');

$foto1 = $_FILES['foto'];
$uploadedfile = $foto1['tmp_name'];
$uploadedtypefile = $foto1['type'];
$uploadedsizefile = $foto1['size'];
if (strlen((string) $uploadedfile) != 0)
{
	$tmp_path = realpath(".") . "/../../temp";
	$tmp_exists = file_exists($tmp_path) && is_dir($tmp_path);
	if (!$tmp_exists)
		mkdir($tmp_path, 0755);
	
	$filename = "$tmp_path/ad-logo-tmp.jpg";
	ResizeImage($foto1, 100, 90, 80, $filename);
}
?>
<script language="javascript">
	setTimeout("kembali()",1);
</script>
<script language="javascript">
function kembali(){
	document.location.href = "logo2.php?gbrbaru=1&departemen=<?=$_REQUEST['departemen']?>";
}	
</script>