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
<title>JIBAS SimTaka [Cetak Penulis]</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?=GetHeader('alls')?>

<center><font size="4"><strong>DATA PENULIS</strong></font><br /> </center><br /><br />
 
<br />
	<?php
	$sql = "SELECT * FROM penulis ORDER BY nama";
	$result = QueryDb($sql);
	$num = @mysqli_num_rows($result);
	?>
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table">
	  <tr>
		<td height="30" align="center" class="header">Kode</td>
		<td height="30" align="center" class="header">Nama</td>
		<td height="30" align="center" class="header">Jumlah Judul</td>
		<td height="30" align="center" class="header">Jumlah Pustaka</td>
		<td height="30" align="center" class="header">Kontak</td>
		<td height="30" align="center" class="header">Keterangan</td>
	  </tr>
	  <?php
	  if ($num>0){
		  while ($row=@mysqli_fetch_array($result)){
				$num_judul = @mysqli_num_rows(QueryDb("SELECT * FROM pustaka p, penulis pn WHERE pn.replid={$row['replid']} AND pn.replid=p.penulis"));
				$num_pustaka = @mysqli_fetch_row(QueryDb("SELECT COUNT(d.replid) FROM pustaka p, daftarpustaka d, penulis pn WHERE d.pustaka=p.replid AND pn.replid='".$row['replid']."' AND p.penulis=pn.replid"));	
		  ?>
		  <tr>
			<td height="25" align="center"><?=$row['kode']?></td>
			<td height="25"><div class="tab_content"><?=$row['nama']?></div></td>
			<td height="25" align="center">&nbsp;<?=$num_judul?></td>
			<td height="25" align="center">&nbsp;<?=(int)$num_pustaka[0]?></td>
			<td height="25"><div class="tab_content"><?=$row['kontak']?></div></td>
			<td height="25"><div class="tab_content"><?=$row['keterangan']?></div></td>
		  </tr>
		  <?php
		  }
	  } else {
	  ?>
	  <tr>
		<td height="25" colspan="6" align="center" class="nodata">Tidak ada data</td>
	  </tr>
	  <?php
	  }
	  ?>	
	</table>
</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>