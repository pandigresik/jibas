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
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');

OpenDb();

$idperpustakaan = -1;
if (isset($_REQUEST['idperpustakaan']))
  $idperpustakaan = (int)$_REQUEST['idperpustakaan'];

$filter="";
if ($idperpustakaan != -1)
  $filter=" AND d.perpustakaan=".$idperpustakaan;
  
$waktu = $_REQUEST['waktu'];
$waktu = explode('-', (string) $waktu);
?>
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="tab">
<tr height='25'>
  <td width='5%' align="center" class="header">No</td>
  <td width='15%' align="center" class="header">Tgl Pinjam</td>
  <td width='25%' align="center" class="header">Peminjam</td>
  <td width='*' align="center" class="header">Pustaka</td>
</tr>
<?php
$sql = "SELECT IF(p.nis IS NOT NULL, p.nis, IF(p.nip IS NOT NULL, p.nip, p.idmember)) AS idanggota,
		       p.tglpinjam, p.info1, d.kodepustaka, pu.judul
	      FROM pinjam p, daftarpustaka d, pustaka pu
		 WHERE MONTH(p.tglpinjam)='".$waktu[0]."' AND YEAR(p.tglpinjam)='".$waktu[1]."'
		   AND p.kodepustaka=d.kodepustaka
		   AND d.pustaka=pu.replid $filter
		 ORDER BY tglpinjam DESC";
$result = QueryDb($sql);
$cnt = 0;
while ($row = @mysqli_fetch_row($result))
{
  $cnt += 1; ?>
  <tr height='20'>
    <td align="center"><?=$cnt?></td>
	<td align="center"><?=LongDateFormat($row[1])?></td>
    <td align="left">
	  <font style='font-size: 9px'><?=$row[0]?></font><br>
	  <font style='font-size: 11px; font-weight: bold;'><?=GetMemberName($row[0])?></font>
	</td>
	<td align="left">
	  <font style='font-size: 9px'><?=$row[3]?></font><br>
	  <font style='font-size: 11px; font-weight: bold;'><?=$row[4]?></font>
	</td>
  </tr>
<?php
}
CloseDb();
?>
</table>

<?php
function GetMemberName($idanggota, $jenisanggota)
{
	if ($jenisanggota == "siswa")
	{
		$sql = "SELECT nama
				  FROM jbsakad.siswa
				 WHERE nis = '".$idanggota."'";
	}
	elseif ($jenisanggota == "pegawai")
	{
		$sql = "SELECT nama
				  FROM jbssdm.pegawai
				 WHERE nip = '".$idanggota."'";
	}
	else
	{
		$sql = "SELECT nama
				  FROM jbsperpus.anggota
				 WHERE noregistrasi = '".$idanggota."'";
	}
	$res = QueryDb($sql);
	$row = mysqli_fetch_row($res);
	$namaanggota = $row[0];
	
	return $namaanggota;
}

?>