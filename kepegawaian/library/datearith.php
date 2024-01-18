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
class DateArith
{
	public static function DaysInMonth($M, $Y)
	{
		if ($M == 1 || $M == 3 || $M == 5 || $M == 7 || $M == 8 || $M == 10 || $M == 12)
			return 31;
		elseif ($M == 4 || $M == 6 || $M == 9 || $M == 11)  
			return 30;
		else
			if ((abs($Y - 2000) % 4) == 0)
				return 29;
			else
				return 28;
	}

	public static function DaysInYear($Y)
	{
		$nDiv4 = floor($Y / 4);
		$nNon4 = $Y - $nDiv4;
		return $nDiv4 * 366 + $nNon4 * 365;
	}

	// format dt must be YY-MM-DD HH:MN:SS
	//   second will be ignored
	public static function DateToMinute($dt)
	{
		$pos1 = 0;
		$pos2 = strpos((string) $dt, "-", $pos1);
		$YY =   substr((string) $dt, $pos1, $pos2 - $pos1);

		$pos1 = $pos2 + 1;
		$pos2 = strpos((string) $dt, "-", $pos1);
		$MM   = substr((string) $dt, $pos1, $pos2 - $pos1);

		$pos1 = $pos2 + 1;
		$pos2 = strpos((string) $dt, " ", $pos1);
		$DD   = substr((string) $dt, $pos1, $pos2 - $pos1);

		$pos1 = $pos2 + 1;
		$pos2 = strpos((string) $dt, ":", $pos1);
		$HH   = substr((string) $dt, $pos1, $pos2 - $pos1);

		$pos1 = $pos2 + 1;
		$pos2 = strpos((string) $dt, ":", $pos1);
		$MN   = substr((string) $dt, $pos1, $pos2 - $pos1);

		$total  = $MN;
		$total += $HH * 60;
		$total += $DD * 24 * 60; 
		for ($i = 1; $i <= $MM; $i++)
			$total += DateArith::DaysInMonth($i, $YY) * 24 * 60;
		$total += DateArith::DaysInYear($YY) * 24 * 60;

		return $total;
	}

	public static function FormatDigit($digit)
	{
		return strlen((string) $digit) == 2 ? $digit : "0$digit";
	}

	public static function TimeDiff($time1, $time2, &$hourDiff, &$minuteDiff, &$secondDiff)
	{
		$hourDiff = 0;
		$minuteDiff = 0;
		$secondDiff = 0;

		if (!str_contains((string) $time1, ":"))
			return;

		if (!str_contains((string) $time2, ":"))
			return;

		$atime1 = explode(":", (string) $time1);
		$atime2 = explode(":", (string) $time2);

		if (count($atime1) == 2)
			$itime1 = [$atime1[0], $atime1[1], 0];
		else
			$itime1 = [$atime1[0], $atime1[1], $atime1[2]];

		if (count($atime2) == 2)
			$itime2 = [$atime2[0], $atime2[1], 0];
		else
			$itime2 = [$atime2[0], $atime2[1], $atime2[2]];

		if (count($itime1) != 3 || count($itime2) != 3)
			return;

		//if ($itime2[0] == 0)
		//	$itime2[0] = 24;

		if ($itime1[0] > $itime2[0])
		{
			$itime2[0] += 24;
			/*
			$tempi = $itime1;
			$itime1 = $itime2;
			$itime2 = $tempi;
			*/
		}

		$deltam = 0;
		$temp = $itime2[2] - $itime1[2];
		if ($temp < 0)
		{
			$secondDiff = 60 + $temp;
			$deltam = 1;
		}
		else
		{
			$secondDiff = $temp;
		}

		$deltah = 0;
		$temp = $itime2[1] - $itime1[1];
		if ($temp < 0)
		{
			$minuteDiff = 60 + $temp;
			$deltah = 1;
		}
		else
		{
			$minuteDiff = $temp;
		}
		$minuteDiff -= $deltam;

		$hourDiff = abs($itime2[0] - $itime1[0]);
		$hourDiff -= $deltah;
	}
}
?>