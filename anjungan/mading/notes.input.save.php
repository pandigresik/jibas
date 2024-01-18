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
require_once("notes.input.config.php");
require_once("notes.input.func.php");
require_once("mading.common.func.php");

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

try
{
    OpenDb();
    
    $dept = $_REQUEST['departemen'];
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    
    // -- Validate Login --
    $info = "";
    $logintype = "";
    if (!ValidateInputNotesLogin($dept, $login, $password, $logintype, $info))
    {
        CloseDb();
        
        http_response_code(500);
        echo $info;
        
        exit();
    }
    
    // Validate File Size ---
    $maxFileSize = ($logintype == "S") ? $studentMaxFileSize : $employeeMaxFileSize;
    $maxFileSizeByte = $maxFileSize * 1024 * 1024;
    
    $nfile = (int)$_REQUEST['ngambar'];
    $isFileSizeValid = true;
    $lastFileNameCheck = "";
    for($i = 1; $isFileSizeValid && $i <= $nfile; $i++)
    {
        $doc = "gambar_file_$i";
        $file = $_FILES[$doc];
        $size = $file['size'];
        
        $lastFileNameCheck = $file['name'];
        $isFileSizeValid = $size <= $maxFileSizeByte;
    }
    
    $nfile = (int)$_REQUEST['nfile'];
    for($i = 1; $isFileSizeValid && $i <= $nfile; $i++)
    {
        $doc = "file_file_$i";
        $file = $_FILES[$doc];
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
        
    BeginTrans();

    $uploadPath = "$FILESHARE_UPLOAD_DIR/anjungan";
    if (!file_exists($uploadPath))
        mkdir($uploadPath, 0755);
        
    $uploadPath = "$uploadPath/notes";
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
    
    $kepada = SafeInput($_REQUEST['kepada']);
    
    $text = trim((string) $_REQUEST['pesan']);
    $pesan = RecodeNewLine($text);
    $fpesan = FormattedText($text);
    $fprevpesan = FormattedPreviewText($text, $previewTextLength);
    
    $tautan = trim((string) $_REQUEST['tautan']);
    
    $sql = "INSERT INTO jbsvcr.notes
               SET departemen = '$dept', nis = $nis, nip = $nip, kategori = 'mading',
                   judul = '$judul', fjudul = '$fjudul', kepada = '$kepada', pesan = '$pesan',
                   fprevpesan = '$fprevpesan', fpesan = '$fpesan',
                   tautan = '$tautan', tanggal = NOW(), lastactive = NOW(), lastread = NOW()";
    echo "$sql<br>";
    QueryDbEx($sql);
    
    $sql = "SELECT LAST_INSERT_ID()";
    echo "$sql<br>";
    $res = QueryDbEx($sql);
    $row = mysqli_fetch_row($res);
    $notesid = $row[0];
    
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
        $name = $notesid . "_" . $rnd . "_" . str_replace(" ", "_", (string) $name);
        $type = $file['type'];
        $size = $file['size'];
        $location = "anjungan/notes/" . date('Y');
        $dest = "$FILESHARE_UPLOAD_DIR/$location/$name";
        
        //move_uploaded_file($file['tmp_name'], $dest);
        ImageResizer::ResizeImage($file, $maxPictWidth, $maxPictHeight, $maxPictQuality, $dest);
        
        $sql = "INSERT INTO jbsvcr.notesfile
                   SET notesid = $notesid, filecate = 'pict', filename = '$name', filesize = '$size',
                       filetype = '$type', fileinfo = '$info', ffileinfo = '$finfo', location = '".$location."'";
        echo "$sql<br>";
        QueryDbEx($sql);
    }
    
    $nfile = (int)$_REQUEST['nfile'];
    for($i = 1; $i <= $nfile; $i++)
    {
        $doc = "file_file_$i";
        $file = $_FILES[$doc];
        
        $info = "file_info_$i";
        $text = trim((string) $_REQUEST[$info]);
        $info = $text;
        $finfo = FormattedText($text);
        
        $rnd = random_int(10000, 99999);
        
        $name = $file['name'];
        $name = $notesid . "_" . $rnd . "_" . str_replace(" ", "_", (string) $name);
        $type = $file['type'];
        $size = $file['size'];
        $location = "anjungan/notes/" . date('Y');
        $dest = "$FILESHARE_UPLOAD_DIR/$location/$name";
        
        move_uploaded_file($file['tmp_name'], $dest);
        
        $sql = "INSERT INTO jbsvcr.notesfile
                   SET notesid = $notesid, filecate = 'doc', filename = '$name', filesize = '$size',
                       filetype = '$type', fileinfo = '$info', ffileinfo = '$finfo', location = '".$location."'";
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