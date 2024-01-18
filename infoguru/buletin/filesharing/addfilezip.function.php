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
$idguru = SI_USER_ID();

if (isset($_REQUEST['iddir']))
	$iddir = $_REQUEST['iddir'];
	
OpenDb();
$sql = "SELECT dirfullpath FROM jbsvcr.dirshare WHERE idroot = 0";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$rootname = $row[0];

$sql = "SELECT * FROM jbsvcr.dirshare WHERE replid = '".$iddir."'";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$dirfullpath = $row['dirfullpath'];
CloseDb();

$fullpath = str_replace($rootname, "", (string) $dirfullpath);
$cek = 0;
$ERROR_MSG = "";

if (isset($_REQUEST['Simpan']))
{
	$dir = $_REQUEST['dir']; 
	$iddir = $_REQUEST['iddir'];
	
	$FileShareDir = "$FILESHARE_UPLOAD_DIR/fileshare/";
	$FileShareDir = str_replace("//", "/", $FileShareDir);
	
	$destinationdir = str_replace($rootname, $FileShareDir, (string) $dir);
	$destinationdir = str_replace("//", "/", $destinationdir);
	
	$file = $_FILES["filezip"];
	$uploadedfile = $file['tmp_name'];
	$uploadedsizefile = (int)$file['size'];
	$uploadedsizename = trim((string) $file['name']);
	
	$zip = zip_open($uploadedfile);
	if (is_resource($zip))
	{
        OpenDb();
	
        $success = true;
        BeginTrans();
	
        while ($zip_entry = zip_read($zip))
        {
            $entry = zip_entry_name($zip_entry);
            $entry = str_replace("\\", "/", $entry);
			
            $folderpath = ""; $filename = "";
            GetFolderAndFileName($entry, $folderpath, $filename);
            if (strlen((string) $filename) == 0)
                continue;
        
            $folderid = GetIdFolder($folderpath, $success);
            if ($folderid == -1)
                continue;
            
				$targetfolder = "$destinationdir/$folderpath";
            CheckCreateFolder($targetfolder);
            
            SecurePhpExtension($filename);
            ExtractSaveFile($folderid, $targetfolder, $filename, $zip, $zip_entry, $success);
        } // while

        zip_close($zip);
		
        if ($success)
        {
           CommitTrans(); //RollbackTrans();
           CloseDb();
           ShowMessageClose("Berhasil");
        }
        else
        {
           RollbackTrans();
           CloseDb();
           ShowMessageClose("Gagal mengunggah data!");
        }
    }
    else
    {
        ShowMessageClose("Bukan File ZIP!");
    }
}


// FUNCTIONS DEFINITION =========================================

function GetFolderAndFileName($entry, &$folderpath, &$filename)
{
    $lastpos = -1;
    $pos = strpos((string) $entry, "/");
    while($pos !== FALSE)
    {
       $lastpos = $pos;
       $pos = strpos((string) $entry, "/", $pos + 1);
    }
    
    if ($lastpos == -1)
    {
       $folderpath = "";
       $filename = $entry;	
    }
    else
    {
       $folderpath = trim(substr((string) $entry, 0, $lastpos));
       $filename = trim(substr((string) $entry, $lastpos + 1));	
    }
}

function SearchCreateIdFolder($rootfolder, $lastfoldername, &$success)
{
    global $idguru;
    
    $currfolder = $rootfolder . $lastfoldername . "/";
    
    $sql = "SELECT replid
              FROM jbsvcr.dirshare
             WHERE idguru = '$idguru'
               AND dirfullpath = '".$currfolder."'";
    //echo "$sql<br>";           
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        $sql = "SELECT replid
                  FROM jbsvcr.dirshare
                 WHERE idguru = '$idguru'
                   AND dirfullpath = '".$rootfolder."'";
        //echo "GETROOTFOLDER $sql<br>";           
        $res = QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            $rootid = $row[0];
            
            $sql = "INSERT INTO jbsvcr.dirshare
                       SET idroot = '$rootid',
                           dirname = '$lastfoldername',
                           dirfullpath = '$currfolder',
                           idguru = '".$idguru."'";
            //echo "$sql<br>";               
            QueryDbTrans($sql, $success);
            
            $sql = "SELECT LAST_INSERT_ID()";
            //echo "$sql<br>";
            $res = QueryDb($sql);
            $row = mysqli_fetch_row($res);
            return $row[0];
        }
        return -1;
    }               
    else
    {
        $row = mysqli_fetch_row($res);
        return $row[0];
    }
}

function GetIdFolder($folderpath, &$success)
{
   global $iddir, $dirfullpath, $FileShareDir, $rootname;
    
	if ($folderpath == "")
		return $iddir;
    
	//echo "<br>=====================<br>FF: $folderpath<br>";
	$startpos = 0;
	$pos = strpos((string) $folderpath, "/", $startpos);
	if ($pos !== FALSE)
	{
		$rootfolder = $dirfullpath;
		while($pos !== FALSE)
		{
			$subfolder = substr((string) $folderpath, $startpos, $pos - $startpos);
			$lastid = SearchCreateIdFolder($rootfolder, $subfolder, $success);
			//echo "lastid = $lastid<br>";
			
			$rootfolder = $rootfolder . $subfolder . "/";
			
			$checkfolder = str_replace($rootname, $FileShareDir, $rootfolder);
			CheckCreateFolder($checkfolder);
			
			$startpos = $pos + 1;
			$pos = strpos((string) $folderpath, "/", $startpos);
		}
		
		$subfolder = trim(substr((string) $folderpath, $startpos));
		if (strlen($subfolder) > 0)
		{
			$lastid = SearchCreateIdFolder($rootfolder, $subfolder, $success);
			 //echo "lastid = $lastid<br>";
		}
		
		return $lastid;
	}
	else
	{
		return SearchCreateIdFolder($dirfullpath, $folderpath, $success);
	}
}

function ExtractSaveFile($folderid, $targetfolder, $filename, $zip, $zip_entry, &$success)
{
	$newfile = true;
	$targetfile = "$targetfolder/$filename";
	if (file_exists($targetfile))
	{
		unlink($targetfile);
		$newfile = false;
	}
    
	$zipfilesize = 0;
	if ($fp = fopen($targetfile, "w"))
	{
		 if (zip_entry_open($zip, $zip_entry, "r"))
		 {
			 $zipfilesize = zip_entry_filesize($zip_entry);
			 $buf = zip_entry_read($zip_entry, $zipfilesize);
			 fwrite($fp, $buf);
			 zip_entry_close($zip_entry);
			 $writezip = true;
		 }
		 fclose($fp);
	}
		  
	if ($zipfilesize == 0)
		 return;
		  
	if ($newfile)
		 $sql = "INSERT INTO jbsvcr.fileshare
						SET iddir='$folderid',
							 filename='$filename',
							 filetime=NOW(),
							 filesize='$zipfilesize'";
	else
		 $sql = "UPDATE jbsvcr.fileshare
						SET filetime=NOW(),
							 filesize='$zipfilesize'
					 WHERE iddir='$folderid' 
						AND filename='$filename'";
	QueryDbTrans($sql, $success);
	//echo "$sql<br>";
}

function CheckCreateFolder($targetfolder)
{
   $targetfolder = str_replace("//", "/", (string) $targetfolder);
   if (!file_exists($targetfolder))
      mkdir($targetfolder, 0750, true);
	
	$htaccess = "$targetfolder/.htaccess";
	if (!file_exists($htaccess))
	{
		if ($fp = fopen($htaccess, "w"))
		{
			fwrite($fp, "Options -Indexes\r\n");
			fclose($fp);
		}
	}
}

function SecurePhpExtension(&$filename)
{
	$lastpos = -1; $startpos = 0;
	$pos = strpos((string) $filename, ".", $startpos);
	while($pos !== FALSE)
	{
		$lastpos = $pos;
		
		$startpos = $pos + 1;
		$pos = strpos((string) $filename, ".", $startpos);
	}
	
	if ($lastpos != -1)
	{
		$ext = strtolower(trim(substr((string) $filename, $lastpos)));
		if ($ext == ".php")
			 $filename = $filename . ".txt";
	}
}

function ShowMessageClose($message)
{ ?>
	<script language="javascript">
		alert('<?=$message?>');
		
		opener.RefreshAll();
		window.close();
	</script>
<?php    
}
?>