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
<font size="3"><strong>Jumlah Pegawai<br />Berdasarkan Golongan Per Satuan Kerja</strong></font>
</p>
<table border="0" cellpadding="2" cellspacing="0" width="100%" align="center">
<tr><td align="right" width="100%">
<a href="#" onclick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" />&nbsp;refresh</a>
</td></tr>
</table>

<?php
OpenDb();
$sql = "SELECT golongan FROM golongan ORDER BY urutan";
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) {
	$gol[] = $row[0];
}
$width = floor(75 / count($gol));

$sql = "SELECT satker FROM satker";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) {
	$satker[] = $row['satker'];
}

$sql = "SELECT j.satker, pg.golongan, count(p.nip) AS cnt FROM
		pegawai p, peglastdata pl, peggol pg, pegjab pj, jabatan j
		WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpeggol = pg.replid AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid
		GROUP BY j.satker, pg.golongan HAVING NOT j.satker IS NULL";
$result = QueryDb($sql);
while ($row = mysqli_fetch_array($result)) {
	$s = $row['satker'];
	$g = $row['golongan'];
	$data[$s][$g] = $row['cnt'];
}
CloseDb();
?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%" align="center">
<tr height="30">
	<td class="header" align="center" width="3%">No</td>
    <td class="header" align="center" width="12%">Satuan Kerja</td>
<?php 	for ($i = 0; $i < count($gol); $i++) { ?>
	<td class="header" align="center" width="<?=$width?>%"><?=$gol[$i]?></td>
<?php 	} ?>    
    <td class="header" align="center" width="10%">Jumlah</td>
</tr>
<?php
$cnt = 0;
for($i = 0; $i < count($satker); $i++) {
	$sk = $satker[$i];
?>
<tr height="25">
	<td align="center" valign="top"><?=++$cnt?></td>
    <td align="left" valign="top"><?=$sk?></td>
    <?php 
	$jrow = 0;
	for($j = 0; $j < count($gol); $j++) 
	{ 
		$g = $gol[$j];
		$nilai = $data[$sk][$g];	
		$jrow += $nilai; 
		$tgol[$g] += $nilai; ?>
	    <td align="center" valign="top"><?=$nilai?></td>
    <?php 
	}
	$tjrow += $jrow;
	?>
    <td align="center" valign="top"><?=$jrow?></td>
</tr>
<?php
}
?>
<tr height="30">
	<td style="background-color:#E9E9E9" align="center" valign="top">&nbsp;</td>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>JUMLAH</strong></td>
    <?php 
	$total = 0;
	for($j = 0; $j < count($gol); $j++) 
	{ 
		$g = $gol[$j];
		$nilai = $tgol[$g]; 
		$total += $nilai; ?>
	    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$nilai?></strong></td>
    <?php 
	}
	?>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$total?></strong></td>
</tr>
<?php if ($total > 0) { ?>
<tr height="30">
	<td style="background-color:#E9E9E9" align="center" valign="top">&nbsp;</td>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>PERSENTASE</strong></td>
    <?php 
	for($j = 0; $j < count($gol); $j++) 
	{ 
		$g = $gol[$j];
		$nilai = $tgol[$g]; 
		$pct = "";
		$pct = round($nilai / $total, 2) * 100;	?>
	    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong><?=$pct?>%</strong></td>
    <?php 
	}
	?>
    <td style="background-color:#E9E9E9" align="center" valign="middle"><strong>100%</strong></td>
</tr>
<?php } 

?>
</table>

<script language='JavaScript'>
   Tables('table', 1, 0);
</script>

</body>
</html>