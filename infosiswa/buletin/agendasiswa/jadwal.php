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
require_once('../../include/config.php');
require_once('../../include/getheader.php');
require_once('../../include/common.php');
require_once('../../include/db_functions.php');
require_once("../../include/sessioninfo.php");
//../../include('cek.php');

OpenDb();

$bulan = "";
if (isset($_REQUEST['bulan']))
	$bulan = $_REQUEST['bulan'];
	
$tahun = "";
if (isset($_REQUEST['tahun']))
	$tahun = $_REQUEST['tahun'];

if (($bulan == "") || ($tahun == "")) {
	$sql = "SELECT MONTH(NOW()), YEAR(NOW())";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$bulan = $row[0];
	$tahun = $row[1];
};	

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
<link rel="stylesheet" href="../../style/style.css" />
<link rel="stylesheet" href="../../style/tooltips.css">
<title>SISFO KUA</title>
<style>
.thismonth {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 16px;
	font-weight: bold;
}

.othermonth {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 13px;
	font-weight: bold;
	color:#999999;
}
</style>
<script language="javascript" src="../../script/ajax.js"></script>
<script language = "javascript" type = "text/javascript" src="../../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../../script/tools.js"></script>
<script language="javascript">
function GoToLastMonth() {
	parent.kanan.location.href = "blankagenda.php";
	document.location.href = "jadwal.php?bulan=<?=$last_month?>&tahun=<?=$last_year?>";
}

function GoToNextMonth() {
	parent.kanan.location.href = "blankagenda.php";
	document.location.href = "jadwal.php?bulan=<?=$next_month?>&tahun=<?=$next_year?>";
}

function ChangeCal() {
	var bulan = document.getElementById('bulan').value;
	var tahun = document.getElementById('tahun').value;
	parent.kanan.location.href = "blankagenda.php";
	document.location.href = "jadwal.php?bulan=" + bulan + "&tahun=" + tahun;
}

function CetakJadwal() {
	var bulan = document.getElementById('bulan').value;
	var tahun = document.getElementById('tahun').value;

	var addr = "jadwalcetak.php?bulan="+bulan+"&tahun="+tahun;
	newWindow(addr, 'CetakJadwalNikah','720','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function CetakTanggal(tgl, bln, thn) {
	var addr = "jadwalcetaktanggal.php?tgl="+tgl+"&bln="+bln+"&thn="+thn;
	newWindow(addr, 'CetakJadwalNikahTanggal','720','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refresh() {
	var bulan = document.getElementById('bulan').value;
	var tahun = document.getElementById('tahun').value;
	document.location.href = "jadwal.php?bulan=" + bulan + "&tahun=" + tahun;
	parent.kanan.location.href = "blankagenda.php";
}
function tampil(tanggal,bulan,tahun) {
	var bulan = document.getElementById('bulan').value;
	var tahun = document.getElementById('tahun').value;
	parent.kanan.location.href = "blankagenda.php";
	parent.kiribawah.location.href = "daftaragenda.php?bulan=" + bulan + "&tahun=" + tahun;
}

function tampil2(tanggal,bulan,tahun) {
	parent.kiribawah.location.hash = "tgl" + tanggal; //ref = "daftaragenda.php?bulan=" + bulan + "&tahun=" + tahun;
}
</script>
</head>

<body leftmargin="0" topmargin="0" style="background-color:#DFDFDF" onload="tampil()">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td width="70%" align="left">
Bulan :
<input type="button" class="but" onclick="GoToLastMonth()" value="  <  ">
<select id="bulan" name="bulan" onchange="ChangeCal()">
<?php $namabulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];
   for ($i = 1; $i <= 12; $i++) { ?>
	<option value="<?=$i?>" <?=IntIsSelected($i, $bulan)?>><?=$namabulan[$i - 1]?></option>
<?php } ?>
</select>    
<select id="tahun" name="tahun" onchange="ChangeCal()">
<?php $YNOW = date('Y');
   for ($i = 2005; $i <= $YNOW + 1; $i++) { ?>
	<option value="<?=$i?>" <?=IntIsSelected($i, $tahun)?>><?=$i?></option>
<?php } ?>
</select>    
<input type="button" class="but" onclick="GoToNextMonth()" value="  >  ">
</td>
<td align="right">
	<!--<a href="JavaScript:refresh()"><img src="../../images/ico/refresh.png" border="0" /> Refresh</a>&nbsp;&nbsp;
    <a href="JavaScript:CetakJadwal()"><img src="../../images/ico/print.png" border="0" /> Cetak</a>&nbsp;&nbsp;-->
</td>
</tr>
</table>
<table border="0" cellpadding="5" cellspacing="1" width="100%" style="border-color:#999999">
<tr height="30" bgcolor="#DFFFDF">
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
	echo "<tr height='30'>";
	for ($j = 0; $j < count($cal[$i]); $j++) {
		$tgl = $cal[$i][$j][0];
		$bln = $cal[$i][$j][1];
		$thn = $cal[$i][$j][2];
		
		$tanggal = "$thn-$bln-$tgl";
		
		if (($bln == $bulan) && ($thn == $tahun)){
			$style = "thismonth";
		} else {
			$style = "othermonth";
		}
			
		
		if ($j == 0)
			$color = "#FFCCCC";
		elseif ($j == 5) 
			$color = "#DFFFDF";
		else
			$color = "#DFEFFF";
			
		echo "<td width='30' align='center' valign='middle' style='background-color: $color'>";
		
			
		$sql = "SELECT COUNT(*) FROM jbsvcr.agenda WHERE tanggal='$tanggal' AND nis='".SI_USER_ID()."'";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$njadwal = $row[0];
		
		if (($bln == $bulan) && ($thn == $tahun)){
			echo "<a title='Klik untuk melihat agenda tanggal ".$tgl."-".$bln."-".$thn."' style=\"text-decoration:none;\" href=\"JavaScript:tampil2('".$tgl."','".$bln."','".$thn."')\"> <font class='$style'>$tgl</font></a><br>";
		} else {
			echo "<font class='$style'>$tgl</font><br>";	
		}
		if(($njadwal > 0) && ($bln == $bulan) && ($thn == $tahun)) {
			echo "<sub><font style='background-color:#fda700'><strong>&nbsp;".$njadwal."&nbsp;</strong></font></sub>";
			//echo "<font size='2' style='background-color: green; color: yellow; '>&nbsp;$njadwal&nbsp;</font>";//&nbsp;<img src='../../images/ico/lihat.png' border='0' onmouseover='GetJadwal(this,$tgl,$bln,$thn)'>&nbsp;";
				//echo "<a href=\"JavaScript:CetakTanggal($tgl,$bln,$thn)\" title='Cetak'><img src='../../images/ico/print.png' border='0'></a>&nbsp;";
			
		}
		//echo "<a href=\"JavaScript:Edit($tgl,$bln,$thn)\" title='Tambah/Edit Jadwal'><img src='../../images/ico/ubah.png' border='0'></a>";
		echo "</td>";
	}
	echo "</tr>";
}
CloseDb();
?>
</table>
</body>
</html>