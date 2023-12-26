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
require_once("../include/config.php");
require_once("../include/common.php");
require_once("../include/compatibility.php");
require_once("../library/datearith.php");

if (!isset($_REQUEST['selyear']))
{
    $selyear = date('Y');
    $selmonth = date('n');
    $selday = date('j');
}
else
{
    $selyear = $_REQUEST['selyear'];
    $selmonth = $_REQUEST['selmonth'];
    $selday = $_REQUEST['selday'];
    $selno = $_REQUEST['selno'];
}
$selmaxday = DateArith::DaysInMonth($selmonth, $selyear);
?>
<select name="tahun<?=$selno?>" id="tahun<?=$selno?>" onchange="changeDate(<?=$selno?>)">
<?php
for($i = $G_START_YEAR; $i <= date('Y'); $i++)
{
	$sel = $i == $selyear ? "selected" : "";
	echo "<option value='$i' $sel>$i</option>";
}
?>
</select>
<select name="bulan<?=$selno?>" id="bulan<?=$selno?>" onchange="changeDate(<?=$selno?>)" style="width: 70px">
<?php
for($i = 1; $i <= 12; $i++)
{
	$sel = $i == $selmonth ? "selected" : "";
	echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
}
?>
</select>
<select name="tanggal<?=$selno?>" id="tanggal<?=$selno?>">
<?php
for($i = 1; $i <= $selmaxday; $i++)
{
	$sel = $i == $selday ? "selected" : "";
	echo "<option value='$i' $sel>" . $i . "</option>";
}
?>
</select>