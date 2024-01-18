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
function GetDatePart($value, $part) {
	$inf = explode("-", (string) $value);
	$part = strtolower((string) $part);
	
	/*
	echo $value . "<br>";
	echo count($inf) . "<br>";
	for ($i = 0; $i < count($inf); $i++) 
		echo $inf[$i] . "<br>";
	exit();	*/
	
	if (count($inf) == 3) {
		if ($part == "d")
			return $inf[2];
		elseif ($part == "m")
			return $inf[1];
		elseif ($part == "y")
			return $inf[0];
	} else {
		return "";
	}
}
function StringIsSelected($value, $comparer) {
	if ($value == $comparer) 
		return "selected";
	else
		return "";
}

function IntIsSelected($value, $comparer) {
	$a = (int)$value;
	$b = (int)$comparer;
	
	if ($a == $b) 
		return "selected";
	else
		return "";
}

function StringIsChecked($value, $comparer) {
	if ($value == $comparer) 
		return "checked";
	else
		return "";
}

function IntIsChecked($value, $comparer) {
	if ($value == $comparer) 
		return "checked";
	else
		return "";
}

function RandStr($length) {
	$charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$s = "";
	while(strlen($s) < $length) 
		$s .= substr($charset, random_int(0, 61), 1);
	return $s;		
}

function NamaBulan($bln) {
	if ($bln == 1)
		return "Januari";
	elseif ($bln == 2)
		return "Februari";		
	elseif ($bln == 3)
		return "Maret";		
	elseif ($bln == 4)
		return "April";		
	elseif ($bln == 5)
		return "Mei";
	elseif ($bln == 6)
		return "Juni";		
	elseif ($bln == 7)
		return "Juli";
	elseif ($bln == 8)
		return "Agustus";		
	elseif ($bln == 9)
		return "September";
	elseif ($bln == 10)
		return "Oktober";		
	elseif ($bln == 11)
		return "Nopember";
	elseif ($bln == 12)
		return "Desember";		
}
function NamaBulanPdk($bln) {
	if ($bln == 1)
		return "Jan";
	elseif ($bln == 2)
		return "Feb";		
	elseif ($bln == 3)
		return "Mar";		
	elseif ($bln == 4)
		return "Apr";		
	elseif ($bln == 5)
		return "Mei";
	elseif ($bln == 6)
		return "Jun";		
	elseif ($bln == 7)
		return "Jul";
	elseif ($bln == 8)
		return "Ags";		
	elseif ($bln == 9)
		return "Sep";
	elseif ($bln == 10)
		return "Okt";		
	elseif ($bln == 11)
		return "Nop";
	elseif ($bln == 12)
		return "Des";		
}

function NamaBulanH($Bln) {
    if ($Bln = 1) 
   		return "MUHARRAM";
    elseif ($Bln = 2)
        return "SHAFAR";
    elseif ($Bln = 3 )
        return "RABI'UL AWAL";
	elseif ($Bln = 4 )
		return "RABI'UL AKHIR";
	elseif ($Bln = 5 )
		return "JUMADIL ULA";
	elseif ($Bln = 6 )
		return "JUMADIL TSANI";
	elseif ($Bln = 7 )
		return "RAJAB";
	elseif ($Bln = 8 )
		return "SYA'BAN";
	elseif ($Bln = 9 )
		return "RAMADHAN";
	elseif ($Bln = 10 )
		return "SYAWAL";
	elseif ($Bln = 11 )
		return "DZULQA'DAH";
	elseif ($Bln = 12 )
		return "DZULHIJJAH";
} 
	
function nilaibulan($bln) {
	if ($bln == "Januari")
		return '01';
	elseif ($bln == "Februari")
		return '02';		
	elseif ($bln == "Maret")
		return '03';		
	elseif ($bln == "Mei")
		return '04';		
	elseif ($bln == "April")
		return '05';
	elseif ($bln == "Juni")
		return '06';		
	elseif ($bln == "Juli")
		return '07';
	elseif ($bln == "Agustus")
		return '08';		
	elseif ($bln == "September")
		return '09';
	elseif ($bln == "Oktober")
		return '10';		
	elseif ($bln == "Nopember")
		return '11';
	elseif ($bln == "Desember")
		return '12';		
}

function rpad($string, $padchar, $length) {
	$result = trim((string) $string);
	if (strlen($result) < $length) {
		$nzero = $length - strlen($result);
		$zero = "";
		for($i = 0; $i < $nzero; $i++)
			$zero .= "0";
		$result = $zero . $result;
	}
	return $result;
}

function MySqlDateFormat($date) {
	[$d, $m, $y] = explode('[/.-]', (string) $date); 
	return "$y-$m-$d";
}

function RegularDateFormat($mysqldate) {
	[$y, $m, $d] = explode('[/.-]', (string) $mysqldate); 
	return "$d-$m-$y";
}

function LongDateFormat($mysqldate) {
	[$y, $m, $d] = explode('[/.-]', (string) $mysqldate); 
	return "$d ". NamaBulan($m) ." $y";
}
function ShortDateFormat($mysqldate) {
	[$y, $m, $d] = explode('[/.-]', (string) $mysqldate); 
	return "$d ". NamaBulanPdk($m) ." $y";
}
function change_urut($a, $b, $c) {	
	$s = "";
	if ($a == $b) {
		if ($c == "ASC") 
			$s = "<img src ='images/ico/desc.png'>";
		else 
			$s = "<img src ='images/ico/asc.png'>";
	} 	
	return $s;
}
function FormatMysqlDateBaru($f_x){
 $cek = strstr((string) $f_x,"-");
 if( strlen($cek) == 0)
  {
   $f_y1= explode(' ', (string) $f_x);
   $f_y =  $f_y1[1];
   $bulan = nilaibulan($f_y );
   $lengkap = $f_y1[2]."-".$bulan."-".$f_y1[0] ;
   return $lengkap ;
  }else  {
  $lengkap = MySqlDateFormat($f_x);
     return $lengkap ;

  }
}
function tanggal($tanggal)
{
 $las_tanggal=date("t");
if($tanggal=="")
{
   $tanggal=date("d");
 }
    for($i=1;$i<=$las_tanggal; $i++)
  {
    if($i==$tanggal)
	{
	  $sel="selected";
	
    }else{ 
	  $sel="" ;
	}
   
    echo "<option value='$i' $sel >$i </option>";
  }
  
}
function bulan($bulan)
 { 
 if($bulan==""){
  $bulan=date("m");
 }
  $bulan_a= ["01"=>"januari", "02"=>"Februari", "03"=>"maret", "04"=>"april", "05"=>"mei", "06"=>"juni", "07"=>"juli", "08"=>"Agustus", "09"=>"september", "10"=>"oktober", "11"=>"November", "11"=>"desember"];

    foreach ($bulan_a as $key => $val) {
 
    if($key=="$bulan")
	{
	  $sel="selected";
	
    }else{ 
	  $sel="" ;
	}
   
    echo "<option value='$key' $sel >$val </option>";
	 
   }
 }
 function tahun($tahun)
 {
   if($tahun=="")
    {
    $tahun=date("Y");
	 }
   for($i=2005; $i<2030; $i++)
    {
	  if($i==$tahun)
	  {
	    $sel="selected";
	    }else{ 
	  $sel="" ;
	  }
       echo "<option value='$i' $sel >$i </option>";
	}
  }
  function resize_foto($file,$name) {
	$src = imagecreatefromjpeg($file); 

	$filename = $name;
	[$width, $height]=getimagesize($file);
	if ($width<$height){
		$newheight=170;
		$newwidth=113;
	} else if ($width>$height){
		$newwidth=170;
	$newheight=113;
	}
	$tmp=imagecreatetruecolor($newwidth,$newheight);
	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
	imagejpeg($tmp,$filename,100);
	imagedestroy($src);
	imagedestroy($tmp);
}
function LowRomeNumber($int){
$r=['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII', 'XVIII', 'XIX', 'XX', 'XXI', 'XXII', 'XXIII', 'XXIV', 'XXV', 'XXVI', 'XXVII', 'XXVIII', 'XXIX', 'XXX'];
$int=(int)$int;
	if ($int>0 && $int<=30){
		return $r[$int-1];
	} else {
		return "Out Of Range";
	}
}
function Number2Alphabet($int){
$r=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
$int=(int)$int;
	if ($int>0 && $int<=30){
		return $r[$int-1];
	} else {
		return "Out Of Range";
	}
}

function Debug($name, $variable)
{
	echo "<pre>$name: ";
	print_r($variable);
	echo "</pre>";
}

function format_tgl($tanggal)
{
	$mdy = explode('-',(string) $tanggal);
	$hasil = $mdy[2].' '.NamaBulan($mdy[1]).' '.$mdy[0];
	
	return $hasil;
}

function CQ($string)
{
	return $string;
}

function MaxDayInMonth($month, $year)
{
	return match ($month) {
     1, 3, 5, 7, 8, 10, 12 => 31,
     4, 6, 9, 11 => 30,
     default => $year % 4 == 0 ? 29 : 28,
 };
}

function random($number) 
{
	if ($number)
	{
		$total = '';
    	for($i=1;$i<=$number;$i++)
		{
       		$nr=random_int(0,9);
       		$total .= $nr;
       	}
    	return $total;
	}
}

?>