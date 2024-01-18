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
require_once("video.input.config.php");
require_once("video.input.func.php");
require_once("login.func.php");
require_once("mading.common.func.php");

/*

 */

try
{
    OpenDb();
    
    $dept = $_REQUEST['departemen'];
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    
    // -- Validate Login --
    $info = "";
    $logintype = "";
    if (!ValidateLogin($dept, $login, $password, $logintype, $info))
    {
        CloseDb();
        
        http_response_code(500);
        echo $info;
        
        exit();
    }
    
    BeginTrans();

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
    
    // Validate File Size
    $maxFileSize = ($logintype == "S") ? $studentMaxFileSize : $employeeMaxFileSize;
    $maxFileSizeByte = $maxFileSize * 1024 * 1024;
    
    $file = $_FILES["video_file"];
    $filename = $file['name'];
    $filesize = $file['size'];
    if ($filesize > $maxFileSizeByte)
    {
        CloseDb();
        
        $info = ($logintype == "S") ? "siswa" : "pegawai";
        $info = "Untuk $info, ukuran file video $filename tidak boleh melebihi $maxFileSize MB";
        
        http_response_code(500);
        echo $info;
        
        exit();
    }
    
    // == Prepare Folder
    $uploadPath = "$FILESHARE_UPLOAD_DIR/anjungan";
    if (!file_exists($uploadPath))
        mkdir($uploadPath, 0755);
        
    $uploadPath = "$uploadPath/video";
    if (!file_exists($uploadPath))
        mkdir($uploadPath, 0755);

    $uploadPath = "$uploadPath/" . date('Y');
    if (!file_exists($uploadPath))
        mkdir($uploadPath, 0755);
    
    // == Save Video
    $text = trim((string) $_REQUEST['judul']);
    $judul = $text;
    $fjudul = FormattedText($text);
    
    $text = trim((string) $_REQUEST['keterangan']);
    $keterangan = RecodeNewLine($text);
    $fketerangan = FormattedText($text);
    $fprevketerangan = FormattedPreviewText($text, $previewTextLength);
    
    $text = trim((string) $_REQUEST["video_info"]);
    $fileinfo = $text;
    $ffileinfo = FormattedText($text);
    
    $filetype = $file['type'];
    $location = "anjungan/video/" . date('Y');
    
    $sql = "INSERT INTO jbsvcr.video
               SET departemen = '$dept', nis = $nis, nip = $nip, kategori = 'mading',
                   judul = '$judul', fjudul = '$fjudul', keterangan = '$keterangan',
                   fprevketerangan = '$fprevketerangan', fketerangan = '$fketerangan',
                   filename = 'VIDEO', filesize = '$filesize', filetype = '$filetype',
                   fileinfo = '$fileinfo', ffileinfo = '$ffileinfo', location = '$location',
                   tanggal = NOW(), lastactive = NOW(), lastread = NOW()";
    echo "$sql<br>";
    QueryDbEx($sql);
    
    $sql = "SELECT LAST_INSERT_ID()";
    echo "$sql<br>";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $videoid = $row[0];
    
    $rnd = random_int(10000, 99999);
    $name = $file['name'];
    $vidname = $videoid . "_" . $rnd . "_" . str_replace(" ", "_", (string) $name);
    $dest = "$FILESHARE_UPLOAD_DIR/$location/$vidname";
    
    move_uploaded_file($file['tmp_name'], $dest);
    
    $sql = "UPDATE jbsvcr.video
               SET filename = '$vidname'
             WHERE replid = '".$videoid."'";
    echo "$sql<br>";
    QueryDbEx($sql);         
    
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