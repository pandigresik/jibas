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
require_once('../include/getheader.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php'); 
function delete($file) {
 if (file_exists($file)) {
   chmod($file,0777);
   if (is_dir($file)) {
     $handle = opendir($file); 
     while($filename = readdir($handle)) {
       if ($filename != "." && $filename != "..") {
         delete($file."/".$filename);
       }
     }
     closedir($handle);
     rmdir($file);
   } else {
     unlink($file);
   }
 }
}
$cek = 0;
$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) {
	OpenDb();
	$sql="SELECT replid FROM jbsvcr.gambarlogin ORDER BY replid DESC LIMIT 1";
	$result=QueryDb($sql);
	$row=@mysqli_fetch_array($result);
	$lastid=(int)$row['replid'];
	CloseDb();
	/*
	$foto=$_FILES["file"];
  	$uploadedfile = $foto['tmp_name'];
	$uploadedtypefile = $foto['type'];
  	$uploadedsizefile = $foto['size'];
	$dir="design/";
	$dir2="../design/";
	//$namafile=str_replace(" ","",$foto['name']);
	$cnt=(int)$lastid+1;
	$namafile="bg".$cnt.".jpg";
	move_uploaded_file($uploadedfile, $dir2.$namafile);
	*/
	$dir="design/";
	$dir2="../design/";
	$foto=$_FILES["file"];
	$uploadedfile = $foto['tmp_name'];
	$uploadedtypefile = $foto['type'];
	$uploadedsizefile = $foto['size'];
	//if (strlen($uploadedfile)!=0)
		//$gantifoto=", foto='$foto_data'";
  	if($uploadedtypefile=='image/jpeg')
    $src = imagecreatefromjpeg($uploadedfile);
	$cnt=(int)$lastid+1;
	$filename="bg".$cnt.".jpg";
	//$filename = "x.jpg";
	[$width, $height]=getimagesize($uploadedfile);
	if ($width<$height){
	$newheight=1024;
   	$newwidth=1280;
	} else if ($width>$height){
	$newwidth=1280;
   	$newheight=1024;
	}
   	$tmp=imagecreatetruecolor($newwidth,$newheight);
   	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
  	imagejpeg($tmp,$filename,75);
	imagedestroy($src);
  	imagedestroy($tmp); // NOTE: menghapus file di temp
	//$foto_data=addslashes(fread(fopen($filename,"r"),filesize($filename)));
	copy($filename, $dir2."/".$filename);	
	delete($filename);
	OpenDb();
		$sql0 = "UPDATE jbsvcr.gambarlogin SET aktif=0";
		$result0 = QueryDb($sql0);
		$sql = "INSERT INTO jbsvcr.gambarlogin SET direktori='$dir',namafile='$filename',aktif=1";
		$result = QueryDb($sql);
		if ($result) { ?>
			<script language="javascript">				
				opener.get_fresh();
				window.close();
			</script> 
<?php 	}
	
	CloseDb();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOSISWA [Tambah Gambar]</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>

<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF" >
<form name="main" onSubmit="return validate()" action="gambarlogin_add.php" method="POST" enctype="multipart/form-data">
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr height="25">
<td width="120" class="header"><div align="center">Tambah Gambar</div></td>
</tr>
<tr>
  <td align="center"><div align="left">
    <input name="file" type="file" />
  </div></td>
</tr>
<tr>
	<td align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but" />&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />    </td>
</tr>
<!-- END OF TABLE CONTENT -->
</table>
</form>
<!-- Tamplikan error jika ada -->
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

<!-- Pilih inputan pertama -->

<?php if ($cek == 1) { ?>
<script language="javascript">
	document.getElementById('urutan').focus();
</script>

<?php } ?>
</body>
</html>