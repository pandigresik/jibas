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
require_once("../include/sessionchecker.php");
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../include/sessioninfo.php");

OpenDb();

$tglskrg = date("d");
$blnskrg = date("n");
$thnskrg = date("Y");

$bulan = "";
if (isset($_REQUEST['bulan']))
	$bulan = $_REQUEST['bulan'];
	
$tahun = "";
if (isset($_REQUEST['tahun']))
	$tahun = $_REQUEST['tahun'];

if (($bulan == "") || ($tahun == "")) 
{
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

if ($bulan == 12) 
{
	$next_month = 1;
	$next_year = $tahun + 1;
}
else 
{
	$next_month = $bulan + 1;
	$next_year = $tahun;
}

if ($bulan == 1) 
{
	$last_month = 12;
	$last_year = $tahun - 1;
	
	$tmp = ($tahun - 1) . "-12-1";
} 
else 
{
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
for ($i = 0; $i < ($first_weekday_this_month - 1); $i++) 
{
	$cal[$nweek][$nday][0] = $last_day_last_month - ($first_weekday_this_month - 1) + ($i + 1);
	$cal[$nweek][$nday][1] = $last_month;
	$cal[$nweek][$nday][2] = $last_year;
	
	$nday++;
	//echo $cal[$nweek][$nday] . "<br>";
}

for ($i = 1; $i <= $last_day_this_month; $i++) 
{
	$cal[$nweek][$nday][0] = $i;
	$cal[$nweek][$nday][1] = $bulan;
	$cal[$nweek][$nday][2] = $tahun;
	
	if ($nday == 6) 
	{
		$nday = 0;
		$nweek++;
	} 
	else 
	{
		$nday++;
	}
}

if (($nday > 0) && ($nday < 7)) 
{
	$start = 1;
	for ($i = $nday; $i < 7; $i++) 
	{
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
<style>
.thismonth {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 20px;
	font-weight: bold;
}

.othermonth {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 14px;
	font-weight: bold;
	color:#999999;
}
.style1 {
	color: #006633;
	font-weight: bold;
}
</style>

<title>JIBAS Kepegawaian</title>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function GoToLastMonth() {
	document.location.href = "jadwalcal.php?bulan=<?=$last_month?>&tahun=<?=$last_year?>";
}

function GoToNextMonth() {
	document.location.href = "jadwalcal.php?bulan=<?=$next_month?>&tahun=<?=$next_year?>";
}

function ChangeCal() {
	var bulan = document.getElementById('bulan').value;
	var tahun = document.getElementById('tahun').value;
	
	document.location.href = "jadwalcal.php?bulan=" + bulan + "&tahun=" + tahun;
}

function ShowDetailJadwal(tgl, bln, thn) {
	var addr = "jadwallist.php?tgl="+tgl+"&bln="+bln+"&thn="+thn;
	parent.jadwallist.location.href = addr;
}

function ShowRekap(bln, thn, agenda) {
	var addr = "jadwalrekap.php?bln="+bln+"&thn="+thn+"&agenda="+agenda;
	parent.jadwallist.location.href = addr;
}

function Refresh() {
	var bulan = document.getElementById('bulan').value;
	var tahun = document.getElementById('tahun').value;
	
	document.location.href = "jadwalcal.php?bulan=" + bulan + "&tahun=" + tahun;
}

function Cetak() {
	var bulan = document.getElementById('bulan').value;
	var tahun = document.getElementById('tahun').value;
	newWindow('jadwalcal_cetak.php?bulan=' + bulan + '&tahun=' + tahun, 'CetakAgenda','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>

</head>

<body style="background-color:#F3F3F3" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table border="0" cellpadding="2" cellspacing="0" width="490" align="center">
<tr><td width="100%" align="left">
<strong>Bulan :</strong>
<input type="button" class="but" onclick="GoToLastMonth()" value="  <  ">
<select id="bulan" name="bulan" onchange="ChangeCal()">
<?php $namabulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];
   for ($i = 1; $i <= 12; $i++) { ?>
	<option value="<?=$i?>" <?=IntIsSelected($i, $bulan)?>><?=$namabulan[$i - 1]?></option>
<?php } ?>
</select>    
<select id="tahun" name="tahun" onchange="ChangeCal()">
<?php $YNOW = date('Y');
   for ($i = 2007; $i <= $YNOW + 10; $i++) { ?>
	<option value="<?=$i?>" <?=IntIsSelected($i, $tahun)?>><?=$i?></option>
<?php } ?>
</select>    
<input type="button" class="but" onclick="GoToNextMonth()" value="  >  ">
<a href="JavaScript:Refresh()"><img src="../images/ico/refresh.png" border="0" />&nbsp;Refresh</a>&nbsp;&nbsp;
<a href="JavaScript:Cetak()"><img src="../images/ico/print.png" border="0" />&nbsp;Cetak</a>
</td>
</tr>
</table>

<table border="1" bordercolor="#CCCCCC" cellpadding="5" cellspacing="0" width="490" style="border-color:#999999" align="center">
<tr height="30" bgcolor="#DFFFDF">
	<td width="70" class="redheader" align="center" style="background-color:#990000; color:#FFFFFF"><b>Minggu</b></td>
    <td width="70" class="header" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Senin</b></td>
    <td width="70" class="header" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Selasa</b></td>
    <td width="70" class="header" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Rabu</b></td>
    <td width="70" class="header" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Kamis</b></td>
    <td width="70" class="greenheader" align="center" style="background-color:#339900; color:#FFFFFF"><b>Jum'at</b></td>
    <td width="70" class="header" align="center" style="background-color:#3366CC; color:#FFFFFF"><b>Sabtu</b></td>
</tr>
<?php
for ($i = 0; $i < count($cal); $i++) 
{
	echo "<tr height='40'>";
	
	for ($j = 0; $j < count($cal[$i]); $j++) 
	{
		$tgl = $cal[$i][$j][0];
		$bln = $cal[$i][$j][1];
		$thn = $cal[$i][$j][2];
		
		$tanggal = "$thn-$bln-$tgl";
		
		$sql = "SELECT COUNT(*) FROM jadwal WHERE tanggal='$tanggal' AND exec=0";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$njadwal = $row[0];
		
		$sql = "SELECT COUNT(*) FROM jadwal WHERE tanggal='$tanggal' AND exec=1";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$nexecjadwal = $row[0];
		
		if ($j == 0)
			$color = "#FFCCCC";
		elseif ($j == 5) 
			$color = "#DFFFDF";
		else
			$color = "#DFEFFF";
		
		if ($njadwal > 0)
			$color = "#CCFF00";
			
		if ($tgl == $tglskrg && $bln == $blnskrg && $thn == $thnskrg) 
			$color = "#FFCC00";
			
		if (($bln == $bulan) && ($thn == $tahun)) 
			$style = "thismonth";
		else 
			$style = "othermonth";
			
		echo "<td align='center' valign='middle' style='background-color: $color'>";
		echo "<a href='JavaScript:ShowDetailJadwal($tgl,$bln,$thn)'>";
		echo "<font class='$style'>$tgl</font><br>";
		if($nexecjadwal > 0) 
			echo "<font size='2' style='background-color:#999999; color:#FFFFFF;'>&nbsp;$nexecjadwal&nbsp;</font>";			
		if($njadwal > 0) 
			echo "<font size='2' style='background-color:green; color:#FFFFFF;'>&nbsp;$njadwal&nbsp;</font>";
		
		echo "</a>";		  
		echo "</td>";
	}
	echo "</tr>";
}
?>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="490" align="center">
<tr>
<td align="left" valign="top" width="60%">
<br>
<fieldset>
<legend><font style="color:white; background-color:black; font-weight:bold">&nbsp;Agenda bulan <?= namabulan($bulan) . " $tahun" ?>&nbsp;</font></legend>	
<table border="0" cellspacing="0" align="left">
<?php
$bln2=$bulan;
$thn2=$tahun;
$sql="SELECT agenda, nama
		FROM jenisagenda
	   ORDER BY urutan";
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result))
{
	$sql1 = "SELECT COUNT(nip)
			   FROM jadwal
			  WHERE (MONTH(tanggal)='$bln2' AND YEAR(tanggal)='$thn2')
			    AND jenis='".$row[0]."' AND exec=0";
	$result1 = QueryDb($sql1);
	$row1 = mysqli_fetch_row($result1);
	if ($row1[0] > 0)
	{
		$jumlah = $row1[0]." Orang";
		$det = "<a href=\"JavaScript:ShowRekap($bln2,$thn2,'$row[0]')\")><img src='../images/ico/lihat.png' border='0'></a>";
	}
	else
	{
		$jumlah = "Tidak Ada";
		$det = "";
	}
	echo "<tr>".
    "<td><strong>".$row[1]." : </strong> <span class=\"style1\">".$jumlah."&nbsp;$det</span></td>".
  	"</tr>";
}
?>
</table>
</fieldset>
</td>
<td align="left" valign="top" width="40%">
<br>
<fieldset>
<legend><font style="color:white; background-color:#999; font-weight:bold">&nbsp;Legend&nbsp;</font></legend>	
<table border="0" cellpadding="0" cellspacing="2">
<tr>
	<td width="30" bgcolor="#CCFF00">&nbsp;</td>
    <td>: Ada agenda</td>
</tr>
<tr>
	<td bgcolor="#FFCC00">&nbsp;</td>
    <td>: Tanggal hari ini</td>
</tr>
</table>
</fieldset>
</td>
</tr>
</table>

</body>
</html>
<?php
CloseDb();
?>