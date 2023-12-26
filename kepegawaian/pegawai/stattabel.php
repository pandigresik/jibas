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
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

$stat = $_REQUEST['stat'];
if ($stat == 5)
{
	header("location: stattabeldiklat.php");
	exit();
}
elseif ($stat == 6)
{
	header("location: stattabeljk.php");
	exit();
}
elseif ($stat == 7)
{
	header("location: stattabelnikah.php");
	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function ShowDetail(ref) {
	parent.statdetail.location.href = "statdetail.php?ref="+ref+"&stat=<?=$stat?>";
}

function CetakWord() {
	var addr = "cetakword.php?key=<?=$key?>&keyword=<?=$keyword?>";
	newWindow(addr, 'StatWord','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body style="background-color:#DFDFDF">
<?php
if ($stat == 1)
{
	$column  = "Satuan Kerja";
	$column2 = "Jumlah";
	$sql = "SELECT j.satker, count(pj.replid) FROM 
	        pegjab pj, peglastdata pl, pegawai p, jabatan j 
			WHERE pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND pj.nip = p.nip 
		      AND NOT j.satker IS NULL
			  AND p.aktif=1 GROUP BY satker";	
}
elseif ($stat == 2)
{
	$column  = "Pendidikan";
	$column2 = "Jumlah";
	$sql = "SELECT ps.tingkat, COUNT(p.nip) FROM
            pegawai p, peglastdata pl, pegsekolah ps, jbsumum.tingkatpendidikan pk
            WHERE p.nip = pl.nip AND pl.idpegsekolah = ps.replid AND ps.tingkat = pk.pendidikan AND p.aktif = 1 
		    GROUP BY ps.tingkat";	
}
elseif ($stat == 3)
{
	$column  = "Golongan";
	$column2 = "Jumlah";
	$sql = "SELECT pg.golongan, COUNT(p.nip) FROM pegawai p, peglastdata pl, peggol pg, golongan g
            WHERE p.nip = pl.nip AND pl.idpeggol = pg.replid AND pg.golongan = g.golongan AND p.aktif = 1 
			GROUP BY pg.golongan ORDER BY g.urutan";	
}
elseif ($stat == 4)
{
	$column  = "Usia";
	$column2 = "Jumlah";
	
	$sql = "SELECT G, COUNT(nip) FROM (
	          SELECT nip, IF(usia < 24, '<24',
                          IF(usia >= 24 AND usia <= 29, '24-29',
                          IF(usia >= 30 AND usia <= 34, '30-34',
                          IF(usia >= 35 AND usia <= 39, '35-39',
                          IF(usia >= 40 AND usia <= 44, '40-44',
                          IF(usia >= 45 AND usia <= 49, '45-49',
                          IF(usia >= 50 AND usia <= 55, '50-55', '>56'))))))) AS G,
						  IF(usia < 24, '1',
                          IF(usia >= 24 AND usia <= 29, '2',
                          IF(usia >= 30 AND usia <= 34, '3',
                          IF(usia >= 35 AND usia <= 39, '4',
                          IF(usia >= 40 AND usia <= 44, '5',
                          IF(usia >= 45 AND usia <= 49, '6',
                          IF(usia >= 50 AND usia <= 55, '7', '8'))))))) AS GG FROM
                (SELECT nip, FLOOR(DATEDIFF(NOW(), tgllahir) / 365) AS usia FROM pegawai WHERE aktif = 1) AS X) AS X GROUP BY G ORDER BY GG ASC";	
}

?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
<tr height="25">
	<td class="header" align="center" width="5%">No</td>
    <td class="header" align="center" width="60%"><?=$column?></td>
    <td class="header" align="center" width="25%"><?=$column2?></td>
    <td class="header" align="center" width="10%">&nbsp;</td>
</tr>
<?php
OpenDb();
$result = QueryDb($sql);
while ($row = mysqli_fetch_row($result)) {
?>
<tr height="20">
	<td align="center" valign="top"><?=++$cnt?></td>
    <td align="center" valign="top"><?=$row[0]?></td>
    <td align="center" valign="top"><?=$row[1]?></td>
    <td align="center" valign="top">
    	<a href="JavaScript:ShowDetail('<?=$row[0]?>')"><img src="../images/ico/lihat.png" border="0" /></a> 
    </td>
</tr>
<?php
}
CloseDb();
?>
</table>
<script language='JavaScript'>
   Tables('table', 1, 0);
</script>

</body>
</html>