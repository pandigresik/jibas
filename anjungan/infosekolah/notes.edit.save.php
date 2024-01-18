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
require_once("infosekolah.common.func.php");
require_once("notes.edit.session.php");

if(!isset($_SESSION['allowedit']))
{
    http_response_code(500);
    
    echo "Invalid Session";
    exit();
}

/*
judul = Ini Dia CEO Paling Seksi di 2013
kepada = Siswa SMA
pesan = Menjalankan bisnis memang kadang membuat stres dan melelahkan, namun di tengah kepadatan menjalankan bisnis banyak juga beberapa individu yang masih terlihat menakjubkan.
tautan = http://finance.detik.com/read/2013/10/14/103409/2385815/4/ini-dia-ceo-paling-seksi-di-2013?f9911023
edit_gambar_replid_1 = 6
edit_gambar_delete_1 = 0
edit_gambar_info_1 = aaaaaaaa
edit_gambar_replid_2 = 7
edit_gambar_delete_2 = 0
edit_gambar_info_2 = bbbbbbbb
edit_gambar_replid_3 = 8
edit_gambar_delete_3 = 0
edit_gambar_info_3 = cccccccccc
edit_ngambar = 3
gambar_info_1 = dddddddd
ngambar = 1
edit_file_replid_1 = 9
edit_file_delete_1 = 0
edit_file_info_1 = Peraturan presiden (perpres) tentang koordinasi kebijakan antar-pemerintah daerah
edit_file_replid_2 = 10
edit_file_delete_2 = 0
edit_file_info_2 = Segera terbit dalam hitungan minggu
edit_file_replid_3 = 11
edit_file_delete_3 = 0
edit_file_info_3 = Pemerintah Provinsi DKI Jakarta tak bisa sendirian mengatasi persoalan kemacetan Jakarta
edit_nfile = 3
file_info_1 = pi
nfile = 1
N FILES = 2
FILE Array
name = Blue hills.jpg
type = image/jpeg
tmp_name = /tmp/phpz7OzF5
error = 0
size = 28521
FILE Array
name = 22-per-7-sebagai-Aproksimai-Pi.pdf
type = application/octet-stream
tmp_name = /tmp/phpyVpwc0
error = 0
size = 668305
 */

try
{
    $notesid = $_REQUEST['notesid'];
    
    OpenDb();
    $sql = "SELECT IF(nis IS NULL, 'P', 'S') AS ownertype
              FROM jbsvcr.notes
             WHERE replid = '".$notesid."'";
    $res = QueryDbEx($sql);
    if (mysqli_num_rows($res) == 0)
    {
        CloseDb();
        
        http_response_code(500);
        echo "Tidak ditemukan data Notes!";
        
        exit();
    }
    $row = mysqli_fetch_row($res);
    $ownertype = $row[0];
    
    // Validate File Size
    $maxFileSize = ($ownertype == "S") ? $studentMaxFileSize : $employeeMaxFileSize;
    $maxFileSizeByte = $maxFileSize * 1024 * 1024;
    
    $ngambar = (int)$_REQUEST['ngambar'];
    $isFileSizeValid = true;
    $lastFileNameCheck = "";
    for($i = 1; $isFileSizeValid && $i <= $ngambar; $i++)
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
        
    $uploadPath = "$uploadPath/notes";
    if (!file_exists($uploadPath))
        mkdir($uploadPath, 0755);

    $uploadPath = "$uploadPath/" . date('Y');
    if (!file_exists($uploadPath))
        mkdir($uploadPath, 0755);
    
    
    $text = trim((string) $_REQUEST['judul']);
    $judul = $text;
    $fjudul = FormattedText($text);
    
    $kepada = SafeInput($_REQUEST['kepada']);
    
    $text = trim((string) $_REQUEST['pesan']);
    $pesan = RecodeNewLine($text);
    $fpesan = FormattedText($text);
    $fprevpesan = FormattedPreviewText($text, $previewTextLength);
    
    $tautan = trim((string) $_REQUEST['tautan']);
    
    $sql = "UPDATE jbsvcr.notes
               SET judul = '$judul', fjudul = '$fjudul', kepada = '$kepada',
                   pesan = '$pesan', fpesan = '$fpesan', fprevpesan = '$fprevpesan',
                   tautan = '$tautan', lastactive = NOW(), lastread = NOW()
             WHERE replid = '".$notesid."'";
    echo "$sql<br>";
    QueryDbEx($sql);
    
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
                      FROM jbsvcr.notesfile
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
                
                $sql = "DELETE FROM jbsvcr.notesfile
                         WHERE replid = '".$replid."'";
                echo "$sql<br>";
                QueryDbEx($sql);
            }
        }
        else
        {
            $sql = "UPDATE jbsvcr.notesfile
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
        $name = $notesid . "_" . $rnd . "_" . str_replace(" ", "_", (string) $name);
        $type = $file['type'];
        $size = $file['size'];
        $location = "anjungan/notes/" . date('Y');
        $dest = "$FILESHARE_UPLOAD_DIR/$location/$name";
        
        echo "MOVE " . $file['tmp_name'] . " to $dest<br>";
        //move_uploaded_file($file['tmp_name'], $dest);
        ImageResizer::ResizeImage($file, $maxPictWidth, $maxPictHeight, $maxPictQuality, $dest);
        
        $sql = "INSERT INTO jbsvcr.notesfile
                   SET notesid = $notesid, filecate = 'pict', filename = '$name', filesize = '$size',
                       filetype = '$type', fileinfo = '$info', ffileinfo = '$finfo', location = '".$location."'";
        echo "$sql<br>";
        QueryDbEx($sql);
    }
    
    $nfile = (int)$_REQUEST['edit_nfile'];
    for($i = 1; $i <= $nfile; $i++)
    {
        $parm = "edit_file_replid_$i";
        $replid = $_REQUEST[$parm];
        
        $parm = "edit_file_delete_$i";
        $isdel = $_REQUEST[$parm];
        
        $parm = "edit_file_info_$i";
        $text = trim((string) $_REQUEST[$parm]);
        $info = $text;
        $finfo = FormattedText($info);
                
        if ($isdel == 1)
        {
            $sql = "SELECT location, filename
                      FROM jbsvcr.notesfile
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
                
                $sql = "DELETE FROM jbsvcr.notesfile
                         WHERE replid = '".$replid."'";
                echo "$sql<br>";
                QueryDbEx($sql);
            }
        }
        else
        {
            $sql = "UPDATE jbsvcr.notesfile
                       SET fileinfo = '$info', ffileinfo = '$finfo'
                     WHERE replid = '".$replid."'";
            echo "$sql<br>";
            QueryDbEx($sql);
        }
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
        
        echo "MOVE " . $file['tmp_name'] . " to $dest<br>";
        move_uploaded_file($file['tmp_name'], $dest);
        
        $sql = "INSERT INTO jbsvcr.notesfile
                   SET notesid = $notesid, filecate = 'doc', filename = '$name', filesize = '$size',
                       filetype = '$type', fileinfo = '$info', ffileinfo = '$finfo', location = '".$location."'";
        echo "$sql<br>";
        QueryDbEx($sql);
    }

    CommitTrans();
    CloseDb();
    
    // clear session
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