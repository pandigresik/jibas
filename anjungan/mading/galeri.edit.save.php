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
require_once("mading.common.func.php");
require_once("galeri.edit.session.php");

if (!isset($_SESSION['allowedit']))
{
    http_response_code(500);
    echo "Session Invalid";
    
    exit();
}

try
{
    $galleryid = $_REQUEST['galleryid'];
    
    OpenDb();
    $sql = "SELECT IF(nis IS NULL, 'P', 'S') AS ownertype
              FROM jbsvcr.gallery
             WHERE replid = '".$galleryid."'";
    $res = QueryDbEx($sql);
    if (mysqli_num_rows($res) == 0)
    {
        CloseDb();
        
        http_response_code(500);
        echo "Tidak ditemukan data galeri!";
        
        exit();
    }
    $row = mysqli_fetch_row($res);
    $ownertype = $row[0];
    
    // Validate File Size
    $maxFileSize = ($ownertype == "S") ? $studentMaxFileSize : $employeeMaxFileSize;
    $maxFileSizeByte = $maxFileSize * 1024 * 1024;
    $isFileSizeValid = true;
    $lastFileNameCheck = "";
    
    $newcover = (int)$_REQUEST['newcover'];
    if ($newcover == 1)
    {
        $file = $_FILES["newcover_file"];
        $size = $file['size'];    
        $lastFileNameCheck = $file['name'];
        $isFileSizeValid = $size <= $maxFileSizeByte;
    }
    
    $ngambar = (int)$_REQUEST['ngambar'];
    for($i = 1; $isFileSizeValid && $i <= $ngambar; $i++)
    {
        $doc = "gambar_file_$i";
        $file = $_FILES[$doc];
        $size = $file['size'];
        
        $lastFileNameCheck = $file['name'];
        $isFileSizeValid = $size <= $maxFileSizeByte;
    }
    
    if (!$isFileSizeValid)
    {
        CloseDb();
        
        $info = ($ownertype == "S") ? "siswa" : "pegawai";
        $info = "Untuk $info, ukuran file $lastFileNameCheck tidak boleh melebihi $maxFileSize MB";
        
        http_response_code(500);
        echo $info;
        
        exit();
    }
    
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
    
    $text = trim((string) $_REQUEST['judul']);
    $judul = $text;
    $fjudul = FormattedText($text);
    
    $text = trim((string) $_REQUEST['pesan']);
    $pesan = RecodeNewLine($text);
    $fpesan = FormattedText($text);
    $fprevpesan = FormattedPreviewText($text, $previewTextLength);
    
    $sql = "UPDATE jbsvcr.gallery
               SET judul = '$judul', fjudul = '$fjudul', 
                   keterangan = '$pesan', fketerangan = '$fpesan', fprevketerangan = '$fprevpesan',
                   lastactive = NOW(), lastread = NOW()
             WHERE replid = '".$galleryid."'";
    echo "$sql<br>";
    QueryDbEx($sql);
    
    $newcover = (int)$_REQUEST['newcover'];
    if ($newcover == 1)
    {
        $rnd = random_int(10000, 99999);
        
        $file = $_FILES["newcover_file"];
        $name = $file['name'];
        $name = $galleryid . "_" . $rnd . "_" . str_replace(" ", "_", (string) $name);
        $type = $file['type'];
        $size = $file['size'];
        $location = "anjungan/galeri/" . date('Y');
        $dest = "$FILESHARE_UPLOAD_DIR/$location/$name";
        
        $text = trim((string) $_REQUEST['newcover_info']);
        $info = $text;
        $finfo = FormattedText($text);
        
        echo "MOVE " . $file['tmp_name'] . " to $dest<br>";
        //move_uploaded_file($file['tmp_name'], $dest);
        ImageResizer::ResizeImage($file, $maxPictWidth, $maxPictHeight, $maxPictQuality, $dest);
        
        $imgsize = getimagesize($dest);
        $width = $imgsize[0];
        $height = $imgsize[1];
        
        $sql = "UPDATE jbsvcr.galleryfile
                   SET filename = '$name', filesize = '$size',
                       filetype = '$type', fileinfo = '$info', ffileinfo = '$finfo', location = '$location',
                       width = '$width', height = '$height'
                 WHERE galleryid = '$galleryid'
                   AND iscover = 1";
                       
        echo "$sql<br>";
        QueryDbEx($sql);
    }
    else
    {
        $text = trim((string) $_REQUEST['cover_info']);
        $info = $text;
        $finfo = FormattedText($text);
        
        $sql = "UPDATE jbsvcr.galleryfile
                   SET fileinfo = '$info', ffileinfo = '$finfo'
                 WHERE galleryid = '$galleryid'
                   AND iscover = 1";
        echo "$sql<br>";
        QueryDbEx($sql);
    }
    
    $ngambar = (int)$_REQUEST['edit_ngambar'];
    for($i = 1; $i <= $ngambar; $i++)
    {
        $parm = "edit_gambar_replid_$i";
        $replid = $_REQUEST[$parm];
        
        $parm = "edit_gambar_delete_$i";
        $isdel = $_REQUEST[$parm];
        
        $parm = "edit_gambar_info_$i";
        $text = $_REQUEST[$parm];
        $info = $text;
        $finfo = FormattedText($text);
        
        if ($isdel == 1)
        {
            $sql = "SELECT location, filename
                      FROM jbsvcr.galleryfile
                     WHERE replid = '".$replid."'";
            $res = QueryDbEx($sql);
            if (mysqli_num_rows($res) > 0)
            {
                $row = mysqli_fetch_row($res);
                
                $location = $row[0];
                $filename = $row[1];
                
                $location = "$FILESHARE_UPLOAD_DIR/$location/$filename";
                
                echo "DELETE FILE $location<br>";
                if (file_exists($location))
                    unlink($location);
                
                $sql = "DELETE FROM jbsvcr.galleryfile
                         WHERE replid = '".$replid."'";
                echo "$sql<br>";
                QueryDbEx($sql);
            }
        }
        else
        {
            $sql = "UPDATE jbsvcr.galleryfile
                       SET fileinfo = '$info', ffileinfo = '$finfo'
                     WHERE replid = '".$replid."'";
            echo "$sql<br>";
            QueryDbEx($sql);
        }
    }
    
    $ngambar = (int)$_REQUEST['ngambar'];
    for($i = 1; $i <= $ngambar; $i++)
    {
        $doc = "gambar_file_$i";
        $file = $_FILES[$doc];
        
        $info = "gambar_info_$i";
        $text = trim((string) $_REQUEST[$info]);
        $info = $text;
        $finfo = FormattedText($text);
        
        $rnd = random_int(10000, 99999);
        
        $name = $file['name'];
        $name = $galleryid . "_" . $rnd . "_" . str_replace(" ", "_", (string) $name);
        $type = $file['type'];
        $size = $file['size'];
        $location = "anjungan/galeri/" . date('Y');
        $dest = "$FILESHARE_UPLOAD_DIR/$location/$name";
        
        echo "MOVE " . $file['tmp_name'] . " to $dest<br>";
        //move_uploaded_file($file['tmp_name'], $dest);
        ImageResizer::ResizeImage($file, $maxPictWidth, $maxPictHeight, $maxPictQuality, $dest);
        
        $imgsize = getimagesize($dest);
        $width = $imgsize[0];
        $height = $imgsize[1];
        
        $sql = "INSERT INTO jbsvcr.galleryfile
                   SET galleryid = '$galleryid', filename = '$name', filesize = '$size',
                       filetype = '$type', fileinfo = '$info', ffileinfo = '$finfo', location = '$location',
                       width = '$width', height = '".$height."'";
        echo "$sql<br>";
        QueryDbEx($sql);
    }
    
    CommitTrans();
    //RollbackTrans();
    CloseDb();
    
    //clear session
    unset($_SESSION['allowedit']);
    
    http_response_code(200);
    echo "OK";
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


/*
galleryid = 15
judul = Judul
pesan = Pesan
newcover = 1
newcover_info = anggita
edit_gambar_replid_1 = 49
edit_gambar_delete_1 = 0
edit_gambar_info_1 = 1
edit_gambar_replid_2 = 50
edit_gambar_delete_2 = 0
edit_gambar_info_2 = 2
edit_gambar_replid_3 = 51
edit_gambar_delete_3 = 0
edit_gambar_info_3 = 3
edit_gambar_replid_4 = 52
edit_gambar_delete_4 = 0
edit_gambar_info_4 = 4
edit_gambar_replid_5 = 53
edit_gambar_delete_5 = 0
edit_gambar_info_5 = 5
edit_gambar_replid_6 = 54
edit_gambar_delete_6 = 0
edit_gambar_info_6 = 6
edit_gambar_replid_7 = 55
edit_gambar_delete_7 = 0
edit_gambar_info_7 = 7
edit_gambar_replid_8 = 56
edit_gambar_delete_8 = 0
edit_gambar_info_8 = 8
edit_gambar_replid_9 = 57
edit_gambar_delete_9 = 0
edit_gambar_info_9 = 9
edit_ngambar = 9
gambar_info_1 = baru1
gambar_info_2 = baru2
gambar_info_3 = baru3
ngambar = 3
N FILES = 4
FILE Array
name = anggita.jpg
type = image/jpeg
tmp_name = /tmp/phpIZGj1G
error = 0
size = 83328
FILE Array
name = 132120_img_4945.jpg
type = image/jpeg
tmp_name = /tmp/phpmBKu4x
error = 0
size = 25216
FILE Array
name = 132138_img_4954.jpg
type = image/jpeg
tmp_name = /tmp/phpbIV17o
error = 0
size = 17984
FILE Array
name = asian-girl-desktop-background-334046.jpg
type = image/jpeg
tmp_name = /tmp/phpiKFWbg
error = 0
size = 784710

 */
?>