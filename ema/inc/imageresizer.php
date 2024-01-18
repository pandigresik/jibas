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
// =============================================================
//  RESIZE JPEG! IMAGE ONLY                                     
// =============================================================

function ResizeImage($foto, $newwidth, $newheight, $quality, $output)
{
	$uploadedfile = $foto['tmp_name'];	
	if (strlen((string) $uploadedfile) != 0)
	{
		// get type
		$type = $foto['type'];
		
		// get image size
		[$width, $height] = getimagesize($uploadedfile);
	
		// get scalling factor
		$npercent = 1.0;
		if ($newwidth < $width || $newheight < $height) 
		{
			$scalewidth  = $newwidth / $width;
			$scaleheight = $newheight / $height;
			
			if ($scalewidth < $scaleheight)
				$npercent = $scalewidth;
			else
				$npercent = $scaleheight;
		}
		$newheight = $height * $npercent;
		$newwidth  = $width * $npercent;
		
		// echo "Resize to $newwidth x $newheight <br>";
		
		$tmp = imagecreatetruecolor($newwidth, $newheight);
		// imageantialias($tmp, TRUE);
		
		// Create source image
		if ($type == "image/jpeg")
			$src = imagecreatefromjpeg($uploadedfile);
		elseif ($type == "image/gif")
			$src = imagecreatefromgif($uploadedfile);
		elseif ($type == "image/png")
			$src = imagecreatefrompng($uploadedfile);
		
		// Resize and copy to src buffer
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		
		// Create output image
		if ($type == "image/jpeg")
			imagejpeg($tmp, $output, $quality);
		elseif ($type == "image/gif")
			imagegif($tmp, $output);
		elseif ($type == "image/png")
			imagepng($tmp, $output, $quality);
		
		imagedestroy($src);
		imagedestroy($tmp); 
		
		// Set read access by group & all people
		chmod($output, 0644);
		
		return true;
	}
	return false;
}

?>