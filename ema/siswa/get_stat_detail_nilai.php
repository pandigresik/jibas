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
require_once('../inc/sessionchecker.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');

$tingkat = $_REQUEST['tingkat'];
$pelajaran = $_REQUEST['pelajaran'];
$dasarpenilaian = $_REQUEST['dasarpenilaian'];
$semester = $_REQUEST['semester'];
$tahunajaran = $_REQUEST['tahunajaran'];
$rentang = $_REQUEST['rentang'];
$ixrentang = $_REQUEST['i'];

OpenDb();
$sql = "SELECT MIN(nilaiangka) as nmin, MAX(nilaiangka) AS nmax
          FROM jbsakad.nap n, jbsakad.infonap i, jbsakad.aturannhb a, jbsakad.kelas k
         WHERE n.idinfo = i.replid
           AND n.idaturan = a.replid
           AND i.idkelas = k.replid
           AND a.dasarpenilaian = '$dasarpenilaian'
           AND i.idpelajaran = '$pelajaran'
           AND i.idsemester = '$semester'
           AND k.idtahunajaran = '$tahunajaran'
           AND k.idtingkat = '".$tingkat."'";
//echo "$sql<br>";

$res = QueryDb($sql);
$row = @mysqli_fetch_array($res);
                    
if ($row['nmin'] >= 0 && $row['nmax'] <= 10)
    $dasar = 1; //satuan
else
    $dasar = 10; //satuan

$rentang = [9*$dasar, 8*$dasar, 7*$dasar, 6*$dasar, 5*$dasar, 4*$dasar, 3*$dasar, 2*$dasar, 1*$dasar, 0];
$filter = [$rentang[0], $rentang[1].'_'.$rentang[0], $rentang[2].'_'.$rentang[1], $rentang[3].'_'.$rentang[2], $rentang[4].'_'.$rentang[3], $rentang[5].'_'.$rentang[4], $rentang[6].'_'.$rentang[5], $rentang[7].'_'.$rentang[6], $rentang[8].'_'.$rentang[7], $rentang[9].'_'.$rentang[8]];

$temp = $filter[$ixrentang];
if (str_contains($temp, "_"))
{
    $ix = explode("_", $temp);
    $nmin = $ix[0];
    $nmax = $ix[1];
    
    $nfilter = "(n.nilaiangka >= $nmin AND n.nilaiangka < $nmax)";
}
else
{
    if ($ixrentang == 0)
    {
        $nmin = $temp; //9 * $dasar;
        $nfilter = "n.nilaiangka >= $nmin";
    }
    else
    {
        $ix = explode("_", $temp);
		$nmin = $ix[0];
		$nmax = $ix[1];
    
	    $nfilter = "(n.nilaiangka >= $nmin AND n.nilaiangka < $nmax)";
    }
}

$sql = "SELECT n.nis, s.nama, k.kelas, n.nilaiangka
          FROM nap n, infonap i, aturannhb a, kelas k, siswa s
         WHERE n.idinfo = i.replid
           AND n.idaturan = a.replid
           AND i.idkelas = k.replid
           AND n.nis = s.nis
           AND a.dasarpenilaian = '$dasarpenilaian'
           AND i.idpelajaran = '$pelajaran'
           AND i.idsemester = '$semester'
           AND k.idtahunajaran = '$tahunajaran'
           AND k.idtingkat = '$tingkat'
           AND $nfilter
         ORDER BY s.nama";
//echo "$sql<br>";         
$res = QueryDb($sql);         
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="90%" border="1" cellpadding='5' cellspacing='0' class="tab" align="left">
<tr height="25">
    <td width="7%"  align="center" class="header">No.</td>
    <td width="15%" align="center" class="header">NIS</td>
    <td width="*" align="center" class="header">Nama</td>
    <td width="15%" align="center" class="header">Kelas</td>
    <td width="15%" align="center" class="header">Nilai Rapor</td>
</tr>
<?php
$cnt = 0;
while($row = mysqli_fetch_array($res))
{
    $cnt += 1; ?>
    <tr>
        <td align='center'><?=$cnt?></td>
        <td align='left'><?=$row['nis']?></td>
        <td align='left'><?=$row['nama']?></td>
        <td align='center'><?=$row['kelas']?></td>
        <td align='center'><?=$row['nilaiangka']?></td>
    </tr>
<?php
}
?>
</table>
</body>
</html>
<?php
CloseDb();
?>