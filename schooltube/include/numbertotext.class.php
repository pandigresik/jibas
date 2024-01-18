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
class NumberToText
{
	private ?string $number = null;
	private ?string $fraction = null;
	private string $dictionary = "0123456789.";
	
	public function Convert($number)
	{
		$number = trim((string) $number);
		if (!$this->IsValidNumber($number))
			throw new Exception("Invalid number");
		
		$pos = strpos($number, ".");
		if ($pos !== FALSE)
		{
			$this->number = substr($number, 0, $pos);
			$this->fraction = substr($number, $pos + 1);
			if ((int)$this->fraction == 0)
				$this->fraction = "";
		}
		else
		{
			$this->number = trim($number);
			$this->fraction = "";
		}
		
		$numlen = strlen($this->number);
		$fraclen = strlen($this->fraction);
		
		//echo "num = $this->number<br>";
		//echo "frac = $this->fraction<br>";
		
		if ($numlen > 15)
			throw new Exception("Number length should less or equal to 15!");
		
		$result = "";
		$index = 0;
		while ($index < $numlen)
		{
			$result = $result . $this->ParseNumber($index, $numlen) . " ";
			$index++;
		}
		
		if ($fraclen > 0)
		{
			$result = $result . "Koma ";
			$index = 0;
			while ($index < $fraclen)
			{
				$digit = substr($this->fraction, $index, 1);
				$result = $result . $this->DigitToText($digit) . " ";
				$index++;
			}
		}
		
		return trim($result);
	}
	
	private function ParseNumber(&$index, $numlen)
	{
		$digit = substr((string) $this->number, $index, 1);
		$position = $numlen - $index;
		
		//echo "ix $index d $digit p $position";
		$result = "";
		
		if ($digit == "0")
		{
			//echo " r <br>";
			return "";
		}
		
		if ($position == 1 || $position == 7 || $position == 10 || $position == 13)
		{
			$result = $this->DigitToText($digit);
			
			if ($position == 7)
				$result = $result . " Juta";
			elseif ($position == 10)
				$result = $result . " Milyar";
			elseif ($position == 13)
				$result = $result . " Trilyun";
		}
		elseif ($position == 2 || $position == 5 || $position == 8 || $position == 11 || $position == 14)
		{
			$nextdigit = substr((string) $this->number, $index + 1, 1);
			if ($digit == "1")
			{
				if ($nextdigit == "0")
					$result = "Sepuluh";
				elseif ($nextdigit == "1")
					$result = "Sebelas";
				else
					$result = $this->DigitToText($nextdigit) . " Belas";
				$index++;
			}
			else
			{
				if ($nextdigit == "0")
					$result = $this->DigitToText($digit) . " Puluh";
				else
					$result = $this->DigitToText($digit) . " Puluh " . $this->DigitToText($nextdigit);
				$index++;
			}
			
			if ($position == 5)
				$result = $result . " Ribu";
			elseif ($position == 8)
				$result = $result . " Juta";
			elseif ($position == 11)
				$result = $result . " Milyar";
			elseif ($position == 14)
				$result = $result . " Trilyun";
		}
		elseif ($position == 3 || $position == 6 || $position == 9 || $position == 12 || $position == 15)
		{
			if ($digit == "1")
				$result = "Seratus";
			else
				$result = $this->DigitToText($digit) . " Ratus";
			
			if (substr((string) $this->number, $index + 1, 2) == "00")
			{
				if ($position == 6)
					$result = $result . " Ribu";
				elseif ($position == 9)
					$result = $result . " Juta";
				elseif ($position == 12)
					$result = $result . " Milyar";
				elseif ($position == 15)
					$result = $result . " Trilyun";	
					
				$index += 2;
			}
			elseif (substr((string) $this->number, $index + 1, 1) == "0") 
			{
				$index++;
			}
		}
		elseif ($position == 4)
		{
			if ($digit == "1" && $numlen == 4)
				$result = "Seribu";
			else
				$result = $this->DigitToText($digit) . " Ribu";
		}
		
		//echo " r $result <br>";
		
		return $result;
	}
	
	private function IsValidNumber($number)
	{
		$valid = true;
		$numlen = strlen((string) $number);
		
		for($i = 0; $valid && $i < $numlen; $i++)
		{
			$digit = substr((string) $number, $i, 1);
			$valid = str_contains((string) $this->dictionary, $digit);
		}
		
		return $valid;
	}
	
	private function DigitToText($digit)
	{
		switch ($digit)
		{
			case "0": return "Nol"; break;
			case "1": return "Satu"; break;
			case "2": return "Dua"; break;
			case "3": return "Tiga"; break;
			case "4": return "Empat"; break;
			case "5": return "Lima"; break;
			case "6": return "Enam"; break;
			case "7": return "Tujuh"; break;
			case "8": return "Delapan"; break;
			case "9": return "Sembilan";
		}
	}
}
?>