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
require_once('../include/errorhandler.php');
require_once('../include/sessionchecker.php');
require_once("../include/config.php");
require_once("../include/common.php");
require_once("../include/db_functions.php");
require_once("../library/datearith.php");

if (!isset($_REQUEST['tahun1']))
{
    OpenDb();
    
    $sql = "SELECT DAY(NOW()) AS DD1, MONTH(NOW()) AS MM1, YEAR(NOW()) AS YY1,
                   DAY(DATE_SUB(NOW(), INTERVAL 30 DAY)) AS DD2, 
                   MONTH(DATE_SUB(NOW(), INTERVAL 30 DAY)) AS MM2, 
                   YEAR(DATE_SUB(NOW(), INTERVAL 30 DAY)) AS YY2";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    $tanggal1 = $row[0];
    $bulan1 = $row[1];
    $tahun1 = $row[2];
    $maxDay1 = DateArith::DaysInMonth($bulan1, $tahun1);
    
    $tanggal2 = $row[3];
    $bulan2 = $row[4];
    $tahun2 = $row[5];
    $maxDay2 = DateArith::DaysInMonth($bulan2, $tahun2);
    
    CloseDb();
}
else
{
    $tahun1 = $_REQUEST['tahun1'];
    $bulan1 = $_REQUEST['bulan1'];
    $tanggal1 = $_REQUEST['tanggal1'];
    $maxDay1 = DateArith::DaysInMonth($bulan1, $tahun1);
    
    $tahun2 = $_REQUEST['tahun2'];
    $bulan2 = $_REQUEST['bulan2'];
    $tanggal2 = $_REQUEST['tanggal2'];
    $maxDay2 = DateArith::DaysInMonth($bulan2, $tahun2);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style1.css" />
<script type="application/x-javascript" src="../script/jquery3/jquery-1.2.6.js"></script>
<script language="javascript">
changeDate = function()
{
	var tahun1 = $("#tahun1").val();
	var bulan1 = $("#bulan1").val();
    var tanggal1 = $("#tanggal1").val();
    var tahun2 = $("#tahun2").val();
	var bulan2 = $("#bulan2").val();
    var tanggal2 = $("#tanggal2").val();
    
	document.location.href = "lembur.header.php?tahun1="+tahun1+"&bulan1="+bulan1+"&tanggal1="+tanggal1+"&tahun2="+tahun2+"&bulan2="+bulan2+"&tanggal2="+tanggal2;
	parent.content.location.href = "blank.php";
}

changeDay = function()
{
	parent.content.location.href = "blank.php";
}

showPresence = function()
{
	var tahun1 = $("#tahun1").val();
	var bulan1 = $("#bulan1").val();
    var tanggal1 = $("#tanggal1").val();
    var tahun2 = $("#tahun2").val();
	var bulan2 = $("#bulan2").val();
    var tanggal2 = $("#tanggal2").val();
    
	parent.content.location.href = "lembur.content.php?tahun1="+tahun1+"&bulan1="+bulan1+"&tanggal1="+tanggal1+"&tahun2="+tahun2+"&bulan2="+bulan2+"&tanggal2="+tanggal2;
}
</script>
</head>

<body>
<table border="0" width="100%">
<tr>
<td align="left" width="50%">
		
Tanggal:
<select name="tahun2" id="tahun2" onchange="changeDate()">
<?php
for($i = $G_START_YEAR; $i <= date('Y'); $i++)
{
	$sel = $i == $tahun2 ? "selected" : "";
	echo "<option value='$i' $sel>$i</option>";
}
?>
</select>
<select name="bulan2" id="bulan2" onchange="changeDate()">
<?php
for($i = 1; $i <= 12; $i++)
{
	$sel = $i == $bulan2 ? "selected" : "";
	echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
}
?>
</select>
<select name="tanggal2" id="tanggal2" onchange="changeDay()">
<?php
for($i = 1; $i <= $maxDay2; $i++)
{
	$sel = $i == $tanggal2 ? "selected" : "";
	echo "<option value='$i' $sel>" . $i . "</option>";
}
?>
</select>
&nbsp;s/d&nbsp;
<select name="tahun1" id="tahun1" onchange="changeDate()">
<?php
for($i = $G_START_YEAR; $i <= date('Y'); $i++)
{
	$sel = $i == $tahun1 ? "selected" : "";
	echo "<option value='$i' $sel>$i</option>";
}
?>
</select>
<select name="bulan1" id="bulan1" onchange="changeDate()">
<?php
for($i = 1; $i <= 12; $i++)
{
	$sel = $i == $bulan1 ? "selected" : "";
	echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
}
?>
</select>
<select name="tanggal1" id="tanggal1" onchange="changeDay()">
<?php
for($i = 1; $i <= $maxDay1; $i++)
{
	$sel = $i == $tanggal1 ? "selected" : "";
	echo "<option value='$i' $sel>" . $i . "</option>";
}
?>
</select>
<input type="button" class="but" value="Lihat" onclick="showPresence()">
		
</td>
<td align="right" width="50%">
		
<font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
<font class="subtitle">Presensi Lembur Pegawai</font><br>
<a href="presensi.php" target="_parent">Presensi</a> &gt; Presensi Lembur Pegawai

</td>		
</tr>		
</table>		

</body>
</html>








