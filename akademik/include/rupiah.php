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
function KataAngka($num) {
	switch($num) {
		case 0:	return "";			break;
		case 1:	return "Satu";		break;
		case 2:	return "Dua";		break;
		case 3:	return "Tiga";		break;
		case 4:	return "Empat";		break;
		case 5:	return "Lima";		break;
		case 6:	return "Enam";		break;
		case 7:	return "Tujuh";		break;
		case 8:	return "Delapan";	break;
		case 9:	return "Sembilan";	break;
	}
}

function FormatNumerik($value) {
	if (!is_numeric($value)) 
		return $value;
		
	$duit = (string)$value;
	$duit = trim($duit);
	if (strlen($duit) == 0) return "";
	
	if (strstr($duit,"E")) 
		return $duit; 
	
	
	$posmin = strpos($duit, "-");
	if ($posmin === false) 
		$negatif = 0;
	else
		$negatif = 1;
	
	if ($negatif) 
		$duit = str_replace("-", "", $duit);

	$len = strlen($duit);
	$nPoint = (int)($len / 3);
	if (($len % 3) == 0)
		$nPoint--;
	
	$rp = "";
	for ($i = 0; $i < $nPoint; $i++) {
		$j = 0;
		$temp = "";
		while((strlen($duit) >= 0) && ($j++ < 3)) {
			$temp = substr($duit, strlen($duit) - 1, 1) . $temp;
			if (strlen($duit) >= 2)
				$duit = substr($duit, 0, strlen($duit) - 1);
			else
				$duit = "";
		}
		if (strlen($rp) > 0)
			$rp = $temp . "," . $rp;
		else
			$rp = $temp;		
	}
	if (strlen($duit) > 0)
		$rp = $duit . "," . $rp;
	
	if ($negatif)
		return "-" . $rp;
	else
		return $rp;
		
	
}

function FormatRupiah($value) {
	//if (!is_numeric($value)) 
	//	return $value;
	$value=number_format($value, '0', ",","" );	
	$duit = (string)$value;
	$duit = trim($duit);
	if (strlen($duit) == 0) return "";
	
	if (strstr($duit,"E")) 
		return "Rp ".$duit; 
	
	
	$posmin = strpos($duit, "-");
	if ($posmin === false) 
		$negatif = 0;
	else
		$negatif = 1;
	
	if ($negatif) 
		$duit = str_replace("-", "", $duit);

	$len = strlen($duit);
	$nPoint = (int)($len / 3);
	if (($len % 3) == 0)
		$nPoint--;
	
	$rp = "";
	for ($i = 0; $i < $nPoint; $i++) {
		$j = 0;
		$temp = "";
		while((strlen($duit) >= 0) && ($j++ < 3)) {
			$temp = substr($duit, strlen($duit) - 1, 1) . $temp;
			if (strlen($duit) >= 2)
				$duit = substr($duit, 0, strlen($duit) - 1);
			else
				$duit = "";
		}
		if (strlen($rp) > 0)
			$rp = $temp . "." . $rp;
		else
			$rp = $temp;		
	}
	if (strlen($duit) > 0)
		$rp = $duit . "." . $rp;
	
	if ($negatif)
		return "(Rp " . $rp . ")";
	else
		return "Rp " . $rp;
		
	
}

function UnformatRupiah($value) {
	$pos = strpos((string) $value, "(");

	$negatif = true;
	if ($pos === false) 
		$negatif = false;

	$value = str_replace("Rp","",(string) $value);
	$value = str_replace(".","",$value);
	$value = str_replace(" ","",$value);
	$value = str_replace("(","",$value);
	$value = str_replace(")","",$value);
	
	
	if ($negatif)
		$num = "-" . $value;
	else
		$num = $value;
		
	return (float)$num;
}

function KalimatUang($uang) {
	if ($uang == 0) 
		return "Nol Rupiah";
	
	$sUang = (string)$uang;
	$nAngka = strlen($sUang);
	
	for($i = 0; $i < $nAngka; $i++) {
		$d = substr($sUang, $i, 1);
		$digit[$i] = $d;	
	}
	
	$kalimat = "";
	$i = 0;
	$nAwalanNol = 0;
	while ($i < $nAngka) {
		$d = (int)$digit[$i];
		$nSisaDigit = $nAngka - $i - 1;
		
		$kata = "";
		if ($d == 1) {
			switch($nSisaDigit) {
				case 0:
					$kata = "Satu"; break;
				case 1:
				case 4:
				case 7:
				case 10:
					$i++;
					$d = (int)$digit[$i];
					$nSisaDigit--;
					if ($d == 0) {
						$kata = "Sepuluh ";
					} else if ($d == 1) {
						$kata = "Sebelas ";
					} else {
						$kata = KataAngka($d) . " Belas ";	
					}
					switch($nSisaDigit) {
						case 3:
							$kata = $kata . " Ribu ";
							break;
						case 6:
							$kata = $kata . " Juta ";
							break;
						case 9:
							$kata = $kata . " Milyar ";
							break;
					}
					break;
				case 2:
				case 5:
				case 8:
					$kata = "Seratus "; break;
				case 3:
					$kata = "Seribu "; break;
				case 6:
					$kata = "Satu Juta "; break;
				case 9:
					$kata = "Satu Milyar "; break;
			}
		} else if ($d != 0) {
			switch($nSisaDigit) {
				case 0:
					$kata = KataAngka($d); break;
				case 1:
				case 4:
				case 7:
				case 10:
				case 13:
					$kata = KataAngka($d) . " Puluh ";
					if ($digit[$i + 1] == 0) {
						if ($nSisaDigit - 1 == 3)
							$kata = $kata . " Ribu ";
						else if ($nSisaDigit - 1 == 6) 
							$kata = $kata . " Juta ";
						
						$i++;
					}
					break;
				case 2:
				case 5:
				case 8:
				case 11:
				case 14:
					$kata = KataAngka($d) . " Ratus ";
					break;
				case 3:
					$kata = KataAngka($d) . " Ribu ";
					break;
				case 6:
					$kata = KataAngka($d) . " Juta ";
					break;
				case 9:
					$kata = KataAngka($d) . " Milyar ";
					break;
				case 12:
					$kata = KataAngka($d) . " Trilyun ";
					break;
			}
		} else {
			$nAwalanNol++;
			switch($nSisaDigit) {
				case 3:
					if ($nAwalanNol < 3) {
						$kata = "Ribu ";
						$nAwalanNol = 0;
					}
					break;
				case 6:
					if ($nAwalanNol < 3) {
						$kata = "Juta ";
						$nAwalanNol = 0;
					}
					break;
				case 9:
					if ($nAwalanNol < 3) {
						$kata = "Milyar ";
						$nAwalanNol = 0;
					}
					break;
			}
		}
		
		$kalimat = $kalimat . $kata;
		$i++;
	}
	
	return $kalimat . " Rupiah";
}
?>