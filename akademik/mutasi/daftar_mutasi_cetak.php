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
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/getheader.php');
OpenDb();
$departemen = $_REQUEST['departemen'];
$tahun = $_REQUEST['tahun'];
$urut=$_REQUEST['urut'];
$urutan = $_REQUEST['urutan'];
$varbaris = $_REQUEST['varbaris'];	
$page = $_REQUEST['page'];
$total = $_REQUEST['total'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Daftar Alumni]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=getHeader($departemen)?>

<center>
  <font size="4"><strong>DAFTAR MUTASI SISWA</strong></font><br />
 </center><br /><br />
<table>
<tr>
	<td><strong>Departemen</strong> </td> 
	<td><strong>:&nbsp;<?=$departemen?></strong></td>
</tr>
<tr>
	<td><strong>Tahun Mutasi</strong></td>
	<td><strong>:&nbsp;<?=$tahun?></strong></td>
</tr>

</table>
    <br />
      <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="0" width="100%" align="center" bordercolor="#000000">
    <tr height="30" class="header" align="center">
    	<td width="4%">No</td>
        <td width="13%">N I S</td>
        <td width="30%">Nama </td>
        <td width="15%">Kelas Terakhir </td>
		<td width="18%">Tanggal Mutasi </td>
        <td width="22%">Jenis Mutasi </td>
        <td width="*">Keterangan</td>
    </tr>
<?php 	
	//$sql_siswa = "SELECT s.replid AS replidsiswa, s.nis, s.nama, k.kelas, m.tglmutasi, j.jenismutasi, m.keterangan, m.replid, t.tingkat FROM mutasisiswa m, kelas k, tingkat t, siswa s, jenismutasi j WHERE m.departemen='$departemen' AND k.idtingkat=t.replid AND k.replid=s.idkelas AND j.replid = m.jenismutasi AND s.nis = m.nis AND YEAR(tglmutasi) = '$tahun' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	$sql_siswa = "SELECT s.replid AS replidsiswa, s.nis, s.nama, k.kelas, m.tglmutasi, j.jenismutasi, m.keterangan, m.replid, t.tingkat FROM mutasisiswa m, kelas k, tingkat t, siswa s, jenismutasi j WHERE m.departemen='$departemen' AND k.idtingkat=t.replid AND k.replid=s.idkelas AND j.replid = m.jenismutasi AND s.nis = m.nis AND YEAR(tglmutasi) = '$tahun' ORDER BY $urut $urutan";
	
	$result_siswa = QueryDb($sql_siswa);
	
	if ($page==0)
		$cnt = 0;
	else
		$cnt = (int)$page*(int)$varbaris;
		
	while ($row = mysqli_fetch_array($result_siswa)) { ?>
    <tr height="25">
    	<td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nis'] ?></td>
        <td><?=$row['nama'] ?></td>
        <td align="center"><?=$row['tingkat']; ?> - <?=$row['kelas']; ?></td>
		<td align="center"><?=LongDateFormat($row['tglmutasi']); ?></td>
        <td><?=$row['jenismutasi'] ?></td>
        <td><?=$row['keterangan'] ?></td>
   	</tr>
  <?php }
	CloseDb(); ?>
    <!-- END TABLE CONTENT -->
    </table>
<!--<tr>
    <td align="right">Halaman <strong><?=$page+1?></strong> dari <strong><?=$total?></strong> halaman</td>
</tr>-->
</table>	
</body>
<script language="javascript">
window.print();
</script>
</html>