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
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../library/datearith.php");

if (!isset($_REQUEST['tahun30']) || !isset($_REQUEST['tahun']))
{
    OpenDb();
    $sql = "SELECT YEAR(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                   MONTH(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                   DAY(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                   YEAR(NOW()), MONTH(NOW()), DAY(NOW())";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $tahun30 = $row[0];
    $bulan30 = $row[1];
    $tanggal30 = $row[2];
    $tahun = $row[3];
    $bulan = $row[4];
    $tanggal = $row[5];
    CloseDb();    
}
else
{
    $tahun30 = $_REQUEST['tahun30'];
    $bulan30 = $_REQUEST['bulan30'];
    $tanggal30 = $_REQUEST['tanggal30'];
    $tahun = $_REQUEST['tahun'];
    $bulan = $_REQUEST['bulan'];
    $tanggal = $_REQUEST['tanggal'];
}

$maxDay30 = DateArith::DaysInMonth($bulan30, $tahun30);
$maxDay = DateArith::DaysInMonth($bulan, $tahun);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style1.css" />
<script type="application/x-javascript" src="../script/jquery-1.9.0.js"></script>
<script language="javascript">
changeDate = function()
{
    var tahun30 = $("#tahun30").val();
    var bulan30 = $("#bulan30").val();
    var tanggal30 = $("#tanggal30").val();
    var tahun = $("#tahun").val();
    var bulan = $("#bulan").val();
    var tanggal = $("#tanggal").val();
    
    document.location.href = "rekapall.header.php?tahun30="+tahun30+"&bulan30="+bulan30+"&tanggal30="+tanggal30+"&tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal;
    parent.content.location.href = "blank.php";
}

showPresence = function()
{
    var tahun30 = $("#tahun30").val();
    var bulan30 = $("#bulan30").val();
    var tanggal30 = $("#tanggal30").val();
    var tahun = $("#tahun").val();
    var bulan = $("#bulan").val();
    var tanggal = $("#tanggal").val();
    
    parent.content.location.href = "rekapall.content.php?tahun30="+tahun30+"&bulan30="+bulan30+"&tanggal30="+tanggal30+"&tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal;
}
</script>
</head>

<body style="background-color:#F0F0F0">

<table border="0" width="100%">
<tr>
	<td align="left" width="70%">
		
	Tanggal:
	<select name="tahun30" id="tahun30" onchange="changeDate()">
	<?php
	for($i = $G_START_YEAR; $i <= date('Y'); $i++)
	{
		$sel = $i == $tahun30 ? "selected" : "";
		echo "<option value='$i' $sel>$i</option>";
	}
	?>
	</select>
	<select name="bulan30" id="bulan30" onchange="changeDate()">
	<?php
	for($i = 1; $i <= 12; $i++)
	{
		$sel = $i == $bulan30 ? "selected" : "";
		echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
	}
	?>
	</select>
	<select name="tanggal30" id="tanggal30" onchange="changeDate()">
	<?php
	for($i = 1; $i <= $maxDay30; $i++)
	{
		$sel = $i == $tanggal30 ? "selected" : "";
		echo "<option value='$i' $sel>" . $i . "</option>";
	}
	?>
	</select>
	&nbsp;s/d&nbsp;
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
	<select name="tanggal" id="tanggal" onchange="changeDate()">
	<?php
	for($i = 1; $i <= $maxDay; $i++)
	{
		$sel = $i == $tanggal ? "selected" : "";
		echo "<option value='$i' $sel>" . $i . "</option>";
	}
	?>
	</select>
	
	<input type="button" class="but" value="Lihat" onclick="showPresence()">	
		
	</td>
	<td align="right" width="30%">
		
		<font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Rekapitulasi Presensi Semua Pegawai</font><br>
        <a href="presensi.php" target="_parent">Presensi</a> &gt; Rekapitulasi Presensi Semua Pegawai
		
	</td>
</tr>	
</table>
	

</body>
</html>








