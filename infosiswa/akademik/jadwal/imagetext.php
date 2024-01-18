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
	header("Content-Type: image/png");
	session_start();
	$string = $_REQUEST["string"];
	$string = str_replace(' ','',(string) $string);
	$span = (int)$_REQUEST["span"];
	//----------------
	//create image
  	$height=25;
	$width=$span*$height;
  	 	
  	$fontwidth = ImageFontWidth($font) * strlen($string);
  	$fontheight = ImageFontHeight($font);
  	$w = ($width/2)-($fontwidth-3);
	$font=5; 
	if ($span==1){
		$w=7;
		$font=2;
		$s=explode('-',$string);
		$string=$s[0]."\n-\n".$s[1];
	}
	$im = imagecreate ($width,$height);
  	$background_color = imagecolorallocate ($im, 181, 181, 181);
  	$text_color = imagecolorallocate ($im, 0, 0, 0);
  	imagerectangle($im,0,0,$width-1,$height-1,imagecolorallocate ($im, 181, 181, 181));
  	if ($span==1){
		$w1=$w;
		$w2=$w;
		if (strlen($s[0])==1)
			$w1=$w1+$font+1;
		if (strlen($s[1])==1)
			$w2=$w2+$font+1;
		imagestring ($im, $font, $w1, 0,  $s[0], $text_color);
		imagestring ($im, $font, $w+3, $font*3,  '-', $text_color);
		imagestring ($im, $font, $w2, $font*6,  $s[1], $text_color);
  	} else {
		imagestring ($im, $font, $w, $font-1,  $string, $text_color);
	}
	imagepng ($im);
  	ImageDestroy($im);
  	//----------------
?>