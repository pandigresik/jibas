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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Data_Pegawai.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

OpenDb();
$sql = "SELECT *
          FROM jbssdm.pegawai
         ORDER BY aktif DESC, nama";
$result = QueryDb($sql);
if (@mysqli_num_rows($result) <> 0)
{
?>
<html>
<head>
<title>
Data Pegawai
</title>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style2 {font-size: 14px}
-->
</style>
</head>
<body>
<table border="1">
<tr height="30">
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">No.</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">NIP</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Nama</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Panggilan</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">NUPTK</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">NRG</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tgl Mulai Kerja</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Bagian</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Status</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Aktif</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Jenis Kelamin</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tempat Lahir</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Tanggal Lahir</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Nikah</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Agama</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Suku</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">No Identitas</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Alamat</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Telpon</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Handphone</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Email</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Facebook</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Twitter</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Website</div></td>
    <td valign="middle" bgcolor="#666666"><div align="center" class="style1">Keterangan</div></td>
</tr>
<?php
$cnt = 1;
while ($row = @mysqli_fetch_array($result))
{
	?>
    <tr height="25">
        <td align="center"><?=$cnt?></td>
        <td align="left"><?=$row['nip']?></td>
        <td align="left"><?=$row['gelarawal'] . " " . $row['nama'] . " " . $row['gelarakhir']?></td>
        <td align="left"><?=$row['panggilan']?></td>
        <td align="left"><?=$row['nuptk']?></td>
        <td align="left"><?=$row['nrp']?></td>
        <td align="left"><?=$row['mulaikerja']?></td>
        <td align="left"><?=$row['bagian']?></td>
        <td align="left"><?=$row['status']?></td>
        <td align="left"><?=$row['aktif']?></td>
        <td align="left"><?=$row['kelamin']?></td>
        <td align="left"><?=$row['tmplahir']?></td>
        <td align="left"><?=$row['tgllahir']?></td>
        <td align="left"><?=$row['nikah']?></td>
        <td align="left"><?=$row['agama']?></td>
        <td align="left"><?=$row['suku']?></td>
        <td align="left"><?=$row['noid']?></td>
        <td align="left"><?=$row['alamat']?></td>
        <td align="left"><?=$row['telpon']?></td>
        <td align="left"><?=$row['handphone']?></td>
        <td align="left"><?=$row['email']?></td>
        <td align="left"><?=$row['facebook']?></td>
        <td align="left"><?=$row['twitter']?></td>
        <td align="left"><?=$row['website']?></td>
        <td align="left"><?=$row['keterangan']?></td>
    </tr>
<?php $cnt++;
}	?>
</table>
</body>
</html>
<?php
}
CloseDb();
?>