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
require_once('../include/config.php');
require_once('../include/db_functions.php');
OPenDb();
$departemen=$_REQUEST['departemen']; 
$tahunawal=$_REQUEST['tahunawal'];
$tahunakhir=$_REQUEST['tahunakhir'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../script/tools.js"></script>
</head>

<body leftmargin="0" topmargin="0">


<div align="center">
  <a href="#" onclick="newWindow('cetak_statistik_mutasi.php?departemen=<?=$departemen?>&tahunawal=<?=$tahunawal?>&tahunakhir=<?=$tahunakhir?>','s',750,650,'scrollbars=1')" /><img src="../images/ico/print.png" border="0" /><strong>Cetak</strong></a></div>
<table width="100%" border="0">
  <tr>
    <td><div align="center" id="batang">
	<?php
	$querysuku = "SELECT COUNT(*) As Jum,j.jenismutasi As jenismutasi FROM jbsakad.mutasisiswa m,jbsakad.jenismutasi j,jbsakad.angkatan a,jbsakad.siswa s WHERE	a.departemen='$departemen' AND s.idangkatan=a.replid AND m.nis=s.nis AND s.statusmutasi=m.jenismutasi AND m.jenismutasi=j.replid AND YEAR(m.tglmutasi) >= '$tahunawal' AND YEAR(m.tglmutasi) <= '$tahunakhir' GROUP BY jenismutasi";
$resultsuku = QueryDb($querysuku);
if (@mysqli_num_rows($resultsuku)>0){
	?>
    <img src="gambar_statistik.php?departemen=<?=$departemen?>&tahunawal=<?=$tahunawal?>&tahunakhir=<?=$tahunakhir?>"/></div></td>
  <?php } else { ?>
  <img src="../images/ico/blank_statistik.png"/></div></td>
  <?php
	}
	?>
  </tr>
</table>
</body>
</html>