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
class nama
{
 public $namafile ="";
 public $i ="";
  //$namafile="../jibitheme/theme/black/ket.txt";
  function buk_file()
  { 
   $namafile =$this->namafile ;
   if(file_exists($namafile)){
    $fp=fopen($this->namafile,"r");
		     while($isi=fgets($fp,'1000'))
			{
			 // echo $isi.'<br>';
			  $arrrr[]=$isi;
			  			}
	}
	return $arrrr ;
  }
 function title()
 {
   $arr = $this->buk_file();
	 $pieces = explode(": ", (string) $arr[$this->i]);
	 $title = $pieces[1];
	 if($title =="")
	  {
	   $title ='&nbsp;';
	  }
	 return $title ;
  }
 function tampilkan()
  {
   echo $this ->title();
   }
}
?>