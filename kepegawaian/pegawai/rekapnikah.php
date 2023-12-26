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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once("../include/sessioninfo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../style/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function ShowDetail(sat, nikah) {
	parent.statdetail.location.href = "statdetailnikah.php?sat="+sat+"&nikah="+nikah;
}

function CetakWord() {
	var addr = "cetakword.php?key=<?=$key?>&keyword=<?=$keyword?>";
	newWindow(addr, 'StatWord','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body>
<p align="center">
<font size="3"><strong>Jumlah Pegawai<br />Berdasarkan Status Pernikahan Per Satuan Kerja</strong></font>
</p>
<table border="0" cellpadding="2" cellspacing="0" width="80%" align="center">
<tr><td align="right" width="100%">
<a href="#" onclick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" />&nbsp;refresh</a>
</td></tr>
</table>

<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="80%" align="center">
<tr height="25">
	<td class="header" align="center" width="5%">No</td>
    <td class="header" align="center" width="40%">Satuan Kerja</td>
    <td class="header" align="center" width="15%">Jumlah<br>Pegawai</td>
    <td class="header" align="center" width="10%">Nikah</td>
    <td class="header" align="center" width="10%">%tase</td>
    <td class="header" align="center" width="10%">Belum</td>
    <td class="header" align="center" width="10%">%tase</td>
</tr>
<?php
OpenDb();
$sql = "SELECT j.satker, SUM(IF(p.nikah = 'menikah', 1, 0)) AS Nikah, SUM(IF(p.nikah = 'belum', 1, 0)) AS Belum
		  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
		  WHERE p.aktif = 1  AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND NOT j.satker IS NULL
		  GROUP BY j.satker ";	
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) {
	$jpeg = $row[1] + $row[2];
	
	$tpeg = $tpeg + $jpeg;
	$tn   = $tn   + $row[1];
	$tb   = $tb   + $row[2];
	
	$pctn = "";
	$pctb = "";
	if ($jpeg > 0) {
		$pctn = round($row[1] / $jpeg, 2) * 100;
		$pctb = round($row[2] / $jpeg, 2) * 100;
	}
?>
<tr height="20">
	<td align="center" valign="top"><?=++$cnt?></td>
    <td align="center" valign="top"><?=$row[0]?></td>
    <td align="center" valign="top"><?=$jpeg?></td>
    <td align="center" valign="top"><?=$row[1]?></td>
    <td align="center" valign="top"><?=$pctn . "%"?></td>
    <td align="center" valign="top"><?=$row[2]?></td>
    <td align="center" valign="top"><?=$pctb . "%"?></td>
</tr>
<?php
}
CloseDb();

$pctn = "";
$pctb = "";
if ($tpeg > 0) {
	$pctn = round($tn / $tpeg, 2) * 100;
	$pctb = round($tb / $tpeg, 2) * 100;
}	
?>
<tr height="30">
	<td style="background-color:#CCCCCC" align="center" valign="middle">&nbsp;</td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong>JUMLAH</strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$tpeg?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$tn?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$pctn . "%"?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$tb?></strong></td>
    <td style="background-color:#CCCCCC" align="center" valign="middle"><strong><?=$pctb . "%"?></strong></td>
</tr>
</table>
<script language='JavaScript'>
   Tables('table', 1, 0);
</script>

</body>
</html>