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
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../inc/sessioninfo.php');

function getDepartemen($access)
{
	if ($access == "ALL")
	{
		$sql = "SELECT departemen FROM departemen where aktif=1 ORDER BY urutan ";
		$result = QueryDb($sql);
		$i = 0;
		while($row = mysqli_fetch_row($result))
		{
			$dep[$i] = $row[0];
			$i++;
		}
	}
	else
	{
		$i = 0;
		foreach($access as $value)
		{
			$dep[$i] = $value;
			$i++;
		}
	}
	
	return $dep;
}

function getDepartemenStringList($access)
{
	$depList = "";
	if ($access == "ALL")
	{
		$sql = "SELECT departemen FROM departemen where aktif=1 ORDER BY urutan ";
		$result = QueryDb($sql);
		$i = 0;
		while($row = mysqli_fetch_row($result))
		{
			if ($depList != "")
				$depList .= ",";
			
			$depList .= "'" . $row[0] . "'";	
		}
	}
	else
	{
		$i = 0;
		foreach($access as $value)
		{
			if ($depList != "")
				$depList .= ",";
				
			$depList .= "'" . $value . "'";		
		}
	}
	
	return $depList;
}
?>