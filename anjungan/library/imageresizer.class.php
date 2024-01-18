<?php
class ImageResizer
{
	public static function ResizeImage($foto, $newwidth, $newheight, $quality, $output)
	{
		$uploadedfile = $foto['tmp_name'];
		if (!file_exists($uploadedfile) || !is_readable($uploadedfile))
		{
			echo "Uploaded file not found!";
			return false;	
		}
		
		// Determine new dimension
		[$width, $height] = getimagesize($uploadedfile);
		$npercent = 1.0;
		$scalewidth  = $newwidth / $width;
		$scaleheight = $newheight / $height;
		if ($scalewidth < $scaleheight)
			$npercent = $scalewidth;
		else
			$npercent = $scaleheight;
		$newheight = $height * $npercent;
		$newwidth  = $width * $npercent;
		
		// Create Source Graphics
		$type = $foto['type'];
		if (strpos((string) $type, "jpeg") || strpos((string) $type, "jpg"))
		{
			$src = imagecreatefromjpeg($uploadedfile);
		}
		elseif (strpos((string) $type, "gif"))
		{
			$src = imagecreatefromgif($uploadedfile);
		}
		elseif (strpos((string) $type, "png"))
		{
			$src = imageCreateFromPNG($uploadedfile);
		}
		elseif (strpos((string) $type, "bmp"))
		{
			$src = ImageResizer::imagecreatefrombmp($uploadedfile);
		}
		
		// Create Target Graphics
		$tmp = imagecreatetruecolor($newwidth, $newheight);
		if (!str_contains((string) $type, "png"))
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		
		if (strpos((string) $type, "jpeg") || strpos((string) $type, "jpg"))
		{
			imagejpeg($tmp, $output, $quality);
		}
		elseif (strpos((string) $type, "gif"))
		{
			$background = imagecolorallocate($tmp, 0, 0, 0);
			ImageColorTransparent($tmp, $background); // make the new temp image all transparent
			imagealphablending($tmp, false); // turn off the alpha blending to keep the alpha channel
			imagesavealpha($tmp,true);
			imagecopyresized($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			imagegif($tmp, $output);
		}
		elseif (strpos((string) $type, "png"))
		{
			$background = imagecolorallocate($tmp, 0, 0, 0);
			ImageColorTransparent($tmp, $background); // make the new temp image all transparent
			imagealphablending($tmp, false); // turn off the alpha blending to keep the alpha channel
			imagesavealpha($tmp,true);
			imagecopyresized($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			$quality = $quality / 100;
			imagepng($tmp, $output, $quality);
		}
		elseif (strpos((string) $type, "bmp"))
		{
			ImageResizer::imagebmp($tmp, $output);	
		}
	
		// Cleanup Graphics
		imagedestroy($src);
		imagedestroy($tmp); 
		
		// Set permission
		chmod($output, 0744);
		
		return true;
	}
	
	public static function imagebmp(&$img, $filename = false) 
	{ 
		$wid = imagesx($img); 
		$hei = imagesy($img); 
		$wid_pad = str_pad('', $wid % 4, "\0"); 
		 
		$size = 54 + ($wid + $wid_pad) * $hei; 
		 
		//prepare & save header 
		$header['identifier']       = 'BM'; 
		$header['file_size']        = ImageResizer::dword($size); 
		$header['reserved']         = ImageResizer::dword(0); 
		$header['bitmap_data']      = ImageResizer::dword(54); 
		$header['header_size']      = ImageResizer::dword(40); 
		$header['width']            = ImageResizer::dword($wid); 
		$header['height']           = ImageResizer::dword($hei); 
		$header['planes']           = ImageResizer::word(1); 
		$header['bits_per_pixel']   = ImageResizer::word(24); 
		$header['compression']      = ImageResizer::dword(0); 
		$header['data_size']        = ImageResizer::dword(0); 
		$header['h_resolution']     = ImageResizer::dword(0); 
		$header['v_resolution']     = ImageResizer::dword(0); 
		$header['colors']           = ImageResizer::dword(0); 
		$header['important_colors'] = ImageResizer::dword(0); 
	
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
					fwrite($f, (string) ImageResizer::byte3($rgb)); 
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
					echo ImageResizer::byte3($rgb); 
				} 
				echo $wid_pad; 
			} 
		} 
	} 
	
	public static function imagecreatefrombmp($filename) 
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
				imagesetpixel($img, $x, $y, ImageResizer::dwordize($pixels[$x])); 
			} 
		} 
		fclose($f);             
		 
		return $img; 
	}     
	
	public static function dwordize($str) 
	{ 
		$a = ord($str[0]); 
		$b = ord($str[1]); 
		$c = ord($str[2]);
		
		return $c*256*256 + $b*256 + $a; 
	} 
	
	public static function byte3($n) 
	{ 
		return chr($n & 255) . chr(($n >> 8) & 255) . chr(($n >> 16) & 255);     
	} 
	
	public static function dword($n) 
	{ 
		return pack("V", $n); 
	} 
	
	public static function word($n) 
	{ 
		return pack("v", $n); 
	}
	
	/**
	* Compose a PNG file over a src file.
	* If new width/ height are defined, then resize the PNG (and keep all the transparency info)
	* Author: Alex Le - http://www.alexle.net
	*/
	public static function imageComposeAlpha( &$src, &$ovr, $ovr_x, $ovr_y, $ovr_w = false, $ovr_h = false)
	{
		if( $ovr_w && $ovr_h )
		$ovr = ImageResizer::imageResizeAlpha( $ovr, $ovr_w, $ovr_h );
	
		/* Noew compose the 2 images */
		imagecopy($src, $ovr, $ovr_x, $ovr_y, 0, 0, imagesx($ovr), imagesy($ovr) );
	}
	
	/**
	* Resize a PNG file with transparency to given dimensions
	* and still retain the alpha channel information
	* Author: Alex Le - http://www.alexle.net
	*/
	public static function imageResizeAlpha(&$src, $w, $h)
	{
		/* create a new image with the new width and height */
		$temp = imagecreatetruecolor($w, $h);
	
		/* making the new image transparent */
		$background = imagecolorallocate($temp, 0, 0, 0);
		ImageColorTransparent($temp, $background); // make the new temp image all transparent
		imagealphablending($temp, false); // turn off the alpha blending to keep the alpha channel
	
		/* Resize the PNG file */
		/* use imagecopyresized to gain some performance but loose some quality */
		imagecopyresized($temp, $src, 0, 0, 0, 0, $w, $h, imagesx($src), imagesy($src));
		/* use imagecopyresampled if you concern more about the quality */
		//imagecopyresampled($temp, $src, 0, 0, 0, 0, $w, $h, imagesx($src), imagesy($src));
		return $temp;
	}
}
?>