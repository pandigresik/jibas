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
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

$namabulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];
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
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
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
.style2 {
	font-size: 18px;
	font-weight: bold;
}
</style>

<title>JIBAS Kepegawaian</title>
</head>

<body style="background-color:#F3F3F3" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">
  <?php include("../include/headercetak.php") ?>
  <div align="center">
  <br />
  <span class="style2">Agenda untuk Bulan 
  <?=$namabulan[$bulan-1]." ".$tahun?><br /><br />
  </span>
</div>
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
		
		$sql = "SELECT COUNT(*) FROM jadwal WHERE tanggal='$tanggal'";
		//echo $sql;
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$njadwal = $row[0];
		
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
		//echo "<a href='JavaScript:ShowDetailJadwal($tgl,$bln,$thn)'>";
		echo "<font class='$style'>$tgl</font><br>";
		if($njadwal > 0) 
			echo "<font size='2' style='background-color:green; color:#FFFFFF;'>&nbsp;$njadwal&nbsp;</font>";
		//echo "</a>";		  
		echo "</td>";
	}
	echo "</tr>";
}
CloseDb();
?>
</table><br />
<table width="490" border="0" cellspacing="0" align="center">
<?php
$bln2=$bulan;
$thn2=$tahun;
OpenDb();
$sql="SELECT agenda,nama FROM jenisagenda ORDER BY urutan";
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)){
	$sql1 = "SELECT COUNT(*) FROM jadwal WHERE MONTH(tanggal)='".$bln2."' AND YEAR(tanggal)='$thn2' AND jenis='".$row[0]."'";
	//echo $sql1;
	$result1 = QueryDb($sql1);
	$row1 = mysqli_fetch_row($result1);
	if ($row1[0]>0)
		$jumlah = $row1[0]." Orang";
	else 
		$jumlah = "Tidak Ada";
	echo "<tr>".
    "<td><strong>".$row[1]." : </strong> <span class=\"style1\">".$jumlah."</span></td>".
  	"</tr>";
}
?>
</table>
</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>