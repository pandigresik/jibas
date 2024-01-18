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
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/rupiah.php');

$key = $_REQUEST['key'];
$keyword = $_REQUEST['keyword'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="style/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SISFO KUA</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function ShowDetail(ref) {
	parent.statdetail.location.href = "statdetail.php?ref="+ref+"&key=<?=$key?>&keyword=<?=$keyword?>";
}

function CetakWord() {
	var addr = "cetakword.php?key=<?=$key?>&keyword=<?=$keyword?>";
	newWindow(addr, 'StatWord','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body style="background-color:#DFDFDF">
<?php
$column2="";
if ($column2=="")
	$column2="Jumlah";
if ($key == 2) {
	$info = explode("-", (string) $keyword);
	$thn1 = $info[0];
	$thn2 = $info[1];

	$column = "Tahun";
	$sql = "SELECT YEAR(tanggal) AS tahun, COUNT(*),YEAR(tanggal) as tahun2 FROM aktanikahgab WHERE status=1 AND (YEAR(tanggal) >= $thn1 AND YEAR(tanggal) <= $thn2) GROUP BY tahun ORDER BY tanggal ";	
	//echo $sql;	
} else {
	$info = explode("-", (string) $keyword);
	$thn1 = $info[1] . "-" . $info[0] . "-1";
	$thn2 = $info[3] . "-" . $info[2] . "-31";
	
	if ($key == 1) {
		$column = "Bulan";
		$sql = "SELECT DATE_FORMAT(tanggal, '%M %Y') AS tahun, COUNT(*),MONTH(tanggal) as bulan2 FROM aktanikahgab WHERE status=1 AND tanggal BETWEEN '$thn1' AND '$thn2' GROUP BY tahun ORDER BY tanggal ";	
	} elseif ($key == 3) {
		$column = "Usia Suami";
		$sql = "SELECT (YEAR(tanggal)-thnlahirsuami) AS tahun, COUNT(*),(YEAR(tanggal)-thnlahirsuami) as usia FROM aktanikahgab WHERE status=1 AND tanggal BETWEEN '$thn1' AND '$thn2' GROUP BY tahun ORDER BY tahun";
	} elseif ($key == 4) {
		$column = "Tahun Kelahiran Suami";
		$sql = "SELECT thnlahirsuami AS tahun, COUNT(*) as usia,thnlahirsuami FROM aktanikahgab WHERE status=1 AND tanggal BETWEEN '$thn1' AND '$thn2' GROUP BY tahun ORDER BY tahun";
	} elseif ($key == 5) {
		$column = "Usia Istri";
		$sql = "SELECT (YEAR(tanggal)-thnlahiristri) AS tahun, COUNT(*),(YEAR(tanggal)-thnlahiristri) as usia  FROM aktanikahgab WHERE status=1 AND tanggal BETWEEN '$thn1' AND '$thn2' GROUP BY tahun ORDER BY tahun";
	} elseif ($key == 6) {
		$column = "Tahun Kelahiran Istri";
		$sql = "SELECT thnlahiristri AS tahun, COUNT(*),thnlahiristri FROM aktanikahgab WHERE status=1 AND tanggal BETWEEN '$thn1' AND '$thn2' GROUP BY tahun ORDER BY tahun";
	}
}
if ($key == 7 || $key == 8 || $key == 9 || $key == 10 || $key == 11) {
	if ($key==7){
	$column = "Jabatan";
	$sql = "SELECT j.jabatan as jabatan, COUNT(p.nip), j.replid as ref FROM pegawai p, jabatan j WHERE p.aktif=1 AND p.jabatan=j.replid GROUP BY j.replid ORDER BY j.jabatan ";	
	}
	if ($key==8){
	$column = "Golongan";
	$sql = "SELECT g.golongan as golongan, COUNT(p.nip), g.replid as ref FROM pegawai p, golongan g WHERE p.aktif=1 AND p.golongan=g.replid GROUP BY g.replid ORDER BY g.golongan ";	
	}
	if ($key==9){
	$column = "Pendidikan";
	$sql = "SELECT pend.pendidikan as pendidikan, COUNT(p.nip), pend.replid as ref FROM pegawai p, pendidikan pend WHERE p.aktif=1 AND p.pendidikan=pend.replid GROUP BY pend.replid ORDER BY pend.pendidikan ";	
	}
	if ($key==10){
	$column = "Suku";
	$sql = "SELECT s.suku as suku, COUNT(p.nip), s.suku as ref FROM pegawai p, suku s WHERE p.aktif=1 AND p.suku=s.suku GROUP BY s.suku ORDER BY s.suku ";	
	}
	if ($key==11){
	$column = "Agama";
	$sql = "SELECT a.agama as agama, COUNT(p.nip), a.agama as ref FROM pegawai p, agama a WHERE p.aktif=1 AND p.agama=a.agama GROUP BY a.agama ORDER BY a.agama ";	
	}
}
if ($key == 12) {
	$info = explode("-", (string) $keyword);
	$thn1 = $info[1];
	$thn2 = $info[3];
	$column = "Tahun";
	$sql = "SELECT DATE_FORMAT(tanggal, '%Y') AS tahun, COUNT(*) as jum,tanggal as ref FROM wakaf WHERE YEAR(tanggal) BETWEEN '$thn1' AND '$thn2' GROUP BY tahun ORDER BY tanggal ";	
}
if ($key == 13) {
	$info = explode("-", (string) $keyword);
	$thn1 = $info[1] . "-" . $info[0] . "-1";
	$thn2 = $info[3] . "-" . $info[2] . "-31";
	$column = "Bulan";
	$sql = "SELECT DATE_FORMAT(tanggal, '%M %Y') AS bulan, COUNT(*) as jum,tanggal as ref FROM wakaf WHERE tanggal BETWEEN '$thn1' AND '$thn2' GROUP BY bulan ORDER BY tanggal ";	
}
if ($key == 14) {
	$info = explode("-", (string) $keyword);
	$thn1 = $info[1];
	$thn2 = $info[3];
	$column = "Tahun";
	$column2 = "Nilai";
	$sql = "SELECT DATE_FORMAT(tanggal, '%Y') AS tahun, SUM(nilai) as nilai,tanggal as ref FROM wakaf WHERE YEAR(tanggal) BETWEEN '$thn1' AND '$thn2' GROUP BY tahun ORDER BY tanggal ";	
}
if ($key == 15) {
	$info = explode("-", (string) $keyword);
	$thn1 = $info[1] . "-" . $info[0] . "-1";
	$thn2 = $info[3] . "-" . $info[2] . "-31";
	$column = "Bulan";
	$column2 = "Nilai";
	$sql = "SELECT DATE_FORMAT(tanggal, '%M %Y') AS bulan, SUM(nilai) as nilai,tanggal as ref FROM wakaf WHERE tanggal BETWEEN '$thn1' AND '$thn2' GROUP BY bulan ORDER BY tanggal ";	
}
?>
<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%" bordercolor="#000000">
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
    <td align="center" valign="top"><?php
	if ($column2=="Nilai"){
	echo FormatRupiah($row[1]);
	} else {
	echo $row[1];
	}?></td>
    <td align="center" valign="top"><a href="JavaScript:ShowDetail('<?=$row[2]?>')"><img src="Images/Ico/lihat.png" border="0" /></a> </td>
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