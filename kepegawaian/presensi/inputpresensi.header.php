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
require_once("../library/datearith.php");

$tahun = $_REQUEST['tahun'] ?? date('Y');
$bulan = $_REQUEST['bulan'] ?? date('n');
$tanggal = $_REQUEST['tanggal'] ?? date('j');

$maxDay = DateArith::DaysInMonth($bulan, $tahun);
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
		var tahun = $("#tahun").val();
		var bulan = $("#bulan").val();
		document.location.href = "inputpresensi.header.php?tahun="+tahun+"&bulan="+bulan;
		parent.content.location.href = "blank.php";
}

changeDay = function()
{
		parent.content.location.href = "blank.php";
}

showPresence = function()
{
		var tahun = $("#tahun").val();
		var bulan = $("#bulan").val();
		var tanggal = $("#tanggal").val();
		parent.content.location.href = "inputpresensi.content.php?tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal;
}
</script>
</head>

<body>
<table border="0" width="100%">
<tr>
<td align="left" width="50%">
		
Tanggal:
<select name="tahun" id="tahun" onchange="changeDate()">
<?php
for($i = $G_START_YEAR; $i <= date('Y'); $i++)
{
	$sel = $i == $tahun ? "selected" : "";
	echo "<option value='$i' $sel>$i</option>";
}
?>
</select>
<select name="bulan" id="bulan" onchange="changeDate()">
<?php
for($i = 1; $i <= 12; $i++)
{
	$sel = $i == $bulan ? "selected" : "";
	echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
}
?>
</select>
<select name="tanggal" id="tanggal" onchange="changeDay()">
<?php
for($i = 1; $i <= $maxDay; $i++)
{
	$sel = $i == $tanggal ? "selected" : "";
	echo "<option value='$i' $sel>" . $i . "</option>";
}
?>
</select>
&nbsp;
<input type="button" class="but" value="Lihat" onclick="showPresence()">
		
</td>
<td align="right" width="50%">
		
<font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
<font class="subtitle">Input Presensi Pegawai</font><br>
<a href="presensi.php" target="_parent">Presensi</a> &gt; Input Presensi Pegawai

</td>		
</tr>		
</table>		

</body>
</html>








