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

class ImageResizer
{
	// 1 gif, 2 jpg, 3 png, 6 bmp
	private array $SupportedImageTypes = [1, 2, 3, 6];

	public function Resize($foto, $newwidth, $newheight, $quality, $output)
	{
		$uploadedfile = $foto['tmp_name'];	
		if (strlen((string) $uploadedfile) != 0)
		{
			// get image size
			[$width, $height, $imgtype] = getimagesize($uploadedfile);
			
			// check supperted image type
			if (!in_array($imgtype, $this->SupportedImageTypes))
			{
				trigger_error("Image type is not supported!<br>");
				exit();
			}
			
			// png use 0 to 9
			if ($imgtype == 3)
			{
				$quality = ($quality > 90) ? 90 : $quality;
				$quality = floor($quality / 10);
			}
		
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
			
			$tmp = imagecreatetruecolor($newwidth, $newheight);
			// imageantialias($tmp, TRUE);
			
			// Create source image
			if ($imgtype == 2) // "image/jpeg"
				$src = imagecreatefromjpeg($uploadedfile);
			elseif ($imgtype == 1) // "image/gif"
				$src = imagecreatefromgif($uploadedfile);
			elseif ($imgtype == 3) // "image/png"
				$src = imagecreatefrompng($uploadedfile);
			elseif ($imgtype == 6) // "image/bmp"
				$src = $this->imagecreatefrombmp($uploadedfile);	
			
			// Resize and copy to src buffer
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			
			// Create output image
			if ($imgtype == 2) // "image/jpeg"
				imagejpeg($tmp, $output, $quality);
			elseif ($imgtype == 1) // "image/gif"
				imagegif($tmp, $output);
			elseif ($imgtype == 3) // "image/png"
				imagepng($tmp, $output, $quality);
			elseif ($imgtype == 6) // "image/png"
				$this->imagebmp($tmp, $output);	
			
			imagedestroy($src);
			imagedestroy($tmp); 
			
			// Set read access by group & all people
			chmod($output, 0644);
			
			return true;
		}
		return false;
	}
	
	private function imagebmp(&$img, $filename = false) 
	{ 
		$wid = imagesx($img); 
		$hei = imagesy($img); 
		$wid_pad = str_pad('', $wid % 4, "\0"); 
		 
		$size = 54 + ($wid + $wid_pad) * $hei; 
		 
		//prepare & save header 
		$header['identifier']       = 'BM'; 
		$header['file_size']        = $this->dword($size); 
		$header['reserved']         = $this->dword(0); 
		$header['bitmap_data']      = $this->dword(54); 
		$header['header_size']      = $this->dword(40); 
		$header['width']            = $this->dword($wid); 
		$header['height']           = $this->dword($hei); 
		$header['planes']           = $this->word(1); 
		$header['bits_per_pixel']   = $this->word(24); 
		$header['compression']      = $this->dword(0); 
		$header['data_size']        = $this->dword(0); 
		$header['h_resolution']     = $this->dword(0); 
		$header['v_resolution']     = $this->dword(0); 
		$header['colors']           = $this->dword(0); 
		$header['important_colors'] = $this->dword(0); 
	
		if ($filename) 
		{ 
			$f = fopen($filename, "wb"); 
			foreach ($header AS $h) 
			{ 
				fwrite($f, (string) $h); 
			} 
			 
			//save pixels 
			for ($y=$hei-1; $y>=0; $y--) 
			{ 
				for ($x=0; $x<$wid; $x++) 
				{ 
					$rgb = imagecolorat($img, $x, $y); 
					fwrite($f, (string) $this->byte3($rgb)); 
				} 
				fwrite($f, $wid_pad); 
			} 
			fclose($f); 
		} 
		else 
		{ 
			foreach ($header AS $h) 
			{ 
				echo $h; 
			} 
			 
			//save pixels 
			for ($y=$hei-1; $y>=0; $y--) 
			{ 
				for ($x=0; $x<$wid; $x++) 
				{ 
					$rgb = imagecolorat($img, $x, $y); 
					echo $this->byte3($rgb); 
				} 
				echo $wid_pad; 
			} 
		} 
	} 

	private function imagecreatefrombmp($filename) 
	{ 
		$f = fopen($filename, "rb"); 
	
		//read header     
		$header = fread($f, 54); 
		$header = unpack(    'c2identifier/Vfile_size/Vreserved/Vbitmap_data/Vheader_size/' . 
							'Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vdata_size/'. 
							'Vh_resolution/Vv_resolution/Vcolors/Vimportant_colors', $header); 
	
		if ($header['identifier1'] != 66 or $header['identifier2'] != 77) 
		{ 
			die('Not a valid bmp file'); 
		} 
		 
		if ($header['bits_per_pixel'] != 24) 
		{ 
			die('Only 24bit BMP images are supported'); 
		} 
		 
		$wid2 = ceil((3*$header['width']) / 4) * 4; 
		 
		$wid = $header['width']; 
		$hei = $header['height']; 
	
		$img = imagecreatetruecolor($header['width'], $header['height']); 
	
		//read pixels     
		for ($y=$hei-1; $y>=0; $y--) 
		{ 
			$row = fread($f, $wid2); 
			$pixels = str_explode($row, 3); 
			for ($x=0; $x<$wid; $x++) 
			{ 
				imagesetpixel($img, $x, $y, $this->dwordize($pixels[$x])); 
			} 
		} 
		fclose($f);             
		 
		return $img; 
	}     

	private function dwordize($str) 
	{ 
		$a = ord($str[0]); 
		$b = ord($str[1]); 
		$c = ord($str[2]); 
		return $c*256*256 + $b*256 + $a; 
	} 
	
	private function byte3($n) 
	{ 
		return chr($n & 255) . chr(($n >> 8) & 255) . chr(($n >> 16) & 255);     
	} 
	
	private function dword($n) 
	{ 
		return pack("V", $n); 
	} 
	
	private function word($n) 
	{ 
		return pack("v", $n); 
	}
}

function ResizeImage($foto, $newwidth, $newheight, $quality, $output)
{
	$IR = new ImageResizer();
	return $IR->Resize($foto, $newwidth, $newheight, $quality, $output);
}

?>