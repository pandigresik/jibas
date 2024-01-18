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
require_once("video.view.func.php");
require_once("login.func.php");
require_once("infosekolah.common.func.php");

$dept = $_REQUEST['dept'];
$login = $_REQUEST['login'];
$password = $_REQUEST['password'];
    
try
{
    OpenDb();
    
    $info = "";
    if (!ValidateLogin($dept, $login, $password, $type, $info))
    {
        CloseDb();
        
        http_response_code(500);
        echo $info;
        
        exit();
    }
    
    BeginTrans();
    
    $videoid = $_REQUEST['videoid'];
    $ncomment = $_REQUEST['ncomment'];
    for($i = 1; $i <= $ncomment; $i++)
    {
        $id = "comment_$i";
        $text = trim((string) $_REQUEST[$id]);
        $comment = RecodeNewLine($text);
        $fcomment = FormattedText($text);
        
        if ($type == "S")
            $sql = "INSERT INTO jbsvcr.videocomment
                       SET nis = '$login', nip = NULL, ";
        else
            $sql = "INSERT INTO jbsvcr.videocomment
                       SET nis = NULL, nip = '$login', ";
                       
        $sql .= "videoid = '$videoid', tanggal = NOW(), komen = '$comment', fkomen = '".$fcomment."'";
        
        QueryDbEx($sql);
    }
    
    $sql = "UPDATE jbsvcr.video
               SET lastactive = NOW()
             WHERE replid = '".$videoid."'";
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
    echo $sql . "<br>" . $dbe->getMessage();
}
catch(Exception $e)
{
    RollbackTrans();
    CloseDb();

    http_response_code(500);
    echo $e->getMessage();
}    

?> 