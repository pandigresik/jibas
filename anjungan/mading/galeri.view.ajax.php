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
require_once("galeri.view.func.php");
require_once("galeri.view.config.php");
require_once("mading.common.func.php");
require_once("login.func.php");

$op = $_REQUEST['op'];
if ($op == "reloadcommentbox")
{
    $galleryid = $_REQUEST['galleryid'];
    ShowCommentBox($galleryid);
}
elseif ($op == "showprevcomment")
{
    $galleryid = $_REQUEST['galleryid'];
    
    try
    {
        OpenDb();
        ShowPrevComment($galleryid);
        CloseDb();
        
        http_response_code(200);
    }
    catch(DbException $dbe)
    {
        CloseDb();
        
        http_response_code(500);
        echo $dbe->getMessage();
    }
    catch(Exception $e)
    {
        CloseDb();
        
        http_response_code(500);
        echo $e->getMessage();
    }
}
elseif ($op == "shownewcomment")
{
    $galleryid = $_REQUEST['galleryid'];
    $maxcommentid = $_REQUEST['maxcommentid'];
    
    try
    {
        OpenDb();
        ShowComment($galleryid, $maxcommentid);
        CloseDb();
        
        http_response_code(200);
    }
    catch(DbException $dbe)
    {
        CloseDb();
        
        http_response_code(500);
        echo $dbe->getMessage();
    }
    catch(Exception $e)
    {
        CloseDb();
        
        http_response_code(500);
        echo $e->getMessage();
    }
}
elseif ($op == "getmaxcommentid")
{
    $galleryid = $_REQUEST['galleryid'];
    
    try
    {
        OpenDb();
        $maxcommentid = GetMaxCommentId($galleryid);
        CloseDb();
    
        http_response_code(200);
        echo $maxcommentid;
    }
    catch(DbException $dbe)
    {
        CloseDb();
        
        http_response_code(500);
        echo $dbe->getMessage();
    }
    catch(Exception $e)
    {
        CloseDb();
        
        http_response_code(500);
        echo $e->getMessage();
    }
}
elseif ($op == "deletecomment")
{
    $replid = $_REQUEST['replid'];
    $rowid = $_REQUEST['rowid'];
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    
    try
    {
        OpenDb();
        $info = "";
        $type = "";
        if (!ValidateDelCmtLogin($login, $password, $type, $info))
        {
            http_response_code(500);
            echo $info;
        }
        else
        {
            if (!ValidateCommentOwner($replid, $login))
            {
                http_response_code(500);
                echo "Anda tidak berhak menghapus komentar ini!";    
            }
            else
            {
                DeleteComment($replid);
                
                http_response_code(200);
                echo "OK";    
            }
        }
        CloseDb();
    }
    catch(DbException $dbe)
    {
        CloseDb();
        
        http_response_code(500);
        echo $dbe->getMessage();
    }
    catch(Exception $e)
    {
        CloseDb();
        
        http_response_code(500);
        echo $e->getMessage();
    }
}
elseif ($op == "editgallery")
{
    $galleryid = $_REQUEST['galleryid'];
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    
    OpenDb();
    $info = "";
    $type = "";
    if (!ValidateEditGalleryLogin($login, $password, $type, $info))
    {
        http_response_code(500);
        echo $info;
    }
    else
    {
        if (!ValidateGalleryOwner($galleryid, $login))
        {
            http_response_code(500);
            echo "Anda tidak berhak mengubah galeri ini!";    
        }
        else
        {
            require_once("galeri.edit.session.php");
            $_SESSION['allowedit'] = true;
            
            http_response_code(200);
            echo "OK";    
        }
    }
    CloseDb();
}
elseif ($op == "deletegallery")
{
    $galleryid = $_REQUEST['galleryid'];
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    
    OpenDb();
    BeginTrans();
    
    try
    {
        $info = "";
        $type = "";
        if (!ValidateDeleteGalleryLogin($login, $password, $type, $info))
        {
            http_response_code(500);
            echo $info;
        }
        else
        {
            if (!ValidateGalleryOwner($galleryid, $login))
            {
                http_response_code(500);
                echo "Anda tidak berhak menghapus galeri ini!";    
            }
            else
            {
                DeleteGallery($galleryid);
                CommitTrans();
                
                http_response_code(200);
                echo "OK";    
            }
        }
        
        CloseDb();
    }
    catch(DbException $dbe)
    {
        RollbackTrans();
        CloseDb();
        
        http_response_code(500);
        echo $dbe->getMessage();
    }
    catch(Exception $e)
    {
        RollbackTrans();
        CloseDb();
        
        http_response_code(500);
        echo $e->getMessage();
    }
}
?> 