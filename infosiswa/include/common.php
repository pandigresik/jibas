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
require_once("sessionchecker.php");

$kriteria = [1 => 'Agama', 'Asal Sekolah', 'Golongan Darah', 'Jenis Kelamin', 'Kewarganegaraan', 'Kode Pos Siswa', 'Kondisi Siswa', 'Pekerjaan Ayah', 'Pekerjaan Ibu', 'Pendidikan Ayah', 'Pendidikan Ibu', 'Penghasilan Orang Tua', 'Status Aktif', 'Status Siswa', 'Suku', 'Tahun Kelahiran', 'Usia'];
$kriteria_judul = [1 => 'AGAMA', 'ASAL SEKOLAH', 'GOLONGAN DARAH', 'JENIS KELAMIN', 'KEWARGANEGARAAN', 'KODE POS SISWA', 'KONDISI SISWA', 'PEKERJAAN AYAH', 'PEKERJAAN IBU', 'PENDIDIKAN AYAH', 'PENDIDIKAN IBU', 'PENGHASILAN ORANG TUA', 'STATUS AKTIF', 'STATUS SISWA', 'SUKU', 'TAHUN KELAHIRAN', 'USIA'];
$kriteria_tabel = [1 => 'agama', 'asalsekolah', 'darah', 'kelamin', 'warga', 'kodepossiswa', 'kondisi', 'pekerjaanayah', 'pekerjaanibu', 'pendidikanayah', 'pendidikanibu', 'penghasilanayah', 'aktif', 'status', 'suku', 'tgllahir'];
$kriteria_file = [1 => 'agama', 'asalsekolah', 'darah', 'kelamin', 'warga', 'kodepos', 'kondisi', 'pekerjaanayah', 'pekerjaanibu', 'pendidikanayah', 'pendidikanibu', 'penghasilan', 'aktif', 'status', 'suku', 'tahunlahir', 'usia'];
$bulan = [1=>'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agust', 'Sep', 'Okt', 'Nov', 'Des'];
$bulan_pjg = [1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

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
		return "November";
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
		return "Nov";
	elseif ($bln == 12)
		return "Des";		
}
function NamaHari($hari) {
	if ($hari == 1)
		return "Senin";
	elseif ($hari == 2)
		return "Selasa";		
	elseif ($hari == 3)
		return "Rabu";		
	elseif ($hari == 4)
		return "Kamis";		
	elseif ($hari == 5)
		return "Jumat";
	elseif ($hari == 6)
		return "Sabtu";
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
function TglDb($value) {
	$tanggal = substr((string) $value,0,2);
	$bulan = substr((string) $value,3,2);
	$tahun = substr((string) $value,6,4);
	$tgl=$tahun."-".$bulan."-".$tanggal;
	return $tgl;
}

function TglText($value) {
	$tahun = substr((string) $value,0,4);
	$bulan = substr((string) $value,5,2);
	$tanggal = substr((string) $value,8,2);
	$tgl=$tanggal."-".$bulan."-".$tahun;
	return $tgl;
}
function TglTextLong($value) {
	$value=trim((string) $value);
	$tahun = substr($value,0,4);
	$bulan = substr($value,5,2);
	$tanggal = substr($value,8,2);
	switch ($bulan){
		case '01':
			$nama_bulan="Januari";
			break;
		case '02':
			$nama_bulan="Februari";
			break;
		case '03':
			$nama_bulan="Maret";
			break;
		case '04':
			$nama_bulan="April";
			break;
		case '05':
			$nama_bulan="Mei";
			break;
		case '06':
			$nama_bulan="Juni";
			break;
		case '07':
			$nama_bulan="Juli";
			break;
		case '08':
			$nama_bulan="Agustus";
			break;
		case '09':
			$nama_bulan="September";
			break;
		case '10':
			$nama_bulan="Oktober";
			break;
		case '11':
			$nama_bulan="November";
			break;
		case '12':
			$nama_bulan="Desember";
			break;
	}
	if ($tanggal<10){
		$tanggal=substr($tanggal,1,1);
	} else {
		$tanggal=$tanggal;
	}
	$tgl=$tanggal." ".$nama_bulan." ".$tahun;
	return $tgl;
}
function TglTextShort($value) {
	$tahun = substr((string) $value,0,4);
	$bulan = substr((string) $value,5,2);
	$tanggal = substr((string) $value,8,2);
	switch ($bulan){
		case '01':
			$nama_bulan="Jan";
			break;
		case '02':
			$nama_bulan="Feb";
			break;
		case '03':
			$nama_bulan="Mar";
			break;
		case '04':
			$nama_bulan="Apr";
			break;
		case '05':
			$nama_bulan="Mei";
			break;
		case '06':
			$nama_bulan="Jun";
			break;
		case '07':
			$nama_bulan="Jul";
			break;
		case '08':
			$nama_bulan="Agust";
			break;
		case '09':
			$nama_bulan="Sep";
			break;
		case 10:
			$nama_bulan="Okt";
			break;
		case 11:
			$nama_bulan="Nov";
			break;
		case 12:
			$nama_bulan="Des";
			break;
	}
	if ($tanggal<10){
		$tanggal=substr($tanggal,1,1);
	} else {
		$tanggal=$tanggal;
	}
	$tgl=$tanggal." ".$nama_bulan." ".$tahun;
	return $tgl;
}
function format_tgl($tanggal){
	$mdy = explode('-',(string) $tanggal);
	$hasil = $mdy[2].' '.NamaBulan($mdy[1]).' '.$mdy[0];
	
	return $hasil;
}
function format_tgl_blnnmr($tanggal){
	$mdy = explode('-',(string) $tanggal);
	$hasil = $mdy[2].'-'.$mdy[1].'-'.$mdy[0];
	
	return $hasil;
}
function unformat_tgl($tanggal){
	$mdy = explode('-',(string) $tanggal);
	$hasil = $mdy[2].'-'.$mdy[1].'-'.$mdy[0];
	
	return $hasil;
}
	
function change_urut($a, $b, $c) {	
	$s = "";
	if ($a == $b) {
		if ($c == "ASC") 
			$s = "<img src ='../images/ico/descending copy.png'>";
		else 
			$s = "<img src ='../images/ico/ascending copy.png'>";
	} 	
	return $s;
}

function removetag($input)
{

	$output="";
	$ambil=0;
	$charlength=strLen((string) $input);
	for ($i=0;$i<=1000;$i++)
	{
		$karakter=substr((string) $input,$i,1);
		if ($ambil==1)
			$ambil=2;
		if ($karakter=="<" || $karakter=="&")
			$ambil=0;
		if ($karakter==">")
			$ambil=1;
		if ($ambil==2 && $karakter!="�")
			$output=$output.$karakter;//document.getElementById('output').value=temp+karakter;
	}
	return $output;
}

function chg_p_to_div($string)
{
	$content = str_replace('<p','<div',(string) $string);
	$content = str_replace('</p>','</div>',$content);
	return $content;
}

function GetOSSlash()
{
	global $G_OS;
	if ($G_OS=="win")
		return "\\";
	elseif ($G_OS=="lin")
		return "/";
}

function CQ($string)
{
	return $string;
}

function RandomString($length)
{
    $set = "abcdefghijklmnoprstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ0123456789";
    
    $result = "";
    for($i = 0; $i < $length; $i++)
    {
        $ix = random_int(0, strlen($set) - 1);
        $result .= substr($set, $ix, 1);
    }
    
    return $result;
}
?>