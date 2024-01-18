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
require_once('../inc/config.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');

OpenDb();

$bulan = "";
if (isset($_REQUEST['bulan']))
	$bulan = $_REQUEST['bulan'];

$elementid = "";
if (isset($_REQUEST['elementid']))
	$elementid = $_REQUEST['elementid'];
	
$tahun = "";
if (isset($_REQUEST['tahun']))
	$tahun = $_REQUEST['tahun'];

if (($bulan == "") || ($tahun == "")) {
	$sql = "SELECT MONTH(NOW()), YEAR(NOW()), DAY(NOW())";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$bulan = $row[0];
	$tahun = $row[1];
};
$sql = "SELECT MONTH(NOW()), YEAR(NOW()), DAY(NOW())";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tanggalskr = $row[2];
$bulanskr = $row[0];
$tahunskr = $row[1];
$tmp = $tahun."-".$bulan."-1";
$sql = "SELECT DAYOFWEEK('$tmp')";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$first_weekday_this_month = $row[0];

if ($bulan == 12) {
	$next_month = 1;
	$next_year = $tahun + 1;
} else {
	$next_month = $bulan + 1;
	$next_year = $tahun;
}


if ($bulan == 1) {
	$last_month = 12;
	$last_year = $tahun - 1;
	
	$tmp = ($tahun - 1) . "-12-1";
} else {
	$last_month = $bulan - 1;
	$last_year = $tahun;
	
	$tmp = $tahun . "-" . ($bulan - 1) . "-1";
}	
$sql = "SELECT DAY(LAST_DAY('$tmp'))";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$last_day_last_month = $row[0];

$now = $tahun . "-" . $bulan . "-1";
$sql = "SELECT DAY(LAST_DAY('$now'))";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$last_day_this_month = $row[0];

$nweek = 0;
$nday = 0;
for ($i = 0; $i < ($first_weekday_this_month - 1); $i++) {
	$cal[$nweek][$nday][0] = $last_day_last_month - ($first_weekday_this_month - 1) + ($i + 1);
	$cal[$nweek][$nday][1] = $last_month;
	$cal[$nweek][$nday][2] = $last_year;
	
	$nday++;
	//echo $cal[$nweek][$nday] . "<br>";
}

for ($i = 1; $i <= $last_day_this_month; $i++) {
	$cal[$nweek][$nday][0] = $i;
	$cal[$nweek][$nday][1] = $bulan;
	$cal[$nweek][$nday][2] = $tahun;
	
	if ($nday == 6) {
		$nday = 0;
		$nweek++;
	} else {
		$nday++;
	}
}

if (($nday > 0) && ($nday < 7)) {
	$start = 1;
	for ($i = $nday; $i < 7; $i++) {
		$cal[$nweek][$i][0] = $start++;
		$cal[$nweek][$i][1] = $next_month;
		$cal[$nweek][$i][2] = $next_year;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../style/style.css" />
<title>Date Picker</title>
<style>
.thismonth {
	font-family: Verdana, "Calibri";
	font-size: 12px;
	font-weight: bold;
}
.today {
	font-family: Verdana, "Calibri";
	font-size: 13px;
	font-weight: bolder;
	text-decoration :blink;
	color:red;
}

.othermonth {
	font-family: Verdana, "Calibri";
	font-size: 10px;
	font-weight: bold;
	color:#999999;
}
</style>

<script language="javascript">
function GoToLastMonth() {
	document.location.href = "cals.php?bulan=<?=$last_month?>&tahun=<?=$last_year?>&elementid=<?=$elementid?>";
}

function GoToNextMonth() {
	document.location.href = "cals.php?bulan=<?=$next_month?>&tahun=<?=$next_year?>&elementid=<?=$elementid?>";
}

function ChangeCal() {
	var bulan = document.getElementById('bulan').value;
	var tahun = document.getElementById('tahun').value;
	document.location.href = "cals.php?elementid=<?=$elementid?>&bulan=" + bulan + "&tahun=" + tahun;
}


function refresh() {
	var bulan = document.getElementById('bulan').value;
	var tahun = document.getElementById('tahun').value;
	document.location.href = "cals.php?elementid=<?=$elementid?>&bulan=" + bulan + "&tahun=" + tahun;
	parent.kanan.location.href = "blankagenda.php";
}

function ambil(tgl,elementid) {
	parent.opener.AcceptDate(tgl,elementid);
	window.close();
}

</script>
</head>

<body leftmargin="0" topmargin="0" style="background-color:#DFDFDF" onload="tampil()">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td width="70%" align="center"><input type="button" class="cmbfrm2" onclick="GoToLastMonth()" value="  <  ">
<select name="bulan" class="cmbfrm" id="bulan" onchange="ChangeCal()">
<?php $namabulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"];
   $namabulanpjg = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
   for ($i = 1; $i <= 12; $i++) { ?>
	<option value="<?=$i?>" <?=IntIsSelected($i, $bulan)?>><?=$namabulanpjg[$i - 1]?></option>
<?php } ?>
</select>    
<select name="tahun" class="cmbfrm" id="tahun" onchange="ChangeCal()">
<?php $YNOW = date('Y');
   for ($i = 1900; $i <= $YNOW + 1; $i++) { ?>
	<option value="<?=$i?>" <?=IntIsSelected($i, $tahun)?>><?=$i?></option>
<?php } ?>
</select>    
<input type="button" class="cmbfrm2" onclick="GoToNextMonth()" value="  >  ">
</td>
</tr>
</table>
<table border="0" cellpadding="5" cellspacing="1" width="100%" style="border-color:#999999">
<tr height="20" bgcolor="#DFFFDF">
	<td width="30" align="center" style="background-color:#990000; color:#FFFFFF"><b>Minggu</b></td>
    <td width="30" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Senin</b></td>
    <td width="30" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Selasa</b></td>
    <td width="30" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Rabu</b></td>
    <td width="30" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Kamis</b></td>
    <td width="30" align="center" style="background-color:#339900; color:#FFFFFF"><b>Jum'at</b></td>
    <td width="30" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Sabtu</b></td>
</tr>
<?php
for ($i = 0; $i < count($cal); $i++) { 
	echo "<tr height='15'>";
	for ($j = 0; $j < count($cal[$i]); $j++) {
		$tgl = $cal[$i][$j][0];
		$bln = $cal[$i][$j][1];
		$thn = $cal[$i][$j][2];
		
		$tanggal = "$thn-$bln-$tgl";
		
		if (($bln == $bulan) && ($thn == $tahun)){
			if (($tgl == $tanggalskr) && ($bln == $bulanskr) && ($thn == $tahunskr)) {
				$dis="<a title='".$tgl." ".$namabulanpjg[$bln-1]." ".$thn."' style=\"text-decoration:none;\" href=\"JavaScript:ambil('".$tgl."-".$bln."-".$thn."','".$elementid."')\"> <span class='today'>$tgl</span></a>";	
			} else {
				$dis="<a title='".$tgl." ".$namabulanpjg[$bln-1]." ".$thn."' style=\"text-decoration:none;\" href=\"JavaScript:ambil('".$tgl."-".$bln."-".$thn."','".$elementid."')\"> <span class='thismonth'>$tgl</span></a>";	
			}
			$style = "thismonth";
		} else {
			$dis="<span class='othermonth'>$tgl</span>";
			$style = "othermonth";
		}
		if ($j == 0)
			$color = "#FFCCCC";
		elseif ($j == 5) 
			$color = "#DFFFDF";
		else
			$color = "#DFEFFF";
			
		echo "<td width='30' align='center' valign='middle' style='background-color: $color' onclick=\'ambil(\'".$tgl."-".$bln."-".$thn."\','".$elementid."')\'>";
		echo $dis;
	
		
		echo "</td>";
	}
	echo "</tr>";
}
CloseDb();
?>
</table>
</body>
</html>