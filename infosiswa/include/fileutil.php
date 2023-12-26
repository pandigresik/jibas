<?php
function delete($file)
{
    if (file_exists($file))
    {
        // chmod($file, 0644);
        if (is_dir($file))
        {
            $handle = opendir($file); 
            while($filename = readdir($handle))
            {
                if ($filename != "." && $filename != "..")
                    delete($file."/".$filename);
             }
        closedir($handle);
        rmdir($file);
        }
        else
        {
            unlink($file);
        }
    }
}

function deleteFolderRecursive($dir)
{
	$current_dir = opendir($dir);
  	while($entryname = readdir($current_dir))
  	{
		if ($entryname == "." || $entryname == "..")
			continue;
		
     	if(is_dir("$dir/$entryname"))
        	deleteFolderRecursive("$dir/$entryname");
	 	else
        	unlink("$dir/$entryname");
  	}
  	closedir($current_dir);
  	rmdir($dir);
}

function fileSizeInByte($filesize)
{
    if ($filesize < 1024)
    {
        return $filesize . " B";
    }
    elseif ($filesize < 1024 * 100 * 9)
    {
        $filesize = round($filesize / 1024, 2);
        return "$filesize KB";
    }
    elseif ($filesize < 1024 * 1024 * 100 * 9)
    {
        $filesize = round($filesize / (1024 * 1024), 2);
        return "$filesize MB";
    }
    else
    {
        $filesize = round($filesize / (1024 * 1024 * 1024), 2);
        return "$filesize GB";
    }
}
?>