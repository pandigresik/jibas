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
function ResizeImage($foto, $newwidth, $newheight, $quality, $output)
{
	$uploadedfile = $foto['tmp_name'];	
	if (strlen((string) $uploadedfile) != 0)
	{
		$type = $foto['type'];
		[$width, $height] = getimagesize($uploadedfile);
		$npercent = 1.0;
		$scalewidth  = $newwidth / $width;
		$scaleheight = $newheight / $height;
		//echo "nilai : $uploadedfile";
		//return false;
		if ($scalewidth < $scaleheight)
			$npercent = $scalewidth;
		else
			$npercent = $scaleheight;
		$newheight = $height * $npercent;
		$newwidth  = $width * $npercent;
		$tmp = imagecreatetruecolor($newwidth, $newheight);
		if ($type == "image/jpeg" || $type == "image/pjpeg")
			$src = imagecreatefromjpeg($uploadedfile);
		elseif ($type == "image/gif")
			$src = imagecreatefromgif($uploadedfile);
		elseif ($type == "image/png" || $type == "image/x-png")
			$src = imageCreateFromPNG($uploadedfile);
		elseif ($type === "image/bmp")
			$src = imagecreatefrombmp($uploadedfile);
		else {
			$type = explode("/", (string) $type);
			//echo "nilai tipe : $type[0], nilai : $type[1] ";
			$tipe = (string)$type[1];
			if ($tipe == "jpeg" || $tipe == "pjpeg" || $tipe == "jpg")
				$src = imagecreatefromjpeg($uploadedfile);
			elseif ($tipe == "gif")
				$src = imagecreatefromgif($uploadedfile);
			elseif ($tipe == "png" || $tipe == "x-png")
				$src = imageCreateFromPNG($uploadedfile);
			elseif ($tipe == "bmp")
				$src = imagecreatefrombmp($uploadedfile);	
			//$src = imagecreatefromstring(file_get_contents($foto['tmp_name']));	
		
		}
		//echo "nilai output : $output";	
		//echo "nilainya ".get_resource_type($uploadedfile);	
			//echo "<pre>";
			//echo $tmp;	
			//echo "<pre>";
		
		//imageComposeAlpha( $photoImage, $overlay, 86, 15, $newW, $newH );
		if ($type != "image/png" && $type != "image/x-png")
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		//imagecopyresampled($tmp, $src, 0, 0, $newwidth, $newheight, $newwidth, $newheight, $width, $height);
		if ($type == "image/jpeg" || $type == "image/pjpeg")
			imagejpeg($tmp, $output, $quality);
		elseif ($type == "image/gif"){
			$background = imagecolorallocate($tmp, 0, 0, 0);
			ImageColorTransparent($tmp, $background); // make the new temp image all transparent
			imagealphablending($tmp, false); // turn off the alpha blending to keep the alpha channel
			imagesavealpha($tmp,true);
			imagecopyresized($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			imagegif($tmp, $output);
		}
		elseif ($type == "image/png" || $type == "image/x-png")
		{
			
			
			$background = imagecolorallocate($tmp, 0, 0, 0);
			ImageColorTransparent($tmp, $background); // make the new temp image all transparent
			imagealphablending($tmp, false); // turn off the alpha blending to keep the alpha channel
			imagesavealpha($tmp,true);
			imagecopyresized($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			$quality = $quality / 100;
			imagepng($tmp, $output, $quality);
		}
		elseif ($type == "image/bmp")
			imagebmp($tmp, $output);	
		else {
			$type = explode("/", (string) $foto['type']);
			$tipe = (string)$type[1];
			if ($tipe == "jpeg" || $tipe == "pjpeg" || $tipe == "jpg")
				imagejpeg($tmp, $output, $quality);
			elseif ($tipe == "gif"){
				$background = imagecolorallocate($tmp, 0, 0, 0);
				ImageColorTransparent($tmp, $background); // make the new temp image all transparent
				imagealphablending($tmp, false); // turn off the alpha blending to keep the alpha channel
				imagesavealpha($tmp,true);
				imagecopyresized($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagegif($tmp, $output);
				
			}
			elseif ($tipe == "png" || $tipe == "x-png") {
				$background = imagecolorallocate($tmp, 0, 0, 0);
				ImageColorTransparent($tmp, $background); // make the new temp image all transparent
				imagealphablending($tmp, false); // turn off the alpha blending to keep the alpha channel
				imagesavealpha($tmp,true);
				imagecopyresized($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				$quality = $quality / 100;
				imagepng($tmp, $output, $quality);
			}
			elseif ($tipe == "bmp")
				imagebmp($tmp, $output);		
		}
	
		imagedestroy($src);
		imagedestroy($tmp); 
		
		chmod($output, 0755);
		return false;
	}
	return false;
}
function CropImage($foto, $widthandheight, $quality, $output, $automaticcrop = true, $srcX = 0, $srcY = 0, $srcW = 0, $srcH = 0 )
{
	//echo $srcX.'-'.$srcY.'-'.$srcW.'-'.$srcH;
	
	if (count($foto)>0 && is_array($foto))
		$uploadedfile = $foto['tmp_name'];	
	else
		$uploadedfile = $foto;
	
	//echo "ini nih ".$foto;
	if (strlen((string) $uploadedfile) != 0)
	{
		if (count($foto)>0 && is_array($foto))
			$type = $foto['type'];
		else {
			$uploadedfile = $foto;
			$imgInfo = @getimagesize($uploadedfile);
			//echo "<pre>";
			//print_r($imgInfo);
			//echo "</pre>";
			//1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
			$imgtype = $imgInfo[2];
			switch($imgtype){
				case '1':$type='image/gif';
					break;
				case '2':$type='image/jpeg';
					break;
				case '3':$type='image/png';
					break;
				case '6':$type='image/bmp';
					break;
			}
		}
		
		unset($width,$height);
		[$width, $height] = @getimagesize($uploadedfile);
		//echo "Ini".$uploadedfile;
		$x = ($width/2)-($widthandheight/2);
		$y = ($height/2)-($widthandheight/2);
		$tmp = imagecreatetruecolor($widthandheight, $widthandheight);
		if ($type == "image/jpeg" || $type == "image/pjpeg")
			$src = imagecreatefromjpeg($uploadedfile);
		elseif ($type == "image/gif")
			$src = imagecreatefromgif($uploadedfile);
		elseif ($type == "image/png" || $type == "image/x-png")
			$src = imageCreateFromPNG($uploadedfile);
		elseif ($type == "image/bmp")
			$src = imagecreatefrombmp($uploadedfile);
		else {
			//$src = imagecreatefromstring(file_get_contents($foto['tmp_name']));	
			$type = explode("/", (string) $type);
			$tipe = (string)$type[1];
			//echo "nilai tipe : $type[0], nilai : $type[1] ";
			if ($tipe == "jpeg" || $tipe == "pjpeg" || $tipe == "jpg")
				$src = imagecreatefromjpeg($uploadedfile);
			elseif ($tipe == "gif")
				$src = imagecreatefromgif($uploadedfile);
			elseif ($tipe == "png" || $tipe == "x-png")
				$src = imageCreateFromPNG($uploadedfile);
			elseif ($tipe)
				$src = imagecreatefrombmp($uploadedfile);			
		}
		//echo get_resource_type($uploadedfile);				//imagecopyresampled($dst_r,$img_r,0,0,$_REQUEST['img_x'],$_REQUEST['img_y'],$targ_w,$targ_h,$_REQUEST['img_w'],$_REQUEST['img_h']);
		//imagecopyresampled ( resource dst_im, resource src_im, int dstX, int dstY, int srcX, int srcY, int dstW, int dstH, int srcW, int srcH )
		
		$x = ($width>$height)?(($width/2)-($height/2)):0;
		$y = ($width>$height)?0:(($height/2)-($width/2));

		$wh = ($width>$height)?$height:$width;
		//$wh = 100;

		//imagecopyresampled ( resource dst_im, resource src_im, int dstX, int dstY, int srcX, int srcY, int dstW, int dstH, int srcW, int srcH )
		if ($automaticcrop)
			imagecopyresampled($tmp, $src, 0, 0, $x, $y, $widthandheight, $widthandheight, $wh , $wh);
		else
			imagecopyresampled($tmp, $src, 0, 0, $srcX, $srcY, $widthandheight, $widthandheight, $srcW , $srcH);

		if ($type == "image/jpeg" || $type == "image/pjpeg")
			imagejpeg($tmp, $output, $quality);
		elseif ($type == "image/gif")
			imagegif($tmp, $output);
		elseif ($type == "image/png" || $type == "image/x-png")
		{
			$quality = $quality / 100;
			imagepng($tmp, $output, $quality);
		}
		elseif ($type == "image/bmp") {
			imagebmp($tmp, $output);	
		}
		else {
			$type = explode("/", (string) $foto['type']);
			$tipe = (string)$type[1];
			if ($tipe == "jpeg" || $tipe == "pjpeg" || $tipe == "jpg")
				imagejpeg($tmp, $output, $quality);
			elseif ($tipe == "gif")
				imagegif($tmp, $output);
			elseif ($tipe == "png" || $tipe == "x-png") {
				$quality = $quality / 100;
				imagepng($tmp, $output, $quality);
			}
			elseif ($tipe == "bmp")
				imagebmp($tmp, $output);	
		}		

	
		imagedestroy($src);
		imagedestroy($tmp); 
		
		chmod($output, 0777);
		return false;
	}
	return false;
	//imagecopyresampled($dst_r,$img_r,0,0,$_REQUEST['img_x'],$_REQUEST['img_y'],
		//$targ_w,$targ_h,$_REQUEST['img_w'],$_REQUEST['img_h']);
}

function imagebmp(&$img, $filename = false) 
{ 
	$wid = imagesx($img); 
	$hei = imagesy($img); 
	$wid_pad = str_pad('', $wid % 4, "\0"); 
	 
	$size = 54 + ($wid + $wid_pad) * $hei; 
	 
	//prepare & save header 
	$header['identifier']       = 'BM'; 
	$header['file_size']        = dword($size); 
	$header['reserved']         = dword(0); 
	$header['bitmap_data']      = dword(54); 
	$header['header_size']      = dword(40); 
	$header['width']            = dword($wid); 
	$header['height']           = dword($hei); 
	$header['planes']           = word(1); 
	$header['bits_per_pixel']   = word(24); 
	$header['compression']      = dword(0); 
	$header['data_size']        = dword(0); 
	$header['h_resolution']     = dword(0); 
	$header['v_resolution']     = dword(0); 
	$header['colors']           = dword(0); 
	$header['important_colors'] = dword(0); 

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
				fwrite($f, (string) byte3($rgb)); 
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
				echo byte3($rgb); 
			} 
			echo $wid_pad; 
		} 
	} 
} 

function imagecreatefrombmp($filename) 
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
			imagesetpixel($img, $x, $y, dwordize($pixels[$x])); 
		} 
	} 
	fclose($f);             
	 
	return $img; 
}     

function dwordize($str) 
{ 
	$a = ord($str[0]); 
	$b = ord($str[1]); 
	$c = ord($str[2]); 
	return $c*256*256 + $b*256 + $a; 
} 

function byte3($n) 
{ 
	return chr($n & 255) . chr(($n >> 8) & 255) . chr(($n >> 16) & 255);     
} 

function dword($n) 
{ 
	return pack("V", $n); 
} 

function word($n) 
{ 
	return pack("v", $n); 
}

/**
* Compose a PNG file over a src file.
* If new width/ height are defined, then resize the PNG (and keep all the transparency info)
* Author: Alex Le - http://www.alexle.net
*/
function imageComposeAlpha( &$src, &$ovr, $ovr_x, $ovr_y, $ovr_w = false, $ovr_h = false)
{
	if( $ovr_w && $ovr_h )
	$ovr = imageResizeAlpha( $ovr, $ovr_w, $ovr_h );

	/* Noew compose the 2 images */
	imagecopy($src, $ovr, $ovr_x, $ovr_y, 0, 0, imagesx($ovr), imagesy($ovr) );
}

/**
* Resize a PNG file with transparency to given dimensions
* and still retain the alpha channel information
* Author: Alex Le - http://www.alexle.net
*/
function imageResizeAlpha(&$src, $w, $h)
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
?>