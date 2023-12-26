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
class Thumbnail
{
	var $image;
	var $path;
	var $resizeImage;
	var $type;
	var $filename;
	function Thumbnail()
		{
		        
		}
	
	function setImage($image)
		{
		
				$this->path=$image;
				$this->type="jpeg";
				if(!@$this->image=imagecreatefromjpeg($image))
					{
						$this->type="png";
						if(!@$this->image=imagecreatefrompng($image))
							{
								$this->type="gif";
								if(!@$this->image=imagecreatefromgif($image))
									{
										echo "unknow file type";
									}
	
							}
					}
				
		}
	
	function getFilename()
		{
			return $this->filename;
		}
		
	function setFilename($file)
		{
			$this->filename=$file;	
		}
	function getType()
		{
			return $this->type;
		}	
	function setType($type)
		{
			$this->type=$type;
		}
	

	function resize($width,$height)
		{
			@list($imageWidth,$imageHeight)=getimagesize($this->path);
			if(($width==""||$width==0)&&($height==""||$height==0))
				{
					$width=$imageWidth;
					$height=$imageHeight;		
				}
			@$this->resizeImage=imagecreatetruecolor($width,$height);
			@imagecopyresized ($this->resizeImage,$this->image,0,0,0,0,$width,$height,$imageWidth,$imageHeight);
			
		}

	
	function display()
		{
			$this->filename = tempnam("/tmp", "FOO"); 
			switch($this->type)
				{
					
					case "png":
						header("Content-type: image/png");	
						@imagepng($this->resizeImage,$this->filename);
						
						break;
					case "jpeg":
						header("Content-type: image/jpeg");	
						@imagejpeg($this->resizeImage,$this->filename);
						break;
					case "gif":
						header("Content-type: image/gif");	
						@imagegif($this->resizeImage,$this->filename);
						break;					
				}
			
		}
}

?>