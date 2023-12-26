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
require_once('../inc/errorhandler.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/config.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Rekap_Presensi_Pegawai.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS EMA</title>
<style type="text/css">
<!--
.style1 {
	font-size: 24px;
	font-weight: bold;
}
-->
</style>
</head>

<body>

<?php
$nip = $_REQUEST['nip'];
$tahun30 = $_REQUEST['tahun30'];
$bulan30 = $_REQUEST['bulan30'];
$tanggal30 = $_REQUEST['tanggal30'];
$tahun = $_REQUEST['tahun'];
$bulan = $_REQUEST['bulan'];
$tanggal = $_REQUEST['tanggal'];

OpenDb();

$sql = "SELECT nama
          FROM jbssdm.pegawai
         WHERE nip = '$nip'";
$nama = FetchSingle($sql);         
?>

<strong>NIP: <?=$nip?></strong><br />
<strong>Nama: <?=$nama?></strong><br />
<strong>Tanggal: <?= $tanggal30 . " " . NamaBulan($bulan30) . " " . $tahun30 . " s/d " . $tanggal . " " . NamaBulan($bulan) . " " . $tahun ?></strong>
<br /><br />

<table border="1" cellpadding="2" cellspacing="0" width="870" style="border-width: 1px; border-collapse: collapse" class="tab" id="table">
<tr height="25">
    <td width="30" align="center" class="header">No</td>
	<td width="60" align="left" class="header">Hari</td>
    <td width="100" align="center" class="header">Tanggal</td>
    <td width="90" align="center" class="header">Status</td>
    <td width="100" align="center" class="header">Jam Masuk</td>
    <td width="100" align="center" class="header">Jam Pulang</td>
    <td width="120" align="center" class="header">Waktu Kerja</td>
    <td width="180" align="center" class="header">Keterangan</td>
    <td width="100" align="center" class="header">Sumber</td>
</tr>  
<?php
$sql = "SELECT tanggal, DATE_FORMAT(tanggal, '%d %M %Y') AS tanggalview, jammasuk, jampulang,
               jamwaktukerja, menitwaktukerja, status, keterangan, source, WEEKDAY(tanggal) AS hari
          FROM jbssdm.presensi
         WHERE tanggal BETWEEN '$tahun30-$bulan30-$tanggal30' AND '$tahun-$bulan-$tanggal'
           AND nip = '$nip'
         ORDER BY tanggal DESC";       
$res = QueryDb($sql);
$no = 0;
$totjkerja = 0;
$totmkerja = 0;
while($row = mysqli_fetch_array($res))
{
    $no += 1;
    $status = $row["status"];
    
    if ($status == 1)
    {
        $bgcolor = "#b3de81";
        $statusname = "Hadir";
    }
    elseif ($status == 2)
    {
        $bgcolor = "#eccbfb";
        $statusname = "Izin";
    }
    elseif ($status == 3)
    {
        $bgcolor = "#eccbfb";
        $statusname = "Sakit";
    }
    elseif ($status == 4)
    {
        $bgcolor = "#eccbfb";
        $statusname = "Cuti";
    }
    elseif ($status == 5)
    {
        $bgcolor = "#fbcbcb";
        $statusname = "Alpa";    
    }
	elseif ($status == 6)
    {
        $bgcolor = "#979797";
        $statusname = "Bebas";    
    }
    
    $totjkerja += $row["jamwaktukerja"];
    $totmkerja += $row["menitwaktukerja"];
?>
<tr height="22">
    <td align="center"><?=$no?></td>
	<td align="left"><?=NamaHari($row["hari"])?></td>
    <td align="center"><?=$row["tanggalview"]?></td>
    <td align="center" bgcolor="<?=$bgcolor?>"><strong><?=$statusname?></strong></td>
    <td align="center"><?=$row["jammasuk"]?></td>
    <td align="center"><?=$row["jampulang"]?></td>
    <td align="left"><?=$row["jamwaktukerja"] . " jam " . $row["menitwaktukerja"] . " menit"?></td>
    <td align="left"><?=$row["keterangan"]?></td>
    <td align="left"><?=$row["source"]?></td>
</tr>
<?php
}
?>
<tr height="30">
    <td style="background-color: #DDD" colspan="6">&nbsp;</td>
    <td style="background-color: #DDD; font-weight: bold;" align="left">
<?php
    if ($totmkerja >= 60)
    {
        $totjkerja += floor($totmkerja / 60);
        $totmkerja %= 60;
    }    
    echo $totjkerja . " jam " . $totmkerja . " menit";
?>
    </td>
    <td style="background-color: #DDD" colspan="2">&nbsp;</td>
</tr>
</table>

<?php
CloseDb();
?>

</body>
</html>