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
require_once('../inc/sessioninfo.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');

$nis=$_REQUEST['nis'];
$tahun=$_REQUEST['tahun'];

OpenDb();
?>
<table width="100%" border="1" cellspacing="0" class="tab">
    <tr class="header" height="30">
        <td width="50"><div align="center">Bulan</div></td>
        <td width="35%"><div align="center">#</div></td>
    </tr>
<?php  $sql = "SELECT MONTH(tanggal) AS bulan
              FROM jbsvcr.catatansiswa
             WHERE YEAR(tanggal)='$tahun' AND nis='$nis'
             GROUP BY MONTH(tanggal)";
    $result=QueryDb($sql);
    if (@mysqli_num_rows($result)>0)
    {
       	while ($row=@mysqli_fetch_array($result))
        {
    	  	$sql_cnt="SELECT COUNT(*) FROM jbsvcr.catatansiswa WHERE nis== '".$nis."' AND MONTH(tanggal)='".$row['bulan']."' AND YEAR(tanggal)='$tahun'";
		  	$res_cnt=QueryDb($sql_cnt);
			$row_cnt=@mysqli_fetch_row($res_cnt); ?>
            <tr onClick="ShowCatatanSiswa('<?=$nis?>','<?=$row['bulan']?>','<?=$tahun?>')" style="cursor:pointer;" title="Klik untuk menampilkan daftar Catatan Siswa">
                <td width="50"><?=$bulan_pjg[$row['bulan']]?></td>
                <td><div align="center"><?=$row_cnt[0]?></div></td>
            </tr>
<?php   	}
	}
    else
    {  ?>
        <tr>
            <td colspan="2">Tidak ada Data</td>
        </tr>
<?php  } ?>
</table>
<?php
CloseDb();
?>