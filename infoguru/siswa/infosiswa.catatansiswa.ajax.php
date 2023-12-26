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
require_once('../include/sessionchecker.php');
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');

$nis = "";
if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];
    
$bulan = "";
if (isset($_REQUEST['bulan']))
	$bulan = $_REQUEST['bulan'];
    
$tahun = "";
if (isset($_REQUEST['tahun']))
	$tahun = $_REQUEST['tahun'];
    
OpenDb();
$sql = "SELECT c.replid as replid,c.judul as judul, c.catatan as catatan, c.nip as nip, p.nama as nama, c.tanggal as tanggal 
          FROM jbsvcr.catatansiswa c, jbssdm.pegawai p 
	     WHERE c.nis='$nis' AND MONTH(c.tanggal)='$bulan' AND YEAR(c.tanggal)='$tahun' AND p.nip=c.nip ";
$result=QueryDb($sql);
$num=@mysqli_num_rows($result);
?>
<table width="100%" border="1" cellspacing="0" class="tab">
<tr>
    <td height="30" width="43" class="header"><div align="center">No</div></td>
    <td height="30" width="900" class="header">Tanggal/Guru</td>
    </tr>
<?php
if ($num>0)
{
  	$cnt=1;
	while ($row=@mysqli_fetch_array($result))
    {
    	$a="";
    	if ($cnt%2==0)
    		$a="style='background-color:#FFFFCC'";  ?>
            
        <tr <?=$a?>>
            <td height="25" valign="top" rowspan="2">
                <div align="center"><?=$cnt?></div>
            </td>
            <td height="25" valign="top">
                <?=ShortDateFormat($row['tanggal'])?><br /><?=$row['nip']?>-<?=$row['nama']?>
            </td>
        </tr>
        <tr <?=$a?>>
            <td height="25">
                <font face="Verdana, Arial, Helvetica, sans-serif" color="#999999">
                    [<?=$row['judul']?>]
                </font><br /><?=$row['catatan']?>
            </td>
<?php      $cnt++;
  	}
}
else
{ ?>
<tr>
    <td height="25" colspan="4">
        <div align="center"><em>Tidak ada catatan Kejadian Siswa untuk NIS : <?=$nis?></em></div>
    </td>
</tr>
<?php
}
?>
</table>
<?php
CloseDb();
?>