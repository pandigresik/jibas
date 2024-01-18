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

if (isset($_REQUEST['Simpan'])) {
	OpenDb();
	$sql="SELECT * FROM jbsumum.identitas";
	$res=QueryDb($sql);
	$num=@mysqli_num_rows($res);
	
	$filename = "../images/logokecil.jpg";
	$handle = fopen($filename, "r");
	$foto_data=addslashes(fread(fopen($filename,"r"),filesize($filename)));
	
	if ($num > 0){
		$row = mysqli_fetch_array($res);
		$replid = $row['replid'];
		$sql="UPDATE jbsumum.identitas SET foto = '$foto_data' WHERE replid='$replid'";
	} else {
		$nama = "nama=NULL";
		$sql="INSERT INTO jbsumum.identitas SET foto = '$foto_data' ";
	}
	
	QueryDb($sql);
	?>	
	<script language="javascript">
		parent.opener.getfresh();
		window.close();
	</script>
<?php 
} else { 
	$gambar = $_FILES['gambar']['name'];
	$foto1 = $_FILES['gambar'];
	$uploadedfile = $foto1['tmp_name'];
	$uploadedtypefile = $foto1['type'];
	$uploadedsizefile = $foto1['size'];
	if (strlen((string) $uploadedfile)!=0){
		$ua = get_browser ();
		if (( $ua->browser != "Default Browser" ) && ($ua->version != 3)) 
			header('Content-Type: image/jpeg');
		$src = imagecreatefromjpeg($uploadedfile);
		$filename = "../images/logokecil.jpg";
	
		[$width, $height]=getimagesize($uploadedfile);
		if ($width < $height){
			$newheight=125;
			$newwidth=90;
		} else if ($width > $height){
			$newwidth=125;
			$newheight=90;
		}
		
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		imagejpeg($tmp,$filename,100);
		imagedestroy($src);
		imagedestroy($tmp); // NOTE: menghapus file di temp
		
		OpenDb();
		$sql="SELECT * FROM jbsumum.identitas";
		$res=QueryDb($sql);
		$num=@mysqli_num_rows($res);
			
		$handle = fopen($filename, "r");
		$foto_data=addslashes(fread(fopen($filename,"r"),filesize($filename)));
		
		if ($num > 0){
			$row = mysqli_fetch_array($res);
			$replid = $row['replid'];
			$sql="UPDATE jbsumum.identitas SET foto_sem = '$foto_data' WHERE replid='".$_REQUEST['replid']."'";
		} else {
			$nama = "nama=NULL";
			$sql="INSERT INTO jbsumum.identitas SET foto_sem = '$foto_data' ";
		}
		
		QueryDb($sql);
	}
?>
	<script language="javascript">
        document.location.href = "logo.php?gambar=<?=$gambar?>&replid=<?=$_REQUEST['replid']?>&ganti=1";
    </script>
<?php } ?>