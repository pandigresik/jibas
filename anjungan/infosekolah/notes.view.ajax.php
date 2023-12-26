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
require_once("notes.view.func.php");
require_once("notes.view.config.php");
require_once("infosekolah.common.func.php");
require_once("login.func.php");

$op = $_REQUEST['op'];
if ($op == "reloadcommentbox")
{
    $notesid = $_REQUEST['notesid'];
    ShowCommentBox($notesid);
}
elseif ($op == "showprevcomment")
{
    $notesid = $_REQUEST['notesid'];
    
    OpenDb();
    ShowPrevComment($notesid);
    CloseDb();
}
elseif ($op == "shownewcomment")
{
    $notesid = $_REQUEST['notesid'];
    $maxcommentid = $_REQUEST['maxcommentid'];
    
    OpenDb();
    ShowComment($notesid, $maxcommentid);
    CloseDb();
}
elseif ($op == "getmaxcommentid")
{
    $notesid = $_REQUEST['notesid'];
    
    OpenDb();
    $maxcommentid = GetMaxCommentId($notesid);
    CloseDb();
    
    echo $maxcommentid;
}
elseif ($op == "deletecomment")
{
    $replid = $_REQUEST['replid'];
    $rowid = $_REQUEST['rowid'];
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    
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
elseif ($op == "editnotes")
{
    $notesid = $_REQUEST['notesid'];
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    
    OpenDb();
    $info = "";
    $type = "";
    if (!ValidateEditNotesLogin($login, $password, $type, $info))
    {
        http_response_code(500);
        echo $info;
    }
    else
    {
        if (!ValidateNotesOwner($notesid, $login))
        {
            http_response_code(500);
            echo "Anda tidak berhak mengubah notes ini!";    
        }
        else
        {
            require_once("notes.edit.session.php");
            $_SESSION['allowedit'] = true;
            
            http_response_code(200);
            echo "OK";    
        }
    }
    CloseDb();
}
elseif ($op == "deletenotes")
{
    $notesid = $_REQUEST['notesid'];
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    
    OpenDb();
    BeginTrans();
    
    try
    {
        $info = "";
        $type = "";
        if (!ValidateDeleteNotesLogin($login, $password, $type, $info))
        {
            http_response_code(500);
            echo $info;
        }
        else
        {
            if (!ValidateNotesOwner($notesid, $login))
            {
                http_response_code(500);
                echo "Anda tidak berhak menghapus notes ini!";    
            }
            else
            {
                DeleteNotes($notesid);
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