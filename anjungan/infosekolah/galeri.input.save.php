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
require_once("../include/config.php");
require_once("../include/common.php");
require_once("../include/compatibility.php");
require_once("../include/db_functions.php");
require_once("../include/compatibility.php");
require_once("../library/imageresizer.class.php");
require_once("galeri.input.config.php");
require_once("galeri.input.func.php");
require_once("login.func.php");
require_once("infosekolah.common.func.php");

try
{
    OpenDb();
    
    // ** Validate Login **    
    $dept = $_REQUEST['departemen'];
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    
    $info = "";
    $logintype = "";
    if (!ValidateEmployeeLogin($dept, $login, $password, $logintype, $info))
    {
        CloseDb();
        
        http_response_code(500);
        echo $info;
        
        exit();
    }
    // ** 
    
    // ** Validate Image File Size **
    $maxFileSize = ($logintype == "S") ? $studentMaxFileSize : $employeeMaxFileSize;
    $maxFileSizeByte = $maxFileSize * 1024 * 1024;
    
    $isFileSizeValid = true;
    $lastFileNameCheck = "";
    
    // -- Check Cover Size
    $file = $_FILES["cover_file"];
    $size = $file['size'];    
    $lastFileNameCheck = $file['name'];
    $isFileSizeValid = $size <= $maxFileSizeByte;
        
    // -- Check Image Size
    $ngambar = (int)$_REQUEST['ngambar'];
    for($i = 1; $isFileSizeValid && $i <= $ngambar; $i++)
    {
        $parm = "gambar_file_$i";
        $file = $_FILES[$parm];
        $size = $file['size'];
        
        $lastFileNameCheck = $file['name'];
        $isFileSizeValid = $size <= $maxFileSizeByte;
    }
    
    if (!$isFileSizeValid)
    {
        CloseDb();
        
        $info = ($logintype == "S") ? "siswa" : "pegawai";
        $info = "Untuk $info, ukuran file $lastFileNameCheck tidak boleh melebihi $maxFileSize MB";
        
        http_response_code(500);
        echo $info;
        
        exit();
    }
    // **
    
    BeginTrans();

    $uploadPath = "$FILESHARE_UPLOAD_DIR/anjungan";
    if (!file_exists($uploadPath))
        mkdir($uploadPath, 0755);
        
    $uploadPath = "$uploadPath/galeri";
    if (!file_exists($uploadPath))
        mkdir($uploadPath, 0755);

    $uploadPath = "$uploadPath/" . date('Y');
    if (!file_exists($uploadPath))
        mkdir($uploadPath, 0755);
    
    if ($logintype == "S")
    {
        $nis = "'$login'";
        $nip = "NULL";
    }
    else
    {
        $nis = "NULL";
        $nip = "'$login'";
    }
    
    $text = trim((string) $_REQUEST['judul']);
    $judul = $text;
    $fjudul = FormattedText($text);
    
    $text = trim((string) $_REQUEST['keterangan']);
    $keterangan = RecodeNewLine($text);
    $fketerangan = FormattedText($text);
    $fprevketerangan = FormattedPreviewText($text, $previewTextLength);
    
    $sql = "INSERT INTO jbsvcr.gallery
               SET departemen = '$dept', nis = $nis, nip = $nip, kategori = 'ifse',
                   judul = '$judul', fjudul = '$fjudul', keterangan = '$keterangan',
                   fprevketerangan = '$fprevketerangan', fketerangan = '$fketerangan',
                   tanggal = NOW(), lastactive = NOW(), lastread = NOW()";
    echo "$sql<br>";
    QueryDbEx($sql);
    
    $sql = "SELECT LAST_INSERT_ID()";
    echo "$sql<br>";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $galleryid = $row[0];
    
    // == Save Cover
    $file = $_FILES["cover_file"];
    $text = trim((string) $_REQUEST["cover_info"]);
    $fileinfo = $text;
    $ffileinfo = FormattedText($text);
    
    $rnd = random_int(10000, 99999);
    $name = $file['name'];
    $pict = $galleryid . "_" . $rnd . "_" . str_replace(" ", "_", (string) $name);
    $type = $file['type'];
    $size = $file['size'];
    $location = "anjungan/galeri/" . date('Y');
    $dest = "$FILESHARE_UPLOAD_DIR/$location/$pict";
    
    ImageResizer::ResizeImage($file, $maxPictWidth, $maxPictHeight, $maxPictQuality, $dest);
    
    $imgsize = getimagesize($dest);
    $width = $imgsize[0];
    $height = $imgsize[1];
        
    $sql = "INSERT INTO jbsvcr.galleryfile
               SET galleryid = $galleryid, filename = '$pict', filesize = '$size',
                   filetype = '$type', fileinfo = '$fileinfo', ffileinfo = '$ffileinfo', location = '$location',
                   iscover = 1, width = '$width', height = '".$height."'";
    echo "$sql<br>";
    QueryDbEx($sql);

    // == Save Pictures ==
    $ngambar = (int)$_REQUEST['ngambar'];
    for($i = 1; $i <= $ngambar; $i++)
    {
        $doc = "gambar_file_$i";
        $file = $_FILES[$doc];
        
        $reqid = "gambar_info_$i";
        $text = trim((string) $_REQUEST[$reqid]);
        $gbrinfo = $text;
        $fgbrinfo = FormattedText($text);
        
        $rnd = random_int(10000, 99999);
        
        $name = $file['name'];
        $name = $galleryid . "_" . $rnd . "_" . str_replace(" ", "_", (string) $name);
        $type = $file['type'];
        $size = $file['size'];
        $location = "anjungan/galeri/" . date('Y');
        $dest = "$FILESHARE_UPLOAD_DIR/$location/$name";
        
        //move_uploaded_file($file['tmp_name'], $dest);
        ImageResizer::ResizeImage($file, $maxPictWidth, $maxPictHeight, $maxPictQuality, $dest);
        
        $imgsize = getimagesize($dest);
        $width = $imgsize[0];
        $height = $imgsize[1];
        
        $sql = "INSERT INTO jbsvcr.galleryfile
                   SET galleryid = $galleryid, filename = '$name', filesize = '$size',
                       filetype = '$type', fileinfo = '$gbrinfo', ffileinfo = '$fgbrinfo', location = '$location',
                       iscover = 0, width = '$width', height = '".$height."'";
        echo "$sql<br>";
        QueryDbEx($sql);
    }
    
    CommitTrans();
    CloseDb();
    
    http_response_code(200);
}
catch(DbException $dbe)
{
    RollbackTrans();
    CloseDb();
    
    http_response_code(500);
    echo "<font style='font-family: Tahoma; font-size: 20px; color: red;'>";
    echo "Oops, something has gone wrong";
    echo "</font>";
    echo "<br><br><br>";
    echo $dbe->getMessage();
}
catch(Exception $e)
{
    RollbackTrans();
    CloseDb();
    
    http_response_code(500);
    echo "<font style='font-family: Tahoma; font-size: 20px; color: red;'>";
    echo "Oops, something has gone wrong";
    echo "</font>";
    echo "<br><br><br>";
    echo $e->getMessage();
}

/*
 *judul =
kepada =
pesan =
gambar_info1 =
gambar_info2 =
gambar_info3 =
ngambar = 3
file_info1 =
file_info2 =
nfile = 2
N FILES = 5
FILE Array
name = 13870_461241853930879_1819101320_n.jpg
type = image/jpeg
tmp_name = /tmp/phpgnIVYs
error = 0
size = 62308
FILE Array
name = 484899_4376413968489_1982672841_n.jpg
type = image/jpeg
tmp_name = /tmp/phpizom59
error = 0
size = 22001
FILE Array
name = ahmadinejadpresstvdlm.jpg
type = image/jpeg
tmp_name = /tmp/phpIubacR
error = 0
size = 34131
FILE Array
name = death.txt
type = text/plain
tmp_name = /tmp/phpaLmujy
error = 0
size = 171
FILE Array
name = Akademik.pdf
type = application/pdf
tmp_name = /tmp/phpcKqPqf
error = 0
size = 4251394
 */

/*
echo $uploadPath . "<br>";    

foreach($_REQUEST as $key => $value)
{
    echo "$key = $value<br>";
}

echo "N FILES = " . count($_FILES) . "<br>";

foreach($_FILES as $file)
{
    echo "FILE $file<br>";
    foreach($file as $key => $value)
    {
        echo "  $key = $value<br>";
    }
}
*/
?>