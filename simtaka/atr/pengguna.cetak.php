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
require_once('../inc/sessioninfo.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../lib/GetHeaderCetak.php');

OpenDb();
$departemen='yayasan';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../sty/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SimTaka [Cetak Daftar Pengguna]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=GetHeader('alls')?>

<center><font size="4"><strong>DATA PENGGUNA</strong></font><br /> </center><br /><br />

<br />
<?php
$sql = "SELECT h.login, h.aktif, h.lastlogin, h.departemen, h.tingkat, h.keterangan, h.info1 AS idperpustakaan
		  FROM ".get_db_name('user').".hakakses h, ".get_db_name('user').".login l
		 WHERE h.modul='SIMTAKA'
		   AND l.login=h.login";
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
<tr height="30" >
  <td width='4%' align="center" class="header">No</td>
  <td width='10%' align="center" class="header">NIP</td>
  <td width='15%' align="center" class="header">Nama</td>
  <td width='15%' align="center" class="header">Tingkat</td>
  <td width='15%' align="center" class="header">Perpustakaan</td>
  <td width='15%' align="center" class="header">Departemen</td>
  <td width='*' align="center" class="header">Keterangan</td>
</tr>
<?php
if ($num > 0)
{
  $cnt = 0;
  while ($row=@mysqli_fetch_row($result))
  {
	$cnt += 1;
	
	$sql = "SELECT nama
		      FROM ".get_db_name('sdm').".pegawai
			 WHERE nip='".$row[0]."'";
	$res = QueryDb($sql);
	$r = @mysqli_fetch_row($res);
	$namapeg = $r[0];
	
	if ($row[4]==2)
	{
		$sql = "SELECT nama FROM perpustakaan WHERE replid='".$row[6]."'";
		$res = QueryDb($sql);
		$r = @mysqli_fetch_row($res);
		$namaperpus = $r[0];
		$namatingkat = "Staf Perpustakaan";
	}
	else
	{
		$namaperpus = "<i>Semua</i>";
		$namatingkat = "Manajer Perpustakaan";
	} ?>
	<tr height="25">
	  <td align="center"><?=$cnt?></td>
	  <td align="left"><?=$row[0]?></td>
	  <td align="left"><?=$namapeg?></td>
	  <td align="left"><?=$namatingkat?></td>
	  <td align="left"><?=$namaperpus?></td>
	  <td align="center"><?=$row[3]?></td>
	  <td align="left"><?=$row[5]?></td>
	</tr>
<?php
  } //while
}
else
{
  ?>
  <tr>
	<td height="25" colspan="7" align="center" class="nodata">Tidak ada data</td>
  </tr>
<?php
}
?>	
</table>
</td></tr></table>
</body>
<?php
CloseDb();
?>
<script language="javascript">
window.print();
</script>
</html>