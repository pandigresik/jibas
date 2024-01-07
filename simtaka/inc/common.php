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
require_once("session.checker.php");

$bulan = array(1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agust','Sep','Okt','Nov','Des');

function StringIsSelected($value, $comparer)
{
	if ($value == $comparer) 
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

function IntIsSelected($value, $comparer) {
	$a = (int)$value;
	$b = (int)$comparer;
	
	if ($a == $b) 
		return "selected";
	else
		return "";
}
function LongDateFormat($mysqldate) {
	list($y, $m, $d) = explode('[/.-]', $mysqldate); 
	return "$d ". NamaBulan($m) ." $y";
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
		return "November";
	elseif ($bln == 12)
		return "Desember";		
}
function BlnPdk($bln) {
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
		return "Nov";
	elseif ($bln == 12)
		return "Des";		
}
function JmlHari($bln,$th) {
	if ($bln == 4 || $bln == 6|| $bln == 9 || $bln == 11) 
		$n = 30;
	else if ($bln == 2 && $th % 4 <> 0)
		$n = 28;
	else if ($bln == 2 && $th % 4 == 0)
		$n = 29;
	else 
		$n = 31;
	return $n;
}
function getname($field,$table,$cond){	
	OpenDb();
	$sql = "SELECT $field FROM $table WHERE replid='$cond'";
	$result = QueryDb($sql);
	$row = @mysqli_fetch_array($result);
	return $row[0];
}
function getname2($field,$table,$field2,$cond){	
	OpenDb();
	$sql = "SELECT $field FROM $table WHERE $field2='$cond'";
	$result = QueryDb($sql);
	$row = @mysqli_fetch_array($result);
	return $row[0];
}
function MySqlDateFormat($date) {
	list($d, $m, $y) = explode('[/.-]', $date); 
	return "$y-$m-$d";
}
function RegularDateFormat($date) {
	list($y, $m, $d) = explode('[/.-]', $date); 
	return "$d-$m-$y";
}
function chg_p_to_div($string){
	$content = str_replace('<p','<div',$string);
	$content = str_replace('</p>','</div>',$content);
	return $content;
}

function CQ($string)
{
	return $string;
}
?>