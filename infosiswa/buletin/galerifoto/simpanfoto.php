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
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('../../include/imageresizer.php');
require_once('../../include/fileinfo.php');
require_once('../../include/fileutil.php');
require_once('../../include/sessionchecker.php');

$source = $_REQUEST["source"];
for ($i = 1; $i <= 3; $i++)
{
	$nama = $_REQUEST["nama".$i];
	$keterangan = $_REQUEST["keterangan".$i];
	$foto =	$_FILES["file".$i];
  	
	$iduser = SI_USER_ID();
	$salt = RandomString(7);
	$origfile = $foto['name'];
	$ext = GetFileExt($origfile);
	$fn = GetFileName($origfile);
	$fn = str_replace(" ", "", (string) $fn);
	$fn = date('ymdHis') . "-" . $iduser . "-" . $salt . "-" . $fn;
	$fn = md5($fn) . $ext;
	$output1 = "$FILESHARE_UPLOAD_DIR/galerisiswa/photos/";
	$output2 = "$FILESHARE_UPLOAD_DIR/galerisiswa/thumbnails/";	
	
	if (!is_dir($output1))
	{
		mkdir($output1, 0750, true);
		
		$fhtaccess = "$output1/.htaccess";
		$fhtaccess = str_replace("//", "/", $fhtaccess);
		if ($fp = @fopen($fhtaccess, "w"))
		{
			@fwrite($fp, "Options -Indexes\r\n");
			@fclose($fp);
		}
	}
	
	if (!is_dir($output2))
	{
		mkdir($output2, 0740, true);
		
		$fhtaccess = "$output2/.htaccess";
		$fhtaccess = str_replace("//", "/", $fhtaccess);
		if ($fp = @fopen($fhtaccess, "w"))
		{
			@fwrite($fp, "Options -Indexes\r\n");
			@fclose($fp);
		}
	}

	if ($origfile != "")
	{
	    $output1 = "$output1/$fn";
		ResizeImage($foto, 800, 600, 70, $output1);
		
		$output2 = "$output2/$fn";
		ResizeImage($foto, 125, 75, 70, $output2);
	
		OpenDb();
		$sql = "INSERT INTO jbsvcr.galerifoto
				   SET idguru='".SI_USER_ID()."', nama='$nama', keterangan='$keterangan', filename='$fn'";
		$result = QueryDb($sql);
		CloseDb();
	
		if (!$result)
		{ ?>
		<script language="javascript">
			alert ('Gagal menyimpan Gambar <?=$foto[\NAME]?>');
			opener.ubah_profil();
			window.close()
		</script>
	<?php }
	}
}
?>
<script language="javascript">
	opener.get_fresh();
	window.close()
</script>